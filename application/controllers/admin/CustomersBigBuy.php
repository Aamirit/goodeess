<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomersBigBuy extends CI_Controller {

	public function __construct(){
	    parent::__construct();
		if (empty($_SESSION['email']) && is_user_allow('manage_orders') === false) {
			redirect(base_url('login'));
		}
        $this->layout = 'template';
		$this->load->model('admin/Offers_model','offers');
		$this->load->model('admin/Multimedia_model','multimedia');
		$this->load->model('admin/Campaigns_model','campaigns');
		$this->load->model('admin/Orders_model','orders');
		$this->load->model('admin/Customers_bigbuy_model','customers_bigbuy');
        $this->load->model('admin/Event_model','event');
	}

    public function resend_big_buyorder(){
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
         }

       $formdata = $this->input->post();
       $order_id = $formdata["order_id"];
        $where = "order_id='".$order_id."'";
        $order_data =  $this->customers_bigbuy->get_where('*', $where, true, '', '1', '');
        if(!empty($order_data)){
            $order_data = $order_data[0];
            $orders = $this->getStickyOrdersById($order_id);
            $orders = json_decode($orders);
            if(!empty($orders->response_code) && $orders->response_code == 100){
                $blaclEmails = array("izaskun.angulo@gmail.com");
                $where = "order_id = '".$order_id."' AND api_response_bigbuy != '
                ' AND api_response_bigbuy IS NOT NULL";
                $contactDB =  $this->customers_bigbuy->get_where('*', $where, true, '', '1', '');
                $contactDB = $contactDB[0];
                
                    $product_id = 0;
                    $product_name = '';
                    $product_price = '';
                    if(!empty($orders->products)){
                        foreach($orders->products as $key2 => $value2){
                            $product_id = $value2->product_id;
                            $product_name = $value2->name;
                            $product_price = $value2->price;
                        }
                    }
                    if(empty($product_id)){
                        exit;
                    }
                    // $where = "product_id = '".$product_id."' AND email='".$orders->email_address."'";
                    // // $sqls = "select * from customers_bigbuy where ";
                    
                    // // $query22 = mysqli_query($conn, $sqls);
                    // $checkdupeOrders =$this->customers_bigbuy->get_where('*', $where, true, '', '1', '');
                    // $checkdupeOrders = $checkdupeOrders[0];
           
                    // if(!empty($checkdupeOrders)) {
                    //     exit;
                    // }
            
                    // $skuarray = !empty($product_id)?getSkuKonnective($product_id):false;
                    if(!in_array($orders->email_address,$blaclEmails)){
                        $formData = [];
                        $postal_code = $orders->billing_postcode;
                        if($orders->shipping_country=="PT" || $orders->shipping_country=="pt"){
                            $postal_code = str_replace(array("-"," "),"",$postal_code);
                            $postal_code = wordwrap($postal_code,4,'-',true);
                        }
            
                        $formData['firstName'] = $orders->first_name;
                        
                        $formData['lastName'] = $orders->last_name;
                        $formData['address1'] = $orders->billing_street_address;
                        $formData['phoneNumber'] = $orders->customers_telephone;
                        $formData['country'] = $orders->billing_country;
                        $formData['city'] = $orders->shipping_city;
                        $formData['postalCode'] = $postal_code;
                        $formData['emailAddress'] = $orders->email_address;
                        //$formData['emailAddress'] = 'mamir.tvs@gmail.com';
                        $formData['title'] = $product_name;
                        $formData['qty'] = 1;
                        $formData['price'] = $product_price;
                        $skuarray = $this->getSkuSticky($orders);
                        $cBB = $this->createBigBuy($formData,$skuarray);
                        $cBB['api_response'] = empty($cBB['api_response']) ? "success":'';
                        $customer_rec = '';
                        $customer = $this->getExistingOrCreateCustomer($formData, $orders->shipping_country);
                        if($customer->data[0]){
                            $customer_rec = json_encode($customer->data[0]);
                        }
                        $event_id = $this->getEventId($orders->billing_country);
                        $event_response = $this->triggerCustomEvent($event_id, $orders);
                       
                        $eventdata = [
                            "order_id"=> $order_id,
                            "event_response_data"=> $event_response['response'],
                            "event_post_data" => $event_response['post_data']
                        ];
                        
                        
                        $sql_event_id = $this->event->save($eventdata);
                        // print_r($sql_event_id);
                        // exit;

                        // $sql_event = "INSERT INTO event_logs(order_id,event_post_data,event_response_data) VALUES ('".$order_id."','".$conn->real_escape_string($event_response['post_data'])."','".$conn->real_escape_string($event_response['response'])."')";
                        // $conn->query($sql_event);

                        $customers_bigbuy_data = [
                            "email"=> $orders->email_address,
                            "product_id"=> $product_id,
                            "order_id"=> $order_id,
                            "customer_data" => json_encode($formData),
                            "post_data_bigbuy" => $cBB['api_post'],
                            "api_response_bigbuy" =>$cBB['api_response'] ,
                            "is_sent" => 1 ,
                            "is_manual" => 1,
                            "bigcommerce_customer" => $customer_rec,

                        ];
                        $where = "order_id = '".$order_id."'";   
                        $customer_big_buy_id = $this->customers_bigbuy->update_by_where($customers_bigbuy_data,$where);
                        print_r($customer_big_buy_id);
                        exit;

                        // $sql = "INSERT INTO customers_bigbuy (email,product_id,order_id,customer_data,post_data_bigbuy,api_response_bigbuy,is_sent,is_manual, bigcommerce_customer) VALUES ('".$orders->email_address."','".$product_id."','".$order_id."','".$conn->real_escape_string(json_encode($formData))."','".$conn->real_escape_string($cBB['api_post'])."','".$conn->real_escape_string($cBB['api_response'])."',1,0,'".$conn->real_escape_string($customer_rec)."')";
                        if($conn->query($sql) === TRUE){
            
                        }
                        else{
                            createLog("<======================>");
                            createLog($sql);
                        }
                        exit;
                    }
                
            
            }
        }
        
    } 

    public function createBigBuy($formData, $productData)
{
    $firstName = '';
    if(!empty($formData['firstName'])){
        $firstName = substr(strip_tags($formData['firstName']),0,20);
    }
    $lastName = '';
    if(!empty($formData['lastName'])){
        $lastName = substr(strip_tags($formData['lastName']),0,20);
    }
    $address = '';
    if(!empty($formData['address1'])){
        $address = substr(strip_tags($formData['address1']),0,70);
    }

    $data = [];
    $json_array = array(
        'order' => array(
            'internalReference' => rand(0, 9999999),
            'language' => 'es',
            'paymentMethod' => 'paypal',
            'carriers' => array_chunk($productData['carriers'], 1, 1),
            'shippingAddress' =>
                array(
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'country' => !empty($formData['country'])?$formData['country']:'',
                    'postcode' => !empty($formData['postalCode'])?$formData['postalCode']:'',
                    'town' => !empty($formData['city'])?$formData['city']:'',
                    'address' => $address,
                    'phone' => !empty($formData['phoneNumber'])?$formData['phoneNumber']:'',
                    'email' => !empty($formData['emailAddress'])?$formData['emailAddress']:'',
                    'comment' => !empty($formData['customer_message'])?$formData['customer_message']:'',
                ),
            'products' => array_chunk($productData['fnl_product'], 3, 3)
        ),
    );

    $data['api_post'] = json_encode($json_array);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.bigbuy.eu/rest/order/create.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($json_array),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ZWFmZTlhNzk1MTVlNmQyYjBkYTM1MzM5NzExNGIwNjVlZGQzYzBlMTk4NGE3NGNjOTBlZmNhYzQzYzJkZjc0OQ',
            'Content-Type: application/json',
            'Accept: application/json',
            'Access-Control-Allow-Origin: *',
            'Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS',
            'Access-Control-Allow-Headers: token, Content-Type, X-Auth-Token',
            'Access-Control-Max-Age: 1728000'
        ),
    ));
   // $response = curl_exec($curl);
    $response = true;
    $err = curl_error($curl);
    curl_close($curl);

    $descode = json_decode($response,true);

    $data['api_response'] = $response;
    // debug($data,false);

    if(!empty($descode['code']) && $descode['code'] == 'ER010' && !empty($formData['country']) && $formData['country'] == 'PT') {

        $json_array = array(
            'order' => array(
                'internalReference' => rand(0, 9999999),
                'language' => 'es',
                'paymentMethod' => 'paypal',
                'carriers' => array(array("name"=>"ctt")),
                'shippingAddress' =>
                    array(
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'country' => !empty($formData['country'])?$formData['country']:'',
                        'postcode' => !empty($formData['postalCode'])?$formData['postalCode']:'',
                        'town' => !empty($formData['city'])?$formData['city']:'',
                        'address' => $address,
                        'phone' => !empty($formData['phoneNumber'])?$formData['phoneNumber']:'',
                        'email' => !empty($formData['emailAddress'])?$formData['emailAddress']:'',
                        'comment' => !empty($formData['customer_message'])?$formData['customer_message']:'',
                    ),
                'products' => array_chunk($productData['fnl_product'], 3, 3)
            ),
        );

        $data['api_post'] = json_encode($json_array);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.bigbuy.eu/rest/order/create.json',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($json_array),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ZWFmZTlhNzk1MTVlNmQyYjBkYTM1MzM5NzExNGIwNjVlZGQzYzBlMTk4NGE3NGNjOTBlZmNhYzQzYzJkZjc0OQ',
                'Content-Type: application/json',
                'Accept: application/json',
                'Access-Control-Allow-Origin: *',
                'Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS',
                'Access-Control-Allow-Headers: token, Content-Type, X-Auth-Token',
                'Access-Control-Max-Age: 1728000'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $data['api_response'] = $response;
        debug($data,false);
    }
    return $data;
}

