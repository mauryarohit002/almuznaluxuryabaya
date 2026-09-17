<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Api_Model.php';
class Delivery_model extends my_api_model{
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
        
        $query = "
                SELECT dm.id AS id,
                    dm.entry_no AS entry_no,
                    DATE_FORMAT(dm.entry_date, '%d-%m-%Y') AS entry_date,
                    total_qty,
                    bm.branch_name AS branch_name
                FROM delivery_master dm
                INNER JOIN branch_master bm ON bm.branch_id = dm.branch_id
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
    public function get_entry_no($table, $field, $branch_id, $fin_year){
        $query = "SELECT entry_no AS max_entry_no
                  FROM $table
                  WHERE delete_status = 0 
                  AND branch_id = $branch_id 
                  AND fin_year = '$fin_year' 
                  order by id DESC LIMIT 1";
        $result = $this->db->query($query)->row_array();
        $max_entry_no = isset($result['max_entry_no']) ? (int)$result['max_entry_no'] : 0;
        return ($max_entry_no + 1);
    }
    
    public function get_details($id){      
        $query="SELECT    dm.id AS id,
                    dm.entry_no AS entry_no,
                    DATE_FORMAT(dm.entry_date, '%d-%m-%Y') AS entry_date,
                    total_qty,
                    bm.branch_name AS branch_name
                FROM delivery_master dm
                INNER JOIN branch_master bm ON bm.branch_id = dm.branch_id
                WHERE dm.delete_status = 0
                  AND dm.id = $id";
        $data= $this->db->query($query)->result_array();
        if(!empty($data)){ 
            $data[0]['isExist'] = $this->isExist($id);
            $data[0]['trans_data'] = $this->get_transaction($id);
        }
        return $data;
    }
    public function get_transaction($delivery_id){      
        $query="SELECT 
                dt.id,
                dt.type,
                dt.qrcode,
                dt.obt_id,
                dt.apparel_name,
                dt.status,
                dt.status_notes,
                dt.created_by,
                DATE_FORMAT(dt.created_at, '%d-%m-%Y %h:%i %p') AS created_at
                FROM delivery_trans dt
                WHERE dt.delivery_id = $delivery_id";
        $data = $this->db->query($query)->result_array();
        if(!empty($data)){ 
            foreach ($data as $key => $value) { 
                $data[$key]['isExist'] = $this->isExist($value['id'],true);
            }
        }
        return $data;
    }
   
   

}
?>
