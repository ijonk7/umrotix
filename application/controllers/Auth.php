<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('M_auth');
	}
	
	public function index() {
		$session = $this->session->userdata('status');

		if ($session == '') {
			$this->load->view('login');
		} else {
			redirect('my-to-dos');
		}
	}

	public function login() {
		$this->form_validation->set_rules('username', 'Username', 'required|min_length[4]|max_length[15]');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == TRUE) {
			$username = trim($_POST['username']);
			$password = trim($_POST['password']);

			$data = $this->M_auth->login($username, $password);

			if ($data == false) {
				$this->session->set_flashdata('error_msg', 'Username / Password Anda Salah.');
				redirect('Auth');
			} else {
				$session = [
					'userdata' => $data,
					'status' => "Loged in"
				];
				$this->session->set_userdata($session);
				redirect('my-to-dos');
			}
		} else {
			$this->session->set_flashdata('error_msg', validation_errors());
			redirect('');
		}
	}

	public function registrasi() {

        if (!$_POST) {
			$this->load->view('registrasi');
        } else {		
			$this->form_validation->set_rules('nama', 'Name Lengkap', 'required|trim');
			$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[admin.username]');
			$this->form_validation->set_rules('email', 'Email Address', 'required|trim|valid_email|is_unique[admin.email]');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if($this->form_validation->run() == TRUE) {
				$encrypted_password = sha1($this->input->post('password'));
				$data = array(
					'nama'  => $this->input->post('nama'),
					'username'  => $this->input->post('username'),
					'email'  => $this->input->post('email'),
					'password' => $encrypted_password,
					'foto'  => 'profil1.jpg',
				);

				$this->db->insert('admin', $data);
				$this->session->set_flashdata('register_success',1);
				redirect('');
			} else {
				$this->session->set_flashdata('error_msg', validation_errors());
				redirect('register');
			}			
	 	}
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect('');
	}
}
