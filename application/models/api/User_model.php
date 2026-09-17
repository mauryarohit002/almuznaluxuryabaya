<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Api_Model.php';
class user_model extends my_api_model{
	public function __construct(){ parent::__construct(); } 
	public function get_user_by_mobile($mobile_no){
        $query="SELECT 
                customer.customer_id as id,
                customer.customer_status as is_active,
                customer.customer_app_type_id as type_id,
                IFNULL(app_type.app_type_name, '') as type_name,
                (4 - IFNULL(session.cnt, 0)) as cnt
                FROM customer_master customer
                LEFT JOIN app_type_master app_type ON(app_type.app_type_id = customer.customer_app_type_id)
                LEFT JOIN (
                    SELECT 
                    usm.usm_user_id as customer_id,
                    COUNT(usm.usm_user_id) as cnt
                    FROM user_session_master usm
                    WHERE created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
                    GROUP BY usm.usm_user_id
                ) as session ON(session.customer_id = customer.customer_id)
                WHERE customer.customer_mobile = '$mobile_no'";
        return $this->db->query($query)->result_array();
    }
    public function get_user($user_name, $branch_id = NULL){
        $query="SELECT *
                FROM user_master 
                WHERE user_name = '$user_name'";
        if($branch_id) {
            $query .= " AND user_branch_id = '$branch_id'";
        }
        return $this->db->query($query)->result_array();
    }
    public function get_session_by_date($date){
        $query="SELECT usm.usm_id,
                DATE_FORMAT(usm.created_at, '%Y-%m-%d') as created_at
                FROM user_session_master usm
                WHERE 1
                HAVING created_at <= '$date'";
        return $this->db->query($query)->result_array();
    }
    public function get_session_by_user($user_id){ 
        $query="SELECT usm.usm_id
                FROM user_session_master usm
                WHERE usm.usm_user_id = '$user_id'";
        return $this->db->query($query)->result_array();
    }
}
?>
