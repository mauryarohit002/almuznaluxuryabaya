<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class production_report_model extends my_model{
    public function __construct(){ parent::__construct('report', 'production_report'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        $having 	= '';    
        if(isset($_REQUEST['_proces_name']) && !empty($_REQUEST['_proces_name'])){
            // $subsql .=" AND proces.proces_name = '".$_REQUEST['_proces_name']."'";
            $having .=" AND proces_name = '".$_REQUEST['_proces_name']."'";
            $record['filter']['_proces_name']['value'] = $_REQUEST['_proces_name'];
            $record['filter']['_proces_name']['text']  = $_REQUEST['_proces_name'];
        }
        if(isset($_REQUEST['_karigar_name']) && !empty($_REQUEST['_karigar_name'])){
            // $subsql .=" AND karigar.karigar_name = '".$_REQUEST['_karigar_name']."'";
             $having .=" AND karigar_name = '".$_REQUEST['_karigar_name']."'";
            $record['filter']['_karigar_name']['value'] = $_REQUEST['_karigar_name'];
            $record['filter']['_karigar_name']['text']  = $_REQUEST['_karigar_name'];
        }
        if(isset($_REQUEST['_customer_name']) && !empty($_REQUEST['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_REQUEST['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_REQUEST['_customer_name'];
            $record['filter']['_customer_name']['text']  = $_REQUEST['_customer_name'];
        }
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $subsql .=" AND jim.jim_entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
        }
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND jim.jim_entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND jim.jim_entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }
        if(isset($_REQUEST['_order_no']) && !empty($_REQUEST['_order_no'])){
            $subsql .="  AND om.om_id = '".$_REQUEST['_order_no']."'";
            $record['filter']['_order_no']['value'] = $_REQUEST['_order_no'];
            $record['filter']['_order_no']['text']  = $_REQUEST['_order_no'];
        }
        if(isset($_REQUEST['_bm_item_code']) && !empty($_REQUEST['_bm_item_code'])){
            $subsql .="  AND obt.obt_id = '".$_REQUEST['_bm_item_code']."'";
            $record['filter']['_bm_item_code']['value'] = $_REQUEST['_bm_item_code'];
            $record['filter']['_bm_item_code']['text']  = $_REQUEST['_bm_item_code'];
        }
        if(isset($_REQUEST['_order_date_from'])){
            if($_REQUEST['_order_date_from'] != ''){
                $subsql .=" AND om.om_entry_date >= '".$_REQUEST['_order_date_from']."'";
                $record['filter']['_order_date_from'] = $_REQUEST['_order_date_from'];
            }
        }
        if(isset($_REQUEST['_order_date_to'])){
            if($_REQUEST['_order_date_to'] != ''){
                $subsql .=" AND om.om_entry_date <= '".$_REQUEST['_order_date_to']."'";
                $record['filter']['_order_date_to'] = $_REQUEST['_order_date_to'];
            }
        }
        if(isset($_REQUEST['_job_status']) && !empty($_REQUEST['_job_status']) && $_REQUEST['_job_status'] != 'ALL'){
            $having .=" AND job_status = '".$_REQUEST['_job_status']."'";
            $record['filter']['_job_status']['value'] = $_REQUEST['_job_status'];
            $record['filter']['_job_status']['text']  = $_REQUEST['_job_status'];
        }  
        $query="SELECT  
                jim.jim_entry_no as entry_no,
                obt.obt_item_code,  
                DATE_FORMAT(jim.jim_entry_date, '%d-%m-%Y') as entry_date,
                IF(om.om_status=0,om.om_em_entry_no,om.om_entry_no) as order_no,
                IF(om.om_status=0,DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y'),DATE_FORMAT(om.om_entry_date, '%d-%m-%Y')) as order_date,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(customer.customer_name) as customer_name,
              
                IFNULL((SELECT upper(karigar.karigar_name) as karigar_name  
                FROM job_issue_trans jit 
                INNER JOIN job_issue_master jim ON(jit.jit_jim_id = jim.jim_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                WHERE jit.jit_obt_id=obt.obt_id AND jit.jit_delete_status=0 ORDER BY jit.jit_id DESC LIMIT 1),'') as karigar_name,

                IFNULL((SELECT upper(proces.proces_name) as proces_name  
                FROM job_issue_trans jit 
                INNER JOIN job_issue_master jim ON(jit.jit_jim_id = jim.jim_id)
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                WHERE jit.jit_obt_id=obt.obt_id AND jit.jit_delete_status=0 ORDER BY jit.jit_id DESC LIMIT 1),'') as proces_name,
   
                IFNULL((SELECT IF(IFNULL(jrt.jrt_id, 0) = 0, 'PENDING', 'COMPLETED') as job_status  
                FROM job_issue_trans jit 
                LEFT JOIN job_receive_trans jrt ON(jit.jit_id = jrt.jrt_jit_id AND jrt.jrt_delete_status=0)
                WHERE jit.jit_obt_id=obt.obt_id AND jit.jit_delete_status=0 ORDER BY jit.jit_id DESC LIMIT 1),'') as job_status, 
                jim.jim_created_at as created_at
                FROM job_issue_master jim
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                WHERE jim.jim_delete_status = 0
                AND jit.jit_delete_status = 0
                $subsql
                GROUP BY obt.obt_id
                HAVING 1 
                $having
                ORDER BY jim.jim_id DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']   = count($data);
        $record['data']             = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                'entry_no'      => (int)$value['entry_no'],
                'entry_date1'   => $value['entry_date'],
                'entry_date' 	=> (int)strtotime($value['entry_date']),
                'order_no'      => (int)$value['order_no'],
                'order_date1'   => $value['order_date'],
                'obt_item_code'  => $value['obt_item_code'],
                'order_date' 	=> (int)strtotime($value['order_date']),
                'customer_name' => $value['customer_name'],
                'proces_name'   => $value['proces_name'],
                'karigar_name'  => $value['karigar_name'],
                'apparel_name'  => $value['apparel_name'],
                'job_status'    => $value['job_status'],
            ]);
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _entry_no(){
        $subsql = '';
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (jim.jim_entry_no LIKE '".$name."%') ";
        }
        $query="SELECT jim.jim_entry_no as id, 
                UPPER(jim.jim_entry_no) as name
                FROM job_issue_master jim
                WHERE jim.jim_delete_status = 0
                $subsql
                GROUP BY jim.jim_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

    public function _order_no(){   
        $subsql = '';
        $having ='';
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $having .= " AND (name LIKE '".$name."%') ";
        }   
        $query="SELECT om.om_id as id, 
                IF(om.om_status=0,om.om_em_entry_no,om.om_entry_no) as name
                FROM job_issue_master jim
                INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                WHERE jim.jim_delete_status = 0
                AND jit.jit_delete_status = 0
                AND obt.obt_delete_status = 0
                AND om.om_delete_status = 0
                $subsql
                GROUP BY om.om_id ASC
                HAVING 1 $having
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

    public function _bm_item_code(){  
        $subsql = '';
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (obt.obt_item_code LIKE '".$name."%') ";
        }   
        $query="SELECT obt.obt_id as id, 
                obt.obt_item_code as name
                FROM job_issue_master jim
                INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                WHERE jim.jim_delete_status = 0
                AND jit.jit_delete_status = 0
                AND obt.obt_delete_status = 0
                AND om.om_delete_status = 0
                $subsql 
                GROUP BY om.om_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

    public function _customer_name(){
        $subsql = '';
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (customer.customer_name LIKE '".$name."%') ";
        }
        $query="SELECT customer.customer_name as id, 
                UPPER(customer.customer_name) as name
                FROM job_issue_master jim
                INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE jim.jim_delete_status = 0
                AND jit.jit_delete_status = 0
                AND obt.obt_delete_status = 0
                AND om.om_delete_status = 0
                $subsql 
                GROUP BY customer.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _proces_name(){
        $subsql = '';
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (proces.proces_name LIKE '".$name."%') ";
        }
        $query="SELECT proces.proces_name as id, 
                UPPER(proces.proces_name) as name
                FROM job_issue_master jim
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                WHERE jim.jim_delete_status = 0
                $subsql 
                GROUP BY proces.proces_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _karigar_name(){
        $subsql = '';
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (karigar.karigar_name LIKE '".$name."%') ";
        }
        $query="SELECT karigar.karigar_name as id, 
                UPPER(karigar.karigar_name) as name
                FROM job_issue_master jim
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                WHERE jim.jim_delete_status = 0
                $subsql 
                GROUP BY karigar.karigar_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>