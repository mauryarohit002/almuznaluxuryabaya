<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class purchase_readymade_model extends my_model{
    public function __construct(){ parent::__construct('transaction', 'purchase_readymade'); }
    public function isExist($id){
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_prmm_id = $id AND (brmm_ot_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;

        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_prmm_id = $id AND (brmm_prrt_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_prmm_id = $id AND (brmm_outward_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_prmm_id = $id AND (brmm_gt_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;

        return false; 
    }
    public function isTransExist($id){
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_prmt_id = $id AND (brmm_ot_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;

        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_prmt_id = $id AND (brmm_prrt_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_prmt_id = $id AND (brmm_outward_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_prmt_id = $id AND (brmm_gt_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        return false;
    }
    public function isBarcodeExist($id){
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_id = $id AND (brmm_ot_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;

        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_id = $id AND (brmm_prrt_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_id = $id AND (brmm_outward_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        $data = $this->db->query("SELECT brmm_id FROM barcode_readymade_master WHERE brmm_id = $id AND (brmm_gt_qty) > 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        return false;
    }
    public function get_list($wantCount, $per_page = 20, $offset = 0){
        $record 	= [];
        $subsql 	= '';
        $limit  	= '';
        $ofset  	= '';
        
        if(!$wantCount){
            $limit .= " LIMIT $per_page";
            $ofset .= " OFFSET $offset";
        }
        
        if(isset($_GET['_entry_no']) && !empty($_GET['_entry_no'])){
            $subsql .=" AND prmm.prmm_entry_no = '".$_GET['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
            $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
        }
        if(isset($_GET['_bill_no']) && !empty($_GET['_bill_no'])){
            $subsql .=" AND prmm.prmm_bill_no = '".$_GET['_bill_no']."'";
            $record['filter']['_bill_no']['value'] = $_GET['_bill_no'];
            $record['filter']['_bill_no']['text'] = $_GET['_bill_no'];
        }
        if(isset($_GET['_order_no']) && !empty($_GET['_order_no'])){
            $subsql .=" AND prmm.prmm_order_no = '".$_GET['_order_no']."'";
            $record['filter']['_order_no']['value'] = $_GET['_order_no'];
            $record['filter']['_order_no']['text'] = $_GET['_order_no'];
        }
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND prmm.prmm_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND prmm.prmm_entry_date <= '".$_entry_date_to."'";
        }
        if(isset($_GET['_supplier_name']) && !empty($_GET['_supplier_name'])){
            $subsql .=" AND supplier.supplier_name = '".$_GET['_supplier_name']."'";
            $record['filter']['_supplier_name']['value'] = $_GET['_supplier_name'];
            $record['filter']['_supplier_name']['text'] = $_GET['_supplier_name'];
        } 
        $query="SELECT prmm.*, 
                UPPER(supplier.supplier_name) as supplier_name
                FROM purchase_readymade_master prmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                WHERE prmm.prmm_delete_status = 0
                AND prmm.prmm_branch_id = ".$_SESSION['user_branch_id']."
                AND prmm.prmm_fin_year = '".$_SESSION['fin_year']."'
                $subsql
                ORDER BY prmm.prmm_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['prmm_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){
        $record['prmm_entry_no'] 	= $this->get_max_entry_no(['entry_no' => 'prmm_entry_no', 'delete_status' => 'prmm_delete_status', 'fin_year' => 'prmm_fin_year']);
        $record['prmm_uuid'] 	    = $_SESSION['user_id'].''.time();
        $record['cost_char'] 	= $this->db_operations->get_record('cost_char_master', ['cost_char_id' => 1]);
        return $record;
    }
    public function get_supplier_state($id){
        $query="SELECT supplier.supplier_state_id as state_id
                FROM supplier_master supplier
                WHERE supplier.supplier_id = $id";
        return $this->db->query($query)->result_array();
    }
    public function generate_barcode(){
        $year   = date('Y');
        $month  = date('m');
        $query  = "SELECT brmm.brmm_counter as counter 
                    FROM barcode_readymade_master brmm 
                    WHERE brmm.brmm_barcode_year = '$year' 
                    AND brmm.brmm_barcode_month = '$month'
                    ORDER BY brmm.brmm_counter DESC
                    LIMIT 1";
        // echo "<pre>"; print_r($query); exit;
        $data = $this->db->query($query)->result_array();
        return empty($data[0]['counter']) ? 10000001 : ($data[0]['counter'] + 1);
    }
    public function get_data_for_edit($id){
        $query="SELECT prmm.*,
                UPPER(supplier.supplier_name) as supplier_name
                FROM purchase_readymade_master prmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                WHERE prmm.prmm_id = $id
                AND prmm.prmm_delete_status = 0";
        $record['master_data'] = $this->db->query($query)->result_array();
        if(!empty($record['master_data'])){
            $record['master_data'][0]['isExist'] = $this->isExist($id);
        }
        $record['cost_char'] 	= $this->db_operations->get_record('cost_char_master', ['cost_char_id' => 1]);
        return $record;
    }
    public function get_transaction($prmm_id){
        $query="SELECT prmt.*,
                IFNULL(UPPER(sku.sku_name), '') as sku_name,
                IFNULL(UPPER(size.size_name), '') as size_name
                FROM purchase_readymade_trans prmt
                LEFT JOIN sku_master sku ON(sku.sku_id = prmt.prmt_sku_id)
                LEFT JOIN size_master size ON(size.size_id = prmt.prmt_size_id)
                WHERE prmt.prmt_prmm_id = $prmm_id
                AND prmt.prmt_delete_status = 0
                ORDER BY prmt.prmt_id DESC";
        $record = $this->db->query($query)->result_array();
        if(!empty($record)){
            foreach ($record as $key => $value) {
                $record[$key]['isExist'] 		= $this->isTransExist($value['prmt_id']);
                $record[$key]['encrypt_prmt_id'] 	= encrypt_decrypt("encrypt", $value['prmt_id'], SECRET_KEY);
            }
        }
        return $record;
    }
    public function get_data_for_qrcode_print($clause, $_id){ 
        $rollno= ENV == PROD ? 'brmm.brmm_roll_no' : 0;
        $query ="SELECT 
                sku.sku_id,
                UPPER(sku.sku_name) as sku_name,
                UPPER(brmm.brmm_description) as description,
                brmm.brmm_roll_no as qrcode,
                brmm.brmm_prmt_qty as qty, 
                brmm.brmm_mrp as mrp, 
                sku.sku_offer_price as offer_price, 
                sku.sku_cp as sku_cp, 
                brmm.brmm_prmt_rate as rate,
                -- CONCAT('R', '', brmm.brmm_cost_char) as cost_char,
                brmm.brmm_cost_char as cost_char,
                $rollno as roll_no
                FROM barcode_readymade_master brmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = brmm.brmm_supplier_id)
                LEFT JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
                WHERE brmm.brmm_delete_status = 0 
                AND ".$clause." = $_id";
        // echo "<pre>"; print_r($query); exit();
        $data['barcode_data'] = $this->db->query($query)->result_array();
        $data['company_data'] = $this->db_operations->get_record('company_master', ['company_id' => 1]);
        // echo "<pre>"; print_r($data); exit();

        return $data;
    }
    public function get_data_for_print($prmm_id){
        $query="SELECT 
                prmm.*,
                prmm.prmm_entry_no as entry_no, 
                DATE_FORMAT(prmm.prmm_entry_date, '%d-%m-%Y') as entry_date,
                DATE_FORMAT(prmm.prmm_bill_date, '%d-%m-%Y') as bill_date,
                prmm.prmm_bill_no as bill_no,
                prmm.prmm_notes as notes,
                prmm.prmm_total_qty as total_qty,
                prmm.prmm_total_amt as total_amt,
                 IFNULL(UPPER(user.user_name),'') as user_name,
                UPPER(supplier.supplier_name) as supplier_name,
                UPPER(supplier.supplier_address) as supplier_address
                FROM purchase_readymade_master prmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                LEFT JOIN user_master user ON(user.user_id = prmm.prmm_created_by)
                WHERE prmm.prmm_delete_status = 0 
                AND prmm.prmm_id = $prmm_id";
        // echo "<pre>"; print_r($query); exit();
        $record['master_data'] = $this->db->query($query)->result_array();

        $query="SELECT 
                IFNULL(UPPER(sku.sku_name), '') as sku_name,
                IFNULL(UPPER(size.size_name), '') as size_name,
                SUM(prmt.prmt_qty) as qty, 
                prmt.prmt_rate as rate,
                SUM(prmt.prmt_amt) as amt
                FROM purchase_readymade_trans prmt
                LEFT JOIN sku_master sku ON(sku.sku_id = prmt.prmt_sku_id)
                LEFT JOIN size_master size ON(size.size_id = prmt.prmt_size_id)
                WHERE prmt.prmt_delete_status = 0 
                AND prmt.prmt_prmm_id = $prmm_id
                GROUP BY sku.sku_id, prmt.prmt_rate
                ORDER BY sku.sku_name, prmt.prmt_rate ASC";
        // echo "<pre>"; print_r($query); exit();
        $record['trans_data'] = $this->db->query($query)->result_array();
         $record['company_data'] = $this->db_operations->get_record('company_master', ['company_id' => 1]);
        // echo "<pre>"; print_r($record); exit();

        return $record;
    }
    public function get_name($term, $id){
        $query="SELECT UPPER(".$term."_name) as name FROM ".$term."_master WHERE ".$term."_id = $id";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? '' : $data[0]['name'];
    }
    public function get_barcode_qty($prmt_id){
        $data = $this->db->query("SELECT SUM(brmm_prmt_qty) as qty FROM barcode_readymade_master WHERE brmm_delete_status = false AND brmm_prmt_id = $prmt_id ")->result_array();
        if(!empty($data)){
            $qty = $data[0]["qty"];
        }else{
            $qty = 0;
        }
        return $qty;
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
            $subsql .= " AND (prmm.prmm_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT prmm.prmm_entry_no as id, UPPER(prmm.prmm_entry_no) as name
                FROM purchase_readymade_master prmm
                WHERE 1
                $subsql
                GROUP BY prmm.prmm_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _bill_no(){
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
            $subsql .= " AND (prmm.prmm_bill_no LIKE '%".$name."%') ";
        }
        $query="SELECT prmm.prmm_bill_no as id, UPPER(prmm.prmm_bill_no) as name
                FROM purchase_readymade_master prmm
                WHERE 1
                $subsql
                GROUP BY prmm.prmm_bill_no ASC
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
            $subsql .= " AND (supplier.supplier_name LIKE '%".$name."%') ";
        }
        $query="SELECT supplier.supplier_name as id, UPPER(supplier.supplier_name) as name
                FROM purchase_readymade_master prmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                WHERE 1
                $subsql
                GROUP BY supplier.supplier_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }


    public function get_data_for_barcode_print($_id){
        $rollno= ENV == PROD ? 'brmm.brmm_roll_no' : 0;
        $query ="SELECT  
                sku.sku_id,
                UPPER(sku.sku_name) as sku_name,
                UPPER(brmm.brmm_description) as description,
                brmm.brmm_roll_no as qrcode, 
                brmm.brmm_mrp as mrp, 
                brmm.brmm_prmt_rate as rate,
                CONCAT('R', '', brmm.brmm_cost_char) as cost_char,
                $rollno as roll_no
                FROM barcode_readymade_master brmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = brmm.brmm_supplier_id)
                INNER JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
                WHERE brmm.brmm_delete_status = 0 
                AND brmm.brmm_prmm_id = $_id";
        // echo "<pre>"; print_r($query); exit();
        $data['barcode_data'] = $this->db->query($query)->result_array();
       
      // echo "<pre>"; print_r($data); exit();
        return $data;
    }

    public function get_data_from_sku($sku_id)
    {
        $this->db->select('s.sku_mrp, s.sku_cp');
        $this->db->from('sku_master s');
        $this->db->where('s.sku_id', $sku_id);

        $query = $this->db->get();

        return $query->row_array();
    }
    public function check_duplicate($name) {
        return $this->db
            ->where('sku_name', $name)
            ->get($this->table)
            ->row();
    }
    public function _sku_id(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;

        // STOP if supplier not selected
        if(!isset($_GET['param1']) || empty($_GET['param1'])){
            return [];   // return empty array
        }

        $supplier_id = $_GET['param1'];
        $subsql .= " AND (sku.sku_supplier_id = $supplier_id) ";

        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }

        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }

        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name = $_GET['name'];
            $subsql .= " AND (sku.sku_name LIKE '".$name."%') ";
        }

        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param = $_GET['param'];
            $subsql .= " AND (sku.sku_status = $param) ";
        }

        $query="SELECT 
                sku.sku_id as id, 
                UPPER(sku.sku_name) as name
                FROM sku_master sku
                WHERE sku.sku_delete_status = 0
                $subsql
                GROUP BY sku.sku_id 
                ORDER BY sku.sku_name ASC
                LIMIT $limit
                OFFSET $offset";

        return $this->db->query($query)->result_array();
    }


}?>