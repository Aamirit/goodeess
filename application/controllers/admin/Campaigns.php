<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaigns extends CI_Controller {

	public function __construct(){
	    parent::__construct();
		if (empty($_SESSION['email']) && is_user_allow('manage_campaigns') === false) {
			redirect(base_url('login'));
		}
        $this->layout = 'template';
		$this->load->model('admin/Campaigns_model','campaigns');
	}
	public function campaigns()
    {
		$data = [];
		$data['campaigns'] = $this->campaigns->list();
		$this->load->view('admin/campaigns/campaigns', $data);
    }
    
	public function addcampaigns()
	{
		$data =[];
		$data['response'] = false;
		$data['image_errors'] = '';
		$formData = $this->input->post();

		$this->load->library('form_validation');

        $config = array(
            array(
                'field'   => 'campaign_title',
                'label'   => 'Campaign Title',
                'rules'   => 'required'
                ),
            array(
                'field'   => 'country_code',
                'label'   => 'Country Code',
                'rules'   => 'required|max_length[5]'
                ),
            array(
                'field'   => 'campaign_crm_id',
                'label'   => 'Campaign CRM ID',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'country_code',
                'label'   => 'Country Code',
                'rules'   => 'required|max_length[5]'
            ),
            array(
                'field'   => 'vat',
                'label'   => 'VAT',
                'rules'   => 'trim'
            )
        );


		$this->form_validation->set_rules($config); 
		if($this->form_validation->run() == true){
			$insert_id = $this->campaigns->add($formData);
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
    $product_id = $_REQUEST['id'];
    $data['data'] = $this->campaigns->getedit($product_id);
    if(!empty($data['data'])){
      $data['response'] = true;
    }

    echo json_encode($data);
    exit;
	}
    
	public function editcampaigns()
	{
		$data =[];
		$data['response'] = false;
		$formData = $this->input->post();

		$this->load->library('form_validation');

        $config = array(
            array(
                'field'   => 'campaign_title',
                'label'   => 'Campaign Title',
                'rules'   => 'required'
                ),
            array(
                'field'   => 'country_code',
                'label'   => 'Country Code',
                'rules'   => 'required|max_length[5]'
                ),
            array(
                'field'   => 'campaign_settings',
                'label'   => 'Campaign Settings',
                'rules'   => 'trim'
            ),
            array(
                'field'   => 'campaign_crm_id',
                'label'   => 'Campaign CRM ID',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'vat',
                'label'   => 'VAT',
                'rules'   => 'trim'
            )
        );


		$this->form_validation->set_rules($config); 
		if($this->form_validation->run() == true){
			$insert_id = $this->campaigns->updatedit($formData,$formData['campaign_id']);
			$data['response'] = true;
		}else {
			$data['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($data);
		exit;
	}
  
	public function deletecampaign()
	{
        $data = [];
        $data['response'] = false;
        $campaign_id = $this->input->post();
        // print_r($campaign_id['campaign_id']);
        // exit;
        $delete = $this->campaigns->deleterow($campaign_id['campaign_id']);
        if($delete){
        $data['response'] = true;
        }
        echo json_encode($data);
        exit;
	}

	
}
