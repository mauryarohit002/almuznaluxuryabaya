<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Api_Model.php';
class job_issue_model extends my_api_model{
	public function __construct(){ parent::__construct(); }
    public function isExist($id){
        return false;
    }
   
    public function job_assign($search, $args){
        $where  = '';
        $having = '';
        
        if(isset($search['karigar_id']) && !empty($search['karigar_id']))
            $where .= " AND karigar.karigar_id LIKE '%".$search['karigar_id']."%'";

        if(isset($search['karigar_name']) && !empty($search['karigar_name']))
            $where .= " AND karigar.karigar_name LIKE '%".$search['karigar_name']."%'";

        // if(isset($search['issue_from']) && !empty($search['issue_from'])){
        //     $issue_from = date('Y-m-d',strtotime($search['issue_from']));
        //     $where .=" AND jim.jim_entry_date >= '".$issue_from."'";
        // }else{
        //      $where .=" AND jim.jim_entry_date >= '".date('Y-m-d')."'";
        // }

        // if(isset($search['issue_to']) && !empty($search['issue_to'])){     
        //     $issue_to = date('Y-m-d',strtotime($search['issue_to']));
        //     $where .=" AND jim.jim_entry_date <= '".$issue_to."'";
        // }else{
        //      $where .=" AND jim.jim_entry_date <= '".date('Y-m-d')."'";
        // }
        
        $query="SELECT
                karigar.karigar_id,
                UPPER(karigar.karigar_name) as karigar_name,
                COUNT(jit.jit_id) AS qty
                FROM job_issue_trans jit
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN job_issue_master jim ON(jim.jim_id=jit.jit_jim_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                WHERE jit.jit_delete_status = 0 
                AND jim.jim_delete_status=0
                $where
                GROUP BY jim.jim_karigar_id
                ORDER BY karigar.karigar_name ASC"; 

        if (isset($args['wantCount']) && $args['wantCount'] == true) 
            return $this->db->query($query)->num_rows();

        if (isset($args['limit']) && !empty($args['limit']))
            $query .= " LIMIT ".(int) $args['limit'];

        if (isset($args['offset']) && !empty($args['offset']))
            $query .= " OFFSET ".(int) $args['offset'];
    
        return $this->db->query($query)->result_array();
    }

    public function read($search, $args){ 
        $where  = '';
        $having = '';
        
        if(isset($search['karigar_id']) && !empty($search['karigar_id']))
            $where .= " AND karigar.karigar_id LIKE '%".$search['karigar_id']."%'";

        if(isset($search['karigar_name']) && !empty($search['karigar_name']))
            $where .= " AND karigar.karigar_name LIKE '%".$search['karigar_name']."%'";

        if(isset($search['proces_name']) && !empty($search['proces_name']))
            $where .= " AND proces.proces_name LIKE '%".$search['proces_name']."%'";

        if(isset($search['apparel_name']) && !empty($search['apparel_name']))
            $where .= " AND apparel.apparel_name LIKE '%".$search['apparel_name']."%'";
       
        // if(isset($search['issue_from']) && !empty($search['issue_from'])){
        //     $issue_from = date('Y-m-d',strtotime($search['issue_from']));
        //     $where .=" AND jim.jim_entry_date >= '".$issue_from."'";
        // }else{
        //      $where .=" AND jim.jim_entry_date >= '".date('Y-m-d')."'";
        // }

        // if(isset($search['issue_to']) && !empty($search['issue_to'])){     
        //     $issue_to = date('Y-m-d',strtotime($search['issue_to']));
        //     $where .=" AND jim.jim_entry_date <= '".$issue_to."'";
        // }else{
        //      $where .=" AND jim.jim_entry_date <= '".date('Y-m-d')."'";
        // }
        
        $query="SELECT
                jim.jim_entry_no as issue_no,  
                DATE_FORMAT(jim.jim_entry_date, '%d-%m-%Y') as issue_date,
                UPPER(karigar.karigar_name) as karigar_name,
                UPPER(proces.proces_name) as proces_name,
                UPPER(apparel.apparel_name) as apparel_name, 
                obt.obt_item_code as barcode, 
                om.om_entry_no as order_no,
                DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as order_date,
                IF(IFNULL(jrt.jrt_jit_id, 0)=0,'ISSED','FINISHED') as status
                FROM job_issue_trans jit
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                INNER JOIN job_issue_master jim ON(jim.jim_id=jit.jit_jim_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                WHERE jit.jit_delete_status = 0 
                AND jim.jim_delete_status=0
                $where
                GROUP BY jit.jit_id
                ORDER BY jit.jit_id DESC";

        if (isset($args['wantCount']) && $args['wantCount'] == true) 
            return $this->db->query($query)->num_rows();

        if (isset($args['limit']) && !empty($args['limit']))
            $query .= " LIMIT ".(int) $args['limit'];

        if (isset($args['offset']) && !empty($args['offset']))
            $query .= " OFFSET ".(int) $args['offset'];
    
        return $this->db->query($query)->result_array();
        
    }

    public function no_job_karigar($search, $args){
        $where  = '';
        $subsql = '';
        $having = '';
        
        if(isset($search['karigar_id']) && !empty($search['karigar_id']))
            $where .= " AND karigar.karigar_id LIKE '%".$search['karigar_id']."%'";

        if(isset($search['karigar_name']) && !empty($search['karigar_name']))
            $where .= " AND karigar.karigar_name LIKE '%".$search['karigar_name']."%'";

        if(isset($search['issue_from']) && !empty($search['issue_from'])){
            $issue_from = date('Y-m-d',strtotime($search['issue_from']));
            $subsql .=" AND jim.jim_entry_date >= '".$issue_from."'";
        }else{
             $subsql .=" AND jim.jim_entry_date >= '".date('Y-m-d')."'";
        }

        if(isset($search['issue_to']) && !empty($search['issue_to'])){     
            $issue_to = date('Y-m-d',strtotime($search['issue_to']));
            $subsql .=" AND jim.jim_entry_date <= '".$issue_to."'";
        }else{
             $subsql .=" AND jim.jim_entry_date <= '".date('Y-m-d')."'";
        }
        
        $query="SELECT
                karigar.karigar_id,
                UPPER(karigar.karigar_name) as karigar_name,
                karigar.karigar_mobile
                FROM karigar_master karigar
                WHERE karigar.karigar_id NOT IN (
                        SELECT jim.jim_karigar_id
                        FROM job_issue_master jim
                        WHERE jim.jim_delete_status = 0
                        $subsql
                    )
                $where
                GROUP BY karigar.karigar_id
                ORDER BY karigar.karigar_name ASC"; 
        //print_r($query);die;        

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
