<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class receipt_model extends my_model{
    public function __construct(){ parent::__construct('voucher', 'receipt'); }
    public function isExist($id){
        // $data = $this->db->query("SELECT bm_id FROM barcode_master WHERE bm_pm_id = $id AND (bm_sot_mtr + bm_it_mtr + bm_prt_mtr) > 0 LIMIT 1")->result_array();
        // if(!empty($data)) return true;

        return false;
    }
    public function isTransExist($id){
        // $data = $this->db->query("SELECT bm_id FROM barcode_master WHERE bm_pm_id = $id AND (bm_sot_mtr + bm_it_mtr + bm_prt_mtr) > 0 LIMIT 1")->result_array();
        // if(!empty($data)) return true;

        return false;
    }
    public function isOrderTransExist($id){
        return false;
    }
    public function get_list($wantCount, $per_page = 20, $offset = 0){
        $record     = [];
        $subsql     = '';
        $limit      = '';
        $ofset      = '';
        
        if(!$wantCount){
            $limit .= " LIMIT $per_page";
            $ofset .= " OFFSET $offset";
        }
        
        if(isset($_GET['_entry_no']) && !empty($_GET['_entry_no'])){
            $subsql .=" AND receipt.receipt_entry_no = ".$_GET['_entry_no'];
            $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
            $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
        }
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND receipt.receipt_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND receipt.receipt_entry_date <= '".$_entry_date_to."'";
        }
        if(isset($_GET['_customer_name']) && !empty($_GET['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_GET['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_GET['_customer_name'];
            $record['filter']['_customer_name']['text'] = $_GET['_customer_name'];
        }
        if(isset($_GET['status']) && !empty($_GET['status'])){
            $subsql .=' AND receipt.receipt_adjust_status = '.$_GET['status'];
        }
        if(isset($_GET['_total_amt_from'])){
            if($_GET['_total_amt_from'] != ''){
                $subsql .=" AND receipt.receipt_total_amt >= ".$_GET['_total_amt_from'];
            }
        }
        if(isset($_GET['_total_amt_to'])){
            if($_GET['_total_amt_to'] != ''){
                $subsql .=" AND receipt.receipt_total_amt <= ".$_GET['_total_amt_to'];
            }
        }
        $query="SELECT receipt.*,
                DATE_FORMAT(receipt.receipt_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(customer.customer_name) as customer_name
                FROM receipt_master receipt
                LEFT JOIN customer_master customer ON(customer.customer_id = receipt.receipt_customer_id)
                WHERE receipt.receipt_delete_status = 0
                AND receipt.receipt_branch_id = ".$_SESSION['user_branch_id']."
                AND receipt.receipt_fin_year = '".$_SESSION['fin_year']."'
                $subsql
                ORDER BY receipt.receipt_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['receipt_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){
        $record['receipt_entry_no'] 	= $this->get_max_entry_no(['entry_no' => 'receipt_entry_no', 'delete_status' => 'receipt_delete_status', 'fin_year' => 'receipt_fin_year']);
        $record['receipt_uuid'] 	    = $_SESSION['user_id'].''.time();
        return $record;
    }
    public function get_data_for_edit($id){
        $query="SELECT receipt.*,
                UPPER(customer.customer_name) as customer_name
                FROM receipt_master receipt
                INNER JOIN customer_master customer ON(customer.customer_id = receipt.receipt_customer_id)
                WHERE receipt.receipt_delete_status = 0
                AND receipt.receipt_id = $id";
        $data['master_data'] = $this->db->query($query)->result_array();
        if(!empty($data['master_data'])){
            $data['master_data'][0]['isExist'] = $this->isExist($id);
        }
        return $data;
    }

    public function get_data_for_print($receipt_id){
        $query="SELECT receipt.receipt_entry_no as entry_no, 
                DATE_FORMAT(receipt.receipt_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(customer.customer_name) as customer_name,
                receipt.*
                FROM receipt_master receipt
                INNER JOIN customer_master customer ON(customer.customer_id = receipt.receipt_customer_id)
                WHERE receipt.receipt_delete_status = 0
                AND receipt.receipt_fin_year = '".$_SESSION['fin_year']."'
                AND receipt.receipt_id = $receipt_id";
        // echo "<pre>"; print_r($query); exit();
        $record['master_data'] = $this->db->query($query)->result_array();
        
        $query="SELECT rot.*,
                1 as rot_checked
                FROM receipt_order_trans rot
                WHERE rot.rot_delete_status = 0
                AND rot.rot_receipt_id = $receipt_id";
        $record['order_data'] = $this->db->query($query)->result_array();
        if(!empty($record['order_data'])){
            foreach ($record['order_data'] as $key => $value) {
                $order_data   = $this->get_order($value['rot_om_id']);
                $balance_amt  = (!empty($order_data))? $order_data[0]['balance_amt'] : 0;
                $total_amt      = $value['rot_adjust_amt'] + $balance_amt;
                $balance_amt    = $total_amt - $value['rot_adjust_amt'];
                $record['order_data'][$key]['rot_total_amt']    = $total_amt;
                $record['order_data'][$key]['balance_amt']      = $balance_amt;
            }
            usort($record['order_data'], function($a, $b){
                return $b['balance_amt'] - $a['balance_amt'];
            });
        }

        // echo "<pre>"; print_r($record); exit();

        return $record;
    }
    
    public function get_transaction($receipt_id){
        $query="SELECT rot.*,
                1 as rot_checked
                FROM receipt_order_trans rot
                WHERE rot.rot_delete_status = 0
                AND rot.rot_receipt_id = $receipt_id";
        $data['order_data'] = $this->db->query($query)->result_array();
        if(!empty($data['order_data'])){
            foreach ($data['order_data'] as $key => $value) {
                $order_data   = $this->get_order($value['rot_om_id']);
                $total_amt      = $value['rot_adjust_amt'] + $order_data[0]['balance_amt'];
                $balance_amt    = $total_amt - $value['rot_adjust_amt'];
                $data['order_data'][$key]['rot_total_amt']    = $total_amt;
                $data['order_data'][$key]['balance_amt']      = $balance_amt;
            }
            usort($data['order_data'], function($a, $b){
                return $b['balance_amt'] - $a['balance_amt'];
            });
        }
        return $data;
    }
    public function get_payment_mode_data($receipt_id){
        $query="SELECT rpmt.rpmt_id,
                rpmt.rpmt_amt as rpmt_amt,
                rpmt.rpmt_payment_mode_id as rpmt_payment_mode_id,
                UPPER(payment_mode.payment_mode_name) as payment_mode_name
                FROM receipt_payment_mode_trans rpmt
                INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = rpmt.rpmt_payment_mode_id)
                WHERE rpmt.rpmt_delete_status = 0
                AND rpmt.rpmt_receipt_id = $receipt_id
                ORDER BY payment_mode.payment_mode_name ASC";
        $data = $this->db->query($query)->result_array();
        $ids  = '';
        $subsql='';
        $record=[];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record, $value);
                $ids .= empty($ids) ? $value['rpmt_payment_mode_id'] : ', '.$value['rpmt_payment_mode_id'];
            }
            $subsql .=" AND payment_mode.payment_mode_id NOT IN(".$ids.")";
        }

        $query="SELECT 0 as rpmt_id,
                0 as rpmt_amt,
                payment_mode.payment_mode_id as rpmt_payment_mode_id,
                UPPER(payment_mode.payment_mode_name) as payment_mode_name
                FROM payment_mode_master payment_mode
                WHERE payment_mode.payment_mode_status = 1
                $subsql
                ORDER BY payment_mode.payment_mode_name ASC";
        $data = $this->db->query($query)->result_array();
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record, $value);
            }
        }
        usort($record, function($a, $b){
            return $a['payment_mode_name'] > $b['payment_mode_name'];
        });
        return $record;
    }
    public function get_customer_from_order($om_id){
        $query="SELECT customer.customer_id,
                CONCAT(UPPER(customer.customer_name), ' - ', customer.customer_mobile) as customer_name
                FROM order_master om
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                WHERE om.om_delete_status = 0
                AND om.om_id = $om_id";
        return $this->db->query($query)->result_array();
    }
    public function get_order_data($customer_id, $om_id){
        $subsql = '';
        if(isset($_POST['rot_om_id']) && !empty($_POST['rot_om_id'])){
            $ids = implode(', ', $_POST['rot_om_id']);
            $subsql .= " AND om.om_id NOT IN (".$ids.")";
        }
        if($om_id > 0){
            $subsql .= " AND om.om_id = $om_id";
        }
        $query="SELECT 0 as rot_id,
                0 as rot_checked,
                om.om_id as rot_om_id,
                IF(om.om_status=0,om.om_em_entry_no,om.om_em_entry_no) as rot_entry_no,
                IF(om.om_status=0,'ESTIMATE','ORDER') as rot_type,
                IF(om.om_status=0,DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y'),DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y')) as rot_entry_date,
                (om.om_total_amt - om.om_advance_amt) as rot_total_amt,
                0 as rot_adjust_amt,
                (om.om_total_amt - (om.om_advance_amt + om.om_allocated_amt)) as balance_amt
                FROM order_master om
                WHERE om.om_delete_status = 0
                AND (om.om_total_amt - (om.om_advance_amt + om.om_allocated_amt)) > 0
                AND om.om_customer_id = $customer_id
                $subsql
                ORDER BY balance_amt DESC";
        return $this->db->query($query)->result_array();
    }
    public function get_balance_data($customer_id, $om_id){
        $subsql = '';
        if($om_id > 0){
            $subsql .= " AND om.om_id = $om_id";
        }

        $query="SELECT SUM(customer.customer_opening_amt) as amt
                FROM customer_master customer
                WHERE customer.customer_id = $customer_id
                GROUP BY customer.customer_id";
        $data = $this->db->query($query)->result_array();
        $opening_amt = empty($data) ? 0 : $data[0]['amt'];

        $query="SELECT SUM(om.om_total_amt - (om.om_advance_amt + om.om_allocated_amt)) as amt
                FROM order_master om
                WHERE om.om_delete_status = 0
                AND (om.om_total_amt - (om.om_advance_amt + om.om_allocated_amt)) > 0
                AND om.om_customer_id = $customer_id
                $subsql 
                GROUP BY om.om_customer_id";
        $data = $this->db->query($query)->result_array();
        $order_amt = empty($data) ? 0 : $data[0]['amt'];

        $closing_amt = ($opening_amt + $order_amt);
        $balance_amt = $closing_amt;
        $type 		 = TO_RECEIVE;
        if($balance_amt < 0){
            $balance_amt    = abs($balance_amt);
            $type 		    = TO_PAY;
        }
        return [
                    'opening_amt'       => $opening_amt,
                    'order_amt'         => $order_amt,
                    'balance_amt'       => $balance_amt,
                    'type'              => $type,
                ];
    }
    public function isAdjusted($receipt_id){
        $cnt = $this->db_operations->get_cnt('receipt_order_trans', ['rot_receipt_id' => $receipt_id, 'rot_delete_status' => false]);
        if(!empty($cnt)) return true;

        return false;
    }
    public function get_order($om_id){
        $query="SELECT om.*,
                (om.om_total_amt - (om.om_advance_amt + om.om_allocated_amt)) as balance_amt
                FROM order_master om
                WHERE om.om_delete_status = 0
                AND om.om_id = $om_id";
        return $this->db->query($query)->result_array();
    }

    public function _om_id(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page 	= 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page 	= $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name 	= $_GET['name'];
            $subsql .= " AND (om.om_em_entry_no LIKE '".$name."%' OR om.om_em_entry_date LIKE '".$name."%') ";
        }
        if($_SESSION['user_id'] != 1){
			$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
		}
        $query="SELECT om.om_id as id, 
                CONCAT(UPPER(om.om_em_entry_no), ' / ', DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y')) as name
                FROM order_master om
                INNER JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
                WHERE om.om_delete_status = 0
                $subsql
                GROUP BY om.om_id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

    // search_functions
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
                $subsql .= " AND (receipt.receipt_entry_no LIKE '".$name."%') ";
            }
            $query="SELECT receipt.receipt_entry_no as id, receipt.receipt_entry_no as name
                    FROM receipt_master receipt
                    WHERE receipt.receipt_delete_status = 0
                    AND receipt.receipt_fin_year = '".$_SESSION['fin_year']."'
                    $subsql
                    GROUP BY receipt.receipt_entry_no ASC
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
                $subsql .= " AND (customer.customer_name LIKE '".$name."%') ";
            }
            $query="SELECT customer.customer_name as id, UPPER(customer.customer_name) as name
                    FROM receipt_master receipt
                    INNER JOIN customer_master customer ON(customer.customer_id = receipt.receipt_customer_id)
                    WHERE receipt.receipt_delete_status = 0
                    AND receipt.receipt_fin_year = '".$_SESSION['fin_year']."'
                    $subsql
                    GROUP BY customer.customer_name ASC
                    LIMIT $limit
                    OFFSET $offset";
            // echo $query; exit();
            return $this->db->query($query)->result_array();
        }            
    // search_functions
}
?>