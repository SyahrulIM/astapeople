<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bank_password extends CI_Controller
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
        $this->db->select('bp.*, 
        GROUP_CONCAT(u.full_name SEPARATOR ", ") as pic_names,
        GROUP_CONCAT(pic.iduser) as pic_ids');
        $this->db->from('ppl_bank_password bp');
        $this->db->join('ppl_pic_bank_password pic', 'bp.idppl_bank_password = pic.idppl_bank_password', 'left');
        $this->db->join('user u', 'pic.iduser = u.iduser', 'left');
        $this->db->where('bp.status', 1);
        $this->db->group_by('bp.idppl_bank_password');

        $query = $this->db->get();

        $data = [
            'title' => 'Bank Password',
            'data_bp' => $query->result(),
            'users' => $this->db->get('user')->result()
        ];

        $this->load->view('theme/v_head', $data);
        $this->load->view('Bank_password/v_bank_password', $data);
    }

    public function createBankAccount()
    {
        $data = [
            'account' => $this->input->post('account'), // bikin uppercase
            'browser' => $this->input->post('browser'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'verification' => $this->input->post('verification'),
            'category' => $this->input->post('category'),
            'description' => $this->input->post('description'),
            'created_by' => $this->session->userdata('username'),
            'created_date' => date("Y-m-d H:i:s"),
            'updated_by' => $this->session->userdata('username'),
            'updated_date' => date("Y-m-d H:i:s"),
            'status' => 1
        ];

        // insert ke tabel ppl_bank_password
        $this->db->insert('ppl_bank_password', $data);
        $idppl_bank_password = $this->db->insert_id();

        // simpan PIC kalau ada
        $pic_ids = $this->input->post('pic_ids'); // format: "1,2,3"
        if (!empty($pic_ids)) {
            $picArray = explode(',', $pic_ids);
            foreach ($picArray as $iduser) {
                $this->db->insert('ppl_pic_bank_password', [
                    'idppl_bank_password' => $idppl_bank_password,
                    'iduser' => $iduser,
                    'status' => 1
                ]);
            }
        }

        $this->session->set_flashdata('success', 'Account & Password berhasil ditambahkan.');
        redirect('bank_password');
    }

    public function edit($id)
    {
        // Get the bank account data with PIC information
        $this->db->select('bp.*, 
        GROUP_CONCAT(u.iduser SEPARATOR ",") as pic_ids,
        GROUP_CONCAT(u.full_name SEPARATOR ", ") as pic_names');
        $this->db->from('ppl_bank_password bp');
        $this->db->join('ppl_pic_bank_password pic', 'bp.idppl_bank_password = pic.idppl_bank_password', 'left');
        $this->db->join('user u', 'pic.iduser = u.iduser', 'left');
        $this->db->where('bp.idppl_bank_password', $id);
        $this->db->group_by('bp.idppl_bank_password');

        $query = $this->db->get();
        $data = $query->row();

        if ($data) {
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data not found'
            ]);
        }
    }

    public function updateBankAccount($id)
    {
        $data = [
            'account' => $this->input->post('account'),
            'browser' => $this->input->post('browser'),
            'email' => $this->input->post('email'),
            'password' => $this->input->post('password'),
            'verification' => $this->input->post('verification'),
            'category' => $this->input->post('category'),
            'description' => $this->input->post('description'),
            'updated_by' => $this->session->userdata('username'),
            'updated_date' => date("Y-m-d H:i:s")
        ];

        // Update main table
        $this->db->where('idppl_bank_password', $id);
        $this->db->update('ppl_bank_password', $data);

        // Handle PICs - first remove existing
        $this->db->where('idppl_bank_password', $id);
        $this->db->delete('ppl_pic_bank_password');

        // Add new PICs if any
        $pic_ids = $this->input->post('pic_ids');
        if (!empty($pic_ids)) {
            $picArray = explode(',', $pic_ids);
            foreach ($picArray as $iduser) {
                if (!empty($iduser)) {
                    $this->db->insert('ppl_pic_bank_password', [
                        'idppl_bank_password' => $id,
                        'iduser' => $iduser
                    ]);
                }
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Account & Password berhasil diperbarui.'
        ]);
    }

    public function deleteBankAccount($id)
    {
        // Check if this is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request method.'
            ]);
            return;
        }

        // Update related PICs status to 0 (soft delete)
        $this->db->set('status', 0); // Changed to 'status' assuming that's your soft delete column
        $this->db->where('idppl_bank_password', $id);
        $success = $this->db->update('ppl_bank_password');

        if ($success) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Account & Password berhasil dihapus.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus data.'
            ]);
        }
    }
}
