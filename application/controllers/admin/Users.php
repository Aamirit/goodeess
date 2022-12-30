<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct(){
	    parent::__construct();
		if (empty($_SESSION['email']) && is_user_allow('manage_users') === false) {
			redirect(base_url('login'));
		}
        $this->layout = 'template';
		$this->load->model('admin/Users_model','users');
		$this->load->model('admin/Permissions_model','permissions');
	}
	public function users()
    {
		$data = [];
		$data['users'] = $this->users->list();
		$this->load->view('admin/users/users', $data);
    }
    
	public function create_user()
	{


		$data =[];
		$data['response'] = false;
		$data['image_errors'] = '';
		$formData = $this->input->post();
		$this->load->library('form_validation');
        $config = array(
            array(
                'field'   => 'name',
                'label'   => 'User Name',
                'rules'   => 'required'
                ),
            array(
                'field'   => 'email',
                'label'   => 'Email',
                'rules'   => 'required|valid_email|is_unique[admin.email]'
                ),
            array(
                'field'   => 'password',
                'label'   => 'Password',
                'rules'   => 'required'
            )
        );


		$this->form_validation->set_rules($config); 
		if($this->form_validation->run() == true){
			$formData['password'] = md5($formData['password']);
			$insert_id = $this->users->add($formData);
			$permissions = array('user_id'=>$insert_id,'permissions' => !empty($formData['permissions']) ? json_encode($formData['permissions']):json_encode([]));
			$permission_id = $this->permissions->add($permissions);
			$data['response'] = true;
		}else {
			$data['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($data);
		exit;
	}
    
	public function getedit()
	{
    $data = [];
    $data['response'] = false;
    $user_id = $_REQUEST['id'];
    $data['data'] = $this->users->getedit($user_id);
		$data['permissions'] = $this->permissions->getedit($user_id);
    if(!empty($data['data'])){
      $data['response'] = true;
    }

    echo json_encode($data);
    exit;
	}
    
	public function editUser()
	{
		$data =[];
		$data['response'] = false;
		$formData = $this->input->post();

		$this->load->library('form_validation');
        $user = $this->users->getedit($formData['id']);
		$config = array(
			array(
				'field'   => 'name',
				'label'   => 'User Name',
				'rules'   => 'required'
			),
			array(
				'field'   => 'email',
				'label'   => 'Email',
				'rules'   => 'required|valid_email'
			)
		);
		if(!empty($user) && !empty($formData['email']) && $user[0]['email'] !== $formData['email']) {
			$config[] = array(
				'field'   => 'email',
				'label'   => 'Email',
				'rules'   => 'required|valid_email|is_unique[admin.email]'
			);
		} else {
			$config[] = array(
				'field'   => 'email',
				'label'   => 'Email',
				'rules'   => 'required|valid_email'
			);
		}

		$this->form_validation->set_rules($config); 
		if($this->form_validation->run() == true){
			if(!empty($formData['password'])){
				$formData['password'] = md5($formData['password']);
			}
			$insert_id = $this->users->updatedit($formData,$formData['id']);
			$permissions = array('permissions' => !empty($formData['permissions']) ? json_encode($formData['permissions']):json_encode([]));
			$permission_id = $this->permissions->updatedit($permissions, $formData['id']);
			$data['response'] = true;
		}else {
			$data['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($data);
		exit;
	}
  
	public function delete_user()
	{
        $data = [];
        $data['response'] = false;
        $user_id = $this->input->post();
        $delete = $this->users->deleterow($user_id['id']);
        if($delete){
        	$this->permissions->deleterow($user_id['id']);
        $data['response'] = true;
        }
        echo json_encode($data);
        exit;
	}

	
}
