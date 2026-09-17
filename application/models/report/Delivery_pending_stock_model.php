<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class delivery_pending_stock_model extends my_model{
    public function __construct(){ parent::__construct('report', 'delivery_pending_stock'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        $having 	= '';     
        // pre($_REQUEST);exit();
        if(isset($_REQUEST['_qrcode']) && !empty($_REQUEST['_qrcode'])){
            $subsql .=" AND dt.qrcode = '".$_REQUEST['_qrcode']."'";
            $record['filter']['_qrcode']['value'] = $_REQUEST['_qrcode'];
            $record['filter']['_qrcode']['text']  = $_REQUEST['_qrcode'];
        }
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $inList = implode(',', $_REQUEST['_entry_no']);
            // print_r($inList);exit();
            $subsql .= " AND dm.entry_no IN ($inList)";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  =  $_REQUEST['_entry_no'];
        }
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND dm.entry_date >= '".$_REQUEST['_entry_date_from']."'";

                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND dm.entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }
        if(isset($_REQUEST['_om_entry_no']) && !empty($_REQUEST['_om_entry_no'])){
            $inList = implode(',', $_REQUEST['_om_entry_no']);
            // print_r($inList);exit();
            $subsql .= " AND om.om_em_entry_no IN ($inList)";
            $record['filter']['_om_entry_no']['value'] = $_REQUEST['_om_entry_no'];
            $record['filter']['_om_entry_no']['text']  =  $_REQUEST['_om_entry_no'];
        }
        if(isset($_REQUEST['_om_entry_date_from'])){
            if($_REQUEST['_om_entry_date_from'] != ''){
                $subsql .=" AND om.om_em_entry_date >= '".$_REQUEST['_om_entry_date_from']."'";
                $record['filter']['_om_entry_date_from'] = $_REQUEST['_om_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_om_entry_date_to'])){
            if($_REQUEST['_om_entry_date_to'] != ''){
                $subsql .=" AND om.om_em_entry_date <= '".$_REQUEST['_om_entry_date_to']."'";
                $record['filter']['_om_entry_date_to'] = $_REQUEST['_om_entry_date_to'];
            }
        }
        if(isset($_REQUEST['_branch']) && !empty($_REQUEST['_branch'])){
            $subsql .= " AND branch.branch_name = '".$_REQUEST['_branch']."'";
            $record['filter']['_branch']['value'] = $_REQUEST['_branch'];
            $record['filter']['_branch']['text']  = $_REQUEST['_branch'];
        }
        if($_SESSION['branch_default'] != 1){ 
            $subsql .= " AND dm.branch_id = '".$_SESSION['user_branch_id']."'";
        }
       
        $query = "
			SELECT
			    dm.entry_no AS entry_no,
			    DATE_FORMAT(dm.entry_date, '%d-%m-%Y') AS entry_date,
                IFNULL(om.om_em_entry_no, '') AS om_em_entry_no,
                IFNULL(DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y'), '') AS om_em_entry_date,
			    dm.total_qty AS total_qty,
                branch.branch_name AS branch_name,
                dt.qrcode,
                dt.apparel_name,
                dt.status,
                dt.status_notes,
                dt.created_by,
                DATE_FORMAT(dt.created_at, '%d-%m-%Y %h:%i %p') AS created_at
                FROM delivery_master dm
				INNER JOIN delivery_trans dt ON dt.delivery_id = dm.id
                INNER JOIN branch_master branch ON(branch.branch_id = dm.branch_id) 
				left JOIN order_barcode_trans obt
				    ON obt.obt_id = dt.obt_id
				    AND obt.obt_delete_status = 0
                left JOIN order_master om
                    ON om.om_id = obt.obt_om_id
				WHERE dm.delete_status = 0
				$subsql
				GROUP BY dt.id
				ORDER BY dm.entry_no DESC
				";
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
                'om_em_entry_no'=> (int)$value['om_em_entry_no'],
                'om_em_entry_date1'=> $value['om_em_entry_date'],
                'om_em_entry_date' => (int)strtotime($value['om_em_entry_date']),
                'branch_name'   => $value['branch_name'],
                'qrcode'        => $value['qrcode'],
                'apparel_name'  => $value['apparel_name'],
                'status'        => $value['status'],
                'status_notes'  => $value['status_notes']
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
            $subsql .= " AND (dm.entry_no LIKE '".$name."%') ";
        }
        $query="SELECT dm.entry_no as id, 
                UPPER(dm.entry_no) as name
                FROM delivery_master dm
                WHERE dm.delete_status = 0
                $subsql
                GROUP BY dm.entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _qrcode(){
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
            $subsql .= " AND (dt.qrcode LIKE '".$name."%') ";
        }
        $query="SELECT dt.qrcode as id, 
                UPPER(dt.qrcode) as name
                FROM delivery_master dm
                INNER JOIN delivery_trans dt ON dt.delivery_id= dm.id
                WHERE dm.delete_status = 0
                $subsql
                GROUP BY dt.qrcode ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _om_entry_no(){
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
            $subsql .= " AND (om.om_em_entry_no LIKE '".$name."%') ";
        }
        $query="SELECT om.om_em_entry_no as id, 
                UPPER(om.om_em_entry_no) as name
                FROM order_master om
                WHERE om.om_delete_status = 0
                $subsql
                GROUP BY om.om_em_entry_no ASC
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
    public function _branch_name(){
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
            $subsql .= " AND (branch.branch_name = '".$name."') ";
        }
        $query="SELECT branch.branch_name as id, UPPER(branch.branch_name) as name
                FROM branch_master branch 
                WHERE branch.branch_status = 1
                $subsql
                GROUP BY id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
   
}
?>