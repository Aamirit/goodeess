<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

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
	}
	public function index()
    {
		$data = [];
        $where = "campaigns.campaign_id > 0";
        $campaigns = $this->campaigns->get_where('*', $where, true, '', '', '');
        if(!empty($campaigns)){
            $data['campaigns'] = $campaigns;
        }
		$this->load->view('admin/orders/orders', $data);
    }

	public function orders_list()
	{
		$this->layout = '';
		$like = [];
		$result_array = [];
		
		$orderByColumnIndex = $_POST['order'][0]['column'];
		$orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
		$orderType = $_POST['order'][0]['dir'];
		$offset = $this->input->post('start');
		$limit = $this->input->post('length');
		$draw = $this->input->post('draw');
		$search = $_POST['search']['value'];
		$status_filter = $this->input->post('status_filter');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');

		$where = "orders.id > 0";
		$result_count = $this->orders->count_rows($where);

		if (isset($search) && $search != '') {
			$where .= " AND (orders.first_name  LIKE CONCAT('%','" . $search . "' ,'%'))";
		}

		if(isset($status_filter) && $status_filter != ''){
			$where .= " AND orders.is_successd ='".$status_filter."'";
		}

		if((isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
			$from_date = $from_date.' 00:00:00';
			$to_date = $to_date.' 23:00:00';
			$where .= " AND orders.created_at BETWEEN '".$from_date."' AND '".$to_date."'";
	  }

		$joins = array(
			'0' => array('table_name' => 'customers_bigbuy customers_bigbuy',
					'join_on' => 'customers_bigbuy.order_id = orders.order_id',
					'join_type' => 'left'
			)
		);
		$from_table = "orders orders";
		$select_from_table = 'orders.*,customers_bigbuy.customer_data,customers_bigbuy.api_response_bigbuy';
		$result_data = $this->orders->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn,
		$orderType, '', '', '', '', $limit, $offset , '' ,'');
		// print_r($result_data);
		// exit;
		$result_count_rows = $this->orders->get_by_join_total_rows('*', $from_table, $joins, $where, '', '', '', '', '', '', '', '');

		if (isset($result_data)) {
			foreach ($result_data as $item) {
					$is_successd = $item['is_successd'];
					if($is_successd == 1){
						$status_info = ' <p>Successfull</p>';
					}else {
						$status_info = 'Failed';
					}
					$single_field['name'] = $item['first_name']." ".$item['last_name'];
					$single_field['email'] =  empty($item['email'])?"N/A":$item['email'];
					$single_field['order_id'] = empty($item['order_id'])?"N/A":$item['order_id'];
					if(!empty($item['import_order_response'])){
						$order_response = json_decode($item['import_order_response'], true);
						if(!empty($order_response['orderTotal'])){
							$orderTotal = $order_response['orderTotal'];
						}else {
							$orderTotal = "N/A";
						}
					}else {
						$orderTotal = "N/A";
					}
					$single_field['total'] = $orderTotal;
					$single_field['status'] = $status_info;
					if(empty($item['api_response_bigbuy'])){
						
						$single_field['action'] = '<a class="view_order" href="javascript:void(0)" rel="'.$item['id'].'">View Order</a>';
					}else{
						$single_field['action'] = '<a class="view_error btn btn-danger" href="javascript:void(0)" data="'.$item['api_response_bigbuy'].'" rel="'.$item['id'].'">View Error</a>&nbsp<a class="resend_order btn btn-success" href="javascript:void(0)" data="'.$item['order_id'].'" rel="'.$item['id'].'">Resend Order</a>';
					}

					$result_array[] = $single_field;
			}
			$data['response'] = true;
			$data['draw'] = $draw;
			$data['recordsTotal'] = $result_count;
			$data['recordsFiltered'] = $result_count_rows;
			$data['data'] = $result_array;
	} else {
			$data['draw'] = $draw;
			$data['recordsTotal'] = 0;
			$data['recordsFiltered'] = 0;
			$data['data'] = '';
	}
		echo json_encode($data);
	}

	public function view_order($id=0){
		$this->layout = '';
		$data = array();
		$where = "orders.id='".$id."'";
		$order = $this->orders->get_where('*', $where, true, '', '', '');

		if(!empty($order)){
			$order_info = $order[0];
			$data['order_info'] = $order_info;

			$is_successd = $order_info['is_successd'];
			if($is_successd == 1){
				$status_info = 'Order Details <small class="text-success">(Successful)</small>';
			}else {
				if(!empty($order_info['error_message'])){
					$status_info = 'Order Details <small class="text-danger">(Failed due to '.$order_info['error_message'].')</small>';
				}else {
					$status_info = 'Order Details <small class="text-danger">(Failed)</small>';
				}
			}
			$data['status_info'] = $status_info;

			$this->load->view('admin/orders/order_details', $data);
		}
	}
}
