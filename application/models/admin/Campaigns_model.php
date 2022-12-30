<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Campaigns_model extends CI_Model{

  public function list()
  {
    $query = $this->db->get('campaigns');
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
	  $data['created_by'] = $this->session->userdata('user_id');
    $this->db->insert('campaigns', $data);
    return $this->db->insert_id();
  }
  
  public function deleterow($campaign_id)
  {
    $this->db->delete('campaigns', array('campaign_id' => $campaign_id));
    return true;
  }
  
  public function getedit($campaign_id)
  {
    $query = $this->db->get_where('campaigns',array('campaign_id =' => $campaign_id));
    $results = $query->result('array');
    return $results;
  }
  public function updatedit($formData,$campaign_id)
  {
	  $formData['updated_by'] = $this->session->userdata('user_id');
    $this->db->update('campaigns', $formData, array('campaign_id' => $campaign_id));
  }
}
