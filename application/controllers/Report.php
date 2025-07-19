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
            $subquery = "
                SELECT MAX(pp.created_date) AS created_date, pd.idppl_employee, pd.date
                FROM ppl_presence_detail pd
                JOIN ppl_presence pp ON pp.idppl_presence = pd.idppl_presence
                WHERE pd.date >= '$start_date' AND pd.date <= '$end_date'
                GROUP BY pd.idppl_employee, pd.date
            ";

            $this->db->select('pe.name, pd.date, pd.check_in, pd.check_out');
            $this->db->from('ppl_presence_detail pd');
            $this->db->join('ppl_presence pp', 'pp.idppl_presence = pd.idppl_presence');
            $this->db->join('ppl_employee pe', 'pe.idppl_employee = pd.idppl_employee');
            $this->db->join("($subquery) latest", 'latest.idppl_employee = pd.idppl_employee AND latest.date = pd.date AND latest.created_date = pp.created_date');

            if (!empty($employee_id)) {
                $this->db->where('pd.idppl_employee', $employee_id);
            }

            $this->db->order_by('pe.name', 'ASC');
            $this->db->order_by('pd.date', 'ASC');

            $presence_data = $this->db->get()->result();
        }

        // Get all employees for filter
        $employees = $this->db->get('ppl_employee')->result();

        // Calculate total absence count per date
        $absent_count_by_date = [];

        foreach ($presence_data as $row) {
            if (empty($row->check_in) && empty($row->check_out)) {
                $date = $row->date;
                if (!isset($absent_count_by_date[$date])) {
                    $absent_count_by_date[$date] = 1;
                } else {
                    $absent_count_by_date[$date]++;
                }
            }
        }

        // Determine holiday type by date
        $holiday_by_date = [];

        foreach ($presence_data as $row) {
            $date = $row->date;
            $day_of_week = date('w', strtotime($date)); // 0=Sunday, ..., 6=Saturday

            if (!isset($holiday_by_date[$date])) {
                if ($day_of_week == 0) {
                    $holiday_by_date[$date] = 'Weekend';
                } elseif (isset($absent_count_by_date[$date]) && $absent_count_by_date[$date] > 5) {
                    $holiday_by_date[$date] = 'National Holiday';
                } else {
                    $holiday_by_date[$date] = 'Workday';
                }
            }
        }

        // Append lateness and early leave flags to each row
        foreach ($presence_data as &$row) {
            $day_of_week = date('w', strtotime($row->date));
            $standard_in = null;
            $standard_out = null;

            if ($day_of_week >= 1 && $day_of_week <= 5) { // Monday to Friday
                $standard_in = '08:10:00';
                $standard_out = '16:30:00';
            } elseif ($day_of_week == 6) { // Saturday
                $standard_in = '08:10:00';
                $standard_out = '13:00:00';
            }

            $row->is_late = (!empty($row->check_in) && $row->check_in > $standard_in);
            $row->left_early = (!empty($row->check_out) && $row->check_out < $standard_out);
            $row->holiday_type = $holiday_by_date[$row->date] ?? 'Workday';
        }

        // Summary Counters
        $summary = [
            'present' => 0,
            'absent' => 0,
            'national_holiday' => 0,
            'incomplete' => 0
        ];

        // Hitung summary
        foreach ($presence_data as $row) {
            $isWeekend = $row->holiday_type === 'Weekend';
            $isNationalHoliday = $row->holiday_type === 'National Holiday';

            if ($isWeekend) {
                continue; // Skip weekend
            }

            if ($isNationalHoliday) {
                $summary['national_holiday']++;
            } elseif (empty($row->check_in) && empty($row->check_out)) {
                $summary['absent']++;
            } elseif (empty($row->check_in) || empty($row->check_out)) {
                $summary['incomplete']++;
            } else {
                $summary['present']++;
            }
        }

        // Hitung jumlah hari kerja: Senin–Sabtu (0 = Minggu, 6 = Sabtu), kecuali National Holiday
        $total_days = 0;

        $period = new DatePeriod(
            new DateTime($start_date),
            new DateInterval('P1D'),
            (new DateTime($end_date))->modify('+1 day')
        );

        foreach ($period as $date) {
            $dayOfWeek = $date->format('w'); // 0 = Minggu
            $dateStr = $date->format('Y-m-d');

            $isSunday = ($dayOfWeek == 0);
            $isNationalHoliday = isset($holiday_by_date[$dateStr]) && $holiday_by_date[$dateStr] === 'National Holiday';

            if (!$isSunday && !$isNationalHoliday) {
                $total_days++;
            }
        }

        $data = [
            'title' => 'Report',
            'presence' => $presence_data,
            'employee' => $employees,
            'selected_employee' => $employee_id,
            'absent_count_by_date' => $absent_count_by_date,
            'holiday_by_date' => $holiday_by_date,
            'summary' => $summary,
            'total_days' => $total_days
        ];

        $this->load->view('theme/v_head', $data);
        $this->load->view('Report/v_report');
    }
}
