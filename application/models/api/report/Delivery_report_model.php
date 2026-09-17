<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Api_Model.php';
class Delivery_report_model extends my_api_model{
	public function __construct(){ parent::__construct(); }
    public function isExist($id){
        return false;
    }
   
    public function read($search, $args){
        $where  = '';
        $having = '';
        
        if(isset($search['entry_no']) && !empty($search['entry_no']))
            $where .= " AND dm.entry_no LIKE '%".$search['entry_no']."%'";
    
        if(isset($search['entry_date']) && !empty($search['entry_date'])){
            $date_from = date('Y-m-d',strtotime($search['entry_date']));
            $where .=" AND dm.entry_date = '".$date_from."'";
        }
        if(isset($search['status']) && !empty($search['status']))
            $where .= " AND dt.status = '".strtoupper(trim($search['status']))." '";
        
        if(isset($search['qrcode']) && !empty($search['qrcode']))
            $where .= " AND dt.qrcode = '".trim($search['qrcode'])."' ";
    
        if(isset($search['branch_name']) && !empty($search['branch_name']))
            $where .= " AND bm.branch_name = '".trim($search['branch_name'])."' ";
        
        if(isset($search['order_no']) && !empty($search['order_no']))
            $where .= " AND obt.om_em_entry_no LIKE '%".$search['order_no']."%'";
    
        if(isset($search['order_date']) && !empty($search['order_date'])){
            $date_from = date('Y-m-d',strtotime($search['order_date']));
            $where .=" AND obt.om_em_entry_date = '".$date_from."'";
        }

        $query = "
                SELECT 
                    dm.entry_no AS entry_no,
                    DATE_FORMAT(dm.entry_date, '%d-%m-%Y') AS entry_date,
                    bm.branch_name AS branch_name,
                    dt.qrcode AS qrcode,
                    dt.apparel_name AS apparel_name,
                    dt.status AS status,
                    dt.status_notes AS status_notes,
                    ifnull(obt.om_em_entry_no,'') AS om_em_entry_no,
                    ifnull(DATE_FORMAT(obt.om_em_entry_date, '%d-%m-%Y'),'') AS om_em_entry_date
                FROM delivery_master dm
                INNER JOIN delivery_trans dt ON dt.delivery_id = dm.id
                INNER JOIN branch_master bm ON bm.branch_id = dm.branch_id
                left JOIN (
                    SELECT obt.obt_id,om.om_em_entry_no, om.om_em_entry_date 
                        FROM order_barcode_trans obt
                        INNER JOIN order_master om ON om.om_id = obt.obt_om_id
                        WHERE obt.obt_delete_status = 0
                        AND om.om_delete_status = 0
                ) AS obt ON obt.obt_id = dt.obt_id AND dt.type = 1
                WHERE dm.delete_status = 0
                  $where
                GROUP BY dm.id
                ORDER BY dm.created_at DESC
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
