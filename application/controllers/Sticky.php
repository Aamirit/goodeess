<?php defined('BASEPATH') OR exit('No direct script access allowed');
session_start(); 
class Sticky extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
	
	$data = array();
	//$this->template->set('title', 'Sticky');
	//$this->template->load('sticky_layout', 'contents' , 'checkout_sticky', $data);
	
	header("Access-Control-Allow-Origin: *");

		$ip_server = $_SERVER['SERVER_ADDR'];
		if (!empty($_POST['email']) ) {
                  // $Username = "vipresponse_6790";
				  //$Password = "c5da8ce5e36681";
				  $credentials = base64_encode("vipresponse_6790:c5da8ce5e36681");
		
			                    //$tokenId = $_POST['token'];
								$customerEmail = $_POST['email'];
								$firstname = $_POST['firstname'];
								$lastname = $_POST['lastname'];
								$phone = $_POST['phone'];
								$address = $_POST['address'];
								$zip = $_POST['zip'];
								$city = $_POST['city'];
								$country = $_POST['country'];
								$cardNumber= $_POST['ccnumber'];
								$expdate = $_POST['expdate'];
								$cvv = $_POST['cvv'];
								
						if (substr($cardNumber, 0, 1) == '4') {
								
								$cardType = 'visa';
                                    
                                }elseif (substr($cardNumber, 0, 1) == '5'){
								
								$cardType = 'master';
								
								}else{
								
								$cardType = 'discover';
								
								}
								
								$expiry = str_replace('/','',$expdate);
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
								'expirationDate'=> $expiry,
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
								'shippingCountry'=> $country
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
			                //echo "Transaction ID: " .$result .$expiry;
							//print $data;
							if ($data == '100'){
							
							//echo "Success";
							
							header("Location: https://goodeess-plus.com/ci/Sticky/thankyou");
							die();
							
							}else{
							
							print $data;
							
							}
							
			
		} else { 
		
			echo "No Email found"; 
			
		}

	}
			public function thankyou()
	{
		$data = array();
		$this->template->set('title', 'Success');
		$this->template->load('thankyou_layout', 'contents' , 'thankyou', $data);
	}
	
	
	
		public function error()
	{
		$data = array();
		$this->template->set('title', 'Transaction Error');
		$this->template->load('thankyou_layout', 'contents' , 'error_page', $data);
	}
	

}
