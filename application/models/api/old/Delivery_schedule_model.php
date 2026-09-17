<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Api_Model.php';
class delivery_schedule_model extends my_api_model{
	public function __construct(){ parent::__construct(); }
    public function isExist($id){
        return false;
    }
   
    public function read($search, $args){ 
        $where  = '';
        $having = '';
        
         if(isset($search['order_no']) && !empty($search['order_no']))
            $where .= " AND om.entry_no LIKE '%".$search['order_no']."%'";

         if(isset($search['customer_id']) && !empty($search['customer_id']))
            $where .= " AND customer.customer_id = '".$search['customer_id']."'";

        if(isset($search['customer_name']) && !empty($search['customer_name']))
            $where .= " AND customer.customer_name LIKE '%".$search['customer_name']."%'";

        if(isset($search['customer_mobile']) && !empty($search['customer_mobile']))
            $where .= " AND customer.customer_mobile LIKE '".$search['customer_mobile']."%'";
       

        if(isset($search['date_from']) && !empty($search['date_from'])){
        	$date_from = date('Y-m-d',strtotime($search['date_from']));
            $having .=" AND (delivery_date >= '".$date_from."')";
        }else{
            $having .=" AND (delivery_date >= '".date('Y-m-d')."')";
        }

        if(isset($search['date_to']) && !empty($search['date_to'])){    
            $date_to = date('Y-m-d',strtotime($search['date_to']));
            $having .=" AND (delivery_date <= '".$date_to."')";
        }else{
            $having .=" AND (delivery_date <= '".date('Y-m-d')."')";
        } 
        
        $query="SELECT om.om_id,
				om.om_entry_no as entry_no,
				DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as entry_date,
				UPPER(customer.customer_name) as customer_name,
				customer.customer_mobile as customer_mobile,
				IF(om.om_reschedule_delivery_date != '', om.om_reschedule_delivery_date, om.om_delivery_date) as delivery_date,
				UPPER(om.om_notes) as notes
				FROM order_master om
				INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
				WHERE om.om_delete_status = 0
				$where
				HAVING 1
				$having
				ORDER BY om.om_id";
		// print_r($query);die;
        if (isset($args['wantCount']) && $args['wantCount'] == true) 
             return $this->db->query($query)->num_rows();

        if (isset($args['limit']) && !empty($args['limit']))
            $query .= " LIMIT ".(int) $args['limit'];

        if (isset($args['offset']) && !empty($args['offset']))
            $query .= " OFFSET ".(int) $args['offset'];
    
        return $this->db->query($query)->result_array();
        
    }


   
   

}
?>
