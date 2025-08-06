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
        $employee_id = $this->input->get('employee');

        $presence_data = [];

        if (!empty($start_date) && !empty($end_date)) {
            // Get the most recent record for each employee per date
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
                    MAX(pd.is_permission) AS is_permission
                ')
                ->from('ppl_presence_detail pd')
                ->join('ppl_presence pp', 'pp.idppl_presence = pd.idppl_presence')
                ->join('ppl_employee pe', 'pe.idppl_employee = pd.idppl_employee')
                ->join('time_off t', 't.iduser = pe.iduser AND t.date = pd.date', 'left')
                ->join("($subquery) latest", 'latest.idppl_employee = pd.idppl_employee AND latest.date = pd.date AND latest.max_created = pp.created_date')
                ->group_by(['pe.name', 'pd.idppl_employee', 'pd.date'])
                ->order_by('pe.name', 'ASC')
                ->order_by('pd.date', 'ASC');

            // Jika ada filter karyawan
            if (!empty($employee_id)) {
                $this->db->where('pd.idppl_employee', $employee_id);
            }

            $presence_data = $this->db->get()->result();
        }

        // Get total employees (for national holiday calculation)
        $total_employees = $this->db->count_all('ppl_employee');
        if (!empty($employee_id)) {
            $total_employees = 1; // If filtering by single employee
        }

        // Calculate absent count per date
        $absent_count = [];
        foreach ($presence_data as $row) {
            $date = $row->date;
            if (empty($row->check_in) && empty($row->check_out)) {
                $absent_count[$date] = ($absent_count[$date] ?? 0) + 1;
            }
        }

        // Determine day types (Weekend/National Holiday/Workday)
        $day_types = [];
        foreach ($presence_data as $row) {
            $date = $row->date;
            if (!isset($day_types[$date])) {
                $day_of_week = date('w', strtotime($date));

                if ($day_of_week == 0) { // Sunday
                    $day_types[$date] = 'Weekend';
                } elseif (isset($absent_count[$date]) && $absent_count[$date] >= ($total_employees * 0.8)) {
                    $day_types[$date] = 'National Holiday';
                } else {
                    $day_types[$date] = 'Workday';
                }
            }
        }

        // Process data for view (add late/early flags)
        $processed_data = [];
        foreach ($presence_data as $row) {
            $date = $row->date;
            $day_of_week = date('w', strtotime($date));

            // Set work hours based on day of week
            if ($day_of_week == 6) { // Saturday
                $standard_in = '08:10:00';
                $standard_out = '13:00:00';
            } elseif ($day_of_week >= 1 && $day_of_week <= 5) { // Weekdays
                $standard_in = '08:10:00';
                $standard_out = '16:30:00';
            } else { // Sunday
                $standard_in = null;
                $standard_out = null;
            }

            $processed_data[] = (object)[
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
                'is_verify' => $row->is_verify
            ];
        }

        // Calculate total days in the month (based on start_date)
        $total_days = 0;
        if (!empty($start_date)) {
            // Get month and year from start_date
            $month = date('m', strtotime($start_date));
            $year = date('Y', strtotime($start_date));

            // Get number of days in the month
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            // Calculate total workdays (excluding Sundays and national holidays)
            for ($day = 1; $day <= $days_in_month; $day++) {
                $date = date('Y-m-d', strtotime("$year-$month-$day"));
                $dayOfWeek = date('w', strtotime($date)); // 0 = Sunday

                // Skip Sundays
                if ($dayOfWeek == 0) continue;

                // Skip national holidays
                if (isset($day_types[$date]) && $day_types[$date] === 'National Holiday') {
                    continue;
                }

                $total_days++;
            }
        }

        // Calculate summary
        $summary = [
            'total_days' => $total_days,
            'national_holiday' => 0,
            'absent' => 0,
            'incomplete' => 0,
            'present' => 0,
            'late' => 0,
            'early_leave' => 0,
            'staff' => [], // untuk menampung ID karyawan unik
        ];

        $unique_staff = [];

        foreach ($processed_data as $row) {
            if ($row->holiday_type === 'Weekend') {
                continue;
            }

            // Collect unique employee IDs
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

        // Assign distinct staff count
        $summary['staff'] = count($unique_staff);

        // Get all employees for filter dropdown
        $this->db->select('ppl_employee.idppl_employee as idppl_employee, ppl_employee.name as name');
        $this->db->order_by('name', 'ASC');
        $employees = $this->db->get('ppl_employee')->result();

        // echo '<pre>';
        // print_r($processed_data);
        // die;

        // Prepare data for view
        $data = [
            'title' => 'Attendance Report',
            'presence' => $processed_data,
            'employee' => $employees,
            'selected_employee' => $employee_id,
            'summary' => $summary,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $this->load->view('theme/v_head', $data);
        $this->load->view('Report/v_report');
    }

    public function permit()
    {
        // Check if this is an AJAX request
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Load form validation library
        $this->load->library('form_validation');

        // Set validation rules
        $this->form_validation->set_rules('employee_id', 'Employee ID', 'required|numeric');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('reason', 'Reason', 'required');

        // Run validation
        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
            return;
        }

        // Get POST data
        $employee_id = $this->input->post('employee_id');
        $date = $this->input->post('date');
        $reason = $this->input->post('reason');

        // Prepare update data
        $update_data = [
            'is_permission' => 1,
            'reason' => $reason,
            'status' => 1 // Assuming 1 means approved, adjust as needed
        ];

        // First find the presence_detail record
        $this->db->where('idppl_employee', $employee_id);
        $this->db->where('date', $date);
        $query = $this->db->get('ppl_presence_detail');

        if ($query->num_rows() > 0) {
            // Record exists, update it
            $this->db->where('idppl_employee', $employee_id);
            $this->db->where('date', $date);
            $result = $this->db->update('ppl_presence_detail', $update_data);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Permit submitted successfully'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update permit'
                ]);
            }
        } else {
            // Record doesn't exist, create new one
            $insert_data = [
                'idppl_employee' => $employee_id,
                'date' => $date,
                'check_in' => null,
                'check_out' => null,
                'reason' => $reason,
                'is_permission' => 1,
                'status' => 1,
                // You might need to set idppl_presence here if required
            ];

            $result = $this->db->insert('ppl_presence_detail', $insert_data);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Permit created successfully'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create permit'
                ]);
            }
        }
    }
}
