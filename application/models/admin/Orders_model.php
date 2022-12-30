<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_model extends CI_Model
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
        $this->table_name = "orders";
		parent::__construct();
    }

    public function count_rows($where=array())
    {
      $this->db->select('*')->from($this->table_name);
      if(!empty($where))
      {
        $this->db->where($where);
      }
      return $this->db->get()->num_rows();
    }

    public function get_by_join($select = '' , $from_table = '' , $join_array = array() ,$where = '', $order_by_column = '', $order_by_value = '', $where_in_check = 0, $where_in_key = '', $where_in_value = '' , $or_where = '' , $limit = 0, $offset = 0, $formatting = true, $groupby = '') 
    {
          if($select != '')
          {
              $this->db->select($select, $formatting);
          }
          
          if($from_table != '')
          {
              $this->db->from($from_table);
          }
          else
          {
              $this->db->from($this->table_name);
          }
          if(count($join_array) > 0)
          {
              foreach($join_array as $key => $v)
              {
          if(isset($v['join_type']) && !empty($v['join_type']))
                    $this->db->join($v['table_name'], $v['join_on'], $v['join_type']);
          else
            $this->db->join($v['table_name'], $v['join_on']);
              }
          }
          
          if(!empty($limit))
          {
              $this->db->limit($limit, $offset);
          }
  
          if ($groupby != ''){
              $this->db->group_by ($groupby);
          }
          
          if ($order_by_column != '' && $order_by_value != '') {
              $this->db->order_by($order_by_column, $order_by_value);
          }
  
          if ($where_in_check && $where_in_key != '' && $where_in_value != '') {
              $this->db->where_in($where_in_key, $where_in_value);
          }
          
          if((is_array($where) > 0 && count($where)) || (!is_array($where) && $where != '' ) )
          {
              $this->db->where($where); 
          }
          
          if((is_array($or_where) > 0 && count($or_where)) || (!is_array($or_where) && $or_where != '' ) )
          {
              $this->db->or_where($or_where);
          }
          
          $query = $this->db->get();
          return $query->result_array();
      }

      public function get_by_join_total_rows($select = '' , $from_table = '' , $join_array = array() ,$where = '', $order_by_column = '', $order_by_value = '', $where_in_check = 0, $where_in_key = '', $where_in_value = '' , $or_where = '' , $limit = 0, $offset = 0)
      {
            if($select != '')
            {
                $this->db->select($select);
            }
    
            if($from_table != '')
            {
                $this->db->from($from_table);
            }
            else
            {
                $this->db->from($this->table_name);
            }
            if(count($join_array) > 0)
            {
                foreach($join_array as $key => $v)
                {
            if(isset($v['join_type']) && !empty($v['join_type']))
                      $this->db->join($v['table_name'], $v['join_on'], $v['join_type']);
            else
              $this->db->join($v['table_name'], $v['join_on']);
                }
            }
    
            if(!empty($limit))
            {
                $this->db->limit($limit, $offset);
            }
    
            if ($order_by_column != '' && $order_by_value != '') {
                $this->db->order_by($order_by_column, $order_by_value);
            }
    
            if ($where_in_check && $where_in_key != '' && $where_in_value != '') {
                $this->db->where_in($where_in_key, $where_in_value);
            }
    
            if((is_array($where) && count($where) > 0) || (!is_array($where) && $where != '' ) )
            {
                $this->db->where($where);
            }
    
            if((is_array($or_where) && count($or_where) > 0) || (!is_array($or_where) && $or_where != '' ) )
            {
                $this->db->or_where($or_where);
            }
    
            $query = $this->db->get();
            return $query->num_rows();
        }

        public function get_where ($select, $where = '', $return_type = true, $order_by = '', $limit = '', $groupby = '')
        {
            $this->db->select ($select);
            $this->db->from ($this->table_name);
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

        public function get_where_rows($select, $where = '', $return_type = true, $order_by = '', $limit = '', $groupby = '')
        {
            $this->db->select ($select);
            $this->db->from ($this->table_name);
            ($where) ? $this->db->where ($where) : '';
            if ($groupby != '')
                $this->db->group_by ($groupby);
            if ($order_by != '')
                $this->db->order_by ($order_by);
    
            if ($limit != '')
                $this->db->limit ($limit);
    
            if ($return_type)
            {
                $result = $this->db->get ()->num_rows ();
            }
            else
            {
                $result = $this->db->get ()->num_rows ();
            }
            return $result;
        }

}
