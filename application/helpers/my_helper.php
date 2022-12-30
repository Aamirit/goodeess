<?php

  function konnektive_curl($params, $request){
    $query = http_build_query($params);
    $set_opt = array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST'
    );
    if($request==='import_click'){
      $set_opt[CURLOPT_URL] = 'https://api.konnektive.com/landers/clicks/import/?'.$query;
    }else if($request==='import_lead'){
      $set_opt[CURLOPT_URL] = 'https://api.konnektive.com/leads/import/?'.$query;
    }else {
      $set_opt[CURLOPT_URL] = 'https://api.konnektive.com/order/import/?'.$query;
    }

    $curl = curl_init();
    curl_setopt_array($curl, $set_opt);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }


  function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function process_slug($string) {
  //Clean first and last whitespaces
  $string = trim($string);
  //Lower case everything
  $string = strtolower($string);
  //Make alphanumeric (removes all other characters)
  $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
  //Clean up multiple dashes or whitespaces
  $string = preg_replace("/[\s-]+/", " ", $string);
  //Convert whitespaces and underscore to dash
  $string = preg_replace("/[\s_]/", "-", $string);
  return $string;
}
function get_discount($data){
	$request_data = array('email'=>'phpclicks@gmail.com','promo_code'=>$data['promo_code'],'campaign_id'=>$data['campaign_id'],'shipping_id'=>$data['shipping_id'],'products'=>array(array('product_id'=>$data['product_id'],'quantity'=>1)));
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://vipresponse.sticky.io/api/v1/coupon_validate',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>json_encode($request_data),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Basic dmlwcmVzcG9uc2VfNjc5MDpjNWRhOGNlNWUzNjY4MQ=='
		),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return json_decode($response);
}
function create_fbPixel_conversion($request_data,$source_url, $type = 'purchase'){
  	$session_data = $request_data;
	$resp = array("status" => false);
  	$pairs = array(
		'592811811696689' => 'EAAE3nUIH1EoBAA2zgVkNuUKgSWCUEkItNMuTghvpRtBANU5rH3bPG24mFvJaZCelpZCATCxi9jiBoCehRwmuVOSK3nkjTHQkmMMwLWgykIcx0STbDdYhHCpy2HbddfRb9f317sdzEgIhObHUiKwiPJWaRu3AsqBoBg3K95fax6Tkf9mRK83m8BqZAx6ccIZD',
		'248994933760484' => 'EAACDSnmdBpkBANDdDcy59QT8ecfjZC7wk9b8ivVxfsbNn4K2xX8ZCklElUKZBxCv7tYwZBnPaL54oaEComCjXRqWL50CcCcZC0EZBNHicsEDjBgyFtLkg2PCiV8p8zaw3Vef5BkJhthoZBnNHUajf5BsVoxgp0TXrm9FsC4eaDut82JkV9jXjwiqfu7amhh5CEZD',
		'489141442155413' => 'EAAFoWwsrEP8BAGsGm7Ju0IgTQhThKf2SjbNwpJaG9E7AmK0zRgKs8cRMBKa6lYxxIAZB4oTAFM3RnMPVUhjtSaJbBBcuZANJUEfNJvAZBePhZBVjoCnx3CRUG1W8dUddkYOqAuVZCB0WSaqtZCym2P6CLfJfuKujSoRljpCpZBxuHX0diAZAgCZAHM8DfDutwdAsZD',
		'4709229845756063' => 'EAADR6peU6ggBANeA9BFAvMiZBv0BbZBrlD7RXqkLuxHVeyHcX1BNcrxZA2n8Ngi38fWaZC4uZA4PcSwdZBY0OF3qleQjrNjZAaMJQsxe1CdvTPfawVBcxDGClyUcKUnaNIv49893C2W0itzmSqEc3qnwbA4To0MhC9uyRQKIverkT4VZB3D5s08c91axONuNbRAZD',
		'460862868406701' =>  'EAANQ6GdldxgBADQnbMd6rOpSUDbpjtp3BsNq9OZBtRbSoFdaVmma4zue3gMMZCZC2rSjaC7seR22up975f6s3ZACWZBjoYrVo7ZCuQM4pZBjA1iCpZAd3Xoc1AoyB29HIGaf9OUILPewpHZAuGmEJvYQ0V5HfW9Ju4ZC2882bo9hCaiwZCwU40ZBNDuI',
		'1015746072488024' =>  'EAADmbrGXRiMBAKTZAzzHBZCMTzkB2nwsshdxqMZCl2DiQ61ZCiY6N4wxJgZBzlSYTqgf0XTnIZAhTeSmvcADsjZCe9KRbzppSmGi37ZCoRmc73WGlSUZArmPId5buXaldOgLtfoZCZCW0ZBrdnmycfhtZASbnlIPTTZC2ITdE9ZAOLtgGPYDUFzgBZC4ycsf',
	);
	$ip =  get_client_ip();
	$ip = explode(',', $ip);
	$ip = $ip[0];
	$agent =  $session_data["user_agent"];
	$pixel_id = !empty($session_data['c3']) ? $session_data['c3']:'';
	$fbc = '';
	$email = (!empty($session_data['email']))?$session_data['email']:'NoEmailCaptured@'.time().'.com';
	$phone = (!empty($session_data['phone']))?$session_data['phone']:'No Phone Captured';
	$email = hash('sha256', $email);// sha256($email);
	$phone = hash('sha256', $phone); //sha256($phone);
	if(!empty($pairs[$pixel_id])){
		$accessToken = $pairs[$pixel_id];
		if($type == 'view'){
			$string = '{
        "data": [
            {
                "event_name": "PageView",
                "event_id": "pageview786",
                "event_time": '.time().',
                "action_source": "website",
                "event_source_url": "'.$source_url.'",
                "user_data": {
                    "client_user_agent": "'.$agent.'",
                    "client_ip_address": "'.$ip.'"
                }
            }
        ]
    }';
		}
		elseif($type == 'purchase'){
			$string = '{
        "data": [
            {
                "event_name": "Purchase",
                "event_time": '.time().',
                "action_source": "website",
                "event_source_url": "'.$source_url.'",
                "user_data": {
                    "client_user_agent": "'.$agent.'",
                    "client_ip_address": "'.$ip.'",
                    "em": [
                        "'.$email.'"
                    ],
                    "ph": [
                        "'.$phone.'"
                    ],
                    "fbc": "'.$fbc.'"
                },
                "custom_data": {
                "value": 15.00,
                "currency": "EUR"
             }
            }
        ]
    }';
		}
		elseif($type == 'lead'){
			$string = '{
        "data": [
            {
                "event_name": "Lead",
                "event_time": '.time().',
                "action_source": "website",
                "event_source_url": "'.$source_url.'",
                "user_data": {
                    "client_user_agent": "'.$agent.'",
                    "client_ip_address": "'.$ip.'",
                    "em": [
                        "'.$email.'"
                    ],
                    "ph": [
                        "'.$phone.'"
                    ],
                    "fbc": "'.$fbc.'"
                },
                "custom_data": {
                "value": 15.00,
                "currency": "EUR"
             }
            }
        ]
    }';
		}

		$url = "https://graph.facebook.com/v11.0/".$pixel_id."/events?access_token=".$accessToken;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')
		);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close ($ch);
		$resp['status'] = true;
		$resp['post_data'] = $string;
		$resp['response'] = json_decode($response);
	} else {
		$resp['message'] = 'pixel id not found';
	}
	return $resp;
}
function create_new_prospect($data){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://vipresponse.sticky.io/api/v1/new_prospect',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>json_encode($data),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Basic dmlwcmVzcG9uc2VfNjc5MDpjNWRhOGNlNWUzNjY4MQ=='
		),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return json_decode($response);
}
function upadate_prospect($data){
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://vipresponse.sticky.io/api/v1/prospect_update',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>json_encode($data),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Basic dmlwcmVzcG9uc2VfNjc5MDpjNWRhOGNlNWUzNjY4MQ=='
		),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	return json_decode($response);
}
function is_user_allow($type = ''){
	$CI = & get_instance();
	$permissions = $CI->session->userdata('permissions');
	if(in_array($type, $permissions)){
		return true;
	} else {
		return  false;
	}
}

?>
