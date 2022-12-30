<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Widgets extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		$this->layout = 'template';
	}
	
	public function index()
	{
		$data = [];
		$this->load->view('admin/widgets/widgets', $data);
	}

}
