<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function index()
	{
		// Check if user is already logged in
		if ($this->session->userdata('logged_in')) {
			redirect('dashboard');  // Redirect to the dashboard if logged in
		}

		$this->load->view('Auth/v_auth');
	}

	public function login()
	{
		// Set form validation rules
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal, reload halaman login dengan error
			$this->load->view('Auth/v_auth');
		} else {
			// Ambil input user
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			// Cek user berdasarkan username dengan status = 1
			$query = $this->db->get_where('user', ['username' => $username, 'status' => 1]);
			$user = $query->row();

			if ($user && password_verify($password, $user->password)) {
				// Login berhasil, set session
				$this->session->set_userdata([
					'logged_in' => TRUE,
					'iduser' => $user->iduser,
					'full_name' => $user->full_name,
					'username' => $user->username,
					'idrole' => $user->idrole,
					'foto' => $user->foto,
				]);
				redirect('dashboard'); // Arahkan ke dashboard
			} else {
				// Username / password salah atau akun tidak aktif
				$this->session->set_flashdata('error', 'Invalid username, password, or inactive account.');
				redirect('auth');
			}
		}
	}

	public function logout()
	{
		// Destroy the session and redirect to login page
		$this->session->sess_destroy();
		redirect('auth');
	}
}
