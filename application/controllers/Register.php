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
        $this->session->set_flashdata('success', 'Registrasi berhasil ditambakan silakan menuggu verifikasi dari admin.');
        redirect('auth');
    }
}
