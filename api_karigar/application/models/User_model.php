<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class user_model extends my_model{
	public function __construct(){ parent::__construct(); } 
	
   public function get_user($mobile_no){
        $query="SELECT *
                FROM karigar_master 
                WHERE karigar_mobile = '$mobile_no'";
        return $this->db->query($query)->result_array();
    }
    public function get_session_by_date($date){
        $query="SELECT ksm.ksm_id,
                DATE_FORMAT(ksm.created_at, '%Y-%m-%d') as created_at
                FROM karigar_session_master ksm
                WHERE 1
                HAVING created_at <= '$date'";
        return $this->db->query($query)->result_array();
    }
    public function get_session_by_user($karigar_id){ 
        $query="SELECT ksm.ksm_id
                FROM karigar_session_master ksm
                WHERE ksm.ksm_karigar_id = '$karigar_id'";
        return $this->db->query($query)->result_array();
    }
    

}
?>
