<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			return redirect('dashboard');
		}

		$this->load->view('Auth/v_auth');
	}

	public function login()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');

		if ($this->form_validation->run() === FALSE) {
			return $this->load->view('Auth/v_auth');
		}

		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);

		// Faster: gunakan LIMIT 1 + pilih kolom yang diperlukan saja
		$this->db->select('iduser,idrole,username,password,full_name,foto,status');
		$this->db->from('user');
		$this->db->where('username', $username);
		$this->db->where('status', 1);
		$this->db->limit(1);
		$user = $this->db->get()->row();

		// Validasi user dan password
		if (!$user || !password_verify($password, $user->password)) {
			$this->session->set_flashdata('error', 'Username or password is incorrect.');
			return redirect('auth');
		}

		// Set session cepat & rapi
		$this->session->set_userdata([
			'logged_in' => TRUE,
			'iduser'    => $user->iduser,
			'full_name' => $user->full_name,
			'username'  => $user->username,
			'idrole'    => $user->idrole,
			'foto'      => $user->foto
		]);

		return redirect('dashboard');
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('auth');
	}
}
