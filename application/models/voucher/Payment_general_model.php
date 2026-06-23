<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class payment_general_model extends my_model{
    public function __construct(){ parent::__construct('voucher', 'payment_general'); }
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
            $subsql .=" AND payment_general.payment_general_entry_no = ".$_GET['_entry_no'];
            $record['filters']['_entry_no']['text'] = $_GET['_entry_no'];
            $record['filters']['_entry_no']['value'] = $_GET['_entry_no'];
        }
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND payment_general.payment_general_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND payment_general.payment_general_entry_date <= '".$_entry_date_to."'";
        }
        if(isset($_GET['_general_name']) && !empty($_GET['_general_name'])){
            $subsql .=" AND general.general_name = '".$_GET['_general_name']."'";
            $record['filters']['_general_name']['value'] = $_GET['_general_name'];
            $record['filters']['_general_name']['text'] = $_GET['_general_name'];
        }
        if(isset($_GET['status']) && !empty($_GET['status'])){
            $subsql .=' AND payment_general.payment_general_adjust_status = '.$_GET['status'];
        }
        if(isset($_GET['_total_amt_from'])){
            if($_GET['_total_amt_from'] != ''){
                $subsql .=" AND payment_general.payment_general_total_amt >= ".$_GET['_total_amt_from'];
            }
        }
        if(isset($_GET['_total_amt_to'])){
            if($_GET['_total_amt_to'] != ''){
                $subsql .=" AND payment_general.payment_general_total_amt <= ".$_GET['_total_amt_to'];
            }
        }
        $query="SELECT payment_general.*,
                DATE_FORMAT(payment_general.payment_general_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(general.general_name) as general_name
                FROM payment_general_master payment_general
                LEFT JOIN general_master general ON(general.general_id = payment_general.payment_general_general_id)
                WHERE payment_general.payment_general_delete_status = 0
                AND payment_general.payment_general_branch_id = ".$_SESSION['user_branch_id']."
                AND payment_general.payment_general_fin_year = '".$_SESSION['fin_year']."'
                $subsql
                ORDER BY payment_general.payment_general_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['payment_general_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){
        $record['payment_general_entry_no'] = $this->get_max_entry_no(['entry_no' => 'payment_general_entry_no', 'delete_status' => 'payment_general_delete_status', 'fin_year' => 'payment_general_fin_year', 'branch_id' => 'payment_general_branch_id']);
        $record['payment_general_uuid'] 	= $_SESSION['user_id'].''.time();
        return $record;
    }
    public function get_data_for_edit($id){
        $query="SELECT payment_general.*,
                UPPER(general.general_name) as general_name
                FROM payment_general_master payment_general
                INNER JOIN general_master general ON(general.general_id = payment_general.payment_general_general_id)
                WHERE payment_general.payment_general_delete_status = 0
                AND payment_general.payment_general_id = $id";
        $data['master_data'] = $this->db->query($query)->result_array();
        if(!empty($data['master_data'])){
            $data['master_data'][0]['isExist'] = $this->isExist($id);
        }
        return $data;
    }
    public function get_payment_mode_data($payment_id){
        $query="SELECT pgpmt.pgpmt_id,
                pgpmt.pgpmt_amt as pgpmt_amt,
                pgpmt.pgpmt_payment_mode_id as pgpmt_payment_mode_id,
                UPPER(payment_mode.payment_mode_name) as payment_mode_name
                FROM payment_general_payment_mode_trans pgpmt
                INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = pgpmt.pgpmt_payment_mode_id)
                WHERE pgpmt.pgpmt_delete_status = 0
                AND pgpmt.pgpmt_payment_general_id = $payment_id
                ORDER BY payment_mode.payment_mode_name ASC";
        $data = $this->db->query($query)->result_array();
        $ids  = '';
        $subsql='';
        $record=[];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record, $value);
                $ids .= empty($ids) ? $value['pgpmt_payment_mode_id'] : ', '.$value['pgpmt_payment_mode_id'];
            }
            $subsql .=" AND payment_mode.payment_mode_id NOT IN(".$ids.")";
        }

        $query="SELECT 0 as pgpmt_id,
                0 as pgpmt_amt,
                payment_mode.payment_mode_id as pgpmt_payment_mode_id,
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
    public function get_purchase_data($general_id){
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
                (pm.pm_total_amt) as ppt_total_amt,
                0 as ppt_adjust_amt,
                (pm.pm_total_amt - pm.pm_allocated_amt) as balance_amt
                FROM purchase_master pm
                WHERE pm.pm_delete_status = 0
                AND (pm.pm_total_amt - pm.pm_allocated_amt) > 0
                AND pm.pm_supplier_id = $general_id
                AND pm.pm_branch_id='".$_SESSION['user_branch_id']."'
                $subsql
                ORDER BY balance_amt DESC";
        return $this->db->query($query)->result_array();
    }
    public function get_balance_data($general_id){
        $query="SELECT SUM(general.general_opening_amt) as amt
                FROM general_master general
                WHERE general.general_id = $general_id
                 AND general.general_branch_id='".$_SESSION['user_branch_id']."'
                GROUP BY general.general_id";
        $data = $this->db->query($query)->result_array();
        $opening_amt = empty($data) ? 0 : $data[0]['amt'];

        $closing_amt = $opening_amt;
        $balance_amt = $closing_amt;
        if($balance_amt < 0) $balance_amt = abs($balance_amt);
        return ['opening_amt' => $opening_amt, 'balance_amt' => $balance_amt];
    }
    public function isAdjusted($payment_id){
        $cnt = $this->db_operations->get_cnt('payment_purchase_trans', ['ppt_payment_id' => $payment_id, 'ppt_delete_status' => false]);
        if(!empty($cnt)) return true;

        return false;
    }
    public function get_purchase($pm_id){
        $query="SELECT pm.*,
                (pm.pm_total_amt - pm.pm_allocated_amt) as balance_amt
                FROM purchase_master pm
                WHERE pm.pm_delete_status = 0
                 AND pm.pm_branch_id='".$_SESSION['user_branch_id']."'
                AND pm.pm_id = $pm_id";
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
            $subsql .= " AND (pm.pm_entry_no LIKE '".$name."%' OR pm.pm_entry_date LIKE '".$name."%') ";
        }
        $query="SELECT pm.pm_id as id, 
                CONCAT(UPPER(pm.pm_entry_no), ' / ', DATE_FORMAT(pm.pm_entry_date, '%d-%m-%Y')) as name
                FROM purchase_master pm
                WHERE pm.pm_delete_status = 0
                 AND pm.pm_branch_id='".$_SESSION['user_branch_id']."'
                $subsql
                GROUP BY pm.pm_id ASC
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
                $subsql .= " AND (payment_general.payment_general_entry_no LIKE '".$name."%') ";
            }
            $query="SELECT payment_general.payment_general_entry_no as id, payment_general.payment_general_entry_no as name
                    FROM payment_general_master payment_general
                    WHERE payment_general.payment_general_delete_status = 0
                    AND payment_general.payment_general_fin_year = '".$_SESSION['fin_year']."'
                     AND payment_general.payment_general_branch_id='".$_SESSION['user_branch_id']."'
                    $subsql
                    GROUP BY payment_general.payment_general_entry_no ASC
                    LIMIT $limit
                    OFFSET $offset";
            // echo $query; exit();
            return $this->db->query($query)->result_array();
        }
        public function _general_name(){
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
                $subsql .= " AND (general.general_name LIKE '".$name."%') ";
            }
            $query="SELECT general.general_name as id, UPPER(general.general_name) as name
                    FROM payment_general_master payment_general
                    INNER JOIN general_master general ON(general.general_id = payment_general.payment_general_general_id)
                    WHERE payment_general.payment_general_delete_status = 0
                    AND payment_general.payment_general_fin_year = '".$_SESSION['fin_year']."'
                    AND payment_general.payment_general_branch_id='".$_SESSION['user_branch_id']."'
                    $subsql
                    GROUP BY general.general_name ASC
                    LIMIT $limit
                    OFFSET $offset";
            // echo $query; exit();
            return $this->db->query($query)->result_array();
        }            
    // search_functions
}
?>