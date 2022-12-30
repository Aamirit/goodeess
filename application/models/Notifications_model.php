<?php

include_once('Abstract_model.php');

class Notifications_model extends Abstract_model
{
	/**
	* @var stirng
	* @access protected
	*/
    protected $table_name = "";
	
	/** 
	*  Model constructor
	* 
	* @access public 
	*/
    public function __construct() 
	{
        $this->table_name = "notifications";
		parent::__construct();
    }
	public function add($data)
	{
		$this->db->insert('notifications', $data);
		return $this->db->insert_id();
	}

}
