<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model{

  public function check($select, $where='', $return_type=true, $order_by='', $limit='', $group_by='')
  {
    $this->db->select($select);
    $this->db->from('admin');
    if($where != ''){
      $this->db->where($where);
    }
    if($order_by != ''){ 
      $this->db->order_by($order_by);
    }
    if($limit != ''){
      $this->db->limit($limit);
    }
    if($group_by != ''){
      $this->db->group_by($group_by);
    }
    if($return_type){
      $result = $this->db->get()->result('array');
    }else {
      $result = $this->db->get()->result();
    }
    return $result;
  }

}

?>