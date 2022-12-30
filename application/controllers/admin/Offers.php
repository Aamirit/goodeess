<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offers extends CI_Controller {

	public function __construct(){
	    parent::__construct();
		if (empty($_SESSION['email']) && is_user_allow('manage_products') === false) {
			redirect(base_url('login'));
		}
        $this->layout = 'template';
		$this->load->model('admin/Offers_model','offers');
		$this->load->model('admin/Multimedia_model','multimedia');
		$this->load->model('admin/Campaigns_model','campaigns');
	}
	public function offers()
    {
		$data = [];
        $where = "campaigns.campaign_id > 0";
        $campaigns = $this->campaigns->get_where('*', $where, true, '', '', '');
        if(!empty($campaigns)){
            $data['campaigns'] = $campaigns;
        }
		$offers = $this->offers->list();
        if(!empty($offers)){
            $data['offers'] = $offers;
        }
		$this->load->view('admin/offers/offers', $data);
    }
    
	public function addoffers()
	{
		//print_r($this->input->post());exit();
		$data =[];
		$data['response'] = false;
        $multimedia = [];
        $config2 = array(
            array(
                'field'   => 'campaign_id',
                'label'   => 'Campaign Title',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'offer_title',
                'label'   => 'Offer Title',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'product_eng_title',
                'label'   => 'Offer English Title',
                'rules'   => 'trim'
            ),
            array(
                'field'   => 'country',
                'label'   => 'Country Code',
                'rules'   => 'required|max_length[5]'
            ),
            array(
                'field'   => 'offer_settings',
                'label'   => 'Offer Settings',
                'rules'   => 'trim'
            ),
            array(
                'field'   => 'offer_crm_id',
                'label'   => 'Offer CRM ID',
                'rules'   => 'required'
            ),
            array(
                'field'   => 'offer_price',
                'label'   => 'Offer Price',
                'rules'   => 'required|trim'
            ),
            array(
                'field'   => 'offer_currency',
                'label'   => 'Offer Currency',
                'rules'   => 'max_length[3]'
            ),
			array(
				'field'   => 'offer_description',
				'label'   => 'Offer Description',
				'rules'   => 'min_length[10]'
			),
			array(
				'field'   => 'product_id',
				'label'   => 'Product CRM ID',
				'rules'   => 'required'
			),
			array(
				'field'   => 'billing_model_id',
				'label'   => 'Billing Model ID',
				'rules'   => 'required'
			),
			array(
				'field'   => 'shipping_id',
				'label'   => 'Shipping ID',
				'rules'   => 'required'
			)
        );

		$this->form_validation->set_rules($config2);

		if($this->form_validation->run() == true){
            $formData = $this->input->post();
            if(!empty($formData['featured_offer']) && $formData['featured_offer'] == 'on'){
				$formData['featured_offer'] = 1;
			} else {
				$formData['featured_offer'] = 0;
			}
            $slug = process_slug($formData['offer_title']).'-'.strtolower($formData['country']);
            $formData['offer_slug'] = $slug;
            $country_code = $formData['country'];
            $formData['offer_url'] = base_url('/')."product/".$slug."?geo=".strtolower($formData['country']);
			$formData['free_offer_url'] = base_url('/')."free/product/".$slug."?geo=".strtolower($formData['country']);
            if(!empty($formData['offer_currency'])){
                $offer_currency = strtoupper($formData['offer_currency']);
                unset($formData['offer_currency']);
                $formData['offer_currency'] = $offer_currency;
            }else {
                unset($formData['offer_currency']); 
            }
            $where = "offers.offer_slug = '".$slug."' AND offers.country = '".$country_code."'";
            $offers = $this->offers->get_where('*', $where, true, '', '', '');
            if(empty($offers)){
				$insert_id = $this->offers->add($formData);
                if(!empty($insert_id)){
					// Count total files
					$countfiles = count($_FILES['offer_image']['name']);
					// Looping all files
					for($i=0;$i<$countfiles;$i++) {
						if (!empty($_FILES['offer_image']['name'][$i])) {
							// Define new $_FILES array - $_FILES['file']
							$_FILES['file']['name'] = time().$_FILES['offer_image']['name'][$i];
							$_FILES['file']['type'] = $_FILES['offer_image']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['offer_image']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['offer_image']['error'][$i];
							$_FILES['file']['size'] = $_FILES['offer_image']['size'][$i];

							//Load upload library
							$config['upload_path'] ='./assets/uploads/';
				        	$config['allowed_types'] = 'jpg|jpeg|png|webp';
					        $config['max_size'] = 10000;
				        	$config['max_height'] = 3000;
					        $config['max_width'] = 3000;
					        $config['overwrite'] = true;
							$this->load->library('upload', $config);
							// File upload
							if ($this->upload->do_upload('file')) {
								$uploadData = $this->upload->data();
								$filename = $uploadData['file_name'];
								$multimedia[] = array('offer_image' => $filename, 'offer_id'=>$insert_id);
							}
						}
					}
					if (sizeof($multimedia) > 0){
						$this->multimedia->add($multimedia);
					}
                    $data['response'] = true;
                }
            }else {
                $data['slug_error'] = 'This offer title already exists against country code: '.$country_code;
            }
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
    $data['data'] = $this->offers->getedit($product_id);
    $data['images'] = $this->multimedia->getedit($product_id);
    if(!empty($data['data'])){
      $data['response'] = true;
    }

    echo json_encode($data);
    exit;
	}
    
	public function editoffer()
	{
		$data =[];
		$data['response'] = false;
		$multimedia = [];

        $config2 = array(
            array(
                'field'   => 'campaign_id',
                'label'   => 'Campaign Title',
                'rules'   => 'required'
                ),
            array(
                'field'   => 'offer_title',
                'label'   => 'Offer Title',
                'rules'   => 'required'
                ),
								array(
									'field'   => 'product_eng_title',
									'label'   => 'Offer English Title',
									'rules'   => 'trim'
							),
            array(
                'field'   => 'country',
                'label'   => 'Country Code',
                'rules'   => 'required|max_length[5]'
                ),
            array(
                'field'   => 'offer_settings',
                'label'   => 'Offer Settings',
                'rules'   => 'trim'
                ),
                array(
                    'field'   => 'offer_price',
                    'label'   => 'Offer Price',
                    'rules'   => 'required|trim'
                ),
                array(
                    'field'   => 'offer_crm_id',
                    'label'   => 'Offer CRM ID',
                    'rules'   => 'required'
                ),
                array(
                    'field'   => 'offer_currency',
                    'label'   => 'Offer Currency',
                    'rules'   => 'max_length[3]'
                ),
			array(
				'field'   => 'product_id',
				'label'   => 'Product CRM ID',
				'rules'   => 'required'
			),
			array(
				'field'   => 'billing_model_id',
				'label'   => 'Billing Model ID',
				'rules'   => 'required'
			),
			array(
				'field'   => 'shipping_id',
				'label'   => 'Shipping ID',
				'rules'   => 'required'
			)
        ); 

		$this->form_validation->set_rules($config2);

		if($this->form_validation->run() == true){
            $formData = $this->input->post();
			if(!empty($formData['featured_offer']) && $formData['featured_offer'] == 'on'){
				$formData['featured_offer'] = 1;
			} else {
				$formData['featured_offer'] = 0;
			}

            $slug = process_slug($formData['offer_title']).'-'.strtolower($formData['country']);;
            $formData['offer_slug'] = $slug;
            $country_code = $formData['country'];
			$formData['offer_url'] = base_url('/')."product/".$slug."?geo=".strtolower($formData['country']);
			$formData['free_offer_url'] = base_url('/')."free/product/".$slug."?geo=".strtolower($formData['country']);
            if(!empty($formData['offer_currency'])){
                $offer_currency = strtoupper($formData['offer_currency']);
                unset($formData['offer_currency']);
                $formData['offer_currency'] = $offer_currency;
            }else {
                $formData['offer_currency'] = 'EUR'; 
            }
            $where = "offers.offer_slug = '".$slug."' AND offers.country = '".$country_code."' AND offers.offer_id != '".$formData['offer_id']."'";
            $offers = $this->offers->get_where('*', $where, true, '', '', '');
			// Count total files
			$countfiles = count($_FILES['offer_image']['name']);
			// Looping all files
			for($i=0;$i<$countfiles;$i++) {
				if (!empty($_FILES['offer_image']['name'][$i])) {
					// Define new $_FILES array - $_FILES['file']
					$_FILES['file']['name'] = time().$_FILES['offer_image']['name'][$i];;
					$_FILES['file']['type'] = $_FILES['offer_image']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['offer_image']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['offer_image']['error'][$i];
					$_FILES['file']['size'] = $_FILES['offer_image']['size'][$i];

					//Load upload library
					$config['upload_path'] ='./assets/uploads/';
					$config['allowed_types'] = 'jpg|jpeg|png|webp';
					$config['max_size'] = 10000;
					$config['max_height'] = 3000;
					$config['max_width'] = 3000;
					$config['overwrite'] = true;
					$this->load->library('upload', $config);
					// File upload
					$uploadFile = $this->upload->do_upload('file');
					if ($uploadFile) {
						$uploadData = $this->upload->data();
						$filename = $uploadData['file_name'];
						$multimedia[] = array('offer_image' => $filename, 'offer_id'=>$formData['offer_id']);
					}
				}
			}
			if (sizeof($multimedia) > 0){
				$this->multimedia->add($multimedia);
			}
            if(empty($offers)){
//				if(!empty($_FILES['offer_image']['tmp_name'])){
//					$config['upload_path'] ='./assets/uploads/';
//					$config['allowed_types'] = 'jpg|jpeg|png';
//					$config['max_size'] = 10000;
//					$config['max_height'] = 3000;
//					$config['max_width'] = 3000;
//					$config['overwrite'] = true;
//					$this->load->library('upload', $config);
//					if(!$this->upload->do_upload('offer_image')){
//						$data['image_errors'] = $this->upload->display_errors();
//					}else {
//						$formData['offer_image'] = $this->upload->data('file_name');
//					}
//				}
				$response = $this->offers->updatedit($formData,$formData['offer_id']);
                if(!empty($response)){
                    $data['response'] = true;
                }
            }else {
                $data['slug_error'] = 'This offer title already exists against country code: '.$country_code;
            }
		}else {
			$data['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($data);
		exit;
	}
  
	public function deleteoffer()
	{
    $data = [];
    $data['response'] = false;
	$offer_id = $this->input->post();
    // print_r($campaign_id['campaign_id']);
    // exit;
	$delete = $this->offers->deleterow($offer_id['offer_id']);
    if($delete){
      $data['response'] = true;
    }
    echo json_encode($data);
    exit;
	}
	public function deleteImage()
	{
		$data = [];
		$data['response'] = false;
		$image_id = $this->input->get('id');
		$image = $this->multimedia->getSingle($image_id);
		$delete = $this->multimedia->deleterow($image_id);
		if($delete){
			$unlinkUrl = "assets/uploads/".$image['offer_image'];
			if(file_exists($unlinkUrl)){
				// unlink($unlinkUrl);
			}
			$data['response'] = true;
		}
		echo json_encode($data);
		exit;
	}

	public function offers_list()
	{
		$this->layout = '';
/////////////////////////////////////////////
		$data = $row = array();

		// Fetch member's records
		$memData = $this->offers->getRows($_POST);
        $compaingn_id = $this->input->post('compaign_filter');
		foreach($memData as $member){
			$singleField = array();
			$slug = $member->offer_slug;
			if(strpos($member->offer_title,'(copy)')){
				$offer_url = base_url('/')."product/".$slug."?geo=".strtolower($member->country).'(copy)';
				$free_offer_url = base_url('/')."free/product/".$slug."?geo=".strtolower($member->country).'(copy)';
			}else{
				$offer_url = base_url('/')."product/".$slug."?geo=".strtolower($member->country);
				$free_offer_url = base_url('/')."free/product/".$slug."?geo=".strtolower($member->country);
			}
			$singleField['offer_title'] = $member->offer_title;
			$singleField['product_eng_title'] = $member->product_eng_title;
			$singleField['offer_price'] = "$".$member->offer_price;
			$singleField['discount_offer_price'] = "$".$member->discount_offer_price;
			$singleField['offer_url'] = '<a target="_blank" href="'.$offer_url.'">'.$offer_url.'</a>';
			$singleField['free_offer_url'] = '<a target="_blank" href="'.$free_offer_url.'">'.$free_offer_url.'</a>';
			$singleField['offer_country'] = $member->country;
			$singleField['duplicate'] = '<button type="button" class="btn btn-primary btn-flat duplicate_btn"  data-id="'.$member->offer_id.'">Duplicate</button>';
			$singleField['edit'] = '<button type="button" class="btn btn-primary btn-flat editBtn"  data-toggle="modal" data-target="#edit-offers" data-id="'.$member->offer_id.'">Edit</button>';
			$singleField['delete'] = '<form class="deleteProduct"><input type="hidden" name="offer_id" value="'.$member->offer_id.'"><button type="submit" class="btn btn-block btn-danger btn-flat">Delete</button></form>';
			$data[] = $singleField;
		}
		$total_records = $this->offers->countAll();
//		if(!empty($compaingn_id)) {
//			$where = "campaign_id = $compaingn_id";
//			$total_records = $this->offers->countAll($where);
//		} else {
//			$total_records = $this->offers->countAll();
//		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $total_records,
			"recordsFiltered" => $this->offers->countFiltered($_POST),
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}

	public function duplicate_offers()
	{
		$multimedia = [];
		$data =[];
		$data['response'] = false;
		$offers_id = $this->input->post('id');
		$where = "offer_id='".$offers_id."'";
		$multimedia_details =  $this->multimedia->get_multimedia('*', $where, true, '', '', '');
		$offer_details =  $this->offers->get_where('*', $where, true, '', '1', '');
		$offer_details = $offer_details[0];
		$offer_details['offer_title'] = $offer_details['offer_title'].'(copy)';
		$offer_details['product_eng_title'] = $offer_details['product_eng_title'].'(copy)';
		$offer_details['offer_url'] = $offer_details['offer_url'].'(copy)';
		$offer_details['free_offer_url'] = $offer_details['free_offer_url'].'(copy)';


		unset($offer_details['offer_id']);
		$insert_id = $this->offers->add($offer_details);

		foreach($multimedia_details as $key => $val){
			$multimedia[] = array(
				'offer_image' => $val['offer_image'], 
				'offer_id'=> $insert_id
			);
		}

		if(sizeof($multimedia) > 0){
			$this->multimedia->add($multimedia);
		}
		
		$data['response'] = true;
		echo json_encode($data);
		exit;
	}



	
}
