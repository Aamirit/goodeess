<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('vendor/autoload.php');

use SecurionPay\SecurionPayGateway;
use SecurionPay\Exception\SecurionPayException;

class Home extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->layout = 'sticky_layout';
		$this->load->model('offers_model', 'offers');
		$this->load->model('campaigns_model', 'campaigns');
		$this->load->model('orders_model', 'orders');
		$this->load->model('admin/Multimedia_model','multimedia');
		$this->load->model('notifications_model', 'notifications');
	}

	public function checkout($slug){
		$promos = array();
		$promo_code = $this->input->get('promocode');
		$data = [];
		$data['slug'] = $slug;
		$data['shipping_free'] = true;
		$data['shipping_fee'] = 0;
		$where = "offers.offer_slug='".$slug."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$offer = $offer[0];
			if($offer['discount_offer_price'] != 0){
				$offer['offer_price'] = $offer['discount_offer_price'];
			}
			$compaign_id = $offer['campaign_id'];
			$where2 = "campaigns.campaign_id='".$compaign_id."'";
			$compaigns = $this->campaigns->get_where('*', $where2, true, '', '1', '');
			$data['vat'] = $compaigns[0]['vat'];
          if($promo_code){
			  $promos['promo_code'] = $promo_code;
			  $promos['shipping_id'] = $offer['shipping_id'];
			  $promos['product_id'] = $offer['product_id'];
			  $promos['campaign_id'] = $compaigns[0]['campaign_crm_id'];
			  $response = get_discount($promos);
			  $offer_price = $offer['offer_price'] - $response->coupon_amount;
			  if($offer_price == 0){
				  $data['shipping_free'] = false;
				  $data['shipping_fee'] = SHIPPING_PRICE;
			  }
			  $offer['offer_price'] = $offer_price;
		  }
			$data['offer'] = $offer;
			$data['promocode'] = $promo_code;
			$data['images'] = $this->multimedia->getedit($offer['offer_id']);
			$data['title'] = $offer['offer_title'];
			$this->load->view('checkout/checkout', $data);
		}
	}
	public function checkout2($slug){
		$data = [];
		$data['slug'] = $slug;
		$where = "offers.offer_slug='".$slug."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$data['offer'] = $offer[0];
		}
		$this->load->view('checkout/my_checkout', $data);
	}
   public function product($slug){
		$this->layout = 'site_template';
		$geo = 'fr';
		$data = [];
		$query = $this->input->get();
		$data['slug'] = $slug;
		$where = "offers.offer_slug='".$slug."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$data['offer'] = $offer[0];
			$percent = (($offer[0]['offer_price'] - $offer[0]['discount_offer_price'])*100) /$offer[0]['offer_price'] ;
			$data['images'] = $this->multimedia->getedit($offer[0]['offer_id']);
			if($percent !== 0) {
				$percent = number_format($percent, 2);
			}
			$data['discount'] = $percent;
			$data['title'] = $offer[0]['offer_title'];
		}
		if($this->input->get('geo')){
			$this->session->set_userdata('site_lang', $this->input->get('geo'));
		}
		if($this->input->get('affId')){
			$this->session->set_userdata('affId', $this->input->get('affId'));
		}
		foreach($query as $key => $value){
			$this->session->set_userdata($key, $value);
		}
		if($this->session->userdata('geo')){
			$geo = $this->session->userdata('geo');
		}
		$data['geo'] = $geo;
		$this->load->view('product/product', $data);
	}
	public function checkout_process(){
		$data = [];
		$data['response'] = false;
		
		if(!$this->input->is_ajax_request()){
			exit('No direct script access allowed');
		}

		$this->form_validation->set_rules('firstName', 'firstName', 'required|trim', array('required' => '%s is required!'));
		$this->form_validation->set_rules('lastName', 'lastName', 'required|trim', array('required' => '%s is required!'));
		$this->form_validation->set_rules('address', 'address', 'required|trim', array('required' => '%s is required!'));
		$this->form_validation->set_rules('phone', 'phone', 'required|trim', array('required' => '%s is required!'));
		$this->form_validation->set_rules('city', 'city', 'required|trim', array('required' => '%s is required!'));
		$this->form_validation->set_rules('zip', 'zip', 'required|trim', array('required' => '%s is required!'));
		$this->form_validation->set_rules('email', 'email', 'required|trim|valid_email', array('required' => '%s is required!'));
		$this->form_validation->set_rules('goodeess-terms', 'goodeess-terms', 'required|trim', array('required' => '%s is required!'));
		$this->form_validation->set_rules('token', 'token', 'required|trim', array('required' => '%s is required!'));

		if($this->form_validation->run()==true){
			$formData = $this->input->post();
			$ip_server = $_SERVER['SERVER_ADDR'];


			if (!empty($formData['email']) ) {
				if (isset($formData['status']) && $formData['status'] != "error") {
					// $Username = "vipresponse_6790";
					//$Password = "c5da8ce5e36681";
					$credentials = base64_encode("vipresponse_6790:c5da8ce5e36681");
					//$tokenId = $_POST['token'];
					$customerEmail = $formData['email'];
					$firstname = $formData['firstname'];
					$lastname = $formData['lastname'];
					$phone = $formData['phone'];
					$address = $formData['address'];
					$zip = $formData['zip'];
					$city = $formData['city'];
					$country = $formData['country'];
					$cardNumber= $formData['ccnumber'];
					$expdatem = $formData['expdatem'];
					$expdatey = $formData['expdatey'];

					$expdate = $expdatem.$expdatey;

					$cvv = $formData['cvv'];

					//3DS parameters received from the form
					$cavv = $formData['authenticationValue'];
					$ds_trans_id = $formData['ds_trans_id'];
					$eci = $formData['eci'];
					$d_version = $formData['protocolVersion'];



					if (substr($cardNumber, 0, 1) == '4') {

						$cardType = 'visa';

					}elseif (substr($cardNumber, 0, 1) == '5'){

						$cardType = 'master';

					}else{

						$cardType = 'discover';

					}

					//$expiry = str_replace('/','',$expdate);
					//$exp = preg_replace ("/", "", $expdate);
					$curl = curl_init();

					$params = array(
						'firstName'=>$firstname,
						'lastName'=>$lastname,
						'billingFirstName'=>$firstname,
						'billingLastName'=>$lastname,
						'billingAddress1'=>$address,
						'billingAddress2'=> 'FL 7',
						'billingCity'=> $city,
						'billingState'=>'N/A',
						'billingZip'=>$zip,
						'billingCountry'=>$country,
						'phone'=>$phone,
						'email'=>$customerEmail,
						'creditCardType'=> $cardType,
						'creditCardNumber'=> $cardNumber,
						'expirationDate'=> $expdate,
						'CVV'=> $cvv,
						'shippingId'=> '2',
						'tranType'=> 'Sale',
						'ipAddress'=>  $ip_server,
						//campaignId, this will activate the gateway selected on each campaign
						'campaignId'=> '3',
						//'productId'=> '67',
						//'auth_amount'=> '0.00',
						//'cascade_enabled'=> '0',
						//'save_customer'=> '1',
						//'validate_only_flag'=> '0',
						//'void_flag'=>'0',
						'offers' => array(
							[
								//product one-time purchase
								'offer_id'=>'1',
								'product_id'=> '67',
								//billing model 2
								'billing_model_id'=>'2',
								'quantity'=>'1',
								'step_num'=>'2'

							],
							[
								//product with trial
								'offer_id'=>'1',
								'product_id'=> '2',
								//billing model 3
								'billing_model_id'=>'3',
								'quantity'=>'1',
								'step_num'=>'2',

								'trial' => array(

									'product_id'=> '2',
								)
							]
						),
						'billingSameAsShipping'=>'YES',
						'shippingAddress1'=> $address,
						'shippingAddress2'=>'APT 7',
						'shippingCity'=> $city,
						'shippingState'=>'N/A',
						'shippingZip'=> $zip,
						'shippingCountry'=> $country,
						'three_d_redirect_url'=>'',
						'alt_pay_return_url'=>'',
						'sessionId'=>'',
						'cascade_override'=>'',
						'create_member'=>'',
						'event_id'=>'',
						'ssn_nmi'=>'',
						'utm_source'=>'',
						'utm_medium'=>'',
						'utm_campaign'=>'campaign',
						'utm_content'=>'content',
						'utm_term'=>'term',
						'device_category'=>'mobile',
						'checkingAccountNumber'=>'',
						'checkingRoutingNumber'=>'',
						'sepa_iban'=>'',
						'sepa_bic'=>'',
						'eurodebit_acct_num'=>'',
						'eurodebit_route_num'=>'',
						'referrer_id'=>'ABCD1234',
						'3d_version'=>$d_version,
						'cavv'=>$cavv,
						'eci'=>$eci,
						'ds_trans_id'=>$ds_trans_id

					);

					curl_setopt_array($curl, array(
						CURLOPT_URL => 'https://vipresponse.sticky.io/api/v1/new_order',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_POSTFIELDS => json_encode($params),
						CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json",
							"Authorization: Basic dmlwcmVzcG9uc2VfNjc5MDpjNWRhOGNlNWUzNjY4MQ=="
						),
					));

					$response = curl_exec($curl);
					$result = json_decode($response, true);
					curl_close($curl);

					$data = $result ['response_code'];


					if ($data == '100'){



						print "Success! New Order triggered: " . $response;


						header( "refresh:5;url=https://goodeess-plus.com/ci/Sticky/thankyou" );
						exit;

					}else{

						//print $data; response error
						print $response;

					}


				}else{

					//show invalid card for non-3DS, param response with error
					echo 'Invalid card(Non-3DS)';
					header( "refresh:5;url=https://www.goodeess-plus.com/ci/Payment/sticky" );

					exit;

				}


			} else {

				echo "No Email found";

			}
//			$cardToken = $formData['token'];
//			$customerRequest = array(
//				'email' => $formData['email']
//			);
//			$securionPay = new SecurionPayGateway('sk_test_MtSDdnfkz1qGguVTmBof2o6p');
//			$customer = $securionPay->createCustomer($customerRequest);
//			//Add Customer to a Subscription
//			$planRequest = array(
//				'planId' => 'plan_GboBEmWQGJzYi0WBrd06TvNh',
//				'customerId' => $customer->getId()
//			);
//
//			$plan = $securionPay->createSubscription($planRequest);
//
//			$request = array(
//				'amount' => intval($formData['offer_price']),
//				'currency' => strval($formData['offer_currency']),
//				'card' => $cardToken
//			);
//
//			try {
//
//				$charge = $securionPay->createCharge($request);
//
//				$chargeId = $charge->getId();
//
//				if(!empty($chargeId)){
//					$slug = $formData['slug'];
//					$where = "offers.offer_slug='".$slug."'";
//					$joins = array(
//            '0' => array('table_name' => 'campaigns',
//                'join_on' => 'campaigns.campaign_crm_id = offers.campaign_id',
//                'join_type' => 'left'
//            )
//        	);
//					$from_table = "offers";
//					$select_from_table = 'offers.*, campaigns.*';
//					$offers = $this->offers->get_by_join($select_from_table, $from_table, $joins, $where, '', '', '', '', '', '', '', '','','');
//
//					if(!empty($offers)){
//						$campaign_id = $offers[0]['campaign_id'];
//						$offer_crm_id = $offers[0]['offer_crm_id'];
//						$offer_price = $offers[0]['offer_price'];
//						$campaign_crm_id = $offers[0]['campaign_crm_id'];
//						$user_data = array(
//							'first_name' => $formData['firstName'],
//							'last_name' => $formData['lastName'],
//							'email' => $formData['email'],
//							'securion_pay_token' => $cardToken,
//							'offer_crm_id' => $offer_crm_id
//						);
//						$orders_id = $this->orders->save($user_data);
//
//						if(!empty($orders_id)){
//							// IP address request to my_helper.php
//							$client_ip = get_client_ip();
//							echo client_ip;
//							$import_click_params = array(
//                'campaignId' => $campaign_crm_id,
//                'loginId' => LOGIN_ID,
//                'pageType' => 'checkoutPage',
//                'password' => PASSWORD,
//                'requestUri' => $formData['current_url'],
//                'ipAddress' => $client_ip
//            	);
//							// Import click request to my_helper.php
//							$import_click_response = json_decode(konnektive_curl($import_click_params, 'import_click'), true);
//
//							if($import_click_response['result']==="SUCCESS"){
//								$this->session->set_userdata('sessionId', $import_click_response['message']['sessionId']);
//								$import_lead_params = array(
//									'address1' => $formData['address1'],
//									'campaignId' => $campaign_crm_id,
//									'city' => $formData['city'],
//									'country' => $formData['country'],
//									'emailAddress' => $formData['email'],
//									'firstName' => $formData['firstName'],
//									'ipAddress' => $client_ip,
//									'lastName' => $formData['lastName'],
//									'loginId' => LOGIN_ID,
//									'password' => PASSWORD,
//									'phoneNumber' => $formData['phoneNumber'],
//									'postalCode' => $formData['postalCode'],
//									'sessionId' => $import_click_response['message']['sessionId'],
//									'billShipSame' => true
//								);
//
//								// Import lead request to my_helper.php
//								$import_lead_response = json_decode(konnektive_curl($import_lead_params, 'import_lead'), true);
//
//								if($import_lead_response['result']==="SUCCESS"){
//									$update = array(
//										'import_lead_post_data' => json_encode($import_lead_params),
//										'import_lead_response' => json_encode($import_lead_response)
//									);
//									$where2 = "orders.id='".$orders_id."'";
//									$db_response = $this->orders->update_by_where($update, $where2);
//
//									if(!empty($db_response)){
//										$import_order_params = array(
//											'address1' => $formData['address1'],
//											'city' => $formData['city'],
//											'country' => $formData['country'],
//											'emailAddress' => $formData['email'],
//											'firstName' => $formData['firstName'],
//											'ipAddress' => $client_ip,
//											'lastName' => $formData['lastName'],
//											'loginId' => LOGIN_ID,
//											'password' => PASSWORD,
//											'paySource' => 'PREPAID',
//											'phoneNumber' => $formData['phoneNumber'],
//											'postalCode' => $formData['postalCode'],
//											'sessionId' => $import_click_response['message']['sessionId'],
//											'campaignId' => $campaign_crm_id,
//											'product1_id' => $offer_crm_id,
//											'product1_price' => $offer_price,
//											'product1_qty' => 1
//											// ,
//											// 'shipAddress1' => $formData['address1'],
//											// 'shipCity' => $formData['city'],
//											// 'shipPostalCode' => $formData['postalCode'],
//											// 'shipCountry' => $formData['country']
//										);
//										// Import order request to my_helper.php
//										$import_order_response = json_decode(konnektive_curl($import_order_params, 'import_order'), true);
//
//										if($import_order_response['result']==="SUCCESS"){
//											$orderId = $import_order_response['message']['orderId'];
//											$update1 = array(
//												'import_order_post_data' => json_encode($import_order_params),
//												'import_order_response' => json_encode($import_order_response),
//												'order_id' => $orderId
//											);
//											$where3 = "orders.id='".$orders_id."'";
//											$db_response1 = $this->orders->update_by_where($update1, $where3);
//											if(!empty($db_response1)){
//												$data['response'] = true;
//												$data['redirect_url'] = base_url('thankyou/'.$slug);
//											}
//										}else {
//											$update1 = array(
//												'import_order_post_data' => json_encode($import_order_params),
//												'import_order_response' => json_encode($import_order_response)
//											);
//											$where3 = "orders.id='".$orders_id."'";
//											$db_response1 = $this->orders->update_by_where($update1, $where3);
//											$data['IMPORT_ORDER_ERROR'] = $import_order_response['message'];
//											echo json_encode($data);
//											exit;
//										}
//									}
//								}else {
//									$update = array(
//										'import_lead_post_data' => json_encode($import_lead_params),
//										'import_lead_response' => json_encode($import_lead_response)
//									);
//									$where2 = "orders.id='".$orders_id."'";
//									$db_response = $this->orders->update_by_where($update, $where2);
//									$data['IMPORT_LEAD_ERROR'] = $import_lead_response['message'];
//									echo json_encode($data);
//									exit;
//								}
//							}else {
//
//								$data['IMPORT_CLICK_ERROR'] = $import_click_response['message'];
//								echo json_encode($data);
//								exit;
//							}
//						}
//					}
//				}
//
//			} catch (SecurionPayException $e) {
//					$errorType = $e->getType();
//					$errorCode = $e->getCode();
//					$errorMessage = $e->getMessage();
//					$data['SecurionPay_ERROR'] = $errorMessage;
//					echo json_encode($data);
//					exit;
//			}

		}else {
			$data['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($data);
		exit;
	}

public function checkout_process2(){
		$this->session->unset_userdata('user_data');
		$data = [];
		$resp = array();
		$data['response'] = false;

		if(!$this->input->is_ajax_request()){
			exit('No direct script access allowed');
		}
		$this->form_validation->set_rules('firstname', 'First Name', 'required|trim', array('required' => $this->lang->line("first_name_req")));
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim', array('required' => $this->lang->line("last_name_req")));
		$this->form_validation->set_rules('address', 'Address', 'required|trim', array('required' => $this->lang->line("address_req")));
		$this->form_validation->set_rules('phone', 'PhoneNumber', 'required|trim', array('required' => $this->lang->line("phoneNumber_req")));
		$this->form_validation->set_rules('city', 'city', 'required|trim', array('required' => $this->lang->line("city_req")));
		$this->form_validation->set_rules('zip', 'zip', 'required|trim', array('required' => $this->lang->line("postalCode_req")));
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', array('required' => $this->lang->line("email_req")));
		$this->form_validation->set_rules('goodeess-terms', 'Goodeess-terms', 'required|trim', array('required' => $this->lang->line("goodeess-terms_req")));
		$this->form_validation->set_rules('ccnumber', 'CardNumber', 'required|trim', array('required' => $this->lang->line("cardNumber_req")));
		$this->form_validation->set_rules('expdatem', 'Expire Month', 'required|trim', array('required' => $this->lang->line("expdate_req")));
		$this->form_validation->set_rules('expdatey', 'Expire Year', 'required|trim', array('required' => $this->lang->line("expdate_req")));
		$this->form_validation->set_rules('cvv', 'Card Security Code', 'required|trim', array('required' => $this->lang->line("cardSecurityCode_req")));
		$this->form_validation->set_rules('cardholder', 'Card holder', 'required|trim', array('required' => $this->lang->line("cardholder_req")));
		if($this->form_validation->run()==true){
			$formData = $this->input->post();

			$slug = $formData['slug'];
			$where = "offers.offer_slug='".$slug."'";
			$offer = $this->offers->get_where('*', $where, true, '', '1', '');
			if(!empty($offer)){

			}
			$ip_server = $_SERVER['SERVER_ADDR'];
			if (!empty($formData['email']) && !empty($offer)) {
				$offer = $offer[0];
				$compaign_id = $offer['campaign_id'];
				$where2 = "campaigns.campaign_id='".$compaign_id."'";
				$compaigns = $this->campaigns->get_where('*', $where2, true, '', '1', '');
				$user_data = array(
					'first_name' => $formData['firstname'],
					'last_name' => $formData['lastname'],
					'email' => $formData['email'],
					'offer_crm_id' => $offer['offer_crm_id']
				);
				$orders_id = $this->orders->save($user_data);
				if (isset($formData['status']) && $formData['status'] != "error") {
					$credentials = base64_encode("vipresponse_6790:c5da8ce5e36681");
					$customerEmail = $formData['email'];
					$firstname = $formData['firstname'];
					$lastname = $formData['lastname'];
					$phone = $formData['phone'];
					$address = $formData['address'];
					$zip = $formData['zip'];
					$city = $formData['city'];
					$country = $formData['country'];
					$cardNumber= $formData['ccnumber'];
					$expdatem = $formData['expdatem'];
					$expdatey = $formData['expdatey'];

					$expdate = $expdatem.$expdatey;

					$cvv = $formData['cvv'];

					//3DS parameters received from the form
					$cavv = $formData['authenticationValue'];
					$ds_trans_id = $formData['ds_trans_id'];
					$eci = $formData['eci'];
					$d_version = $formData['protocolVersion'];
					//	$x_id = $formData['x_transaction_id'];

					$urlencoded_cavv = urlencode($cavv);

					if (substr($cardNumber, 0, 1) == '4') {

						$cardType = 'visa';

					}elseif (substr($cardNumber, 0, 1) == '5'){

						$cardType = 'master';

					}else{

						$cardType = 'discover';

					}

					$curl = curl_init();
					//$product = array();

					if($offer["price_type"] == 1) {
						//product with trial
						$product = array( [	'offer_id'=> !empty($offer['offer_crm_id']) ? $offer['offer_crm_id'] : '1',
							'product_id'=> $offer["product_id"],
							'billing_model_id'=>!empty($offer['billing_model_id']) ? $offer['billing_model_id'] : '2',
							'quantity'=>'1',
							'step_num'=>'2'],

							['offer_id'=> '1',
								'product_id'=> '2',
								'billing_model_id'=>'3',
								'quantity'=>'1',
								'step_num'=>'2',
								'trial' => array(
									'product_id'=> '2' ,
								)]);


					}else {

						$product = array( [	'offer_id'=> !empty($offer['offer_crm_id']) ? $offer['offer_crm_id'] : '1',
							'product_id'=> $offer["product_id"],
							'billing_model_id'=>!empty($offer['billing_model_id']) ? $offer['billing_model_id'] : '2',
							'quantity'=>'1',
							'step_num'=>'2'],

							['offer_id'=> '1',
								'product_id'=> '2',
								'billing_model_id'=>'3',
								'quantity'=>'1',
								'step_num'=>'2',
								'trial' => array(
									'product_id'=> '2' ,
								)]);
					}
                    if(!empty($formData['product']) && substr($formData['product'], -1) == '1'){
						$offer['shipping_id'] = 3;
						$formData['promocode'] = FREE_PRODUCT;
					}
					$params = array(
						'prospectId'=> !empty($this->session->userdata('prospectId')) ? $this->session->userdata('prospectId') : '',
						'firstName'=>$firstname,
						'lastName'=>$lastname,
						'billingFirstName'=>$firstname,
						'billingLastName'=>$lastname,
						'billingAddress1'=>$address,
						'billingAddress2'=> 'FL 7',
						'billingCity'=> $city,
						'billingState'=>'N/A',
						'billingZip'=>$zip,
						'billingCountry'=>$country,
						'phone'=>$phone,
						'email'=>$customerEmail,
						'creditCardType'=> $cardType,
						'creditCardNumber'=> $cardNumber,
						'expirationDate'=> $expdate,
						'CVV'=> $cvv,
						'shippingId'=> !empty($offer['shipping_id']) ? $offer['shipping_id'] : '3',
						'tranType'=> 'Sale',
						'ipAddress'=>  $ip_server,
						'campaignId'=> $compaigns[0]["campaign_crm_id"],//!empty($compaign_id) ? $compaign_id : '3',
						'offers' => $product,
						'billingSameAsShipping'=>'YES',
						'shippingAddress1'=> $address,
						'shippingAddress2'=>'APT 7',
						'shippingCity'=> $city,
						'shippingState'=>'N/A',
						'shippingZip'=> $zip,
						'shippingCountry'=> $country,
						'three_d_redirect_url'=>base_url("thankyou/".$slug),
						'alt_pay_return_url'=>'',
						'sessionId'=>'',
						'cascade_override'=>'',
						'create_member'=>'',
						'event_id'=>'',
						'ssn_nmi'=>'',
						'utm_source'=>'',
						'utm_medium'=>'',
						'utm_campaign'=>'campaign',
						'utm_content'=>'content',
						'utm_term'=>'term',
						'device_category'=>'mobile',
						'checkingAccountNumber'=>'',
						'checkingRoutingNumber'=>'',
						'sepa_iban'=>'',
						'sepa_bic'=>'',
						'eurodebit_acct_num'=>'',
						'eurodebit_route_num'=>'',
						'referrer_id'=>'ABCD1234',
						'3d_version'=>$d_version,
						'cavv'=>$urlencoded_cavv,
						'eci'=>$eci,
						'ds_trans_id'=>$ds_trans_id,
						'promoCode' =>!empty($formData['promocode']) ? $formData['promocode'] : ''
					);

					curl_setopt_array($curl, array(
						CURLOPT_URL => 'https://vipresponse.sticky.io/api/v1/new_order_with_prospect',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_POSTFIELDS => json_encode($params),
						CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json",
							"Authorization: Basic dmlwcmVzcG9uc2VfNjc5MDpjNWRhOGNlNWUzNjY4MQ=="
						),
					));

					$response = curl_exec($curl);

					$result = json_decode($response, true);
					curl_close($curl);
					$data = $result['response_code'];
					$saved_params = $params;
					unset($saved_params['creditCardNumber']);
					unset($saved_params['expirationDate']);
					unset($saved_params['CVV']);
					unset($saved_params['creditCardType']);
					$user_agient = $this->agent->browser().' '.$this->agent->version();
					$saved_params['user_agent'] = $user_agient;
					$saved_params['c3'] = !empty($this->session->userdata('c3')) ? $this->session->userdata('c3') : '';
					if ($data == '100'){
					    $this->session->unset_userdata('is_free');
						$this->session->set_userdata('user_data', $saved_params);
						$this->session->set_userdata('order_id', $orders_id);
						$update = array(
							'import_order_post_data' => json_encode($saved_params),
							'import_order_response' => json_encode($result),
							'is_successd' => 1,
							'order_id' => $result['order_id'],
							'product_id' => $offer['product_id'],
							'offer_id' => $offer['offer_id']
						);
						$where2 = "orders.id='".$orders_id."'";
						$db_response = $this->orders->update_by_where($update, $where2);
						$resp['response'] = true;
						if(isset($formData['promocode']) && !empty($formData['promocode'])){
							$resp['redirect_url'] = base_url('/')."./home/thankyou/".$slug."?coupon=".$formData['promocode'];
						} else {
							$resp['redirect_url'] = base_url('/')."./home/thankyou/".$slug;
						}

					}     else{
						$update = array(
							'import_order_post_data' => json_encode($saved_params),
							'import_order_response' => json_encode($result),
						);
						$where2 = "orders.id='".$orders_id."'";
						$db_response = $this->orders->update_by_where($update, $where2);
						$resp['response_code'] = $data;
						$resp['response'] = false;
						$resp['error_message'] = !empty($result['decline_reason']) ? $result['decline_reason'] : 'Invalid card(Non-3DS).' ;
					}
				}else{
					$update = array(
						'error_message' => 'Invalid card(Non-3DS)..' ,
					);
					$where2 = "orders.id='".$orders_id."'";
					$db_response = $this->orders->update_by_where($update, $where2);
					$resp['response'] = false;
					$resp['error_message'] = !empty($result['decline_reason']) ? $result['decline_reason'] : 'Invalid card(Non-3DS)..' ;
				}

			} else {
				$resp['response'] = false;
				$resp['error_message'] = 'No Email found' ;
			}
		}else {
			$resp['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($resp);
		exit;
	}
	public function thankyou($slug){
		$this->session->unset_userdata('prospectId');
		$this->layout = 'thankyou_layout';
		$promos = array();
		$promo_code = $this->input->get('coupon');
		$c4 = '';
		$data = array();
		$where = "offers.offer_slug='".$slug."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$offer = $offer[0];
			if($offer['discount_offer_price'] != 0){
				$offer['offer_price'] = $offer['discount_offer_price'];
			}
			if($this->session->userdata('c4')){
				$c4 = $this->session->userdata('c4');
			}

			$orders_id = $this->session->userdata('order_id');
			$conversion = create_fbPixel_conversion($this->session->userdata('user_data'),$offer['offer_url']);
			if(!empty($conversion['status'])){
				$post_data = json_encode($conversion['post_data']);
				$response_data = json_encode($conversion['response']);
				$update = array(
					'fbc_post_data' => json_encode($post_data),
					'fbc_response_data' => json_encode($response_data),
				);
				$where2 = "orders.id='".$orders_id."'";
				$db_response = $this->orders->update_by_where($update, $where2);
			}
			$compaign_id = $offer['campaign_id'];
			$where2 = "campaigns.campaign_id='".$compaign_id."'";
			$compaigns = $this->campaigns->get_where('*', $where2, true, '', '1', '');
			$data['vat'] = $compaigns[0]['vat'];
			$data['c4'] = $c4;
			if($promo_code){
				$promos['promo_code'] = $promo_code;
				$promos['shipping_id'] = $offer['shipping_id'];
				$promos['product_id'] = $offer['product_id'];
				$promos['campaign_id'] = $compaigns[0]['campaign_crm_id'];
				$response = get_discount($promos);
				$offer['offer_price'] = $offer['offer_price'] - $response->coupon_amount;
			}
			$data['totalAmount'] = $offer['offer_price'];
			$data['currencyCode'] = 'EUR';
			$data['orderReference'] = $orders_id;
			$data['voucherCode'] = !empty($promo_code) ? $promo_code : '';
			$data['offer'] = $offer;

		}
		$this->session->unset_userdata('c4');
		$this->load->view('checkout/thankyou', $data);
	}

	public function switchLang($language = ""){
		$this->session->set_userdata('site_lang', $language);
		header('Location: '.base_url().'home/checkout');
	}
    public function discount_price(){
		$this->layout = '';
        $resp = array();
        $resp['status'] = false;
           $data = array();
            $promo_code = $this->input->get('promo_code');
            $offer_id = $this->input->get('offer_id');
		$where = "offers.offer_id='".$offer_id."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$offer = $offer[0];
			if($offer['discount_offer_price'] != 0){
				$offer['offer_price'] = $offer['discount_offer_price'];
			}
			$data['promo_code'] = $promo_code;
			$data['shipping_id'] = $offer['shipping_id'];
			$data['product_id'] = $offer['product_id'];
			$compaign_id = $offer['campaign_id'];
			$where2 = "campaigns.campaign_id='".$compaign_id."'";
			$compaigns = $this->campaigns->get_where('*', $where2, true, '', '1', '');
			if(!empty($compaigns)){
				$compaigns = $compaigns[0];
				$data['campaign_id'] = $compaigns['campaign_crm_id'];
				$resp['status'] = true;
			}
		}
		$response = get_discount($data);
		$offer_price = $offer['offer_price'] - $response->coupon_amount;
		if($offer_price == 0){
			$this->session->set_userdata('is_free', false);
		} else {
			$this->session->set_userdata('is_free', true);
		}
		$resp['response'] = $response;
		$resp['promocode'] = $promo_code;
		echo json_encode($resp);
		exit;

	}
	public function free_product($slug){
		$this->layout = 'free_site_template';
		$geo = 'fr';
		$data = [];
		$query = $this->input->get();
		$data['slug'] = $slug;
		$where = "offers.offer_slug='".$slug."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$data['offer'] = $offer[0];
			$percent = (($offer[0]['offer_price'] - $offer[0]['discount_offer_price'])*100) /$offer[0]['offer_price'] ;
			$data['images'] = $this->multimedia->getedit($offer[0]['offer_id']);
			if($percent !== 0) {
				$percent = number_format($percent, 2);
			}
			$data['discount'] = $percent;
			$data['title'] = $offer[0]['offer_title'];
		}
		if($this->input->get('geo')){
			$this->session->set_userdata('site_lang', $this->input->get('geo'));
		}
		if($this->input->get('affId')){
			$this->session->set_userdata('affId', $this->input->get('affId'));
		}
		foreach($query as $key => $value){
			$this->session->set_userdata($key, $value);
		}
		if($this->session->userdata('geo')){
			$geo = $this->session->userdata('geo');
		}
		$data['geo'] = $geo;
		$this->load->view('product/free_product', $data);
	}
	public function free_checkout($slug){
		$promos = array();
		$promo_code = FREE_PRODUCT;
		$data = [];
		$data['slug'] = $slug;
		$data['shipping_free'] = true;
		$data['shipping_fee'] = 0;
		$where = "offers.offer_slug='".$slug."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$offer = $offer[0];
			if($offer['discount_offer_price'] != 0){
				$offer['offer_price'] = $offer['discount_offer_price'];
			}
			$compaign_id = $offer['campaign_id'];
			$where2 = "campaigns.campaign_id='".$compaign_id."'";
			$compaigns = $this->campaigns->get_where('*', $where2, true, '', '1', '');
			$data['vat'] = $compaigns[0]['vat'];
			if($promo_code){
				$promos['promo_code'] = $promo_code;
				$promos['shipping_id'] = $offer['shipping_id'];
				$promos['product_id'] = $offer['product_id'];
				$promos['campaign_id'] = $compaigns[0]['campaign_crm_id'];
				$response = get_discount($promos);
				$offer_price = $offer['offer_price'] - $response->coupon_amount;
				if($offer_price <= 0){
					$data['shipping_free'] = false;
					$data['shipping_fee'] = SHIPPING_PRICE;
				}
				$offer['offer_price'] = $offer_price;
			}
			$data['offer'] = $offer;
			$data['promocode'] = $promo_code;
			$data['images'] = $this->multimedia->getedit($offer['offer_id']);
			$data['title'] = $offer['offer_title'];
			$this->load->view('checkout/free_checkout', $data);
		}
	}
	public function free_thankyou($slug){
		$this->session->unset_userdata('prospectId');
		$this->layout = 'thankyou_layout';
		$promos = array();
		$promo_code = FREE_PRODUCT;
		$c4 = '';
		$data = array();
		$where = "offers.offer_slug='".$slug."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$offer = $offer[0];
			if($offer['discount_offer_price'] != 0){
				$offer['offer_price'] = $offer['discount_offer_price'];
			}
			if($this->session->userdata('c4')){
				$c4 = $this->session->userdata('c4');
			}

			$orders_id = $this->session->userdata('order_id');
			$conversion = create_fbPixel_conversion($this->session->userdata('user_data'),$offer['offer_url']);
			$post_data = json_encode($conversion['post_data']);
			$response_data = json_encode($conversion['response']);
			$update = array(
				'fbc_post_data' => json_encode($post_data),
				'fbc_response_data' => json_encode($response_data),
			);
			$where2 = "orders.id='".$orders_id."'";
			$db_response = $this->orders->update_by_where($update, $where2);

			$compaign_id = $offer['campaign_id'];
			$where2 = "campaigns.campaign_id='".$compaign_id."'";
			$compaigns = $this->campaigns->get_where('*', $where2, true, '', '1', '');
			$data['vat'] = $compaigns[0]['vat'];
			$data['c4'] = $c4;
			if($promo_code){
				$promos['promo_code'] = $promo_code;
				$promos['shipping_id'] = $offer['shipping_id'];
				$promos['product_id'] = $offer['product_id'];
				$promos['campaign_id'] = $compaigns[0]['campaign_crm_id'];
				$response = get_discount($promos);
				$offer['offer_price'] = $offer['offer_price'] - $response->coupon_amount;
			}
			$data['totalAmount'] = SHIPPING_PRICE;
			$data['currencyCode'] = 'EUR';
			$data['orderReference'] = $orders_id;
			$data['voucherCode'] = FREE_PRODUCT;
			$data['offer'] = $offer;
		}
		$this->session->unset_userdata('c4');
		$this->load->view('checkout/free_thankyou', $data);
	}
	public function test_thankyou(){
		$data = array();
		$data['totalAmount'] = SHIPPING_PRICE;
		$data['currencyCode'] = 'EUR';
		$data['orderReference'] = 2;
		$data['voucherCode'] = FREE_PRODUCT;
		$this->load->view('checkout/test_thankyou', $data);
	}
	public function free_checkout_process(){
		$this->session->unset_userdata('user_data');
		$data = [];
		$resp = array();
		$data['response'] = false;

		if(!$this->input->is_ajax_request()){
			exit('No direct script access allowed');
		}
		$this->form_validation->set_rules('firstname', 'First Name', 'required|trim', array('required' => $this->lang->line("first_name_req")));
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|trim', array('required' => $this->lang->line("last_name_req")));
		$this->form_validation->set_rules('address', 'Address', 'required|trim', array('required' => $this->lang->line("address_req")));
		$this->form_validation->set_rules('phone', 'PhoneNumber', 'required|trim', array('required' => $this->lang->line("phoneNumber_req")));
		$this->form_validation->set_rules('city', 'city', 'required|trim', array('required' => $this->lang->line("city_req")));
		$this->form_validation->set_rules('zip', 'zip', 'required|trim', array('required' => $this->lang->line("postalCode_req")));
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', array('required' => $this->lang->line("email_req")));
		$this->form_validation->set_rules('goodeess-terms', 'Goodeess-terms', 'required|trim', array('required' => $this->lang->line("goodeess-terms_req")));
		$this->form_validation->set_rules('ccnumber', 'CardNumber', 'required|trim', array('required' => $this->lang->line("cardNumber_req")));
		$this->form_validation->set_rules('expdatem', 'Expire Month', 'required|trim', array('required' => $this->lang->line("expdate_req")));
		$this->form_validation->set_rules('expdatey', 'Expire Year', 'required|trim', array('required' => $this->lang->line("expdate_req")));
		$this->form_validation->set_rules('cvv', 'Card Security Code', 'required|trim', array('required' => $this->lang->line("cardSecurityCode_req")));
		$this->form_validation->set_rules('cardholder', 'Card holder', 'required|trim', array('required' => $this->lang->line("cardholder_req")));
		if($this->form_validation->run()==true){
			$formData = $this->input->post();

			$slug = $formData['slug'];
			$where = "offers.offer_slug='".$slug."'";
			$offer = $this->offers->get_where('*', $where, true, '', '1', '');
			if(!empty($offer)){

			}
			$ip_server = $_SERVER['SERVER_ADDR'];
			if (!empty($formData['email']) && !empty($offer)) {
				$offer = $offer[0];
				$compaign_id = $offer['campaign_id'];
				$where2 = "campaigns.campaign_id='".$compaign_id."'";
				$compaigns = $this->campaigns->get_where('*', $where2, true, '', '1', '');
				$user_data = array(
					'first_name' => $formData['firstname'],
					'last_name' => $formData['lastname'],
					'email' => $formData['email'],
					'offer_crm_id' => $offer['offer_crm_id']
				);
				$orders_id = $this->orders->save($user_data);
				if (isset($formData['status']) && $formData['status'] != "error") {
					$credentials = base64_encode("vipresponse_6790:c5da8ce5e36681");
					$customerEmail = $formData['email'];
					$firstname = $formData['firstname'];
					$lastname = $formData['lastname'];
					$phone = $formData['phone'];
					$address = $formData['address'];
					$zip = $formData['zip'];
					$city = $formData['city'];
					$country = $formData['country'];
					$cardNumber= $formData['ccnumber'];
					$expdatem = $formData['expdatem'];
					$expdatey = $formData['expdatey'];

					$expdate = $expdatem.$expdatey;

					$cvv = $formData['cvv'];

					//3DS parameters received from the form
					$cavv = $formData['authenticationValue'];
					$ds_trans_id = $formData['ds_trans_id'];
					$eci = $formData['eci'];
					$d_version = $formData['protocolVersion'];
					//	$x_id = $formData['x_transaction_id'];

					$urlencoded_cavv = urlencode($cavv);

					if (substr($cardNumber, 0, 1) == '4') {

						$cardType = 'visa';

					}elseif (substr($cardNumber, 0, 1) == '5'){

						$cardType = 'master';

					}else{

						$cardType = 'discover';

					}

					$curl = curl_init();
					//$product = array();

					if($offer["price_type"] == 1) {
						//product with trial
						$product = array( [	'offer_id'=> !empty($offer['offer_crm_id']) ? $offer['offer_crm_id'] : '1',
							'product_id'=> $offer["product_id"],
							'billing_model_id'=>!empty($offer['billing_model_id']) ? $offer['billing_model_id'] : '2',
							'quantity'=>'1',
							'step_num'=>'2'],

							['offer_id'=> '1',
								'product_id'=> '2',
								'billing_model_id'=>'3',
								'quantity'=>'1',
								'step_num'=>'2',
								'trial' => array(
									'product_id'=> '2' ,
								)]);


					}else {

						$product = array( [	'offer_id'=> !empty($offer['offer_crm_id']) ? $offer['offer_crm_id'] : '1',
							'product_id'=> $offer["product_id"],
							'billing_model_id'=>!empty($offer['billing_model_id']) ? $offer['billing_model_id'] : '2',
							'quantity'=>'1',
							'step_num'=>'2'],

							['offer_id'=> '1',
								'product_id'=> '2',
								'billing_model_id'=>'3',
								'quantity'=>'1',
								'step_num'=>'2',
								'trial' => array(
									'product_id'=> '2' ,
								)]);
					}
					if($this->session->userdata('is_free') == false){
						$offer['shipping_id'] = 3;
					}
					$params = array(
						'firstName'=>$firstname,
						'lastName'=>$lastname,
						'billingFirstName'=>$firstname,
						'billingLastName'=>$lastname,
						'billingAddress1'=>$address,
						'billingAddress2'=> 'FL 7',
						'billingCity'=> $city,
						'billingState'=>'N/A',
						'billingZip'=>$zip,
						'billingCountry'=>$country,
						'phone'=>$phone,
						'email'=>$customerEmail,
						'creditCardType'=> $cardType,
						'creditCardNumber'=> $cardNumber,
						'expirationDate'=> $expdate,
						'CVV'=> $cvv,
						'shippingId'=> 3,
						'tranType'=> 'Sale',
						'ipAddress'=>  $ip_server,
						'campaignId'=> $compaigns[0]["campaign_crm_id"],//!empty($compaign_id) ? $compaign_id : '3',
						'offers' => $product,
						'billingSameAsShipping'=>'YES',
						'shippingAddress1'=> $address,
						'shippingAddress2'=>'APT 7',
						'shippingCity'=> $city,
						'shippingState'=>'N/A',
						'shippingZip'=> $zip,
						'shippingCountry'=> $country,
						'three_d_redirect_url'=>'',
						'alt_pay_return_url'=>'',
						'sessionId'=>'',
						'cascade_override'=>'',
						'create_member'=>'',
						'event_id'=>'',
						'ssn_nmi'=>'',
						'utm_source'=>'',
						'utm_medium'=>'',
						'utm_campaign'=>'campaign',
						'utm_content'=>'content',
						'utm_term'=>'term',
						'device_category'=>'mobile',
						'checkingAccountNumber'=>'',
						'checkingRoutingNumber'=>'',
						'sepa_iban'=>'',
						'sepa_bic'=>'',
						'eurodebit_acct_num'=>'',
						'eurodebit_route_num'=>'',
						'referrer_id'=>'ABCD1234',
						'3d_version'=>$d_version,
						'cavv'=>$urlencoded_cavv,
						'eci'=>$eci,
						'ds_trans_id'=>$ds_trans_id,
						'promoCode' => FREE_PRODUCT
					);
					curl_setopt_array($curl, array(
						CURLOPT_URL => 'https://vipresponse.sticky.io/api/v1/new_order',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_POSTFIELDS => json_encode($params),
						CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json",
							"Authorization: Basic dmlwcmVzcG9uc2VfNjc5MDpjNWRhOGNlNWUzNjY4MQ=="
						),
					));

					$response = curl_exec($curl);

					$result = json_decode($response, true);
					curl_close($curl);
					$data = $result['response_code'];
					$saved_params = $params;
					unset($saved_params['creditCardNumber']);
					unset($saved_params['expirationDate']);
					unset($saved_params['CVV']);
					unset($saved_params['creditCardType']);
					$user_agient = $this->agent->browser().' '.$this->agent->version();
					$saved_params['user_agent'] = $user_agient;
					$saved_params['c3'] = !empty($this->session->userdata('c3')) ? $this->session->userdata('c3') : '';
					if ($data == '100'){
						$this->session->unset_userdata('is_free');
						$this->session->set_userdata('user_data', $saved_params);
						$this->session->set_userdata('order_id', $orders_id);
						$update = array(
							'import_order_post_data' => json_encode($saved_params),
							'import_order_response' => json_encode($result),
							'is_successd' => 1,
							'order_id' => $result['order_id'],
							'product_id' => $offer['product_id'],
							'offer_id' => $offer['offer_id']
						);
						$where2 = "orders.id='".$orders_id."'";
						$db_response = $this->orders->update_by_where($update, $where2);
						$resp['response'] = true;
						if(isset($formData['promocode']) && !empty($formData['promocode'])){
							$resp['redirect_url'] = base_url('/')."./home/free_thankyou/".$slug."?coupon=".$formData['promocode'];
						} else {
							$resp['redirect_url'] = base_url('/')."./home/free_thankyou/".$slug;
						}

					}     else{
						$update = array(
							'import_order_post_data' => json_encode($saved_params),
							'import_order_response' => json_encode($result),
						);
						$where2 = "orders.id='".$orders_id."'";
						$db_response = $this->orders->update_by_where($update, $where2);
						$resp['response_code'] = $data;
						$resp['response'] = false;
						$resp['error_message'] =  !empty($result['decline_reason']) ? $result['decline_reason'] : 'Invalid card(Non-3DS).';
					}
				}else{
					$update = array(
						'error_message' => 'Invalid card(Non-3DS)..',
					);
					$where2 = "orders.id='".$orders_id."'";
					$db_response = $this->orders->update_by_where($update, $where2);
					$resp['response'] = false;
					$resp['error_message'] = 'Invalid card(Non-3DS)..' ;
				}

			} else {
				$resp['response'] = false;
				$resp['error_message'] = 'No Email found' ;
			}
		}else {
			$resp['errors'] = $this->form_validation->error_array();
		}
		echo json_encode($resp);
		exit;
	}
	public function soldout_product($slug){
		$this->layout = 'site_template';
		$geo = 'fr';
		$data = [];
		$query = $this->input->get();
		$data['slug'] = $slug;
		$where = "offers.offer_slug='".$slug."'";
		$offer = $this->offers->get_where('*', $where, true, '', '1', '');
		if(!empty($offer)){
			$data['offer'] = $offer[0];
			$percent = (($offer[0]['offer_price'] - $offer[0]['discount_offer_price'])*100) /$offer[0]['offer_price'] ;
			$data['images'] = $this->multimedia->getedit($offer[0]['offer_id']);
			if($percent !== 0) {
				$percent = number_format($percent, 2);
			}
			$data['discount'] = $percent;
			$data['title'] = $offer[0]['offer_title'];
		}
		if($this->input->get('geo')){
			$this->session->set_userdata('site_lang', $this->input->get('geo'));
		}
		if($this->input->get('affId')){
			$this->session->set_userdata('affId', $this->input->get('affId'));
		}
		foreach($query as $key => $value){
			$this->session->set_userdata($key, $value);
		}
		if($this->session->userdata('geo')){
			$geo = $this->session->userdata('geo');
		}
		$data['geo'] = $geo;
		$this->load->view('notifications/index', $data);
	}
	public function get_notification(){
	    	$this->layout = '';
		$resp = array();
		$resp['status'] = false;
		$data = $this->input->post();
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$resp['message'] = "Invalid email format";
		}
		if($this->notifications->add($data)){
			$resp['status'] = true;
			$resp['message'] = "You will be notified";
		}
		echo  json_encode($resp);
		exit;
	}
	public function new_prospect(){
		$data = [];
		$resp = array();
		$resp['response'] = false;
		if(!$this->input->is_ajax_request()){
			exit('No direct script access allowed');
		}
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', array('required' => $this->lang->line("email_req")));
		if($this->form_validation->run()==true){
			$formData = $this->input->post();
			$slug = $formData['slug'];
			$where = "offers.offer_slug='".$slug."'";
			$offer = $this->offers->get_where('*', $where, true, '', '1', '');
			$offer = $offer[0];
			if(!empty($offer)) {
				$compaign_id = $offer['campaign_id'];
				$where2 = "campaigns.campaign_id='".$compaign_id."'";
				$compaigns = $this->campaigns->get_where('*', $where2, true, '', '1', '');
				$compaigns = $compaigns[0];
				$customerEmail = !empty($formData['email']) ? $formData['email'] : '';
				$firstname = !empty($formData['firstname']) ? $formData['firstname'] : '';
				$lastname = !empty($formData['lastname']) ? $formData['lastname'] : '';
				$phone    =   !empty($formData['phone']) ? $formData['phone'] : '';
				$address  = !empty($formData['address']) ? $formData['address'] : '';
				$zip = !empty($formData['zip']) ? $formData['zip'] : '';
				$city = !empty($formData['city']) ? $formData['city'] : '';
				$country = !empty($formData['country']) ? $formData['country'] : '';
				$ipAddress = get_client_ip();
				$click_id = '';
				if(!empty($this->session->userdata('click_id'))){
					$click_id = $this->session->userdata('click_id');
				}
			   if(empty($this->session->userdata('prospectId'))){
				   $data = array('campaignId'=>$compaigns['campaign_crm_id'],'email'=>$customerEmail,'ipAddress'=>$ipAddress,'firstName'=>$firstname,'lastName'=>$lastname,'phone'=>$phone,'city'=>$city,'zip'=>$zip,'address1'=>$address,'country'=>$country,'click_id'=>$click_id);
				   $response = create_new_prospect($data);
			   } else {
			     	$prospect_id =$this->session->userdata('prospectId');
				    $data = array('prospect_id'=> array($prospect_id => array('email'=>$customerEmail,'ipAddress'=>$ipAddress,'firstName'=>$firstname,'lastName'=>$lastname,'phone'=>$phone,'city'=>$city,'zip'=>$zip,'address'=>$address,'country'=>$country,'click_id'=>$click_id)));
				    $response = upadate_prospect($data);
			   }
			    if(!empty($response->response_code) && $response->response_code == 100 || !empty($this->session->userdata('prospectId'))){
					if(empty($this->session->userdata('prospectId'))){
						$prospect_id = $response->prospectId;
						$this->session->set_userdata('prospectId', $prospect_id);
					}
			    	$resp['response'] = true;
				}
			}
		}
		echo json_encode($resp);
		exit;
	}

}