public function getStickyOrdersById($order_id){
    $post_data = array();
    $post_data['order_id'] = array($order_id);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://vipresponse.sticky.io/api/v1/order_view',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($post_data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic dmlwcmVzcG9uc2VfNjc5MDpjNWRhOGNlNWUzNjY4MQ=='
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}

public function getStickyOrders()
{
    $orders = [];
    $query = [];
    //    $query['loginId'] = "developers-vipresponse";
    //    $query['password'] = "dev@sit";
    $query['start_date'] = date('m/d/Y');
    $query['end_date'] = date('m/d/Y');
    $query['campaign_id']="all";
    //$query['emailAddress'] = 'nas9764@gmail.com';

    $DateTime = new DateTime();
    $DateTime->modify('-2 hours');
    $datetime = $DateTime->format("Y-m-d H:i:s");
    $datetime = explode(' ', $datetime);
    $time = $datetime[1];


    $endTimes = date("Y-m-d H:i:s");
    $endTimeex = explode(" ",$endTimes);
    $endTime = $endTimeex[1];

    $startTimee = date("Y-m-d H:i:s", strtotime("-2 hours"));
    $startTimeex = explode(" ",$startTimee);
    $startTime = $startTimeex[1];

    $query['end_time'] = $endTime;
    $query['start_time'] = $startTime;
    $query['return_type'] = "order_view";
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://vipresponse.sticky.io/api/v1/order_find',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
	"campaign_id":"all",
	"start_date":"01/01/2021",
	"end_date":"01/01/2023",
    "criteria":"all",
    "return_type":"order_view"
    }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic cGhwY2xpY2tzOnNoaHB4Rmt5NFFiS2dh'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

