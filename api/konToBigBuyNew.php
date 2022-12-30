<?php
echo "<h1>Processing........</h1>";
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('API_TOKEN', '603f56394c7fa454543310fe-lIz0jlrxbLUG4KUyeG5CP0Z4OhxLRkKZbtZ3oDS913VZ6LYJMv');

function debug($arr, $exit = true)
{
    print "<pre>";
        print_r($arr);
    print "</pre>";
    if($exit)
        exit;
}

// $servername = "localhost";
// $username = "root";
// $password = '';
// $dbname = "test";

$servername = "localhost";
$username = "goodefym_konnective";
$password = 'P@H}J^FQYHgR';
$dbname = "goodefym_goodies_plus";

############# Create connection #############
$conn = new mysqli($servername, $username, $password, $dbname);

############# Check connection #############
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$orders = getStickyOrders();
$orders = json_decode($orders);

// echo count($orders);
// debug($orders , true);

// exit;

if(!empty($orders->data) && $orders->response_code == 100){
    $blaclEmails = array("izaskun.angulo@gmail.com");
    foreach($orders->data as $key => $value){
        $sql = "select * from customers_bigbuy where order_id = '".$key."'";
        $query = mysqli_query($conn, $sql);
        $contactDB = mysqli_fetch_array($query);
        //|| $value['orderStatus'] == 'HOLD'
        if(!empty($contactDB['order_id']) ){
            echo $contactDB['order_id'].'...already processed<br/>';
            continue;
        } else {
            $product_id = 0;
            $product_name = '';
            $product_price = '';
            if(!empty($value->products)){
                foreach($value->products as $key2 => $value2){
                    $product_id = $value2->product_id;
                    $product_name = $value2->name;
                    $product_price = $value2->price;
                }
            }
            if(empty($product_id)){
                continue;
            }
            
            $sqls = "select * from customers_bigbuy where product_id = '".$product_id."' and email='".$value->email_address."'";
        $query22 = mysqli_query($conn, $sqls);
        $checkdupeOrders = mysqli_fetch_array($query22);
        
        if(!empty($checkdupeOrders)) {
            continue;
        }
           // $skuarray = !empty($product_id)?getSkuKonnective($product_id):false;
            if(!empty($key) && empty($checkdupeOrders) && !in_array($value->email_address,$blaclEmails)){
                $formData = [];
                $postal_code = $value->billing_postcode;
                if($value->shipping_country=="PT" || $value->shipping_country=="pt"){
                    $postal_code = str_replace(array("-"," "),"",$postal_code);
                    $postal_code = wordwrap($postal_code,4,'-',true);
                }

                $formData['firstName'] = $value->first_name;
                $formData['lastName'] = $value->last_name;
                $formData['address1'] = $value->billing_street_address;
                $formData['phoneNumber'] = $value->customers_telephone;
                $formData['country'] = $value->billing_country;
                $formData['city'] = $value->shipping_city;
                $formData['postalCode'] = $postal_code;
                $formData['emailAddress'] = $value->email_address;
                //$formData['emailAddress'] = 'mamir.tvs@gmail.com';
                $formData['title'] = $product_name;
                $formData['qty'] = 1;
                $formData['price'] = $product_price;
                $skuarray = getSkuSticky($value);
                $cBB = createBigBuy($formData,$skuarray);
                $cBB['api_response'] = empty($cBB['api_response']) ? "success":'';
                $customer_rec = '';
                $customer = getExistingOrCreateCustomer($formData, $value->shipping_country);
               if($customer->data[0]){
                   $customer_rec = json_encode($customer->data[0]);
               }

                $sql = "INSERT INTO customers_bigbuy (email,product_id,order_id,customer_data,post_data_bigbuy,api_response_bigbuy,is_sent,is_manual, bigcommerce_customer) VALUES ('".$value->email_address."','".$product_id."','".$key."','".$conn->real_escape_string(json_encode($formData))."','".$conn->real_escape_string($cBB['api_post'])."','".$conn->real_escape_string($cBB['api_response'])."',1,0,'".$conn->real_escape_string($customer_rec)."')";
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

function createBigBuy($formData, $productData)
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
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    $descode = json_decode($response,true);

       $data['api_response'] = $response;
    debug($data,false);

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
function getStickyOrders()
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
function getKonnectiveOrders()
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

function getSkuKonnective($product_id)
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
function getSkuSticky($record)
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

function createLog($data = '')
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
function getExistingOrCreateCustomer($formData, $country_code){
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

?>