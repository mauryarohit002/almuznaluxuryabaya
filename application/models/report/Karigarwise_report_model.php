<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class Karigarwise_report_model extends my_model{
    public function __construct(){ parent::__construct('report', 'karigarwise_report'); }
     public function isExist($id){
        $data = $this->db->query("SELECT jim_id FROM job_issue_master WHERE jim_id = $id AND jim_jrm_id != 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;

        return false;
    }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
            if(isset($_GET['_entry_no']) && !empty($_GET['_entry_no'])){
                $subsql .=" AND jim.jim_entry_no = '".$_GET['_entry_no']."'";
                $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
                $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
            }
            if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
                $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
                $subsql .= " AND jim.jim_entry_date >= '".$_entry_date_from."'";
            }
            if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
                $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
                $subsql .= " AND jim.jim_entry_date <= '".$_entry_date_to."'";
            }
            if(isset($_GET['_order_no']) && !empty($_GET['_order_no'])){
                $subsql .=" AND om.om_entry_no = '".$_GET['_order_no']."'";
                $record['filter']['_order_no']['value'] = $_GET['_order_no'];
                $record['filter']['_order_no']['text'] = $_GET['_order_no'];
            }
            if(isset($_GET['_process_name']) && !empty($_GET['_process_name'])){
                $subsql .=" AND proces.proces_name = '".$_GET['_process_name']."'";
                $record['filter']['_process_name']['value'] = $_GET['_process_name'];
                $record['filter']['_process_name']['text'] = $_GET['_process_name'];
            }
             if(isset($_GET['_karigar']) && !empty($_GET['_karigar'])){
                $subsql .=" AND karigar.karigar_name = '".$_GET['_karigar']."'";
                $record['filter']['_karigar']['value'] = $_GET['_karigar'];
                $record['filter']['_karigar']['text'] = $_GET['_karigar'];
            }
            if(isset($_GET['_apparel_name']) && !empty($_GET['_apparel_name'])){
                $subsql .=" AND apparel.apparel_name = '".$_GET['_apparel_name']."'";
                $record['filter']['_apparel_name']['value'] = $_GET['_apparel_name'];
                $record['filter']['_apparel_name']['text'] = $_GET['_apparel_name'];
            }
        
        $query="SELECT jim.*,
                DATE_FORMAT(jim.jim_entry_date, '%d-%m-%Y') as entry_date,
                obt.obt_item_code as item_code,
                om.om_entry_no as order_no,
                DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as order_date,
                UPPER(proces.proces_name) as proces_name,
                UPPER(karigar.karigar_name) as karigar_name,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(customer.customer_name) as customer_name
                FROM job_issue_master jim
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jim.jim_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE jim.jim_delete_status = 0
                $subsql
                ORDER BY jim.jim_entry_date DESC";
        $data = $this->db->query($query)->result_array();
       
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $data[$key]['isExist'] = $this->isExist($value['jim_id']);
                    if (!empty($data[$key]['isExist']))
                    {
                        $data[$key]['status']="JOB RECIEVE";
                    }else{
                        $data[$key]['status']="JOB ISSUE";
                    }
                }
            }
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        $record['totals']['rows']   = count($data);
        $record['data']             = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                                                'entry_no'   => $value['jim_entry_no'],
                                                'entry_date'=> $value['entry_date'],
                                                'order_no' 	=> $value['order_no'],
                                                'order_date'=> $value['order_date'],
                                                'item_code' => $value['item_code'],
                                                'apparel_name' 	=>$value['apparel_name'],
                                                'proces_name'	=>$value['proces_name'],
                                                'karigar_name' 	=>$value['karigar_name'],
                                                'customer_name' =>$value['customer_name'],
                                                'status'        =>$value['status']
                                               
                                            ]);

            }
        }
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _entry_no(){
        $subsql = "";
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
            $subsql .= " AND (jim_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT jim_entry_no as id, UPPER(jim_entry_no) as name
                FROM job_issue_master 
                WHERE 1
                $subsql
                GROUP BY jim_id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _order_no(){
        $subsql = "";
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
            $subsql .= " AND (om.om_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT om.om_entry_no as id, UPPER(om.om_entry_no) as name
                FROM order_barcode_trans obt
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN job_issue_master jim ON(jim.jim_obt_id = obt.obt_id)
                WHERE 1
                $subsql
                GROUP BY om.om_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _apparel_name(){
        $subsql = "";
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
            $subsql .= " AND (apparel.apparel_name LIKE '%".$name."%') ";
        }
        $query="SELECT apparel.apparel_name as id, UPPER(apparel.apparel_name) as name
                FROM order_barcode_trans obt
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                WHERE obt.obt_delete_status = 0
                $subsql
                GROUP BY apparel.apparel_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _process_name(){
        $subsql = "";
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
            $subsql .= " AND (proces_name LIKE '%".$name."%') ";
        }
        $query="SELECT proces_name as id, UPPER(proces_name) as name
                FROM proces_master 
                WHERE proces_status =1
                $subsql
                GROUP BY proces_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _karigar(){
        $subsql = "";
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
            $subsql .= " AND (karigar_name LIKE '%".$name."%') ";
        }
        $query="SELECT karigar_name as id, UPPER(karigar_name) as name
                FROM karigar_master 
                WHERE karigar_status =1
                $subsql
                GROUP BY karigar_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>