public function getKonnectiveOrders()
{
    $orders = [];

    $query = [];
    $query['loginId'] = "developers-vipresponse";
    $query['password'] = "dev@sit";
    $query['startDate'] = date('m/d/Y');
    $query['endDate'] = date('m/d/Y');
    $query['orderStatus']="COMPLETE";
    //$query['emailAddress'] = 'nas9764@gmail.com';

    $DateTime = new DateTime();
    $DateTime->modify('-2 hours');
    $datetime = $DateTime->format("Y-m-d H:i:s");
    $datetime = explode(' ', $datetime);
    $time = $datetime[1];


    $endTimes = date("Y-m-d H:i:s");
    $endTimeex = explode(" ",$endTimes);
    $endTime = $endTimeex[1];

    $startTimee = date("Y-m-d H:i:s", strtotime("-2 hours"));
    $startTimeex = explode(" ",$startTimee);
    $startTime = $startTimeex[1];

    $query['endTime'] = $endTime;
    $query['startTime'] = $startTime;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://api.konnektive.com/order/query/?". http_build_query($query));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $order_output1 = curl_exec($curl);
    $order_output1 = json_decode($order_output1 , true);
    if(!empty($order_output1['message']['data']) && $order_output1['result']=='SUCCESS'){

        $results = $order_output1['message']['data'];
        foreach($results as $key => $value){
            array_push($orders, $value);
        }

        $totalResults = $order_output1['message']['totalResults'];

        if($totalResults<=25) {
            return $orders;
        }
        $resultsPerPage = $order_output1['message']['resultsPerPage'];
        echo $totalResults.".....<br/>";
        //exit;
        $per = 2;
        if($totalResults > $resultsPerPage){
            $per = ceil($totalResults/$resultsPerPage);
        }

        for($i=2; $i <= $per; $i++){
            $query['page'] = $i;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://api.konnektive.com/order/query/?". http_build_query($query));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $order_output1 = curl_exec($curl);
            $order_output1 = json_decode($order_output1 , true);
            if(!empty($order_output1['message']['data']) && $order_output1['result']=='SUCCESS'){
                $results = $order_output1['message']['data'];
                foreach($results as $key => $value){
                    array_push($orders, $value);
                }
            }
        }

    }
    return $orders;
}

