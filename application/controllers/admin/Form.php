<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		$this->layout = 'template';
		$this->load->helper(array('form', 'url'));
		$this->load->model('CrmSetting_model','crm_model');
	}
	public function validate()
    {
	    $products['data'] = $this->crm_model->setting_crm();
        $this->load->view('admin/crm-setting/crm-setting',$products);
    }
    
	public function post_setting(){
		$data =[];
		$data['response'] = false;
		$formData = $this->input->post();

        $this->load->library('form_validation');

        $config = array(
            array(
                'field'   => 'limelight_url',
                'label'   => 'URL',
                'rules'   => 'required'
                )
            );

        $this->form_validation->set_rules($config); 
        if($this->form_validation->run() == true){
            $insert_id = $this->crm_model->add($formData);
            $data['response'] = true;
        }else {
            $data['errors'] = $this->form_validation->error_array();
        }
        echo json_encode($data);
        exit;
	}

}
