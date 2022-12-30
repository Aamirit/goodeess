<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model{

  public function list()
  {
    $query = $this->db->get('products');
    $results = $query->result('array');
    return $results;
  }

  public function add($data)
  {
    $this->db->insert('products', $data);
    return $this->db->insert_id();
  }
  
  public function deleterow($product_id)
  {
    $this->db->delete('products', array('product_id' => $product_id));
    return true;
  }
  
  public function getedit($product_id)
  {
    $query = $this->db->get_where('products',array('product_id =' => $product_id));
    $results = $query->result('array');
    return $results;
  }
  public function updatedit($formData,$product_id)
  {
    $this->db->update('products', $formData, array('product_id' => $product_id));
  }
}