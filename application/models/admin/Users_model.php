<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model{

  public function list()
  {
    $query = $this->db->get('admin');
    $results = $query->result('array');
    return $results;
  }
  public function get_where ($select, $where = '', $return_type = true, $order_by = '', $limit = '', $groupby = '')
  {
      $this->db->select ($select);
      $this->db->from ('campaigns');
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
  public function add($data)
  {
  	unset($data['permissions']);
    $this->db->insert('admin', $data);
    return $this->db->insert_id();
  }
  
  public function deleterow($campaign_id)
  {
    $this->db->delete('campaigns', array('campaign_id' => $campaign_id));
    return true;
  }
  
  public function getedit($user_id)
  {
    $query = $this->db->get_where('admin',array('id =' => $user_id));
    $results = $query->result('array');
    return $results;
  }
  public function updatedit($formData,$user_id)
  {
  	unset($formData['permissions']);
    $this->db->update('admin', $formData, array('id' => $user_id));
  }
}
