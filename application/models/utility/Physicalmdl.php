<?php defined('BASEPATH') OR exit('No direct script access allowed');
    class Physicalmdl extends CI_model{
        public function __construct(){
            parent::__construct();
        }
        // core_functions
            public function isExist($id){
                // $data = $this->db->query("SELECT psm_id FROM physical_stock_master WHERE psm_id > $id AND psm_branch_id = ".$_SESSION['user_branch_id']."  LIMIT 1")->result_array();
                // if(!empty($data)) return true;

                // $data = $this->db->query("SELECT psm_entry_date FROM physical_stock_master WHERE psm_id = $id AND psm_branch_id = ".$_SESSION['user_branch_id']."  LIMIT 1")->result_array();

                // if(!empty($data)){
                //     $data = $this->db->query("SELECT sm_id FROM sales_master WHERE sm_delete_status = 0 AND sm_entry_date >= '".$data[0]['psm_entry_date']."' AND sm_branch_id = ".$_SESSION['user_branch_id']."  LIMIT 1")->result_array();
                //     if(!empty($data)) return true;
                // }



                return false;
            }
            public function get_list($wantCount, $per_page = PER_PAGE, $offset = OFFSET){
                $record     = [];
                $subsql     = '';
                $limit      = '';
                $ofset      = '';
                $role       = $_SESSION['user_role'];
                if(!$wantCount){
                    $limit .= " LIMIT $per_page";
                    $ofset .= " OFFSET $offset";
                }
                
                if(isset($_GET['_entry_no']) && !empty($_GET['_entry_no'])){
                    $subsql .=" AND psm.psm_entry_no = ".$_GET['_entry_no'];
                    $record['search']['_entry_no']['value'] = $_GET['_entry_no'];
                    $record['search']['_entry_no']['text'] = $_GET['_entry_no'];
                }
                if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
                    $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
                    $subsql .= " AND psm.psm_entry_date >= '".$_entry_date_from."'";
                }
                if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
                    $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
                    $subsql .= " AND psm.psm_entry_date <= '".$_entry_date_to."'";
                }
                if(isset($_GET['_customer_name']) && !empty($_GET['_customer_name'])){
                    $subsql .=" AND customer.customer_name = '".$_GET['_customer_name']."'";
                    $record['search']['_customer_name']['value'] = $_GET['_customer_name'];
                    $record['search']['_customer_name']['text'] = $_GET['_customer_name'];
                }
                if(isset($_GET['_scan_qty_from'])){
                    if($_GET['_scan_qty_from'] != ''){
                        $subsql .=" AND psm.psm_scan_qty >= ".$_GET['_scan_qty_from'];
                    }
                }
                if(isset($_GET['_scan_qty_to'])){
                    if($_GET['_scan_qty_to'] != ''){
                        $subsql .=" AND psm.psm_scan_qty <= ".$_GET['_scan_qty_to'];
                    }
                }
                if(isset($_GET['_unscan_qty_from'])){
                    if($_GET['_unscan_qty_from'] != ''){
                        $subsql .=" AND psm.psm_unscan_qty >= ".$_GET['_unscan_qty_from'];
                    }
                }
                if(isset($_GET['_unscan_qty_to'])){
                    if($_GET['_unscan_qty_to'] != ''){
                        $subsql .=" AND psm.psm_unscan_qty <= ".$_GET['_unscan_qty_to'];
                    }
                }
                $query="SELECT psm.*
                        FROM physical_stock_master psm
                        WHERE psm.psm_branch_id = ".$_SESSION['user_branch_id']."
                        AND psm.psm_fin_year = '".$_SESSION['fin_year']."'
                        $subsql
                        ORDER BY psm.psm_id DESC
                        $limit
                        $ofset";
                // echo "<pre>"; print_r($query); exit;
                if($wantCount){
                    return $this->db->query($query)->num_rows();
                }
                $record['data'] = $this->db->query($query)->result_array();
    
                if(!empty($record['data'])){
                    foreach ($record['data'] as $key => $value) {
                        $unscan_trans   = $this->get_unscan_barcode($value['psm_id']);
                        // echo "<pre>"; print_r($unscan_trans); exit;
                        $record['data'][$key]['psm_unscan_qty'] = $unscan_trans['qty'];
                        $record['data'][$key]['isExist']        = $this->isExist($value['psm_id']);
                    }
                }
                return $record;
            }
            public function get_data_for_add(){
                $record['psm_entry_no']  = $this->db_operations->get_fin_year_branch_max_id($this->master, 'psm_entry_no', 'psm_fin_year', $_SESSION['fin_year'], 'psm_branch_id', $_SESSION['user_branch_id']);
                $query= "SELECT 
                        SUM((brm.brmm_prmt_qty + brm.brmm_ort_qty + brm.brmm_gt_qty) - (brm.brmm_prrt_qty + brm.brmm_ot_qty + brm.brmm_outward_qty)) as total_qty,
                        SUM(((brm.brmm_prmt_qty + brm.brmm_ort_qty + brm.brmm_gt_qty) - (brm.brmm_prrt_qty + brm.brmm_ot_qty + brm.brmm_outward_qty)) * brm.brmm_prmt_rate) as total_amt 
                        FROM barcode_readymade_master brm 
                        WHERE brm.brmm_delete_status = 0
                        AND ((brm.brmm_prmt_qty + brm.brmm_ort_qty + brm.brmm_gt_qty) - (brm.brmm_prrt_qty + brm.brmm_ot_qty + brm.brmm_outward_qty)) > 0
                        AND brm.brm_branch_id = ".$_SESSION['user_branch_id'];
                $record['unscan_data']  = $this->db->query($query)->result_array();
                // echo "<pre>"; print_r($record);exit;
                return $record;
            }
            public function get_data_for_edit($psm_id){
                $query="SELECT psm.*
                        FROM physical_stock_master psm
                        WHERE psm.psm_id = $psm_id";
                $record['master_data'] = $this->db->query($query)->result_array();
                if(!empty($record['master_data'])){
                    // $unscan_trans   = $this->get_unscan_trans($psm_id);
                    $unscan_barcode = $this->get_unscan_barcode($psm_id);
                    $record['master_data'][0]['psm_unscan_qty'] = $unscan_barcode['qty'];
                    $record['master_data'][0]['psm_unscan_amt'] = $unscan_barcode['amt'];
                    $record['master_data'][0]['isExist']        = $this->isExist($psm_id);
                }
                // echo "<pre>"; print_r($record); exit;
                return $record;   
            }
            public function get_transaction($psm_id){
                $query="SELECT psst.*, psst.psst_rate, brm.brmm_item_code, brm.brmm_description,
                        UPPER(sku.sku_name) as sku_name, 
                        UPPER(apparel.apparel_name) as apparel_name,
                        UPPER(supplier.supplier_name) as supplier_name, 
                        ((brm.brmm_prmt_qty + brm.brmm_ort_qty + brm.brmm_gt_qty) - (brm.brmm_prrt_qty + brm.brmm_ot_qty + brm.brmm_outward_qty)) as bal_qty
                        FROM physical_scan_trans psst
                        INNER JOIN barcode_readymade_master brm ON(brm.brmm_id = psst.psst_brmm_id)
                        INNER JOIN sku_master sku ON(sku.sku_id = psst.psst_sku_id)
                        INNER JOIN supplier_master supplier ON(supplier.supplier_id = sku.sku_supplier_id)
						INNER JOIN apparel_master apparel ON(apparel.apparel_id = sku.sku_apparel_id)
						WHERE psst.psst_psm_id = $psm_id
                        ORDER BY psst.psst_id ASC
                        LIMIT 20";
                $record = $this->db->query($query)->result_array();
                if(!empty($record)){
                    foreach ($record as $key => $value) {
                        $record[$key]['isExist'] = ($value['psst_qty'] == $value['bal_qty']) ? 0 : 1;
                    }
                }
                // echo "<pre>"; print_r($record);exit;
                return $record;
            }
        // core_functions
        
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
                    $subsql .= " AND (psm_entry_no LIKE '%".$name."%') ";
                }
                $query="SELECT psm_entry_no as id, psm_entry_no as name
                        FROM physical_stock_master
                        WHERE psm_branch_id = ".$_SESSION['user_branch_id']."
                        AND psm_fin_year = '".$_SESSION['fin_year']."'
                        $subsql
                        GROUP BY psm_entry_no ASC
                        LIMIT $limit
                        OFFSET $offset";
                // echo $query; exit();
                return $this->db->query($query)->result_array();
            }
        // search_functions
            
        // form_search_functions
            public function _bm_id(){
                $subsql = "";
                if(isset($_GET['name']) && !empty($_GET['name'])){
                    $name   = $_GET['name'];
                    $subsql .= " AND (brmm_item_code =".$name.") ";
                }else{
                    $subsql .= " AND (brmm_item_code ='XXX') ";
                }
                $query="SELECT brmm_id as id, brmm_item_code as name
                        FROM barcode_readymade_master
                        WHERE 1
                        AND brmm_branch_id = ".$_SESSION['user_branch_id']."
                        $subsql
                        GROUP BY brmm_id ASC";
                // echo $query; exit();
                return $this->db->query($query)->result_array();
            }
        // form_search_functions

        // additional_functions
            public function get_unscan_trans($psm_id){
                $query="SELECT SUM(pust.pust_qty) as qty, SUM(pust.pust_qty * pust.pust_rate) as amt
                        FROM physical_unscan_trans pust
                        WHERE pust.pust_psm_id = $psm_id
                        GROUP BY pust.pust_psm_id";
                $data = $this->db->query($query)->result_array();
                return ['qty' => (empty($data) ? 0 : $data[0]['qty']), 'amt' => (empty($data) ? 0 : $data[0]['amt'])];
            }
            public function get_scan_trans($psm_id){
                $query="SELECT SUM(psst.psst_qty) as qty, SUM(psst.psst_qty * psst.psst_rate) as amt
                        FROM physical_scan_trans psst
                        WHERE psst.psst_psm_id = $psm_id";
                $data = $this->db->query($query)->result_array();
                return ['qty' => (empty($data) ? 0 : $data[0]['qty']), 'amt' => (empty($data) ? 0 : $data[0]['amt'])];
            }
            public function get_unscan_barcode($psm_id){
                $query="SELECT SUM(brmm.brmm_pust_qty) as qty, SUM(brmm.brmm_pust_qty * brmm.brmm_prmt_rate) as amt
                        FROM barcode_readymade_master brmm
                        WHERE brmm.brmm_psm_id = $psm_id
                        AND brmm.brmm_pust_qty > 0
                        AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id']."";
                $data = $this->db->query($query)->result_array();
                return ['qty' => (empty($data) ? 0 : $data[0]['qty']), 'amt' => (empty($data) ? 0 : $data[0]['amt'])];
            }
            public function get_unscan_trans_insert($psm_id){
                $query="SELECT brmm.*,
                        ((brmm.brmm_prmt_qty + brmm.brmm_ort_qty + brmm.brmm_gt_qty) - (brmm.brmm_prrt_qty + brmm.brmm_ot_qty + brmm.brmm_outward_qty)) as bal_qty
                        FROM barcode_readymade_master brmm
                        WHERE brmm.brmm_psm_id != $psm_id
                        AND brmm.brmm_delete_status = 0
                        AND ((brmm.brmm_prmt_qty + brmm.brmm_ort_qty + brmm.brmm_gt_qty) - (brmm.brmm_prrt_qty + brmm.brmm_ot_qty + brmm.brmm_outward_qty)) > 0
                        AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id']."
                        ORDER BY brmm.brmm_id ASC";
                // echo "<pre>"; print_r($query); exit;
                return $this->db->query($query)->result_array();
            }
            public function get_prev_scan_data($psm_id, $bm_id){
                $query="SELECT psst.*
                        FROM physical_scan_trans psst
                        WHERE psst.psst_psm_id != $psm_id
                        AND psst.psst_brmm_id = $bm_id
                        ORDER BY psst.psst_id DESC
                        LIMIT 1";
                return $this->db->query($query)->result_array();
            }
            public function get_barcode_data($bm_id){
                $query="SELECT brmm.*, 
                        brmm.brmm_prmt_rate as rate,
                        ((brmm.brmm_prmt_qty + brmm.brmm_ort_qty + brmm.brmm_gt_qty) - (brmm.brmm_prrt_qty + brmm.brmm_ot_qty + brmm.brmm_outward_qty)) as bal_qty
                        FROM barcode_readymade_master brmm
                        WHERE brmm.brmm_id = $bm_id
                        AND brmm.brmm_branch_id = ".$_SESSION['user_branch_id']."";
                return $this->db->query($query)->result_array();
            }
            public function get_barcode_by_psst_id($psst_id){
                $query="SELECT brmm.*, psst.*,
                        ROUND(brmm.brmm_prmt_rate) as rate,
                        ((brmm.brmm_prmt_qty + brmm.brmm_ort_qty + brmm.brmm_gt_qty) - (brmm.brmm_prrt_qty + brmm.brmm_ot_qty + brmm.brmm_outward_qty)) as bal_qty
                        FROM physical_scan_trans psst
                        INNER JOIN barcode_readymade_master brmm ON(brmm.brmm_id = psst.psst_brmm_id)
                        WHERE psst.psst_id = $psst_id";
                return $this->db->query($query)->result_array();
            }
        // additional_functions
    }
?>