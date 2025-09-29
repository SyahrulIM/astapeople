<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Allowance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Eeettss gak boleh nakal, Login dulu ya kak hehe.');
            redirect('auth');
        }
    }

    public function index()
    {
        $start = $this->input->get('absensi_start');
        $end   = $this->input->get('absensi_end');
        $idrole = $this->session->userdata('idrole');
        $iduser = $this->session->userdata('iduser');

        $data = [
            'title'   => 'Allowance',
            'start'   => $start,
            'end'     => $end,
            'results' => []
        ];

        if ($start && $end) {
            $sql = "
            SELECT 
                e.idppl_employee, e.name, d.date, d.check_in, d.check_out, d.reason,
                d.is_edit,
                t.reason AS timeoff_reason, t.is_verify
            FROM ppl_employee e
            LEFT JOIN ppl_presence_detail d ON e.idppl_employee = d.idppl_employee
            LEFT JOIN ppl_time_off t ON e.iduser = t.iduser AND d.date = t.date
            WHERE d.date BETWEEN ? AND ?
        ";

            $params = [$start, $end];

            // filter user selain role admin
            if ($idrole != 1) {
                $sql .= " AND e.iduser = ? ";
                $params[] = $iduser;
            }

            $sql .= " ORDER BY e.name, d.date";

            $query = $this->db->query($sql, $params);
            $rows = $query->result();

            $grouped = [];

            foreach ($rows as $row) {
                $id   = $row->idppl_employee;
                $date = $row->date;

                if (!isset($grouped[$id])) {
                    $grouped[$id] = [
                        'name'          => $row->name,
                        'presence'      => [],
                        'total_attend'  => 0,
                        'total_meal'    => 0
                    ];
                }

                $grouped[$id]['presence'][$date] = '-';

                // --- Kasus: hadir absen ---
                if ($row->check_in && $row->check_out) {
                    $check_in_time  = strtotime($row->check_in);
                    $check_out_time = strtotime($row->check_out);

                    $late_limit  = strtotime('08:10:00');
                    $early_limit = strtotime('16:30:00');

                    // tidak telat & tidak pulang cepat
                    if ($check_in_time <= $late_limit && $check_out_time >= $early_limit) {
                        $check_symbol = '<span class="text-success fw-bold">✓</span>';

                        if (!empty($row->is_edit) && $row->is_edit == 1) {
                            $check_symbol = '<span class="text-danger fw-bold">✓</span>';
                        }

                        $grouped[$id]['presence'][$date] = $check_symbol;

                        // Hadir valid
                        $grouped[$id]['total_attend']++;

                        // Meal allowance
                        $grouped[$id]['total_meal']++;
                    }
                }
                // --- Kasus: dinas disetujui ---
                elseif (strtolower($row->timeoff_reason ?? '') === 'dinas' && $row->is_verify == 1) {
                    $grouped[$id]['presence'][$date] = '<span class="text-primary fw-bold">C</span>';
                    $grouped[$id]['total_attend']++;
                }
            }

            $data['results'] = $grouped;
        }

        $this->load->view('theme/v_head', $data);
        $this->load->view('Allowance/v_allowance', $data);
    }

    public function exportExcel()
    {
        $start = $this->input->get('absensi_start');
        $end = $this->input->get('absensi_end');

        if (!$start || !$end) {
            $this->session->set_flashdata('error', 'Please select start and end dates.');
            redirect('allowance');
        }

        $query = $this->db->query("
        SELECT 
            e.idppl_employee, e.name, d.date, d.check_in, d.check_out, d.reason,
            t.reason AS timeoff_reason, t.is_verify
        FROM ppl_employee e
        LEFT JOIN ppl_presence_detail d ON e.idppl_employee = d.idppl_employee
        LEFT JOIN ppl_time_off t ON e.iduser = t.iduser AND d.date = t.date
        WHERE d.date BETWEEN ? AND ?
        ORDER BY e.name, d.date
    ", [$start, $end]);

        $rows = $query->result();
        $grouped = [];

        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            (new DateTime($end))->modify('+1 day')
        );

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        foreach ($rows as $row) {
            $id = $row->idppl_employee;
            $date = $row->date;

            if (!isset($grouped[$id])) {
                $grouped[$id] = [
                    'name' => $row->name,
                    'presence' => [],
                    'total_attend' => 0
                ];
            }

            $got_meal = false;

            $timeoff_reason = strtolower(trim($row->timeoff_reason ?? ''));
            $is_verify = intval($row->is_verify);

            if ($row->check_in && $row->check_out) {
                $got_meal = true;
            } elseif ($timeoff_reason === 'Dinas' && $is_verify === 1) {
                $got_meal = true;
            }

            $grouped[$id]['presence'][$date] = $got_meal ? '✓' : '-';
            if ($got_meal) $grouped[$id]['total_attend']++;
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator("Your System")
            ->setTitle("Meal Allowance Report")
            ->setSubject("Meal Allowance from {$start} to {$end}");

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        $col = 'A';
        $sheet->setCellValue($col++ . '1', 'No');
        $sheet->setCellValue($col++ . '1', 'Full Name');

        foreach ($dates as $d) {
            $sheet->setCellValue($col++ . '1', date('d M', strtotime($d)));
        }

        $sheet->setCellValue($col++ . '1', 'Total Attendance');
        $sheet->setCellValue($col++ . '1', 'Meal Allowance (Rp)');
        $sheet->setCellValue($col . '1', 'Total Allowance (Rp)');

        $sheet->getStyle('A1:' . $col . '1')->applyFromArray($headerStyle);

        $rowNum = 2;
        $no = 1;
        foreach ($grouped as $emp) {
            $col = 'A';
            $sheet->setCellValue($col++ . $rowNum, $no++);
            $sheet->setCellValue($col++ . $rowNum, $emp['name']);

            foreach ($dates as $d) {
                $cell = $col . $rowNum;
                $value = $emp['presence'][$d] ?? '-';
                $sheet->setCellValue($cell, $value);

                if ($value === '✓') {
                    $sheet->getStyle($cell)->getFont()->getColor()->setRGB('006100');
                } elseif ($value === '-') {
                    $sheet->getStyle($cell)->getFont()->getColor()->setRGB('FF0000');
                }

                $col++;
            }

            $sheet->setCellValue($col++ . $rowNum, $emp['total_attend']);
            $sheet->setCellValue($col++ . $rowNum, 20000);
            $sheet->setCellValue($col . $rowNum, $emp['total_attend'] * 20000);

            $sheet->getStyle(chr(ord($col) - 1) . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle($col . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

            $rowNum++;
        }

        $lastCol = $col;
        $sheet->getStyle('A1:' . $lastCol . ($rowNum - 1))->applyFromArray($dataStyle);

        foreach (range('A', $lastCol) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->freezePane('C2');

        $filename = 'meal_allowance_' . date('Ymd_His') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        header('Pragma: no-cache');

        $writer->save('php://output');
        exit;
    }
}