public function getSkuKonnective($product_id)
{
    $fnl_product = array();
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.konnektive.com/product/query/?loginId=developers-vipresponse&password=dev@sit&productId='.$product_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Auth-Client: c2e28d90dbd7a888ef73774659e0e13128144e7994faec62a468dad4f7536220',
            'X-Auth-Token: 9qm4gyecq06tx2rwoadiy669z6dw4ab'
        ),
    ));

    $response = curl_exec($curl);
    $json_response = json_decode($response);
    // echo "<pre>";
    // print_r($json_response);
    // curl_close($curl);

    if(!empty($json_response->result) && $json_response->result=='ERROR'){
        //mail("phpclicks@gmail.com","Konnective Cron Error",$json_response->message);
    }

    if(!empty($json_response->message)){
        foreach ($json_response->message as $key => $value) {
            $fnl_product['reference'] = substr($value->productSku, 0, 8);
            $fnl_product['quantity'] = 1;
            $fnl_product['internalReference'] = rand(0, 9999999);
        }

        foreach ($json_response->message as $key => $value) {
            $carriers['name'] = 'gls';
        }
        if(empty( $carriers['name'])){
            $carriers['name'] = 'gls';
        }
        return array('fnl_product'=>$fnl_product,'carriers'=>$carriers);
    } else {
        return false;
    }
}

public function getSkuSticky($record)
{
    $fnl_product = array();
    if(!empty($record->products)){
        foreach ($record->products as $key => $value) {
            $fnl_product['reference'] = substr($value->sku, 0, 8);
            $fnl_product['quantity'] = 1;
            $fnl_product['internalReference'] = rand(0, 9999999);
        }
        if(empty( $carriers['name'])){
            $carriers['name'] = 'gls';
        }
        return array('fnl_product'=>$fnl_product,'carriers'=>$carriers);
    } else {
        return false;
    }
}

public function createLog($data = '')
{
    if(empty($data)){
        return false;
    }

    $log_filename = "logsss";
    if (!file_exists($log_filename)){
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    file_put_contents($log_file_data, $data."\n\n", FILE_APPEND);

    return false;
}

public function getExistingOrCreateCustomer($formData, $country_code){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.bigcommerce.com/stores/w7ecaf8elg/v3/customers?email:in='.$formData['emailAddress'].'',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'X-Auth-Client: 8pkngkjh4xh10st450swoq1hswzd6c1',
            'X-Auth-Token: 1fv9l21qfkx64di5qd8cvpgk6w56p2u'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response);
    if(sizeof($response->data) > 0) {
        return $response;
    } else {
        $abc = array( array(
            'email' => $formData['emailAddress'],
            'first_name' => $formData['firstName'],
            'last_name' => $formData['lastName'],
            //  'company' => $company,
            'phone' => $formData['phoneNumber'],
            // 'notes' => $notes,
            //'tax_exempt_category' => $tax_exempt_category,
            // 'customer_group_id' => 1,
            'addresses' => array(
                array(
                    'address1' =>  $formData['address1'],
                    //  'address2' => $res->address2,
                    // 'address_type' => $address_type,
                    'city' => $formData['city'],
                    // 'company' => $company,
                    'country_code' => strtoupper($country_code),
                    'first_name' => $formData['firstName'],
                    'last_name' => $formData['lastName'],
                    'phone' =>  $formData['phoneNumber'],
                    'postal_code' => $formData['postalCode'],    // Need to replace later with dynamic code.
                    'state_or_province' => $formData['city'],    // Need to replace later with dynamic state name.
                ),
            ),
            'authentication' => array(
                'force_password_reset' => true,
                // 'new_password' => $res->clubPassword,
            ),
            'accepts_product_review_abandoned_cart_emails' => true,
            'store_credit_amounts' => array(
                array(
                    'amount' => 20,
                ),
            )
            // 'origin_channel_id' => $origin_channel_id,
        ));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.bigcommerce.com/stores/w7ecaf8elg/v3/customers',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($abc),
            CURLOPT_HTTPHEADER => array(
                'X-Auth-Token: 1fv9l21qfkx64di5qd8cvpgk6w56p2u',
                'Content-Type: application/json',
                'Access-Control-Allow-Origin: *',
                'Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS',
                'Access-Control-Allow-Headers: token, Content-Type, X-Auth-Token',
                'Access-Control-Max-Age: 1728000'
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return array("status" => false);
        } else {
            $val = json_decode($response);
            return $val;
        }
    }
}

