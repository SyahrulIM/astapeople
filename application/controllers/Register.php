<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Register extends CI_Controller
{

    public function index()
    {
        $this->load->view('Register/v_register');
    }

    public function addUser()
    {
        $username = $this->input->post('inputUsername');
        $email = $this->input->post('inputEmail');
        $fullname = $this->input->post('inputFullname');
        $handphone = $this->input->post('inputHandphone');
        $password = $this->input->post('inputPassword');

        $addUser = [
            'username' => $username,
            'email' => $email,
            'full_name' => $fullname,
            'handphone' => $handphone,
            'idrole' => 4,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_by' => $this->session->userdata('username'),
            'created_date' => date("Y-m-d H:i:s"),
            'updated_by' => $this->session->userdata('username'),
            'updated_date' => date("Y-m-d H:i:s")
        ];

        $this->db->insert('user', $addUser);

        // Start Kirim pesan WhatsApp via Fonnte Untuk Register
        $target = $handphone;

        if (!empty($target)) {
            $token = 'EyuhsmTqzeKaDknoxdxt';
            $message = 'Registrasi anda berhasil. Tunggu verifikasi dari admin maksimal 1x24 Jam.';

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
        // End

        // Start Kirim pesan WhatsApp via Fonnte
        $this->db->select('handphone');
        $this->db->from('user');
        $this->db->where('idrole', 1);
        $this->db->where('is_whatsapp', 1);
        $this->db->where('status', 1);
        $this->db->where('handphone IS NOT NULL');
        $query = $this->db->get();
        $results = $query->result();

        $targets = array_column($results, 'handphone');
        $target = count($targets) > 1 ? implode(',', $targets) : (count($targets) === 1 ? $targets[0] : '');

        if ($target !== '') {
            $token = 'EyuhsmTqzeKaDknoxdxt';
            $message = 'Akun dengan Username: ' . $username . ', Email: ' . $email . ', dan No.Handphone: ' . $handphone . ' membutuhkan Verifikasi dari superadmin di Asta People. Mohon segera diproses, terima kasih.';

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
        // End

        // ========== Start Kirim Email ==========
        $this->load->library('email');

        $config = array(
            'protocol'    => 'smtp',
            'smtp_host'   => 'sandbox.smtp.mailtrap.io',
            'smtp_port'   => 2525,
            'smtp_user'   => '9698c51c14831c',
            'smtp_pass'   => '5f50b84b249acc',
            'smtp_crypto' => 'tls',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'crlf'        => "\r\n",
            'newline'     => "\r\n"
        );

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from('chalung.izha@gmail.com', 'Asta People'); // pengirim
        $this->email->to($email); // penerima pakai variabel

        $this->email->subject('Registrasi User Baru - Asta People');
        $this->email->message("
            <h3>Akun baru berhasil registrasi</h3>
            <p><b>Username:</b> $username</p>
            <p><b>Email:</b> $email</p>
            <p><b>No. Handphone:</b> $handphone</p>
            <p>Butuh verifikasi admin secepatnya.</p>
        ");

        if (!$this->email->send()) {
            log_message('error', $this->email->print_debugger()); // kalau gagal, cek log
        }
        // ========== End Kirim Email ==========

        $this->session->set_flashdata('success', 'Registrasi anda berhasil. Tunggu verifikasi dari admin maksimal 1x24 Jam.');
        redirect('auth');
    }
}
