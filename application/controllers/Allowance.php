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
        $place_filter = $this->input->get('place'); // Optional place filter

        $data = [
            'title'   => 'Allowance',
            'start'   => $start,
            'end'     => $end,
            'place_filter' => $place_filter,
            'results' => []
        ];

        if ($start && $end) {
            // First, get all employees with place - ORDER BY name ASC
            $sql_employees = "
        SELECT e.idppl_employee, e.no_excel, e.name, e.iduser, e.place
        FROM ppl_employee e
        WHERE 1=1
        ";

            $params_employees = [];

            // Filter by user if not admin
            if ($idrole != 1 && $idrole != 5) {
                $sql_employees .= " AND e.iduser = ? ";
                $params_employees[] = $iduser;
            }

            // Filter by place if selected
            if ($place_filter && $place_filter != 'all') {
                $sql_employees .= " AND e.place = ? ";
                $params_employees[] = $place_filter;
            }

            // ORDER BY name ASC - simple alphabetical sorting
            $sql_employees .= " ORDER BY LOWER(e.name) ASC";

            $query_employees = $this->db->query($sql_employees, $params_employees);
            $employees = $query_employees->result();

            // Initialize grouped array with all employees - maintain order
            $grouped = [];
            foreach ($employees as $emp) {
                $grouped[$emp->no_excel] = [
                    'name'          => $emp->name,
                    'place'         => $emp->place,
                    'presence'      => [],
                    'total_attend'  => 0,
                    'total_meal'    => 0
                ];
            }

            // Now get presence data for the date range
            $sql_presence = "
        SELECT 
            e.no_excel, e.name, e.place,
            d.date, d.check_in, d.check_out, d.reason, d.is_edit,
            t.reason AS timeoff_reason, t.is_verify
        FROM ppl_employee e
        LEFT JOIN ppl_presence_detail d ON e.no_excel = d.no_excel 
            AND d.date BETWEEN ? AND ?
        LEFT JOIN ppl_time_off t ON e.iduser = t.iduser AND d.date = t.date
        WHERE 1=1
        ";

            $params_presence = [$start, $end];

            // Filter by user if not admin
            if ($idrole != 1 && $idrole != 5) {
                $sql_presence .= " AND e.iduser = ? ";
                $params_presence[] = $iduser;
            }

            // Filter by place if selected
            if ($place_filter && $place_filter != 'all') {
                $sql_presence .= " AND e.place = ? ";
                $params_presence[] = $place_filter;
            }

            // ORDER BY name ASC - simple alphabetical sorting
            $sql_presence .= " ORDER BY LOWER(e.name) ASC, d.date";

            $query_presence = $this->db->query($sql_presence, $params_presence);
            $rows = $query_presence->result();

            $daily_status = []; // track unik per hari

            // Count total working days in the date range (Monday to Saturday)
            $startDate = new DateTime($start);
            $endDate = new DateTime($end);
            $totalWorkingDays = 0;

            // Calculate working days for the period
            for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
                $dayOfWeek = $date->format('N'); // 1=Monday, 7=Sunday
                if ($dayOfWeek >= 1 && $dayOfWeek <= 6) { // Monday to Saturday
                    $totalWorkingDays++;
                }
            }

            foreach ($rows as $row) {
                $no_excel = $row->no_excel;
                $date = $row->date;
                $place = $row->place;

                // Skip if no date (this happens for employees with no presence records)
                if (!$date) {
                    continue;
                }

                // skip kalau sudah dihitung untuk hari ini
                if (isset($daily_status[$no_excel][$date])) {
                    continue;
                }

                $grouped[$no_excel]['presence'][$date] = '-';

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

                        $grouped[$no_excel]['presence'][$date] = $check_symbol;

                        // Hadir valid
                        $grouped[$no_excel]['total_attend']++;
                        $grouped[$no_excel]['total_meal']++;

                        $daily_status[$no_excel][$date] = true;
                    }
                }
                // --- Kasus: dinas disetujui ---
                elseif (strtolower($row->timeoff_reason ?? '') === 'dinas' && $row->is_verify == 1) {
                    $grouped[$no_excel]['presence'][$date] = '<span class="text-primary fw-bold">C</span>';
                    $grouped[$no_excel]['total_attend']++;

                    $daily_status[$no_excel][$date] = true;
                }
            }

            // Add total working days to data for display
            $data['total_working_days'] = $totalWorkingDays;
            $data['results'] = $grouped;
        }

        // Get distinct places for filter dropdown
        $data['places'] = $this->db->query("SELECT DISTINCT place FROM ppl_employee WHERE place IS NOT NULL AND place != '' ORDER BY place")->result();

        $this->load->view('theme/v_head', $data);
        $this->load->view('Allowance/v_allowance', $data);
    }

    public function exportExcel()
    {
        $start = $this->input->get('absensi_start');
        $end   = $this->input->get('absensi_end');
        $place_filter = $this->input->get('place');

        if (!$start || !$end) {
            show_error('Tanggal tidak boleh kosong');
        }

        // First, get all employees with place - ORDER BY name ASC
        $sql_employees = "
    SELECT e.idppl_employee, e.no_excel, e.name, e.iduser, e.place
    FROM ppl_employee e
    WHERE 1=1
    ";

        $params_employees = [];

        // Filter by place if selected
        if ($place_filter && $place_filter != 'all') {
            $sql_employees .= " AND e.place = ? ";
            $params_employees[] = $place_filter;
        }

        // ORDER BY name ASC - simple alphabetical sorting
        $sql_employees .= " ORDER BY LOWER(e.name) ASC";

        $query_employees = $this->db->query($sql_employees, $params_employees);
        $employees = $query_employees->result();

        // Initialize grouped array with all employees - maintain order
        $grouped = [];
        foreach ($employees as $emp) {
            $grouped[$emp->no_excel] = [
                'name'         => $emp->name,
                'place'        => $emp->place,
                'presence'     => [],
                'total_attend' => 0,
                'total_meal'   => 0
            ];
        }

        // Now get presence data
        $sql = "
    SELECT 
        e.no_excel, e.name, e.place,
        d.date, d.check_in, d.check_out, d.reason, d.is_edit,
        t.reason AS timeoff_reason, t.is_verify
    FROM ppl_employee e
    LEFT JOIN ppl_presence_detail d ON e.no_excel = d.no_excel 
        AND d.date BETWEEN ? AND ?
    LEFT JOIN ppl_time_off t ON e.iduser = t.iduser AND d.date = t.date
    WHERE 1=1
    ";

        $params = [$start, $end];

        // Filter by place if selected
        if ($place_filter && $place_filter != 'all') {
            $sql .= " AND e.place = ? ";
            $params[] = $place_filter;
        }

        // ORDER BY name ASC - simple alphabetical sorting
        $sql .= " ORDER BY LOWER(e.name) ASC, d.date";

        $rows = $this->db->query($sql, $params)->result();

        // Grouping per karyawan
        $daily_status = []; // untuk mencegah double count per hari

        foreach ($rows as $row) {
            $no_excel = $row->no_excel;
            $date = $row->date;

            // Skip if no date
            if (!$date) {
                continue;
            }

            // skip kalau sudah dihitung untuk hari ini
            if (isset($daily_status[$no_excel][$date])) {
                continue;
            }

            // default tanda kosong
            $grouped[$no_excel]['presence'][$date] = '';

            // --- Kasus: hadir absen (harus ada check_in & check_out) ---
            if ($row->check_in && $row->check_out) {
                // ambil timestamp
                $check_in_time  = strtotime($row->check_in);
                $check_out_time = strtotime($row->check_out);

                $day_of_week = date('N', strtotime($date)); // 1=Mon .. 6=Sat, 7=Sun
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
                    // tanda hadir
                    $grouped[$no_excel]['presence'][$date] = '✓';

                    // hitung hadir valid
                    $grouped[$no_excel]['total_attend']++;
                    $daily_status[$no_excel][$date] = true;
                }
            }
            // --- Kasus: dinas disetujui ---
            elseif (strtolower($row->timeoff_reason ?? '') === 'dinas' && $row->is_verify == 1) {
                $grouped[$no_excel]['presence'][$date] = 'C';
                $grouped[$no_excel]['total_attend']++;
                $daily_status[$no_excel][$date] = true;
            }
        }

        // =============== Excel ===============
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Buat daftar tanggal
        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            (new DateTime($end))->modify('+1 day')
        );
        $dates = iterator_to_array($period);

        // Cari kolom terakhir (tambah kolom untuk Place)
        $lastCol = chr(ord('D') + count($dates) + 3);

        // Judul
        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->setCellValue('A1', 'Asta Homeware');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A2:' . $lastCol . '2');
        $sheet->setCellValue('A2', 'Laporan Absensi Karyawan');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A3:' . $lastCol . '3');
        $sheet->setCellValue('A3', 'Periode: ' . $start . ' s/d ' . $end);
        $sheet->getStyle('A3')->getFont()->setSize(12);
        $sheet->getStyle('A3')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Header tabel
        $headerRow = 5;
        $sheet->setCellValue('A' . $headerRow, 'No');
        $sheet->setCellValue('B' . $headerRow, 'Lokasi');
        $sheet->setCellValue('C' . $headerRow, 'Nama');
        $col = 'D';
        foreach ($dates as $d) {
            $sheet->setCellValue($col . $headerRow, $d->format('j'));
            // Minggu = merah
            if ($d->format('N') == 7) {
                $sheet->getStyle($col . $headerRow)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF0000');
            }
            $col++;
        }
        $sheet->setCellValue($col . $headerRow, 'Total');
        $sheet->setCellValue(++$col . $headerRow, 'UM');
        $sheet->setCellValue(++$col . $headerRow, 'Jumlah');
        $sheet->setCellValue(++$col . $headerRow, 'TTD');

        // Isi data
        $rowExcel = $headerRow + 1;
        $no = 1;
        foreach ($grouped as $emp) {
            $sheet->setCellValue('A' . $rowExcel, $no++);
            $sheet->setCellValue('B' . $rowExcel, $emp['place'] ?? '-');
            $sheet->setCellValue('C' . $rowExcel, $emp['name']);

            $col = 'D';
            foreach ($dates as $d) {
                $dateStr = $d->format('Y-m-d');
                $val = isset($emp['presence'][$dateStr]) ? $emp['presence'][$dateStr] : '';
                $sheet->setCellValue($col . $rowExcel, $val);

                // Minggu kasih warna pink
                if ($d->format('N') == 7) {
                    $sheet->getStyle($col . $rowExcel)->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFFFC0CB');
                }
                $col++;
            }

            $total_attend = $emp['total_attend'];
            $sheet->setCellValue($col . $rowExcel, $total_attend);

            // UM Rp20.000
            $sheet->setCellValue(++$col . $rowExcel, 20000);
            $sheet->getStyle($col . $rowExcel)->getNumberFormat()
                ->setFormatCode('"Rp"#,##0');

            // Jumlah = total_attend * 20000
            $jumlah = $total_attend * 20000;
            $sheet->setCellValue(++$col . $rowExcel, $jumlah);
            $sheet->getStyle($col . $rowExcel)->getNumberFormat()
                ->setFormatCode('"Rp"#,##0');

            // TTD kosong
            $sheet->setCellValue(++$col . $rowExcel, '');

            $rowExcel++;
        }

        // Border
        $lastRow = $rowExcel - 1;
        $sheet->getStyle('A' . $headerRow . ':' . $col . $lastRow)
            ->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Auto size kolom
        foreach (range('A', $col) as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        // Output
        $filename = "Asta_People_Laporan_Absensi" . date('Ymd_His') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
