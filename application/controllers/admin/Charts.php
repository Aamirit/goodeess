<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Charts extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();
		$this->layout = 'template';
	}
	
	public function chartjs()
	{
		$data = [];
		$this->load->view('admin/charts/chartjs', $data);
	}
	public function float()
	{
		$data = [];
		$this->load->view('admin/charts/float', $data);
	}

}
