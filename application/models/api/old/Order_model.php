<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Api_Model.php';
class order_model extends my_api_model{
	public function __construct(){ parent::__construct(); }
    public function isExist($id){
        return false;
    }
   
    public function read($search, $args){
        $where  = '';
        $having = '';
        
        if(isset($search['customer_name']) && !empty($search['customer_name']))
            $where .= " AND customer.customer_name LIKE '%".$search['customer_name']."%'";

        if(isset($search['customer_mobile']) && !empty($search['customer_mobile']))
            $where .= " AND customer.customer_mobile LIKE '".$search['customer_mobile']."%'";
        
        if(isset($search['customer_id']) && !empty($search['customer_id']))
            $where .= " AND customer.customer_id = '".$search['customer_id']."'";

        if(isset($search['apparel_name']) && !empty($search['apparel_name']))
            $where .= " AND apparel.apparel_name LIKE '%".$search['apparel_name']."%'";
        
        if(isset($search['apparel_id']) && !empty($search['apparel_id']))
            $where .= " AND apparel.apparel_id = '".$search['apparel_id']."'";

        if(isset($search['order_no']) && !empty($search['order_no']))
            $where .= " AND om.entry_no LIKE '%".$search['order_no']."%'";

        if(isset($search['date_from']) && !empty($search['date_from'])){
            $date_from = date('Y-m-d',strtotime($search['date_from']));
            $where .=" AND om.om_entry_date >= '".$date_from."'";
        }else{
             $where .=" AND om.om_entry_date >= '".date('Y-m-d')."'";
        }

        if(isset($search['date_to']) && !empty($search['date_to'])){    
            $date_to = date('Y-m-d',strtotime($search['date_to']));
            $where .=" AND om.om_entry_date <= '".$date_to."'";
        }else{
             $where .=" AND om.om_entry_date <= '".date('Y-m-d')."'";
        }
        
        $query = "
                SELECT 
                    om.om_entry_no AS entry_no,
                    DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') AS entry_date,
                    UPPER(customer.customer_name) AS customer_name,
                    customer.customer_mobile AS customer_mobile,
                    GROUP_CONCAT(CONCAT(UPPER(apparel_summary.apparel_name), ' (', apparel_summary.qty, ')') SEPARATOR ', ') AS apparel_summary,
                    SUM(apparel_summary.qty) AS total_qty,
                    SUM(apparel_summary.total_amt) AS total_amt
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                INNER JOIN (
                    SELECT 
                        ot.ot_om_id,
                        am.apparel_name,
                        SUM(ot.ot_qty) AS qty,
                        SUM(ot.ot_total_amt) AS total_amt
                    FROM order_trans ot
                    LEFT JOIN apparel_master am ON(am.apparel_id = ot.ot_apparel_id)
                    WHERE ot.ot_delete_status = 0
                    GROUP BY ot.ot_om_id, am.apparel_name
                ) apparel_summary ON apparel_summary.ot_om_id = om.om_id
                WHERE om.om_delete_status = 0
                  $where
                GROUP BY om.om_id
                ORDER BY om.om_created_at DESC
                ";

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
