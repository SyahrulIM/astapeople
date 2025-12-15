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

            // hanya filter kalau bukan admin (idrole=1) dan bukan role 6
            if ($idrole != 1 && $idrole != 5) {
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

        // Ambil data absensi + time off
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
        $rows = $this->db->query($sql, [$start, $end])->result();

        // Grouping per karyawan - gunakan logic yang sama seperti index()
        $grouped = [];
        $daily_status = []; // untuk mencegah double count per hari

        foreach ($rows as $row) {
            $id   = $row->idppl_employee;
            $date = $row->date;

            if (!isset($grouped[$id])) {
                $grouped[$id] = [
                    'name'         => $row->name,
                    'presence'     => [],
                    'total_attend' => 0,
                    'total_meal'   => 0
                ];
            }

            // skip kalau sudah dihitung untuk hari ini
            if (isset($daily_status[$id][$date])) {
                continue;
            }

            // default tanda kosong (bisa diubah sesuai kebutuhan)
            $grouped[$id]['presence'][$date] = '';

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
                    $grouped[$id]['presence'][$date] = '✓';

                    // hitung hadir valid
                    $grouped[$id]['total_attend']++;
                    $grouped[$id]['total_meal']++; // kalau masih pakai total_meal
                    $daily_status[$id][$date] = true;
                }
            }
            // --- Kasus: dinas disetujui ---
            elseif (strtolower($row->timeoff_reason ?? '') === 'dinas' && $row->is_verify == 1) {
                $grouped[$id]['presence'][$date] = 'C';
                $grouped[$id]['total_attend']++;
                $daily_status[$id][$date] = true;
            }
            // lainnya tetap kosong / tidak dihitung
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

        // Cari kolom terakhir (konversi sederhana - asumsikan jumlah kolom tidak melewati 'Z')
        $lastCol = chr(ord('C') + count($dates) + 3);

        // Judul (merge sampai kolom terakhir)
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
        $sheet->setCellValue('B' . $headerRow, 'Nama');
        $col = 'C';
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
            $sheet->setCellValue('B' . $rowExcel, $emp['name']);

            $col = 'C';
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

            // UM Rp20.000 (format angka supaya bisa dijumlahkan di Excel)
            $sheet->setCellValue(++$col . $rowExcel, 20000);
            $sheet->getStyle($col . $rowExcel)->getNumberFormat()
                ->setFormatCode('"Rp"#,##0');

            // Jumlah = total_attend * 20000
            $jumlah = $total_attend * 20000;
            $sheet->setCellValue(++$col . $rowExcel, $jumlah);
            $sheet->getStyle($col . $rowExcel)->getNumberFormat()
                ->setFormatCode('"Rp"#,##0');

            // TTD kosong (untuk tanda tangan manual)
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
