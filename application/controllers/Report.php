<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
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
        $start_date = $this->input->get('absensi_start');
        $end_date = $this->input->get('absensi_end');
        $iduser = $this->session->userdata('iduser');
        $idrole = $this->session->userdata('idrole');

        // ambil employee_id dari tabel
        $this->db->select('idppl_employee');
        $this->db->where('iduser', $iduser);
        $employee_row = $this->db->get('ppl_employee')->row();
        $employee_id = $employee_row ? $employee_row->idppl_employee : null;

        // Tentukan employee filter
        $filter_employee = null;
        if (in_array($idrole, [1, 5])) {
            if (!empty($this->input->get('employee'))) {
                $filter_employee = $this->input->get('employee'); // filter employee tertentu
            } else {
                $filter_employee = null; // tampil semua karyawan
            }
        } else {
            $filter_employee = $employee_id; // non admin -> hanya employee dari session
        }

        $presence_data = [];

        if (!empty($start_date) && !empty($end_date)) {
            // Subquery ambil record terakhir per karyawan per tanggal
            $subquery = $this->db
                ->select('MAX(pp.created_date) AS max_created, pd.idppl_employee, pd.`date`')
                ->from('ppl_presence_detail pd')
                ->join('ppl_presence pp', 'pp.idppl_presence = pd.idppl_presence')
                ->where('pd.`date` >=', $start_date)
                ->where('pd.`date` <=', $end_date)
                ->group_by('pd.idppl_employee, pd.`date`')
                ->get_compiled_select();

            $this->db->select('
            pe.name,
            pd.idppl_employee,
            pd.`date`,
            MAX(t.is_verify) AS is_verify,
            MAX(pd.check_in) AS check_in,
            MAX(pd.check_out) AS check_out,
            MAX(t.reason) AS reason,
            MAX(pd.is_permission) AS is_permission,
            MAX(pd.is_edit) AS is_edit
        ')
                ->from('ppl_presence_detail pd')
                ->join('ppl_presence pp', 'pp.idppl_presence = pd.idppl_presence')
                ->join('ppl_employee pe', 'pe.idppl_employee = pd.idppl_employee')
                ->join('ppl_time_off t', 't.iduser = pe.iduser AND t.date = pd.date', 'left')
                ->join("($subquery) latest", 'latest.idppl_employee = pd.idppl_employee AND latest.date = pd.date AND latest.max_created = pp.created_date')
                ->group_by(['pe.name', 'pd.idppl_employee', 'pd.date'])
                ->order_by('pe.name', 'ASC')
                ->order_by('pd.date', 'ASC');

            // filter kalau ada employee
            if (!empty($filter_employee)) {
                $this->db->where('pd.idppl_employee', $filter_employee);
            }

            $presence_data = $this->db->get()->result();
        }

        // Total employees (untuk deteksi libur nasional)
        $total_employees = $this->db->count_all('ppl_employee');
        if (!empty($filter_employee) && !in_array($idrole, [1, 5])) {
            $total_employees = 1; // non admin hanya 1 orang
        }

        // Hitung absent per tanggal
        $absent_count = [];
        foreach ($presence_data as $row) {
            $date = $row->date;
            if (empty($row->check_in) && empty($row->check_out)) {
                $absent_count[$date] = ($absent_count[$date] ?? 0) + 1;
            }
        }

        // Tentukan tipe hari
        $day_types = [];
        foreach ($presence_data as $row) {
            $date = $row->date;
            if (!isset($day_types[$date])) {
                $day_of_week = date('w', strtotime($date));
                if ($day_of_week == 0) {
                    $day_types[$date] = 'Weekend';
                } elseif (isset($absent_count[$date]) && $absent_count[$date] >= ($total_employees * 0.8)) {
                    $day_types[$date] = 'National Holiday';
                } else {
                    $day_types[$date] = 'Workday';
                }
            }
        }

        // Proses data (cek telat/pulang cepat)
        $processed_data = [];
        foreach ($presence_data as $row) {
            $date = $row->date;
            $day_of_week = date('w', strtotime($date));

            if ($day_of_week == 6) {
                $standard_in = '08:10:00';
                $standard_out = '13:00:00';
            } elseif ($day_of_week >= 1 && $day_of_week <= 5) {
                $standard_in = '08:10:00';
                $standard_out = '16:30:00';
            } else {
                $standard_in = null;
                $standard_out = null;
            }

            $processed_data[] = (object) [
                'name' => $row->name,
                'idppl_employee' => $row->idppl_employee,
                'date' => $date,
                'check_in' => $row->check_in,
                'check_out' => $row->check_out,
                'reason' => $row->reason,
                'is_permission' => $row->is_permission,
                'is_late' => (!empty($row->check_in) && $standard_in && $row->check_in > $standard_in),
                'left_early' => (!empty($row->check_out) && $standard_out && $row->check_out < $standard_out),
                'holiday_type' => $day_types[$date] ?? 'Workday',
                'is_verify' => $row->is_verify,
                'is_edit' => $row->is_edit
            ];
        }

        // Hitung total hari kerja
        $total_days = 0;
        if (!empty($start_date)) {
            $month = date('m', strtotime($start_date));
            $year = date('Y', strtotime($start_date));
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            for ($day = 1; $day <= $days_in_month; $day++) {
                $date = date('Y-m-d', strtotime("$year-$month-$day"));
                $dayOfWeek = date('w', strtotime($date));

                if ($dayOfWeek == 0) continue;
                if (isset($day_types[$date]) && $day_types[$date] === 'National Holiday') continue;

                $total_days++;
            }
        }

        // Summary
        $summary = [
            'total_days' => $total_days,
            'national_holiday' => 0,
            'absent' => 0,
            'incomplete' => 0,
            'present' => 0,
            'late' => 0,
            'early_leave' => 0,
            'staff' => []
        ];

        $unique_staff = [];
        foreach ($processed_data as $row) {
            if ($row->holiday_type === 'Weekend') continue;

            $unique_staff[$row->idppl_employee] = true;

            if ($row->holiday_type === 'National Holiday') {
                $summary['national_holiday']++;
            } elseif (empty($row->check_in) && empty($row->check_out)) {
                $summary['absent']++;
            } elseif (empty($row->check_in) || empty($row->check_out)) {
                $summary['incomplete']++;
            } else {
                $summary['present']++;
                if ($row->is_late) $summary['late']++;
                if ($row->left_early) $summary['early_leave']++;
            }
        }
        $summary['staff'] = count($unique_staff);

        // Ambil semua employee untuk dropdown filter
        $this->db->select('ppl_employee.idppl_employee, ppl_employee.name');
        $this->db->order_by('name', 'ASC');
        $employees = $this->db->get('ppl_employee')->result();

        // Kirim ke view
        $data = [
            'title' => 'Attendance Report',
            'presence' => $processed_data,
            'employee' => $employees,
            'selected_employee' => $filter_employee,
            'summary' => $summary,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $this->load->view('theme/v_head', $data);
        $this->load->view('Report/v_report');
    }

    public function get_attendance_detail()
    {
        $employee_id = $this->input->post('employee_id');
        $date = $this->input->post('date');

        if (!$employee_id || !$date) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
            return;
        }

        $attendance = $this->db->select('
            pd.*,
            pe.name as employee_name,
            t.reason,
            t.is_verify
        ')
            ->from('ppl_presence_detail pd')
            ->join('ppl_employee pe', 'pe.idppl_employee = pd.idppl_employee', 'left')
            ->join('ppl_time_off t', 't.iduser = pe.iduser AND t.date = pd.date', 'left')
            ->where('pd.idppl_employee', $employee_id)
            ->where('pd.date', $date)
            ->order_by('pd.idppl_presence_detail', 'DESC') // ambil yang terbaru kalau ada banyak
            ->limit(1)
            ->get()
            ->row_array();

        if ($attendance) {
            echo json_encode([
                'status' => 'success',
                'data' => $attendance
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data not found'
            ]);
        }
    }

    public function edit()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('employee_id', 'Employee ID', 'required|numeric');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('time_start', 'Start Time', 'required');
        $this->form_validation->set_rules('time_end', 'End Time', 'required');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
            return;
        }

        $employee_id = $this->input->post('employee_id');
        $date        = $this->input->post('date');
        $time_start  = $this->input->post('time_start');
        $time_end    = $this->input->post('time_end');

        $update_data = [
            'check_in'      => $time_start,
            'check_out'     => $time_end,
            'is_edit' => 1 // karena ini edit manual, bukan izin
        ];

        $this->db->where('idppl_employee', $employee_id);
        $this->db->where('date', $date);
        $exists = $this->db->get('ppl_presence_detail')->row();

        if ($exists) {
            $this->db->where('idppl_employee', $employee_id);
            $this->db->where('date', $date);
            $result = $this->db->update('ppl_presence_detail', $update_data);
        } else {
            $insert_data = $update_data;
            $insert_data['idppl_employee'] = $employee_id;
            $insert_data['date'] = $date;
            $result = $this->db->insert('ppl_presence_detail', $insert_data);
        }

        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Absensi berhasil diperbarui'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal memperbarui absensi'
            ]);
        }
    }
}
