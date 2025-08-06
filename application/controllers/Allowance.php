<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
                SELECT e.idppl_employee, e.name, d.date, d.check_in, d.check_out, d.reason
                FROM ppl_employee e
                LEFT JOIN ppl_presence_detail d ON e.idppl_employee = d.idppl_employee
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

                // Tetap dapat meal jika check_in & check_out lengkap ATAU reason 'dinas'
                $got_meal = ($row->check_in && $row->check_out) || (strtolower($row->reason) === 'dinas');

                $grouped[$id]['presence'][$date] = $got_meal ? '✓' : '-';
                if ($got_meal) $grouped[$id]['total_attend']++;
            }

            $data['results'] = $grouped;
        }

        $this->load->view('theme/v_head', $data);
        $this->load->view('Allowance/v_allowance', $data);
    }
}
