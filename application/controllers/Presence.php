<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Presence extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Oops! Please log in first.');
            redirect('auth');
        }
    }

    public function index()
    {
        $presence = $this->db->get('ppl_presence')->result();

        $data = [
            'title' => 'Absensi',
            'presence' => $presence
        ];

        $this->load->view('theme/v_head', $data);
        $this->load->view('Presence/v_presence');
    }

    public function import()
    {
        $file = $_FILES['file']['tmp_name'];
        $filename = $_FILES['file']['name'];
        $month = $this->input->post('month');
        $year = $this->input->post('year');

        if (empty($file) || empty($month) || empty($year)) {
            $this->session->set_flashdata('error', 'File, month, and year are required.');
            redirect('presence');
        }

        $max_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        try {
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if ($extension == 'xlsx' || $extension == 'xls') {
                // === Import dari Excel ===
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getSheetByName('Lap. Log Absen');
                if ($sheet === null) {
                    $this->session->set_flashdata('error', 'Sheet "Lap. Log Absen" tidak ditemukan di file Excel.');
                    redirect('presence');
                }

                $dates = [];
                for ($col = 1; $col <= $max_day; $col++) {
                    $cellValue = $sheet->getCellByColumnAndRow($col, 4)->getValue();
                    if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($sheet->getCellByColumnAndRow($col, 4))) {
                        $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue)->format('Y-m-d');
                    } else {
                        $tanggal = date('Y-m-d', strtotime("$year-$month-" . str_pad($col, 2, '0', STR_PAD_LEFT)));
                    }
                    $dates[$col] = $tanggal;
                }

                // insert header
                $this->db->insert('ppl_presence', [
                    'created_by' => $this->session->userdata('username'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'status' => 1
                ]);
                $idppl_presence = $this->db->insert_id();

                $highestRow = $sheet->getHighestRow();

                for ($row = 6; $row <= $highestRow; $row += 2) {
                    $idCell = trim($sheet->getCellByColumnAndRow(3, $row - 1)->getValue());
                    if (empty($idCell)) continue;
                    $user_id = intval($idCell);

                    for ($col = 1; $col <= $max_day; $col++) {
                        $absenCell = trim($sheet->getCellByColumnAndRow($col, $row)->getValue());
                        list($check_in, $check_out) = $this->_parseTimeCell($absenCell);

                        $date = $dates[$col];
                        $this->db->insert('ppl_presence_detail', [
                            'idppl_presence' => $idppl_presence,
                            'idppl_employee' => $user_id,
                            'date' => $date,
                            'check_in' => $check_in,
                            'check_out' => $check_out,
                            'is_permission' => 0,
                            'status' => 1
                        ]);
                    }
                }
            } elseif ($extension == 'csv') {
                // === Import dari CSV ===
                if (($handle = fopen($file, 'r')) !== false) {
                    $header = fgetcsv($handle); // ambil header row (optional)

                    // insert header
                    $this->db->insert('ppl_presence', [
                        'created_by' => $this->session->userdata('username'),
                        'created_date' => date('Y-m-d H:i:s'),
                        'status' => 1
                    ]);
                    $idppl_presence = $this->db->insert_id();

                    while (($row = fgetcsv($handle)) !== false) {
                        // asumsikan format CSV: user_id, tanggal, check_in, check_out
                        $user_id = intval($row[0]);
                        $date = date('Y-m-d', strtotime($row[1]));
                        $check_in = !empty($row[2]) ? $row[2] : null;
                        $check_out = !empty($row[3]) ? $row[3] : null;

                        $this->db->insert('ppl_presence_detail', [
                            'idppl_presence' => $idppl_presence,
                            'idppl_employee' => $user_id,
                            'date' => $date,
                            'check_in' => $check_in,
                            'check_out' => $check_out,
                            'is_permission' => 0,
                            'status' => 1
                        ]);
                    }
                    fclose($handle);
                }
            } else {
                $this->session->set_flashdata('error', 'Format file tidak didukung. Gunakan XLSX/XLS atau CSV.');
                redirect('presence');
            }

            $this->session->set_flashdata('success', 'Attendance data imported successfully.');
            redirect('presence');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Failed to read the file: ' . $e->getMessage());
            redirect('presence');
        }
    }

    private function _parseTimeCell($absenCell)
    {
        $check_in = null;
        $check_out = null;

        if (!empty($absenCell)) {
            preg_match_all('/\d{2}:\d{2}/', $absenCell, $matches);
            $times = $matches[0];

            if (count($times) == 1) {
                $time = $times[0];
                $hour = intval(explode(':', $time)[0]);
                if ($hour < 12) {
                    $check_in = $time;
                } else {
                    $check_out = $time;
                }
            } elseif (count($times) >= 2) {
                foreach ($times as $time) {
                    $hour = intval(explode(':', $time)[0]);
                    if ($hour < 12) {
                        $check_in = $time;
                        break;
                    }
                }

                for ($i = count($times) - 1; $i >= 0; $i--) {
                    $hour = intval(explode(':', $times[$i])[0]);
                    if ($hour >= 12) {
                        $check_out = $times[$i];
                        break;
                    }
                }
            }
        }

        return [$check_in, $check_out];
    }
}
