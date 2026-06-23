<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class delivery_schedule_model extends my_model{
    public function __construct(){ parent::__construct('report', 'delivery_schedule'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        // echo "<pre>"; print_r($_REQUEST); exit;        
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $subsql .=" AND om.om_entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
        }
        if(isset($_REQUEST['_customer_name']) && !empty($_REQUEST['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_REQUEST['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_REQUEST['_customer_name'];
            $record['filter']['_customer_name']['text']  = $_REQUEST['_customer_name'];
        }
        if(isset($_REQUEST['_customer_mobile']) && !empty($_REQUEST['_customer_mobile'])){
            $subsql .=" AND customer.customer_mobile = '".$_REQUEST['_customer_mobile']."'";
            $record['filter']['_customer_mobile']['value'] = $_REQUEST['_customer_mobile'];
            $record['filter']['_customer_mobile']['text']  = $_REQUEST['_customer_mobile'];
        }
        if(isset($_REQUEST['_apparel_name']) && !empty($_REQUEST['_apparel_name'])){
            $explode= (is_array($_REQUEST['_apparel_name'])) ? $_REQUEST['_apparel_name'] : explode(',', $_REQUEST['_apparel_name']);
            $record['filter']['_apparel_name'] = $explode;
        }
        if(isset($_REQUEST['_trial_date_from'])){
            if($_REQUEST['_trial_date_from'] != ''){
                $subsql .=" AND om.om_trial_date >= '".$_REQUEST['_trial_date_from']."'";
                $record['filter']['_trial_date_from'] = $_REQUEST['_trial_date_from'];
            }
        }
        if(isset($_REQUEST['_trial_date_to'])){
            if($_REQUEST['_trial_date_to'] != ''){
                $subsql .=" AND om.om_trial_date <= '".$_REQUEST['_trial_date_to']."'";
                $record['filter']['_trial_date_to'] = $_REQUEST['_trial_date_to'];
            }
        }
        if(isset($_REQUEST['_delivery_date_from'])){
            if($_REQUEST['_delivery_date_from'] != ''){
                $subsql .=" AND om.om_delivery_date >= '".$_REQUEST['_delivery_date_from']."'";
                $record['filter']['_delivery_date_from'] = $_REQUEST['_delivery_date_from'];
            }
        }
        if(isset($_REQUEST['_delivery_date_to'])){
            if($_REQUEST['_delivery_date_to'] != ''){
                $subsql .=" AND om.om_delivery_date <= '".$_REQUEST['_delivery_date_to']."'";
                $record['filter']['_delivery_date_to'] = $_REQUEST['_delivery_date_to'];
            }
        }
        
        $query="SELECT om.om_id,
                om.om_entry_no as entry_no,
                DATE_FORMAT(om.om_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(customer.customer_name) as customer_name,
                customer.customer_mobile as customer_mobile,
                DATE_FORMAT(om.om_trial_date, '%d-%m-%Y') as trial_date,
                DATE_FORMAT(om.om_delivery_date, '%d-%m-%Y') as delivery_date,
                '' as apparel_name,
                UPPER(om.om_notes) as notes
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0
                $subsql
                ORDER BY om.om_delivery_date DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        $rows           = 0;
        $record['data'] = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $apparel_data = $this->get_apparel($value['om_id']);
                if(!empty($apparel_data)){
                    array_push($record['data'], [
                                                    'entry_no'          => (int)$value['entry_no'],
                                                    'customer_name'     => $value['customer_name'],
                                                    'customer_mobile' 	=> (int)$value['customer_mobile'],
                                                    'entry_date' 	    => $value['entry_date'],
                                                    'trial_date' 	    => $value['trial_date'],
                                                    'delivery_date' 	=> $value['delivery_date'],
                                                    'apparel_name' 	    => '',
                                                    'notes' 	        => $value['notes'],
                                                    'apparel_data'      => $apparel_data
                                                ]);
                }
            }
        }
        $record['totals']['rows'] = count($record['data']);
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function get_apparel($om_id){
        $subsql = '';
        if(isset($_REQUEST['_apparel_name']) && !empty($_REQUEST['_apparel_name'])){
            $explode= (is_array($_REQUEST['_apparel_name'])) ? $_REQUEST['_apparel_name'] : explode(',', $_REQUEST['_apparel_name']);
            $subsql .= " AND apparel.apparel_name IN ('".implode("', '", $explode)."')";
            $record['filter']['_apparel_name'] = $explode;
        }
        if(isset($_REQUEST['_apparel_group']) && !empty($_REQUEST['_apparel_group'])){
            $subsql .=" AND apparel.apparel_group = '".$_REQUEST['_apparel_group']."'";
            $record['filter']['_apparel_group']['value'] = $_REQUEST['_apparel_group'];
            $record['filter']['_apparel_group']['text']  = $_REQUEST['_apparel_group'];
        }
        $query="SELECT UPPER(apparel.apparel_name) as apparel_name, SUM(ot.ot_qty) as qty
                FROM order_trans ot
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                WHERE ot.ot_delete_status = 0
                AND ot.ot_om_id = $om_id
                $subsql
                GROUP BY apparel.apparel_id
                ORDER BY apparel.apparel_name ASC";
        $data = $this->db->query($query)->result_array();
        if(empty($data)) return '';
        $table = '';
        foreach ($data as $key => $value) {
            $table .= '<br/><span>* '.$value['apparel_name'].' '.$value['qty'].' PCS</span>';
        }
        return $table;
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
                FROM order_master om
                WHERE om.om_delete_status = 0
                $subsql
                GROUP BY om.om_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _customer_name(){
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
            $subsql .= " AND (customer.customer_name LIKE '%".$name."%') ";
        }
        $query="SELECT customer.customer_name as id, UPPER(customer.customer_name) as name
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0
                $subsql
                GROUP BY customer.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _customer_mobile(){
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
            $subsql .= " AND (customer.customer_mobile LIKE '%".$name."%') ";
        }
        $query="SELECT customer.customer_mobile as id, UPPER(customer.customer_mobile) as name
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0
                $subsql
                GROUP BY customer.customer_mobile ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _apparel_group(){
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
            $subsql .= " AND (apparel.apparel_group LIKE '%".$name."%') ";
        }
        $query="SELECT apparel.apparel_group as id, UPPER(apparel.apparel_group) as name
                FROM apparel_master apparel
                WHERE 1
                $subsql
                GROUP BY apparel.apparel_group ASC
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
                FROM order_trans ot
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                WHERE ot.ot_delete_status = 0
                $subsql
                GROUP BY apparel.apparel_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>