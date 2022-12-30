<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions_model extends CI_Model{

  public function add($data)
  {
    $this->db->insert('user_permissions', $data);
    return $this->db->insert_id();
  }
  
  public function deleterow($user_id)
  {
    $this->db->delete('user_permissions', array('user_id' => $user_id));
    return true;
  }
  
  public function getedit($user_id)
  {
    $query = $this->db->get_where('user_permissions',array('user_id =' => $user_id));
    $results = $query->result('array');
    return $results;
  }
  public function updatedit($formData,$user_id)
  {
    $this->db->update('user_permissions', $formData, array('user_id' => $user_id));
  }
}
