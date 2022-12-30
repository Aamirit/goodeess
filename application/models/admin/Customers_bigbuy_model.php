<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_bigbuy_model extends CI_Model
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
        $this->table_name = "customers_bigbuy";
		parent::__construct();
    }
	public function get_where ($select, $where = '', $return_type = true, $order_by = '', $limit = '', $groupby = '')
	{
		$this->db->select ($select);
		$this->db->from ('customers_bigbuy');
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
        $this->db->insert('customers_bigbuy', $data);
        return $this->db->insert_id();
    }

	public function update_by_where($data, $where) 
	{
        $this->db->where($where);
        return $this->db->update('customers_bigbuy', $data);
    }

}