public function getEventId($country){
    $eventList = array(
        "DE" => "61e543bac01934001be84f74",
        "ES" => "6261453bec2b52001f12d868",
        "PT" => "62614611e73644001ec7c7f8",
        "FR" => "61a8c2e0206c16001c49fc6d"
    );
    return $eventList[$country];
}

public function triggerCustomEvent($event_id, $order){
    $countryList = array(
        "DE" => "Germany",
        "ES" => "Spain",
        "PT" => "Portugal",
        "FR" => "France"
    );
    $resp = array();
    $data = array();
    $custom_fields = array();
    $custom_fields['Country'] = '';
   // $custom_fields['Email'] = 'mfarhanriaz14@gmail.com';
    $custom_fields['Email'] = $order->email_address;
    $custom_fields['Country'] = !empty($countryList[$order->billing_country]) ? $countryList[$order->billing_country]:$order->billing_country;
    $custom_fields['OrderId'] =$order->order_id;
    $custom_fields['OrderNumber'] =$order->order_id;
    $custom_fields['OrderDate'] =$order->time_stamp;
    $custom_fields['Discount'] =$order->totals_breakdown->volume_discount;
    foreach($order->products as $product){
        if($product->product_id != 2) {
            $custom_fields['ProductId'] =$product->product_id;
            $custom_fields['ProductSku'] =$product->sku;
             $custom_fields['Quantity'] =$product->product_qty;
             $custom_fields['ProductTitle'] =$product->name;
            //$custom_fields['ModeOfPayment'] ='';
            //$custom_fields['ShippingMethod'] ='';
            //$custom_fields['Vendor'] =$product->sku;
            $custom_fields['Variant'] = $product->name;
        }
        if($product->product_id == 2) {
            $custom_fields['RecurringBillDate'] =$product->recurring_date;
        }

    }
    // $custom_fields['PriceAfterDiscount'] =$order->totals_breakdown->volume_discount;

    $custom_fields['Address'] = $order->billing_street_address;
    $custom_fields['City'] = $order->billing_city;
    $custom_fields['Tax'] = $order->totals_breakdown->tax;
    $custom_fields['CustomerName'] = $order->first_name.' '.$order->last_name;
    $custom_fields['SubtotalPrice'] =$order->totals_breakdown->subtotal;
    $custom_fields['ShippingPrice'] =$order->totals_breakdown->shipping;
    $custom_fields['TotalPrice'] =$order->totals_breakdown->total;
    $custom_fields['BillingAddress'] =$order->billing_street_address;

    $data['email'] = $order->email_address;
    //$data['email'] = 'mfarhanriaz14@gmail.com';
    $data['phone'] = $order->customers_telephone;
    $data['fields'] = $custom_fields;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.omnisend.com/v3/events/'.$event_id.'',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'x-api-key: 603f56394c7fa454543310fe-lIz0jlrxbLUG4KUyeG5CP0Z4OhxLRkKZbtZ3oDS913VZ6LYJMv',
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);

    curl_close($curl);
    $resp['response'] = $response;
    $resp['post_data'] = json_encode($data);
    return $resp;
}
}