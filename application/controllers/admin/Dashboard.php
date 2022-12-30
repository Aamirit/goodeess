<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		if(empty($_SESSION['email'])) {
			redirect(base_url('login'));
		}
		$this->layout = 'template';
		$this->load->model('admin/orders_model', 'orders');
	}
	
	public function index()
	{
		$data = [];
		$sql = "SELECT count(orders.product_id) as TotalQuantity,orders.product_id, offers.offer_title FROM orders LEFT JOIN offers
    ON orders.offer_id = offers.offer_id GROUP BY orders.product_id ORDER BY TotalQuantity desc limit 5";
		$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
		$result = $this->db->query($sql);
		$data['result'] = $result->result();

		$where = "orders.id > 0 AND created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW()";
		$where1 = "orders.id > 0 AND created_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()";
		$data['today_orders'] = $this->orders->get_where_rows('*', $where, true, '', '', '');
		$data['last_week_orders'] = $this->orders->get_where_rows('*', $where1, true, '', '', '');
		$this->load->view('admin/dashboard/index', $data);
	
	}

}
