<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
	    parent::__construct();
	    $this->load->model('admin/Auth_model');
		$this->load->model('admin/Permissions_model','permissions');
	}
	public function loginView()
	{ 
		if(empty($this->session->userdata('email'))){
			$this->load->view('admin/auth/login');
		}else{
			$urlRedirect = base_url('admin/dashboard');
			redirect($urlRedirect);
		}
	}
	public function logindash()
	{
		$data = [];
		$data['response'] = false;
		$formData = $this->input->post();
		$formData['password'] = md5($formData['password']);
		if (!empty($formData)) {
			$where = "admin.email='".$formData['email']."' AND admin.password='".$formData['password']."'";
	      	$results = $this->Auth_model->check('*', $where, true, '', '1', '');
			if(!empty($results)){
				$data['response'] = true;
				$permissions = $this->permissions->getedit($results[0]['id']);
				if(!empty($permissions)) {
					$this->session->set_userdata('permissions', json_decode($permissions[0]['permissions']));
				}
				$this->session->set_userdata('email', $formData['email']);
				$this->session->set_userdata('name', $results[0]['name']);
				$this->session->set_userdata('user_id', $results[0]['id']);
	        }
		}
		echo json_encode($data);
		exit;
		
	}
	public function logout()
	{
		session_destroy();
		redirect(base_url('login'));
	}
	
}
