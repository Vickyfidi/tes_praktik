<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pasien extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Pasien', 'pasien');
	}

	public function index()
	{
		$this->load->view('pasien_view');
	}

	public function ajax_list()
	{
		$list = $this->pasien->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pasien) {
			$no++;
			$row = array();
			$row[] = $pasien->no_rm;
			$row[] = $pasien->nama;
			$row[] = $pasien->tgl_lahir;
			$row[] = $pasien->jenis_kelamin;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_pasien(' . "'" . $pasien->id . "'" . ')"> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_pasien(' . "'" . $pasien->id . "'" . ')"> Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pasien->count_all(),
			"recordsFiltered" => $this->pasien->count_filtered(),
			"data" => $data,
		);
		echo json_encode($output);
	}



	public function ajax_add()
	{
		$data = array(
			'no_rm' => $this->input->post('no_rm'),
			'nama' => $this->input->post('nama'),
			'tgl_lahir' => $this->input->post('tgl_lahir'),
			'jenis_kelamin' => $this->input->post('jenis_kelamin')
		);
		$insert = $this->pasien->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->pasien->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_update()
	{
		$data = array(
			'no_rm' => $this->input->post('no_rm'),
			'nama' => $this->input->post('nama'),
			'tgl_lahir' => $this->input->post('tgl_lahir'),
			'jenis_kelamin' => $this->input->post('jenis_kelamin')
		);
		$this->pasien->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->pasien->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
}
