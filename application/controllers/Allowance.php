<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

            // hanya filter kalau bukan admin (idrole=1) dan bukan role 6
            if ($idrole != 1 && $idrole != 6) {
                $sql .= " AND e.iduser = ? ";
                $params[] = $iduser;
            }

            $sql .= " ORDER BY e.name, d.date";

            $query = $this->db->query($sql, $params);
            $rows = $query->result();

            $grouped = [];
            $daily_status = []; // track unik per hari

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

                // skip kalau sudah dihitung untuk hari ini
                if (isset($daily_status[$id][$date])) {
                    continue;
                }

                $grouped[$id]['presence'][$date] = '-';

                // --- Kasus: hadir absen ---
                if ($row->check_in && $row->check_out) {
                    $check_in_time  = strtotime($row->check_in);
                    $check_out_time = strtotime($row->check_out);

                    $day_of_week = date('N', strtotime($date));
                    if ($day_of_week == 6) {
                        // Sabtu
                        $late_limit  = strtotime('08:10:00');
                        $early_limit = strtotime('13:00:00');
                    } else {
                        // Senin - Jumat
                        $late_limit  = strtotime('08:10:00');
                        $early_limit = strtotime('16:30:00');
                    }

                    // tidak telat & tidak pulang cepat
                    if ($check_in_time <= $late_limit && $check_out_time >= $early_limit) {
                        $check_symbol = '<span class="text-success fw-bold">✓</span>';

                        if (!empty($row->is_edit) && $row->is_edit == 1) {
                            $check_symbol = '<span class="text-danger fw-bold">✓</span>';
                        }

                        $grouped[$id]['presence'][$date] = $check_symbol;

                        // Hadir valid
                        $grouped[$id]['total_attend']++;
                        $grouped[$id]['total_meal']++;

                        $daily_status[$id][$date] = true;
                    }
                }
                // --- Kasus: dinas disetujui ---
                elseif (strtolower($row->timeoff_reason ?? '') === 'dinas' && $row->is_verify == 1) {
                    $grouped[$id]['presence'][$date] = '<span class="text-primary fw-bold">C</span>';
                    $grouped[$id]['total_attend']++;

                    $daily_status[$id][$date] = true;
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
        $end   = $this->input->get('absensi_end');

        if (!$start || !$end) {
            show_error('Tanggal tidak boleh kosong');
        }

        // ambil data hasil grouping dari index()
        $sql = "
        SELECT 
            e.idppl_employee, e.name, d.date, d.check_in, d.check_out, d.reason,
            d.is_edit,
            t.reason AS timeoff_reason, t.is_verify
        FROM ppl_employee e
        LEFT JOIN ppl_presence_detail d ON e.idppl_employee = d.idppl_employee
        LEFT JOIN ppl_time_off t ON e.iduser = t.iduser AND d.date = t.date
        WHERE d.date BETWEEN ? AND ?
        ORDER BY e.name, d.date
    ";

        $query = $this->db->query($sql, [$start, $end]);
        $rows  = $query->result();

        // grouping seperti index()
        $grouped = [];
        $daily_status = [];

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

            if (isset($daily_status[$id][$date])) {
                continue;
            }

            $grouped[$id]['presence'][$date] = '-';

            if ($row->check_in && $row->check_out) {
                $check_in_time  = strtotime($row->check_in);
                $check_out_time = strtotime($row->check_out);

                $day_of_week = date('N', strtotime($date));
                if ($day_of_week == 6) {
                    $late_limit  = strtotime('08:10:00');
                    $early_limit = strtotime('13:00:00');
                } else {
                    $late_limit  = strtotime('08:10:00');
                    $early_limit = strtotime('16:30:00');
                }

                if ($check_in_time <= $late_limit && $check_out_time >= $early_limit) {
                    $grouped[$id]['presence'][$date] = '✓';
                    $grouped[$id]['total_attend']++;
                    $grouped[$id]['total_meal']++;
                    $daily_status[$id][$date] = true;
                }
            } elseif (strtolower($row->timeoff_reason ?? '') === 'dinas' && $row->is_verify == 1) {
                $grouped[$id]['presence'][$date] = 'C';
                $grouped[$id]['total_attend']++;
                $daily_status[$id][$date] = true;
            }
        }

        // =========================
        // Export Excel
        // =========================
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            (new DateTime($end))->modify('+1 day')
        );
        $dates = iterator_to_array($period);

        // Header
        $sheet->setCellValue('A1', "Laporan Absensi Karyawan Absensi " . date('d M', strtotime($start)) . " - " . date('d M Y', strtotime($end)));

        // kolom
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Nama');

        $col = 'C';
        foreach ($dates as $d) {
            $sheet->setCellValue($col . '2', $d->format('j'));

            // Tandai merah kalau hari Minggu
            if ($d->format('N') == 7) {
                $sheet->getStyle($col . '2')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF0000');
            }

            $col++;
        }

        $sheet->setCellValue($col . '2', 'Total');
        $sheet->setCellValue(++$col . '2', 'UM');
        $sheet->setCellValue(++$col . '2', 'Jumlah');
        $sheet->setCellValue(++$col . '2', 'TTD');

        // isi data
        $rowExcel = 3;
        $no = 1;
        foreach ($grouped as $emp) {
            $sheet->setCellValue('A' . $rowExcel, $no++);
            $sheet->setCellValue('B' . $rowExcel, $emp['name']);

            $col = 'C';
            foreach ($dates as $d) {
                $dateStr = $d->format('Y-m-d');
                $val = isset($emp['presence'][$dateStr]) ? $emp['presence'][$dateStr] : '';
                $sheet->setCellValue($col . $rowExcel, $val);

                // merah untuk Minggu
                if ($d->format('N') == 7) {
                    $sheet->getStyle($col . $rowExcel)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFFC0CB'); // pink/merah muda
                }

                $col++;
            }

            $sheet->setCellValue($col . $rowExcel, $emp['total_attend']);
            $sheet->setCellValue(++$col . $rowExcel, $emp['total_meal']);
            $sheet->setCellValue(++$col . $rowExcel, $emp['total_attend']); // contoh Jumlah sama total
            $sheet->setCellValue(++$col . $rowExcel, $no - 1);

            $rowExcel++;
        }

        // auto width
        foreach (range('A', $col) as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        // output
        $filename = "Absensi_" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
