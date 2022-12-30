<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CrmSetting_model extends CI_Model{

    public function setting_crm()
    {
        $query = $this->db->get('settings');
        $results = $query->result('array');
        return $results;
    }
    
    public function add($data)
    {
        $this->db->where('setting_id', $data['setting_id']);
        $this->db->update('settings', $data);
    }

}