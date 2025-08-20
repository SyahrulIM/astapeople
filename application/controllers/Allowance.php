<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Move these OUTSIDE the class
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $end = $this->input->get('absensi_end');

        $data = [
            'title' => 'Allowance',
            'start' => $start,
            'end' => $end,
            'results' => []
        ];

        if ($start && $end) {
            $query = $this->db->query("
            SELECT 
                e.idppl_employee, e.name, d.date, d.check_in, d.check_out, d.reason,
                t.reason AS timeoff_reason, t.is_verify
            FROM ppl_employee e
            LEFT JOIN ppl_presence_detail d ON e.idppl_employee = d.idppl_employee
            LEFT JOIN time_off t ON e.iduser = t.iduser AND d.date = t.date
            WHERE d.date BETWEEN ? AND ?
            ORDER BY e.name, d.date
        ", [$start, $end]);

            $rows = $query->result();
            $grouped = [];

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

                if ($row->check_in && $row->check_out) {
                    $got_meal = true;
                } elseif (strtolower($row->timeoff_reason) === 'dinas' && $row->is_verify == 1) {
                    $got_meal = true;
                }

                $grouped[$id]['presence'][$date] = $got_meal ? '✓' : '-';
                if ($got_meal) $grouped[$id]['total_attend']++;
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
            redirect('allowance');
        }

        $query = $this->db->query("
        SELECT 
            e.idppl_employee, e.name, d.date, d.check_in, d.check_out, d.reason,
            t.reason AS timeoff_reason, t.is_verify
        FROM ppl_employee e
        LEFT JOIN ppl_presence_detail d ON e.idppl_employee = d.idppl_employee
        LEFT JOIN time_off t ON e.iduser = t.iduser AND d.date = t.date
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

            if ($row->check_in && $row->check_out) {
                $got_meal = true;
            } elseif (strtolower($row->timeoff_reason) === 'dinas' && $row->is_verify == 1) {
                $got_meal = true;
            }

            $grouped[$id]['presence'][$date] = $got_meal ? '✓' : '-';
            if ($got_meal) $grouped[$id]['total_attend']++;
        }

        // Create Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $col = 'A';
        $sheet->setCellValue($col++ . '1', 'No');
        $sheet->setCellValue($col++ . '1', 'Full Name');
        foreach ($dates as $d) {
            $sheet->setCellValue($col++ . '1', date('d M', strtotime($d)));
        }
        $sheet->setCellValue($col++ . '1', 'Total Attendance');
        $sheet->setCellValue($col++ . '1', 'Meal Allowance (Rp)');
        $sheet->setCellValue($col++ . '1', 'Total Allowance (Rp)');

        // Body
        $rowNum = 2;
        $no = 1;
        foreach ($grouped as $emp) {
            $col = 'A';
            $sheet->setCellValue($col++ . $rowNum, $no++);
            $sheet->setCellValue($col++ . $rowNum, $emp['name']);

            foreach ($dates as $d) {
                $sheet->setCellValue($col++ . $rowNum, $emp['presence'][$d] ?? '-');
            }

            $sheet->setCellValue($col++ . $rowNum, $emp['total_attend']);
            $sheet->setCellValue($col++ . $rowNum, 20000);
            $sheet->setCellValue($col++ . $rowNum, $emp['total_attend'] * 20000);
            $rowNum++;
        }

        // Output
        $filename = 'meal_allowance_' . date('Ymd_His') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
