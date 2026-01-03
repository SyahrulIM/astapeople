<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
        $data = [
            'title' => 'Absensi',
            'presence' => $this->db
                ->order_by('created_date', 'DESC')
                ->get('ppl_presence')
                ->result()
        ];

        $this->load->view('theme/v_head', $data);
        $this->load->view('Presence/v_presence');
    }

    public function import()
    {
        $file     = $_FILES['file']['tmp_name'];
        $filename = $_FILES['file']['name'];
        $month    = $this->input->post('month');
        $year     = $this->input->post('year');
        $place    = $this->input->post('place');

        if (!$file || !$month || !$year || !$place) {
            $this->session->set_flashdata('error', 'File, bulan, tahun, dan gudang wajib diisi.');
            redirect('presence');
        }

        // Sheet berdasarkan gudang
        $sheetName = ($place === 'IV') ? 'Lap. Log Absen' : 'Log';
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $max_day   = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        try {

            // ===== HEADER (LOG IMPORT) =====
            $this->db->insert('ppl_presence', [
                'place'        => $place,
                'month'        => $month,
                'year'         => $year,
                'created_by'   => $this->session->userdata('username'),
                'created_date' => date('Y-m-d H:i:s'),
                'status'       => 1
            ]);
            $idppl_presence = $this->db->insert_id();

            // ===== EXCEL =====
            if (!in_array($extension, ['xls', 'xlsx'])) {
                throw new Exception('Format file tidak didukung.');
            }

            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getSheetByName($sheetName);

            if (!$sheet) {
                throw new Exception('Sheet "' . $sheetName . '" tidak ditemukan.');
            }

            // ===== TANGGAL =====
            $dates = [];
            for ($col = 1; $col <= $max_day; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, 4);
                if (Date::isDateTime($cell)) {
                    $dates[$col] = Date::excelToDateTimeObject($cell->getValue())->format('Y-m-d');
                } else {
                    $dates[$col] = date(
                        'Y-m-d',
                        strtotime("$year-$month-" . str_pad($col, 2, '0', STR_PAD_LEFT))
                    );
                }
            }

            // ===== DATA =====
            $highestRow = $sheet->getHighestRow();

            for ($row = 6; $row <= $highestRow; $row += 2) {

                $no_excel = (int) trim(
                    $sheet->getCellByColumnAndRow(3, $row - 1)->getValue()
                );
                if (!$no_excel) continue;

                for ($col = 1; $col <= $max_day; $col++) {

                    $date = $dates[$col];

                    $absenCell = trim(
                        $sheet->getCellByColumnAndRow($col, $row)->getValue()
                    );

                    list($check_in, $check_out) = $this->_parseTimeCell($absenCell);

                    // ===== RULE FINAL =====
                    $exist = $this->db->get_where('ppl_presence_detail', [
                        'no_excel' => $no_excel,
                        'date'     => $date
                    ])->row();

                    $payload = [
                        'idppl_presence' => $idppl_presence,
                        'no_excel'       => $no_excel,
                        'date'           => $date,
                        'check_in'       => $check_in,
                        'check_out'      => $check_out,
                        'is_permission'  => 0,
                        'place'          => $place,
                        'status'         => 1
                    ];

                    if ($exist) {
                        $this->db->where('idppl_presence_detail', $exist->idppl_presence_detail)
                            ->update('ppl_presence_detail', $payload);
                    } else {
                        $this->db->insert('ppl_presence_detail', $payload);
                    }
                }
            }

            $this->session->set_flashdata(
                'success',
                'Import absensi berhasil (tanggal ada = update, belum ada = insert).'
            );
            redirect('presence');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Import gagal: ' . $e->getMessage());
            redirect('presence');
        }
    }

    private function _parseTimeCell($absenCell)
    {
        $check_in = null;
        $check_out = null;

        if ($absenCell) {
            preg_match_all('/\d{2}:\d{2}/', $absenCell, $m);
            foreach ($m[0] as $time) {
                $hour = (int) explode(':', $time)[0];
                if ($hour < 12 && !$check_in) $check_in = $time;
                if ($hour >= 12) $check_out = $time;
            }
        }

        return [$check_in, $check_out];
    }
}
