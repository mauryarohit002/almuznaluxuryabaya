<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class payment_model extends my_model{
    public function __construct(){ parent::__construct('voucher', 'payment'); }
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
            $subsql .=" AND payment.payment_entry_no = ".$_GET['_entry_no'];
            $record['filters']['_entry_no']['text'] = $_GET['_entry_no'];
            $record['filters']['_entry_no']['value'] = $_GET['_entry_no'];
        }
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND payment.payment_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND payment.payment_entry_date <= '".$_entry_date_to."'";
        }
        if(isset($_GET['_supplier_name']) && !empty($_GET['_supplier_name'])){
            $subsql .=" AND supplier.supplier_name = '".$_GET['_supplier_name']."'";
            $record['filters']['_supplier_name']['value'] = $_GET['_supplier_name'];
            $record['filters']['_supplier_name']['text'] = $_GET['_supplier_name'];
        }
        if(isset($_GET['status']) && !empty($_GET['status'])){
            $subsql .=' AND payment.payment_adjust_status = '.$_GET['status'];
        }
        if(isset($_GET['_total_amt_from'])){
            if($_GET['_total_amt_from'] != ''){
                $subsql .=" AND payment.payment_total_amt >= ".$_GET['_total_amt_from'];
            }
        }
        if(isset($_GET['_total_amt_to'])){
            if($_GET['_total_amt_to'] != ''){
                $subsql .=" AND payment.payment_total_amt <= ".$_GET['_total_amt_to'];
            }
        }
        $query="SELECT payment.*,
                DATE_FORMAT(payment.payment_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(supplier.supplier_name) as supplier_name
                FROM payment_master payment
                LEFT JOIN supplier_master supplier ON(supplier.supplier_id = payment.payment_supplier_id)
                WHERE payment.payment_delete_status = 0
                AND payment.payment_branch_id = ".$_SESSION['user_branch_id']."
                AND payment.payment_fin_year = '".$_SESSION['fin_year']."'
                $subsql
                ORDER BY payment.payment_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['payment_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){
        $record['payment_entry_no'] 	= $this->get_max_entry_no(['entry_no' => 'payment_entry_no', 'delete_status' => 'payment_delete_status', 'fin_year' => 'payment_fin_year']);
        $record['payment_uuid'] 	    = $_SESSION['user_id'].''.time();
        return $record;
    }
    public function get_data_for_edit($id){
        $query="SELECT payment.*,
                UPPER(supplier.supplier_name) as supplier_name
                FROM payment_master payment
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = payment.payment_supplier_id)
                WHERE payment.payment_delete_status = 0
                AND payment.payment_id = $id";
        $data['master_data'] = $this->db->query($query)->result_array();
        if(!empty($data['master_data'])){
            $balance_data    = $this->get_balance_data($data['master_data'][0]['payment_supplier_id']);
            $data['master_data'][0]['isExist'] = $this->isExist($id);
            $data['master_data'][0]['payment_balance_amt_show']      = $balance_data['balance_amt'].' '.$balance_data['type'];
            $data['master_data'][0]['payment_balance_amt']           = $balance_data['balance_amt'] + $data['master_data'][0]['payment_amt'];
            $data['master_data'][0]['payment_balance_type']          = $balance_data['type'];
        }
        return $data;
    }
    public function get_transaction($payment_id){
        $query="SELECT ppt.*,
                pm.pm_bill_no as ppt_bill_no,
                DATE_FORMAT(pm.pm_bill_date, '%d-%m-%Y') as ppt_bill_date,
                1 as ppt_checked
                FROM payment_purchase_trans ppt
                LEFT JOIN purchase_master pm ON(pm.pm_id=ppt.ppt_pm_id )
                WHERE ppt.ppt_delete_status = 0
                AND ppt.ppt_payment_id = $payment_id";
        $data['purchase_data'] = $this->db->query($query)->result_array();
        if(!empty($data['purchase_data'])){
            foreach ($data['purchase_data'] as $key => $value) {
                $purchase_data  = $this->get_purchase($value['ppt_pm_id']);
                $total_amt      = $value['ppt_adjust_amt'] + $purchase_data[0]['balance_amt'];
                $balance_amt    = $total_amt - $value['ppt_adjust_amt'];
                $data['purchase_data'][$key]['ppt_total_amt']    = $total_amt;
                $data['purchase_data'][$key]['balance_amt']      = $balance_amt;
            }
            usort($data['purchase_data'], function($a, $b){
                return $b['balance_amt'] - $a['balance_amt'];
            });
        }

        $query="SELECT pprt.*,
                prmm.prmm_bill_no as pprt_bill_no,
                DATE_FORMAT(prmm.prmm_bill_date, '%d-%m-%Y') as pprt_bill_date,
                1 as pprt_checked
                FROM payment_purchase_readymade_trans pprt
                LEFT JOIN purchase_readymade_master prmm ON(prmm.prmm_id=pprt.pprt_prmm_id)
                WHERE pprt.pprt_delete_status = 0
                AND pprt.pprt_payment_id = $payment_id";
        $data['purchase_readymade_data'] = $this->db->query($query)->result_array();
        if(!empty($data['purchase_readymade_data'])){
            foreach ($data['purchase_readymade_data'] as $key => $value) {
                $purchase_readymade_data  = $this->get_purchase_readymade($value['pprt_prmm_id']);
                $total_amt      = $value['pprt_adjust_amt'] + $purchase_readymade_data[0]['balance_amt'];
                $balance_amt    = $total_amt - $value['pprt_adjust_amt'];
                $data['purchase_readymade_data'][$key]['pprt_total_amt']    = $total_amt;
                $data['purchase_readymade_data'][$key]['balance_amt']      = $balance_amt;
            }
            usort($data['purchase_readymade_data'], function($a, $b){
                return $b['balance_amt'] - $a['balance_amt'];
            });
        }

        return $data;
    }
    public function get_payment_mode_data($payment_id){
        $query="SELECT ppmt.ppmt_id,
                ppmt.ppmt_amt as ppmt_amt,
                ppmt.ppmt_payment_mode_id as ppmt_payment_mode_id,
                UPPER(payment_mode.payment_mode_name) as payment_mode_name
                FROM payment_payment_mode_trans ppmt
                INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = ppmt.ppmt_payment_mode_id)
                WHERE ppmt.ppmt_delete_status = 0
                AND ppmt.ppmt_payment_id = $payment_id
                ORDER BY payment_mode.payment_mode_name ASC";
        $data = $this->db->query($query)->result_array();
        $ids  = '';
        $subsql='';
        $record=[];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record, $value);
                $ids .= empty($ids) ? $value['ppmt_payment_mode_id'] : ', '.$value['ppmt_payment_mode_id'];
            }
            $subsql .=" AND payment_mode.payment_mode_id NOT IN(".$ids.")";
        }

        $query="SELECT 0 as ppmt_id,
                0 as ppmt_amt,
                payment_mode.payment_mode_id as ppmt_payment_mode_id,
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
    public function get_cash_data($payment_id){
        $query="SELECT 
                payment.payment_cash_id as cash_id,
                UPPER(general.general_name) as cash_name
                FROM payment_master payment
                INNER JOIN general_master general ON(general.general_id = payment.payment_cash_id)
                WHERE payment.payment_delete_status = 0
                AND payment.payment_id = $payment_id";
        $data = $this->db->query($query)->result_array();
        if(!empty($data)) return $data;

        $query="SELECT 
                general.general_id as cash_id,
                UPPER(general.general_name) as cash_name
                FROM general_master general
                WHERE general.general_status = 1
                AND general.general_constant = 'CASH'
                AND general.general_type = 'FABRIC'
                ORDER BY general.general_name ASC";
        $data = $this->db->query($query)->result_array();
        return $data;
    }
    public function get_bank_data($payment_id){
        $query="SELECT 
                payment.payment_bank_id as bank_id,
                UPPER(general.general_name) as bank_name
                FROM payment_master payment
                INNER JOIN general_master general ON(general.general_id = payment.payment_bank_id)
                WHERE payment.payment_delete_status = 0
                AND payment.payment_id = $payment_id";
        $data = $this->db->query($query)->result_array();
        if(!empty($data)) return $data;

        $query="SELECT 
                general.general_id as bank_id,
                UPPER(general.general_name) as bank_name
                FROM general_master general
                WHERE general.general_status = 1
                AND general.general_constant = 'BANK'
                AND general.general_type = 'FABRIC'
                ORDER BY general.general_name ASC";
        $data = $this->db->query($query)->result_array();
        return $data;
    }
    public function get_supplier_from_purchase($pm_id){
        $query="SELECT supplier.supplier_id,
                CONCAT(UPPER(supplier.supplier_name), ' - ', supplier.supplier_mobile) as supplier_name
                FROM purchase_master pm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = pm.pm_supplier_id)
                WHERE pm.pm_delete_status = 0
                AND pm.pm_id = $pm_id";
        return $this->db->query($query)->result_array();
    }
    public function get_purchase_data($supplier_id){
        $subsql = '';
        if(isset($_POST['ppt_pm_id']) && !empty($_POST['ppt_pm_id'])){
            $ids = implode(', ', $_POST['ppt_pm_id']);
            $subsql .= " AND pm.pm_id NOT IN (".$ids.")";
        }
        $query="SELECT 0 as ppt_id,
                0 as ppt_checked,
                pm.pm_id as ppt_pm_id,
                pm.pm_entry_no as ppt_entry_no,
                DATE_FORMAT(pm.pm_entry_date, '%d-%m-%Y') as ppt_entry_date,
                pm.pm_bill_no as ppt_bill_no,
                DATE_FORMAT(pm.pm_bill_date, '%d-%m-%Y') as ppt_bill_date,
                (pm.pm_total_amt) as ppt_total_amt,
                0 as ppt_adjust_amt,
                (pm.pm_total_amt - pm.pm_allocated_amt) as balance_amt
                FROM purchase_master pm
                WHERE pm.pm_delete_status = 0
                AND (pm.pm_total_amt - pm.pm_allocated_amt) > 0
                AND pm.pm_supplier_id = $supplier_id
                $subsql
                ORDER BY balance_amt DESC";
        return $this->db->query($query)->result_array();
    }

    public function get_purchase_readymade_data($supplier_id){
        $subsql = '';
        if(isset($_POST['pprt_prmm_id']) && !empty($_POST['pprt_prmm_id'])){
            $ids = implode(', ', $_POST['pprt_prmm_id']);
            $subsql .= " AND prmm.prmm_id NOT IN (".$ids.")";
        }
        $query="SELECT 0 as pprt_id,
                0 as pprt_checked,
                prmm.prmm_id as pprt_prmm_id,
                prmm.prmm_entry_no as pprt_entry_no,
                DATE_FORMAT(prmm.prmm_entry_date, '%d-%m-%Y') as pprt_entry_date,
                prmm.prmm_bill_no as pprt_bill_no,
                DATE_FORMAT(prmm.prmm_bill_date, '%d-%m-%Y') as pprt_bill_date,
                (prmm.prmm_total_amt) as pprt_total_amt,
                0 as pprt_adjust_amt,
                (prmm.prmm_total_amt - prmm.prmm_allocated_amt) as balance_amt
                FROM purchase_readymade_master prmm
                WHERE prmm.prmm_delete_status = 0
                AND (prmm.prmm_total_amt - prmm.prmm_allocated_amt) > 0
                AND prmm.prmm_supplier_id = $supplier_id
                $subsql
                ORDER BY balance_amt DESC";
        return $this->db->query($query)->result_array();
    }

    public function get_balance_data($supplier_id){
        $opening_amt    = 0;
        $purchase_amt   = $this->get_purchase_amt($supplier_id);
        $purchase_readymade_amt   = $this->get_purchase_readymade_amt($supplier_id);

        $payment_amt    = $this->get_payment_amt($supplier_id);
        // $amt_to_adjust  = $this->get_amt_to_adjust($supplier_id);

        // $closing_amt = ($opening_amt + $purchase_amt) - ($payment_amt + $amt_to_adjust);
        $closing_amt = ($opening_amt + $purchase_amt + $purchase_readymade_amt);

        $balance_amt = $closing_amt;
        $type 		 = TO_PAY;
        if($balance_amt < 0){
            $balance_amt    = abs($balance_amt);
            $type 		    = TO_RECEIVE;
        }
        return [
                    'opening_amt'   => $opening_amt,
                    // 'purchase_amt'  => $purchase_amt - $payment_amt,
                    'purchase_amt'  => $purchase_amt,
                    'purchase_readymade_amt'  => $purchase_readymade_amt,

                    // 'payment_amt'   => $payment_amt,
                    // 'amt_to_adjust' => $amt_to_adjust,
                    'balance_amt'   => $balance_amt,
                    'type'          => $type,
                ];
    }
    public function get_purchase_amt($id) {
        $query="SELECT SUM(pm.pm_total_amt-pm.pm_allocated_amt) as amt
                FROM purchase_master pm
                WHERE pm.pm_delete_status = 0
                AND pm.pm_supplier_id = $id
                GROUP BY pm.pm_supplier_id";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? 0 : $data[0]['amt'];
    }
    public function get_purchase_readymade_amt($id) {
        $query="SELECT SUM(prmm.prmm_total_amt-prmm.prmm_allocated_amt) as amt
                FROM purchase_readymade_master prmm
                WHERE prmm.prmm_delete_status = 0
                AND prmm.prmm_supplier_id = $id
                GROUP BY prmm.prmm_supplier_id";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? 0 : $data[0]['amt'];
    }
    public function get_payment_amt($id) {
        $query="SELECT SUM(payment.payment_amt) as amt
                FROM payment_master payment
                WHERE payment.payment_delete_status = 0
                AND payment.payment_adjust_status = 1
                AND payment.payment_supplier_id = $id
                GROUP BY payment.payment_supplier_id";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? 0 : $data[0]['amt'];
    }
    public function get_amt_to_adjust($id) {
        $query="SELECT SUM(payment.payment_amt) as amt
                FROM payment_master payment
                WHERE payment.payment_delete_status = 0
                AND payment.payment_adjust_status = 0
                AND payment.payment_supplier_id = $id
                GROUP BY payment.payment_supplier_id";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? 0 : $data[0]['amt'];
    }
    public function isAdjusted($payment_id){
        $cnt = $this->db_operations->get_cnt('payment_purchase_trans', ['ppt_payment_id' => $payment_id, 'ppt_delete_status' => false]);
        if(!empty($cnt)) return true;
        $cnt = $this->db_operations->get_cnt('payment_purchase_readymade_trans', ['pprt_payment_id' => $payment_id, 'pprt_delete_status' => false]);
        if(!empty($cnt)) return true;

        return false;
    }
    public function get_purchase($pm_id){
        $query="SELECT pm.*,
                (pm.pm_total_amt - pm.pm_allocated_amt) as balance_amt
                FROM purchase_master pm
                WHERE pm.pm_delete_status = 0
                AND pm.pm_id = $pm_id";
        return $this->db->query($query)->result_array();
    }
    public function get_purchase_readymade($prmm_id){
        $query="SELECT prmm.*,
                (prmm.prmm_total_amt - prmm.prmm_allocated_amt) as balance_amt
                FROM purchase_readymade_master prmm
                WHERE prmm.prmm_delete_status = 0
                AND prmm.prmm_id = $prmm_id";
        return $this->db->query($query)->result_array();
    }

    public function _pm_id(){
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
            $subsql .= " AND (pm.pm_bill_no LIKE '".$name."%' OR pm.pm_entry_date LIKE '".$name."%') ";
        }
        $query="SELECT pm.pm_id as id, 
                CONCAT(UPPER(pm.pm_bill_no), ' / ', DATE_FORMAT(pm.pm_entry_date, '%d-%m-%Y')) as name
                FROM purchase_master pm
                WHERE pm.pm_delete_status = 0
                $subsql
                GROUP BY pm.pm_id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _general_id(){
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
        if((isset($_GET['name']) && !empty($_GET['name']))){
            $name 	= $_GET['name'];
            $subsql .= " AND (general.general_name = '".$name."')";
        }
        if((isset($_GET['param']) && !empty($_GET['param']))){
            $status = $_GET['param'];
            $subsql.= " AND (general.general_status = $status)";
        }
        if((isset($_GET['param1']) && !empty($_GET['param1']))){
            $constant = $_GET['param1'];
            $subsql  .= " AND (general.general_constant = '".$constant."')";
        }
        if((isset($_GET['param2']) && !empty($_GET['param2']))){
            $type   = $_GET['param2'];
            $subsql.= " AND (general.general_type = '".$type."')";
        }
        $query="SELECT general.general_id as id, 
                UPPER(general.general_name) as name
                FROM general_master general
                WHERE 1
                $subsql
                GROUP BY general.general_id
                ORDER BY general.general_name ASC
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
                $subsql .= " AND (payment.payment_entry_no LIKE '".$name."%') ";
            }
            $query="SELECT payment.payment_entry_no as id, payment.payment_entry_no as name
                    FROM payment_master payment
                    WHERE payment.payment_delete_status = 0
                    AND payment.payment_fin_year = '".$_SESSION['fin_year']."'
                    $subsql
                    GROUP BY payment.payment_entry_no ASC
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
                    FROM payment_master payment
                    INNER JOIN supplier_master supplier ON(supplier.supplier_id = payment.payment_supplier_id)
                    WHERE payment.payment_delete_status = 0
                    AND payment.payment_fin_year = '".$_SESSION['fin_year']."'
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