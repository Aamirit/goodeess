<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->layout = 'template';
		// $this->load->model('users_model', 'users');
		// if(!$this->session->userdata('admin')) {
	    // $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
	    // redirect('admin/auth/');
	    // }
	}
	
	public function index()
	{
		$data = [];
		$this->layout = 'template';
		$this->load->view('admin/index', $data);
	}

}
