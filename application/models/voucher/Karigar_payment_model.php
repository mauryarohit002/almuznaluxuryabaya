<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class karigar_payment_model extends my_model{
    public function __construct(){ parent::__construct('voucher', 'karigar_payment'); }
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
            $subsql .=" AND karigar_payment.karigar_payment_entry_no = ".$_GET['_entry_no'];
            $record['filters']['_entry_no']['text'] = $_GET['_entry_no'];
            $record['filters']['_entry_no']['value'] = $_GET['_entry_no'];
        }
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND karigar_payment.karigar_payment_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND karigar_payment.karigar_payment_entry_date <= '".$_entry_date_to."'";
        }
        if(isset($_GET['_supplier_name']) && !empty($_GET['_supplier_name'])){
            $subsql .=" AND supplier.supplier_name = '".$_GET['_supplier_name']."'";
            $record['filters']['_supplier_name']['value'] = $_GET['_supplier_name'];
            $record['filters']['_supplier_name']['text'] = $_GET['_supplier_name'];
        }
        if(isset($_GET['status']) && !empty($_GET['status'])){
            $subsql .=' AND karigar_payment.payment_adjust_status = '.$_GET['status'];
        }
        if(isset($_GET['_total_amt_from'])){
            if($_GET['_total_amt_from'] != ''){
                $subsql .=" AND karigar_payment.payment_total_amt >= ".$_GET['_total_amt_from'];
            }
        }
        if(isset($_GET['_total_amt_to'])){
            if($_GET['_total_amt_to'] != ''){
                $subsql .=" AND karigar_payment.payment_total_amt <= ".$_GET['_total_amt_to'];
            }
        }
        $query="SELECT karigar_payment.*,
                DATE_FORMAT(karigar_payment.karigar_payment_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(karigar.karigar_name) as karigar_name
                FROM karigar_payment_master karigar_payment 
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = karigar_payment.karigar_payment_karigar_id)
                WHERE karigar_payment.karigar_payment_delete_status = 0
                AND karigar_payment.karigar_payment_fin_year = '".$_SESSION['fin_year']."'
                 AND karigar_payment.karigar_payment_branch_id = '".$_SESSION['user_branch_id']."'
                $subsql
                ORDER BY karigar_payment.karigar_payment_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['karigar_payment_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){   
        $record['karigar_payment_entry_no'] 	= $this->get_max_entry_no(['entry_no' => 'karigar_payment_entry_no', 'delete_status' => 'karigar_payment_delete_status', 'fin_year' => 'karigar_payment_fin_year','branch_id' => 'karigar_payment_branch_id']);
        $record['karigar_payment_uuid'] 	    = $_SESSION['user_id'].''.time();
        return $record;
    }
    public function get_data_for_edit($id){  
        $query="SELECT karigar_payment.*,
                UPPER(karigar.karigar_name) as karigar_name
                FROM karigar_payment_master karigar_payment
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = karigar_payment.karigar_payment_karigar_id)
                WHERE karigar_payment.karigar_payment_delete_status = 0
                AND karigar_payment.karigar_payment_id = $id";
        $data['master_data'] = $this->db->query($query)->result_array();
        if(!empty($data['master_data'])){
            $data['master_data'][0]['isExist'] = $this->isExist($id);
        }
        return $data;
    }
    public function get_transaction($payment_id){
        $query="SELECT pht.*,
                1 as pht_checked
                FROM payment_hisab_trans pht
                WHERE pht.pht_delete_status = 0
                AND pht.pht_karigar_payment_id = $payment_id";
        $data['hisab_data'] = $this->db->query($query)->result_array();
        if(!empty($data['hisab_data'])){
            foreach ($data['hisab_data'] as $key => $value) {
                $hisab_data  = $this->get_hisab($value['pht_hm_id']);
                $total_amt      = $value['pht_adjust_amt'] + $hisab_data[0]['balance_amt'];
                $balance_amt    = $total_amt - $value['pht_adjust_amt'];
                $data['hisab_data'][$key]['pht_total_amt']    = $total_amt;
                $data['hisab_data'][$key]['balance_amt']      = $balance_amt;
            }
            usort($data['hisab_data'], function($a, $b){
                return $b['balance_amt'] - $a['balance_amt'];
            });
        }
        // echo "<pre>"; print_r($data);die;
        return $data;
    }
    public function get_payment_mode_data($payment_id){
        $query="SELECT kpmt.kpmt_id,
                kpmt.kpmt_amt as kpmt_amt,
                kpmt.kpmt_payment_mode_id as kpmt_payment_mode_id,
                UPPER(payment_mode.payment_mode_name) as payment_mode_name
                FROM karigar_payment_mode_trans kpmt
                INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = kpmt.kpmt_payment_mode_id)
                WHERE kpmt.kpmt_delete_status = 0
                AND kpmt.kpmt_karigar_payment_id = $payment_id
                ORDER BY payment_mode.payment_mode_name ASC";
        $data = $this->db->query($query)->result_array();
        $ids  = '';
        $subsql='';
        $record=[];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record, $value);
                $ids .= empty($ids) ? $value['kpmt_payment_mode_id'] : ', '.$value['kpmt_payment_mode_id'];
            }
            $subsql .=" AND payment_mode.payment_mode_id NOT IN(".$ids.")";
        }

        $query="SELECT 0 as kpmt_id,
                0 as kpmt_amt,
                payment_mode.payment_mode_id as kpmt_payment_mode_id,
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
    public function get_karigar_from_hisab($hm_id){
        $query="SELECT karigar.karigar_id,
                CONCAT(UPPER(karigar.karigar_name), ' - ', karigar.karigar_mobile) as karigar_name
                FROM hisab_master hm
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = hm.hm_karigar_id)
                WHERE hm.hm_delete_status = 0
                AND hm.hm_id = $hm_id";
        return $this->db->query($query)->result_array();
    }
    public function get_hisab_data($karigar_id){
        $subsql = '';
        if(isset($_POST['pht_hm_id']) && !empty($_POST['pht_hm_id'])){
            $ids = implode(', ', $_POST['pht_hm_id']);
            $subsql .= " AND hm.hm_id NOT IN (".$ids.")";
        }
        $query="SELECT 0 as pht_id,
                0 as pht_checked,
                hm.hm_id as pht_hm_id,
                hm.hm_entry_no as pht_entry_no,
                DATE_FORMAT(hm.hm_entry_date, '%d-%m-%Y') as pht_entry_date,
                (hm.hm_total_amt) as pht_total_amt,
                0 as pht_adjust_amt,
                (hm.hm_total_amt - hm.hm_allocated_amt) as balance_amt
                FROM hisab_master hm
                WHERE hm.hm_delete_status = 0
                AND (hm.hm_total_amt - hm.hm_allocated_amt) > 0
                AND hm.hm_karigar_id = $karigar_id
                $subsql
                ORDER BY balance_amt DESC";
        return $this->db->query($query)->result_array();
    }
    public function get_balance_data($karigar_id){ 
        $query="SELECT 0 as amt
                FROM karigar_master karigar
                WHERE karigar.karigar_id = $karigar_id
                GROUP BY karigar.karigar_id";
        $data = $this->db->query($query)->result_array();
        $opening_amt = empty($data) ? 0 : $data[0]['amt'];

        $query="SELECT SUM(hm.hm_total_amt - hm.hm_allocated_amt) as amt
                FROM hisab_master hm
                WHERE hm.hm_delete_status = 0
                AND (hm.hm_total_amt - hm.hm_allocated_amt) > 0
                AND hm.hm_karigar_id = $karigar_id
                GROUP BY hm.hm_karigar_id";
        $data = $this->db->query($query)->result_array();
        $hisab_amt = empty($data) ? 0 : $data[0]['amt'];

        $closing_amt = ($opening_amt + $hisab_amt);
        $balance_amt = $closing_amt;
        $type 		 = TO_PAY;
        if($balance_amt < 0){
            $balance_amt    = abs($balance_amt);
            $type 		    = TO_RECEIVE;
        }
        return [
                    'opening_amt'       => $opening_amt,
                    'hisab_amt'      => $hisab_amt,
                    'balance_amt'       => $balance_amt,
                    'type'              => $type,
                ];
    }
    public function isAdjusted($payment_id){
        $cnt = $this->db_operations->get_cnt('payment_hisab_trans', ['pht_karigar_payment_id' => $payment_id, 'pht_delete_status' => false]);
        if(!empty($cnt)) return true;

        return false;
    }
    public function get_hisab($hm_id){
        $query="SELECT hm.*,
                (hm.hm_total_amt - hm.hm_allocated_amt) as balance_amt
                FROM hisab_master hm
                WHERE hm.hm_delete_status = 0
                AND hm.hm_id = $hm_id";
        return $this->db->query($query)->result_array();
    }

    public function _hm_id(){
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
            $subsql .= " AND (hm.hm_entry_no LIKE '".$name."%' OR hm.hm_entry_date LIKE '".$ame."%') ";
        }
        $query="SELECT hm.hm_id as id, 
                CONCAT(UPPER(hm.hm_entry_no), ' / ', DATE_FORMAT(hm.hm_entry_date, '%d-%m-%Y')) as name
                FROM hisab_master hm
                WHERE hm.hm_delete_status = 0
                $subsql
                GROUP BY hm.hm_id ASC
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
                $subsql .= " AND (karigar_payment.karigar_payment_entry_no LIKE '".$name."%') ";
            }
            $query="SELECT karigar_payment.karigar_payment_entry_no as id, karigar_payment.karigar_payment_entry_no as name
                    FROM karigar_payment_master payment
                    WHERE karigar_payment.payment_delete_status = 0
                    AND karigar_payment.payment_fin_year = '".$_SESSION['fin_year']."'
                    $subsql
                    GROUP BY karigar_payment.karigar_payment_entry_no ASC
                    LIMIT $limit
                    OFFSET $offset";
            // echo $query; exit();
            return $this->db->query($query)->result_array();
        }
        public function _supplier_name(){
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
                $subsql .= " AND (supplier.supplier_name LIKE '".$name."%') ";
            }
            $query="SELECT supplier.supplier_name as id, UPPER(supplier.supplier_name) as name
                    FROM karigar_payment_master payment
                    INNER JOIN supplier_master supplier ON(supplier.supplier_id = karigar_payment.payment_supplier_id)
                    WHERE karigar_payment.payment_delete_status = 0
                    AND karigar_payment.payment_fin_year = '".$_SESSION['fin_year']."'
                    $subsql
                    GROUP BY supplier.supplier_name ASC
                    LIMIT $limit
                    OFFSET $offset";
            // echo $query; exit();
            return $this->db->query($query)->result_array();
        }            
    // search_functions
}
?>