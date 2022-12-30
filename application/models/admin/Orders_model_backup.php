<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Orders_model extends CI_Model{
	function __construct() {
		// Set table name
		$this->table = 'offers';
		// Set orderable column fields
		//$this->column_order = array(null, 'first_name','last_name','email','gender','country','created','status');
		// Set searchable column fields
		$this->column_search = array('offer_title');
		// Set default order
		$this->order = array('offer_title' => 'asc');
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

	public function list()
  {
    $query = $this->db->get('offers');
    $results = $query->result('array');
    return $results;
  }

  public function get_where ($select, $where = '', $return_type = true, $order_by = '', $limit = '', $groupby = '')
  {
      $this->db->select ($select);
      $this->db->from ('offers');
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
  public function add($data)
  {
	  $data['created_by'] = $this->session->userdata('user_id');
    $this->db->insert('offers', $data);
    return $this->db->insert_id();
  }
  
  public function deleterow($offer_id)
  {
    $this->db->delete('offers', array('offer_id' => $offer_id));
    return true;
  }
  
  public function getedit($offer_id)
  {
    $query = $this->db->get_where('offers',array('offer_id =' => $offer_id));
    $results = $query->result('array');
    return $results;
  }
  public function updatedit($formData,$offer_id)
  {
	  $formData['updated_by'] = $this->session->userdata('user_id');
    $this->db->where('offer_id', $offer_id);
    return $this->db->update('offers', $formData);
    // return $this->db->update('offers', $formData, array('offer_id' => $offer_id));
  }

	public function getRows($postData){
		$this->_get_datatables_query($postData);
		if($postData['length'] != -1){
			$this->db->limit($postData['length'], $postData['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	/*
	 * Count all records
	 */
	public function countAll(){
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	/*
	 * Count records based on the filter params
	 * @param $_POST filter data based on the posted parameters
	 */
	public function countFiltered($postData){
		$this->_get_datatables_query($postData);
		$query = $this->db->get();
		return $query->num_rows();
	}

	/*
	 * Perform the SQL queries needed for an server-side processing requested
	 * @param $_POST filter data based on the posted parameters
	 */
	private function _get_datatables_query($postData){

		$this->db->from($this->table);

		$i = 0;
		// loop searchable columns
		foreach($this->column_search as $item){
			// if datatable send POST for search
			if($postData['search']['value']){
				// first loop
				if($i===0){
					// open bracket
					$this->db->group_start();
					$this->db->like($item, $postData['search']['value']);
				}else{
					$this->db->or_like($item, $postData['search']['value']);
				}

				// last loop
				if(count($this->column_search) - 1 == $i){
					// close bracket
					$this->db->group_end();
				}
			}
			$i++;
		}

		if(isset($postData['order'])){
			$this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
		}else if(isset($this->order)){
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
}
