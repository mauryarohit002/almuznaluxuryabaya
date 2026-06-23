<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class Pending_order_report_model extends my_model{
    public function __construct(){ parent::__construct('report', 'Pending_order_report_report'); }
   
    public function get_record(){ 
        $record     = [];
        $subsql     = '';
         
            if(isset($_GET['_entry_no']) && !empty($_GET['_entry_no'])){
                $subsql .=" AND om.om_entry_no = '".$_GET['_entry_no']."'";
                $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
                $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
            }
            if(isset($_GET['_qrcode_no']) && !empty($_GET['_qrcode_no'])){
                $subsql .=" AND obt.obt_item_code = '".$_GET['_qrcode_no']."'";
                $record['filter']['_qrcode_no']['value'] = $_GET['_qrcode_no'];
                $record['filter']['_qrcode_no']['text'] = $_GET['_qrcode_no'];
            }

            if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
                $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
                $subsql .= " AND om.om_entry_date >= '".$_entry_date_from."'";
            }
            if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
                $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
                $subsql .= " AND om.om_entry_date <= '".$_entry_date_to."'";
            }
            
            if(isset($_GET['_apparel_name']) && !empty($_GET['_apparel_name'])){
                $subsql .=" AND apparel.apparel_name = '".$_GET['_apparel_name']."'";
                $record['filter']['_apparel_name']['value'] = $_GET['_apparel_name'];
                $record['filter']['_apparel_name']['text'] = $_GET['_apparel_name'];
            }

            if(isset($_GET['_customer']) && !empty($_GET['_customer'])){
                $subsql .=" AND customer.customer_name = '".$_GET['_customer']."'";
                $record['filter']['_customer']['value'] = $_GET['_customer'];
                $record['filter']['_customer']['text'] = $_GET['_customer'];
            }

            $issue_order=$this->db->query("SELECT jim_obt_id FROM job_issue_master")->result_array();
            $arrAdd=[];
            foreach ($issue_order as $key => $value)
            { 
                array_push($arrAdd,$value['jim_obt_id']);
            }

            $not_issue_order=implode(',', $arrAdd);
        
        $query="SELECT 
                obt.obt_item_code as item_code,
                om.om_entry_no as entry_no,
                DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(customer.customer_name) as customer_name
                FROM order_barcode_trans obt
                INNER JOIN order_trans ot ON(ot.ot_id = obt.obt_ot_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE obt.obt_delete_status = 0 AND obt.obt_id NOT IN($not_issue_order)
                $subsql
                ORDER BY om.om_entry_date DESC";
        $data = $this->db->query($query)->result_array();
       
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        $record['totals']['rows']   = count($data);
        $record['data']             = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                                                'entry_no'   => $value['entry_no'],
                                                'entry_date'=> $value['entry_date'],
                                                'item_code' => $value['item_code'],
                                                'apparel_name'  =>$value['apparel_name'],
                                                'customer_name' =>$value['customer_name']
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
            $subsql .= " AND (om.om_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT om.om_entry_no as id, UPPER(om.om_entry_no) as name
                FROM order_barcode_trans obt
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                WHERE 1
                $subsql
                GROUP BY om.om_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

    public function _qrcode_no(){
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
            $subsql .= " AND (obt_item_code LIKE '%".$name."%') ";
        }
        $query="SELECT obt_item_code as id, UPPER(obt_item_code) as name
                FROM order_barcode_trans obt
                WHERE 1
                $subsql
                GROUP BY obt_item_code ASC
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

    public function _customer(){
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
            $subsql .= " AND (customer_name LIKE '%".$name."%') ";
        }
        $query="SELECT customer_name as id, UPPER(customer_name) as name
                FROM customer_master 
                WHERE customer_status =1
                $subsql
                GROUP BY customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>