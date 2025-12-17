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
        $filter_account = $this->input->get('inputFilterAccount');
        $filter_category = $this->input->get('inputFilterCategory');
        $filter_email = $this->input->get('inputFilterEmail');
        $filter_verification = $this->input->get('inputFilterVerification');

        // Start Data Bank Password
        $this->db->select('bp.*, 
        GROUP_CONCAT(DISTINCT u.full_name SEPARATOR ", ") as pic_names,
        GROUP_CONCAT(DISTINCT pic.iduser) as pic_ids,
        GROUP_CONCAT(DISTINCT d.devices SEPARATOR ", ") as device_names,
        GROUP_CONCAT(DISTINCT d.idppl_devices) as device_ids,
        GROUP_CONCAT(DISTINCT r.nama_role SEPARATOR ", ") as role');
        $this->db->from('ppl_bank_password bp');
        $this->db->join('ppl_pic_bank_password pic', 'bp.idppl_bank_password = pic.idppl_bank_password', 'left');
        $this->db->join('user u', 'pic.iduser = u.iduser', 'left');
        $this->db->join('ppl_devices_bank_password dbp', 'bp.idppl_bank_password = dbp.idppl_bank_password', 'left');
        $this->db->join('ppl_devices d', 'dbp.idppl_devices = d.idppl_devices', 'left');
        $this->db->join('role r', 'u.idrole = r.idrole', 'left');
        $this->db->where('bp.status', 1);
        if ($filter_account) {
            $this->db->where('bp.account', $filter_account);
        }
        if ($filter_category) {
            $this->db->where('bp.category', $filter_category);
        }
        if ($filter_email) {
            $this->db->where('bp.email', $filter_email);
        }
        if ($filter_verification) {
            $this->db->where('bp.verification', $filter_verification);
        }
        $this->db->group_by('bp.idppl_bank_password');
        $query = $this->db->get();
        // End

        // Start Account
        $this->db->distinct();
        $this->db->select('account');
        $this->db->where('account IS NOT NULL', null, false);
        $this->db->where('account !=', '');
        $this->db->where('status', '1');
        $this->db->order_by('account ASC');
        $account = $this->db->get('ppl_bank_password');
        // End

        // Start Account Filter
        $this->db->distinct();
        $this->db->select('account');
        $this->db->where('account IS NOT NULL', null, false);
        $this->db->where('account !=', '');
        $this->db->where('status', '1');
        if ($filter_email) {
            $this->db->where('email', $filter_email);
        }
        $this->db->order_by('account ASC');
        $account_filter = $this->db->get('ppl_bank_password');
        // End

        // Start Category
        $this->db->distinct();
        $this->db->select('category');
        $this->db->where('category IS NOT NULL', null, false);
        $this->db->where('category !=', '');
        $this->db->where('status', '1');
        $this->db->order_by('category ASC');
        $category = $this->db->get('ppl_bank_password');
        // End

        // Start Email
        $this->db->distinct();
        $this->db->select('email');
        $this->db->where('email IS NOT NULL', null, false);
        $this->db->where('email !=', '');
        $this->db->where('status', '1');
        $this->db->order_by('email ASC');
        $email = $this->db->get('ppl_bank_password');
        // End


        // Start Verification
        $this->db->distinct();
        $this->db->select('verification');
        $this->db->where('verification IS NOT NULL', null, false);
        $this->db->where('verification !=', '');
        $this->db->where('status', '1');
        $this->db->order_by('verification ASC');
        $verification = $this->db->get('ppl_bank_password');
        // End

        // Start Email Filter
        $this->db->distinct();
        $this->db->select('email');
        $this->db->where('email IS NOT NULL', null, false);
        $this->db->where('email !=', '');
        $this->db->where('status', '1');
        if ($filter_account) {
            $this->db->where('account', $filter_account);
        }
        $this->db->order_by('email ASC');
        $email_filter = $this->db->get('ppl_bank_password');
        // End

        // Start Verification Filter
        $this->db->distinct();
        $this->db->select('verification');
        $this->db->where('verification IS NOT NULL', null, false);
        $this->db->where('verification !=', '');
        $this->db->where('status', '1');
        if ($filter_account) {
            $this->db->where('account', $filter_account);
        }
        if ($filter_email) {
            $this->db->where('email', $filter_email);
        }
        $this->db->order_by('verification ASC');
        $verification_filter = $this->db->get('ppl_bank_password');
        // End

        // Start PIC Filter
        $this->db->distinct();
        $this->db->select('u.iduser, u.full_name');
        $this->db->from('ppl_pic_bank_password pic');
        $this->db->join('user u', 'pic.iduser = u.iduser', 'left');
        $this->db->join('ppl_bank_password bp', 'bp.idppl_bank_password = pic.idppl_bank_password', 'left');
        $this->db->where('u.full_name IS NOT NULL', null, false);
        $this->db->where('u.full_name !=', '');
        $this->db->where('bp.status', 1);
        if ($filter_email) {
            $this->db->where('bp.email', $filter_email);
        }
        if ($filter_account) {
            $this->db->where('bp.account', $filter_account);
        }
        $this->db->order_by('u.full_name ASC');
        $pic_filter = $this->db->get();
        // End

        // Start Role Filter
        $this->db->distinct();
        $this->db->select('r.idrole, r.nama_role');
        $this->db->from('ppl_pic_bank_password pic');
        $this->db->join('user u', 'pic.iduser = u.iduser', 'left');
        $this->db->join('ppl_bank_password bp', 'bp.idppl_bank_password = pic.idppl_bank_password', 'left');
        $this->db->join('role r', 'u.idrole = r.idrole', 'left');
        $this->db->where('bp.status', 1);
        $this->db->where('r.status', 1);
        if ($filter_email) {
            $this->db->where('bp.email', $filter_email);
        }
        if ($filter_account) {
            $this->db->where('bp.account', $filter_account);
        }
        $this->db->order_by('r.nama_role ASC');
        $role_filter = $this->db->get();
        // End

        // Start devices
        $devices = $this->db->get('ppl_devices');
        // End

        // Start Devices Filter
        $this->db->distinct();
        $this->db->select('d.idppl_devices, d.devices');
        $this->db->from('ppl_devices_bank_password dbp');
        $this->db->join('ppl_devices d', 'dbp.idppl_devices = d.idppl_devices', 'left');
        $this->db->join('ppl_bank_password bp', 'bp.idppl_bank_password = dbp.idppl_bank_password', 'left');
        $this->db->where('d.devices IS NOT NULL', null, false);
        $this->db->where('d.devices !=', '');
        $this->db->where('bp.status', 1);

        if ($filter_email) {
            $this->db->where('bp.email', $filter_email);
        }
        if ($filter_account) {
            $this->db->where('bp.account', $filter_account);
        }
        if ($filter_category) {
            $this->db->where('bp.category', $filter_category);
        }
        if ($filter_verification) {
            $this->db->where('bp.verification', $filter_verification);
        }

        $this->db->order_by('d.devices ASC');
        $devices_filter = $this->db->get();
        // End

        // Start hitung berapa filter aktif
        $active_filters = 0;
        if ($filter_account) $active_filters++;
        if ($filter_category) $active_filters++;
        if ($filter_email) $active_filters++;
        if ($filter_verification) $active_filters++;
        // End

        $data = [
            'title' => 'Bank Password',
            'data_bp' => $query->result(),
            'account' => $account->result(),
            'account_filter' => $account_filter->result(),
            'category' => $category->result(),
            'email' => $email->result(),
            'email_filter' => $email_filter->result(),
            'verification' => $verification->result(),
            'verification_filter' => $verification_filter->result(),
            'role_filter' => $role_filter->result(),
            'active_filters' => $active_filters,
            'users' => $this->db->get('user')->result(),
            'devices' => $devices->result(),
            'devices_filter' => $devices_filter->result(),
        ];

        $this->load->view('theme/v_head', $data);
        $this->load->view('Bank_password/v_bank_password', $data);
    }

    public function createBankAccount()
    {
        $data = [
            'account' => strtoupper($this->input->post('account')), // bikin uppercase
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

        // simpan Devices kalau ada
        $devices_ids = $this->input->post('devices_ids'); // format: "4,5,6"
        if (!empty($devices_ids)) {
            $deviceArray = explode(',', $devices_ids);
            foreach ($deviceArray as $iddevice) {
                $this->db->insert('ppl_devices_bank_password', [
                    'idppl_bank_password' => $idppl_bank_password,
                    'idppl_devices' => $iddevice
                ]);
            }
        }

        $this->session->set_flashdata('success', 'Account & Password berhasil ditambahkan.');
        redirect('bank_password');
    }

    public function edit($id)
    {
        $this->db->select('bp.*, 
        GROUP_CONCAT(DISTINCT u.iduser SEPARATOR ",") as pic_ids,
        GROUP_CONCAT(DISTINCT u.full_name SEPARATOR ", ") as pic_names,
        GROUP_CONCAT(DISTINCT d.idppl_devices SEPARATOR ",") as device_ids,
        GROUP_CONCAT(DISTINCT d.devices SEPARATOR ", ") as device_names
    ');
        $this->db->from('ppl_bank_password bp');
        $this->db->join('ppl_pic_bank_password pic', 'bp.idppl_bank_password = pic.idppl_bank_password', 'left');
        $this->db->join('user u', 'pic.iduser = u.iduser', 'left');
        $this->db->join('ppl_devices_bank_password dbp', 'bp.idppl_bank_password = dbp.idppl_bank_password', 'left');
        $this->db->join('ppl_devices d', 'dbp.idppl_devices = d.idppl_devices', 'left');
        $this->db->where('bp.idppl_bank_password', $id);
        $this->db->group_by('bp.idppl_bank_password');

        $query = $this->db->get();
        $data = $query->row();

        if ($data) {
            // bersihin spasi PIC ids
            $data->pic_ids = isset($data->pic_ids) ? preg_replace('/\s+/', '', $data->pic_ids) : '';

            // bersihin spasi device ids
            $data->device_ids = isset($data->device_ids) ? preg_replace('/\s+/', '', $data->device_ids) : '';

            echo json_encode([
                'status' => 'success',
                'data' => $data
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

        // Update data utama
        $this->db->where('idppl_bank_password', $id);
        $this->db->update('ppl_bank_password', $data);

        // --- Reset PICs lama ---
        $this->db->where('idppl_bank_password', $id);
        $this->db->delete('ppl_pic_bank_password');

        // Tambah PIC baru
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

        // --- Reset Devices lama ---
        $this->db->where('idppl_bank_password', $id);
        $this->db->delete('ppl_devices_bank_password');

        // Tambah Devices baru
        $device_ids = $this->input->post('devices_ids');
        if (!empty($device_ids)) {
            $deviceArray = explode(',', $device_ids);
            foreach ($deviceArray as $deviceId) {
                if (!empty($deviceId)) {
                    $this->db->insert('ppl_devices_bank_password', [
                        'idppl_bank_password' => $id,
                        'idppl_devices' => $deviceId
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
