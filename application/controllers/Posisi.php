<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posisi extends AUTH_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('M_posisi');
	}

	public function index() {
		$data['userdata'] 	= $this->userdata;
		$data['dataPosisi'] = $this->M_posisi->select_all();

		$data['page'] 		= "my-to-dos";
		$data['judul'] 		= "My to-dos";
		$data['deskripsi'] 	= "";

		$data['modal_tambah_posisi'] = show_my_modal('modals/modal_tambah_posisi', 'tambah-posisi', $data);

		$this->template->views('posisi/home', $data);
	}

	public function tampil() {
		$data['dataPosisi'] = $this->M_posisi->select_all();
		$this->load->view('posisi/list_data', $data);
	}

	public function prosesTambah() {
		$this->form_validation->set_rules('posisi', 'to-dos', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->M_posisi->insert($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data to-dos Berhasil ditambahkan', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_err_msg('Data to-dos Gagal ditambahkan', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

	public function update() {
		$data['userdata'] 	= $this->userdata;

		$id 				= trim($_POST['id']);
		$data['dataPosisi'] = $this->M_posisi->select_by_id($id);

		echo show_my_modal('modals/modal_update_posisi', 'update-posisi', $data);
	}

	public function prosesUpdate() {
		$this->form_validation->set_rules('posisi', 'to-dos', 'trim|required');

		$data 	= $this->input->post();
		if ($this->form_validation->run() == TRUE) {
			$result = $this->M_posisi->update($data);

			if ($result > 0) {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data to-dos Berhasil diupdate', '20px');
			} else {
				$out['status'] = '';
				$out['msg'] = show_succ_msg('Data to-dos Berhasil diupdate', '20px');
			}
		} else {
			$out['status'] = 'form';
			$out['msg'] = show_err_msg(validation_errors());
		}

		echo json_encode($out);
	}

	public function delete() {
		$id = $_POST['id'];
		$result = $this->M_posisi->delete($id);
		
		if ($result > 0) {
			echo show_succ_msg('Data to-dos Berhasil dihapus', '20px');
		} else {
			echo show_err_msg('Data to-dos Gagal dihapus', '20px');
		}
	}

	public function deleteAll() {
		$result = $this->M_posisi->deleteAll();
		
		if ($result > 0) {
			echo show_succ_msg('Data to-dos Berhasil dihapus', '20px');
		} else {
			echo show_err_msg('Data to-dos Gagal dihapus', '20px');
		}
	}
}
