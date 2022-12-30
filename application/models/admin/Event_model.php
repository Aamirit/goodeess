<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends CI_Model
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
        $this->table_name = "event_logs";
		parent::__construct();
    }
	public function get_where ($select, $where = '', $return_type = true, $order_by = '', $limit = '', $groupby = '')
	{
		$this->db->select ($select);
		$this->db->from ('event_logs ');
		($where) ? $this->db->where ($where) : '';
		if ($groupby != '')
			$this->db->group_by ($groupby);
		if ($order_by != '')
			$this->db->order_by ($order_by);
  
		if ($limit != '')
			$this->db->limit ($limit);
  
		if ($return_type)
		{
			$result = $this->db->get ()->result ('array');
		}
		else
		{
			$result = $this->db->get ()->result ();
		}
		return $result;
	}

    public function save($data) 
	{
        $this->db->insert('event_logs', $data);
        return $this->db->insert_id();
    }
}
