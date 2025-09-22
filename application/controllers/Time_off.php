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
        if ($this->session->userdata('idrole') == 1 || $this->session->userdata('idrole') == 6) {
            $data_time_off = $this->db
                ->select('user.full_name as full_name,
                      ppl_time_off.date as date,
                      ppl_time_off.reason as reason,
                      ppl_time_off.is_verify as is_verify,
                      ppl_time_off.idppl_time_off as idtime_off
                      ')
                ->join('user', 'user.iduser = ppl_time_off.iduser')
                ->order_by('date', 'DESC')
                ->get('ppl_time_off')->result();
        } else {
            $data_time_off = $this->db
                ->select('ppl_time_off.date as date,
                      ppl_time_off.reason as reason,
                      ppl_time_off.is_verify as is_verify,
                      ppl_time_off.idppl_time_off as idtime_off
                      ')
                ->where('iduser', $this->session->userdata('iduser'))
                ->get('ppl_time_off')
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
        $iduser = $this->session->userdata('iduser');

        $data_exist = $this->db
            ->where('date', $date_request)
            ->where('iduser', $iduser)
            ->get('ppl_time_off')
            ->num_rows();

        if ($data_exist > 0) {
            $this->session->set_flashdata('error', 'Request untuk tanggal tersebut sudah ada.');
        } else {
            $addRequest = [
                'iduser' => $iduser,
                'reason' => $reason,
                'date' => $date_request,
                'is_verify' => 0,
                'status' => 1
            ];

            $this->db->insert('ppl_time_off', $addRequest);
            $this->session->set_flashdata('success', 'Request berhasil ditambahkan.');
            $this->session->set_flashdata('show_logout_modal', true);
        }

        // Ambil data user yang request
        $user = $this->db->get_where('user', ['iduser' => $iduser])->row();

        // Start Kirim pesan WhatsApp via Fonnte
        $this->db->select('handphone');
        $this->db->from('user');
        $this->db->where_in('idrole', [1, 6]);
        $this->db->where('is_whatsapp', 1);
        $this->db->where('status', 1);
        $this->db->where('handphone IS NOT NULL', null, false);
        $query = $this->db->get();
        $results = $query->result();

        $targets = array_column($results, 'handphone');
        $target = count($targets) > 1 ? implode(',', $targets) : (count($targets) === 1 ? $targets[0] : '');

        if ($target !== '') {
            $token = 'EyuhsmTqzeKaDknoxdxt';
            $message = "📢 Pemberitahuan Izin Baru\n\n"
                . "👤 Username: {$user->username}\n"
                . "📅 Tanggal: {$date_request}\n"
                . "📝 Alasan: {$reason}\n\n"
                . "Telah ditambahkan, Butuh diverifikasi. Terima kasih 🙏";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => array(
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $token
                ),
            ));

            curl_exec($curl);
            curl_close($curl);
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

        $this->db->where('idppl_time_off', $id);
        $this->db->update('ppl_time_off', $updateData);

        $this->session->set_flashdata('success', 'Request berhasil diubah.');
        redirect('time_off');
    }

    public function verifyRequest()
    {
        $id = $this->input->post('idtime_off');
        $action = $this->input->post('action'); // 1: setujui, 2: tolak

        $this->db->update('ppl_time_off', ['is_verify' => $action], ['idppl_time_off' => $id]);
        $this->session->set_flashdata('success', 'Request berhasil diperbarui.');
        redirect('time_off');
    }
}
