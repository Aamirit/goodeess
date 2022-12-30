<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		$this->layout = 'template';
		$this->load->model('Product_model','Product');
	}
	
	public function table()
	{
		$data = [];
		$data['products'] = $this->Product->list();
		// $this->load->view('admin/productlist',$products);
		$this->load->view('admin/table/datatable', $data);
	}
	
	public function addproducts()
	{
		$data =[];
		$data['response'] = false;
		$data['image_errors'] = '';
		// $this->load->helper(array('form', 'url'));
		$formData = $this->input->post();

		$this->load->library('form_validation');

      $config = array(
         array(
            'field'   => 'product_name',
            'label'   => 'Product Name',
            'rules'   => 'required'
            ),
          array(
              'field'   => 'product_price',
              'label'   => 'Price',
              'rules'   => 'required'
            ),
          array(
              'field'   => 'offer_id',
              'label'   => 'Offer Id',
              'rules'   => 'required'
            ),
          array(
              'field'   => 'campign_id',
              'label'   => 'Campaign Id',
              'rules'   => 'required'
            ),
          array(
              'field'   => 'shipping_id',
              'label'   => 'Shipping Id',
              'rules'   => 'required'
            ),
          array(
            'field'   => 'billing_id',
            'label'   => 'Billing Id',
            'rules'   => 'required'
          ),
          array(
              'field'   => 'crm_product_id',
              'label'   => 'CRM Id',
              'rules'   => 'required'
            ),
          array(
              'field'   => 'is_upsell',
              'label'   => 'Upsell',
              'rules'   => 'required'
            )
        );


		$this->form_validation->set_rules($config); 
		if($this->form_validation->run() == true){
			$insert_id = $this->Product->add($formData);
			$data['response'] = true;
		}else {
			$data['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($data);
		exit;
	}
  
	public function deleteproduct()
	{
    $data = [];
    $data['response'] = false;
		$product_id = $this->input->post();
    // print_r($product_id['product_id']);
    // exit;
		$delete = $this->Product->deleterow($product_id['product_id']);
    if($delete){
      $data['response'] = true;
    }
    echo json_encode($data);
    exit;
	}

	public function getedit()
	{
    $data = [];
    $data['response'] = false;
		$product_id = $_REQUEST['id'];
		$data['data'] = $this->Product->getedit($product_id);
		if(!empty($data['data'])){
      $data['response'] = true;
    }

    echo json_encode($data);
    exit;
	}
	
	public function editproducts()
	{
		$data =[];
		$data['response'] = false;
		// $this->load->helper(array('form', 'url'));
		$formData = $this->input->post();

		$this->load->library('form_validation');

      $config = array(
         array(
            'field'   => 'product_name',
            'label'   => 'Product Name',
            'rules'   => 'required'
            ),
          array(
              'field'   => 'product_price',
              'label'   => 'Price',
              'rules'   => 'required'
            ),
          array(
              'field'   => 'offer_id',
              'label'   => 'Offer Id',
              'rules'   => 'required'
            ),
          array(
              'field'   => 'campign_id',
              'label'   => 'Campaign Id',
              'rules'   => 'required'
            ),
          array(
              'field'   => 'shipping_id',
              'label'   => 'Shipping Id',
              'rules'   => 'required'
            ),
          array(
            'field'   => 'billing_id',
            'label'   => 'Billing Id',
            'rules'   => 'required'
          ),
          array(
              'field'   => 'crm_product_id',
              'label'   => 'CRM Id',
              'rules'   => 'required'
            ),
          array(
              'field'   => 'is_upsell',
              'label'   => 'Upsell',
              'rules'   => 'required'
            )
        );


		$this->form_validation->set_rules($config); 
		if($this->form_validation->run() == true){
			$insert_id = $this->Product->updatedit($formData,$formData['product_id']);
			$data['response'] = true;
		}else {
			$data['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($data);
		exit;
	}


}
