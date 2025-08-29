<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Time_off extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Check if the user is logged in
        if (!$this->session->userdata('logged_in')) {
            // Redirect to login with a message
            $this->session->set_flashdata('error', 'Eeettss gak boleh nakal, Login dulu ya kak hehe.');
            redirect('auth');  // Assuming 'auth' is your login controller
        }
    }

    public function index()
    {
        if ($this->session->userdata('idrole') == 1) {
            $data_time_off = $this->db
                ->select('user.full_name as full_name,
                      time_off.date as date,
                      time_off.reason as reason,
                      time_off.is_verify as is_verify,
                      time_off.idtime_off as idtime_off
                      ')
                ->join('user', 'user.iduser = time_off.iduser')
                ->get('time_off')->result();
        } else {
            $data_time_off = $this->db
                ->where('iduser', $this->session->userdata('iduser'))
                ->get('time_off')
                ->result();
        }

        $data = [
            'title' => 'Request Time Off',
            'data_time_off' => $data_time_off
        ];

        $this->load->view('theme/v_head', $data);
        $this->load->view('Time_off/v_time_off');
    }


    public function addRequest()
    {
        $reason = $this->input->post('reason');
        $date_request = $this->input->post('dateRequest');

        $data_exist = $this->db
            ->where('date', $date_request)
            ->where('iduser', $this->session->userdata('iduser'))
            ->get('time_off')
            ->num_rows();

        if ($data_exist > 0) {
            $this->session->set_flashdata('error', 'Request untuk tanggal tersebut sudah ada.');
        } else {
            $addRequest = [
                'iduser' => $this->session->userdata('iduser'),
                'reason' => $reason,
                'date' => $date_request,
                'is_verify' => 0,
                'status' => 1
            ];

            $this->db->insert('time_off', $addRequest);
            $this->session->set_flashdata('success', 'Request berhasil ditambahkan.');
            $this->session->set_flashdata('show_logout_modal', true); // Set flag for logout modal
        }

        redirect('time_off');
    }

    public function editRequest()
    {
        $id = $this->input->post('idtime_off');
        $reason = $this->input->post('reason');
        $date_request = $this->input->post('dateRequest');

        $updateData = [
            'reason' => $reason,
            'date' => $date_request
        ];

        $this->db->where('idtime_off', $id);
        $this->db->update('time_off', $updateData);

        $this->session->set_flashdata('success', 'Request berhasil diubah.');
        redirect('time_off');
    }

    public function verifyRequest()
    {
        $id = $this->input->post('idtime_off');
        $action = $this->input->post('action'); // 1: setujui, 2: tolak

        $this->db->update('time_off', ['is_verify' => $action], ['idtime_off' => $id]);
        $this->session->set_flashdata('success', 'Request berhasil diperbarui.');
        redirect('time_off');
    }
}
