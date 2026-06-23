<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class purchase_readymade_return_model extends my_model{
    public function __construct(){ parent::__construct('transaction', 'purchase_readymade_return'); }
    public function isExist($id){
        // $data = $this->db->query("SELECT bm_id FROM barcode_readymade_master WHERE bm_pm_id = $id AND (bm_ot_mtr) > 0 LIMIT 1")->result_array();
        // if(!empty($data)) return true;

        return false;
    }
    public function isTransExist($id){
        // $data = $this->db->query("SELECT bm_id FROM barcode_readymade_master WHERE bm_prt_id = $id AND (bm_ot_mtr) > 0 LIMIT 1")->result_array();
        // if(!empty($data)) return true;

        return false;
    }
    public function isBarcodeExist($id){
        // $data = $this->db->query("SELECT bm_id FROM barcode_readymade_master WHERE bm_id = $id AND (bm_ot_mtr) > 0 LIMIT 1")->result_array();
        // if(!empty($data)) return true;
        
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
            $subsql .=" AND prrm.prrm_entry_no = '".$_GET['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
            $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
        }
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND prrm.prrm_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND prrm.prrm_entry_date <= '".$_entry_date_to."'";
        }
        if(isset($_GET['_supplier_name']) && !empty($_GET['_supplier_name'])){
            $subsql .=" AND supplier.supplier_name = '".$_GET['_supplier_name']."'";
            $record['filter']['_supplier_name']['value'] = $_GET['_supplier_name'];
            $record['filter']['_supplier_name']['text'] = $_GET['_supplier_name'];
        }
        $query="SELECT prrm.*,
                UPPER(supplier.supplier_name) as supplier_name
                FROM purchase_readymade_return_master  prrm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prrm.prrm_supplier_id)
                WHERE prrm.prrm_delete_status = 0
                AND prrm.prrm_branch_id = ".$_SESSION['user_branch_id']."
                $subsql
                ORDER BY prrm.prrm_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['prrm_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){
        $record['prrm_entry_no'] = $this->get_max_entry_no(['entry_no' => 'prrm_entry_no', 'delete_status' => 'prrm_delete_status', 'fin_year' => 'prrm_fin_year']);
        $record['prrm_uuid'] 	= $_SESSION['user_id'].''.time();
        return $record;
    }
    public function get_data_for_edit($id){
        $query="SELECT prrm.*,
                UPPER(supplier.supplier_name) as supplier_name
                FROM purchase_readymade_return_master prrm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prrm.prrm_supplier_id)
                WHERE prrm.prrm_id = $id
                AND prrm.prrm_delete_status = 0";
        $record['master_data'] = $this->db->query($query)->result_array();
        if(!empty($record['master_data'])){
            $record['master_data'][0]['isExist'] = $this->isExist($id);
        }
        return $record;
    }
    public function get_transaction($prrm_id){ 
        $query="SELECT prrt.*,
                brmm.brmm_item_code as qrcode,
                prmm.prmm_bill_no as bill_no,
                prmm.prmm_bill_date as bill_date,
                UPPER(sku.sku_name) as sku_name
                FROM purchase_readymade_return_trans prrt
                INNER JOIN barcode_readymade_master brmm ON(brmm.brmm_id = prrt.prrt_brmm_id)
                INNER JOIN purchase_readymade_master prmm ON(prmm.prmm_id = brmm.brmm_prmm_id)
                INNER JOIN purchase_readymade_trans prmt ON(prmt.prmt_id = brmm.brmm_prmt_id)
                INNER JOIN sku_master sku ON(sku.sku_id = prmt.prmt_sku_id)
                WHERE prrt.prrt_prrm_id = $prrm_id
                AND prrt.prrt_delete_status = 0
                ORDER BY prrt.prrt_id DESC";
        $record = $this->db->query($query)->result_array();
        if(!empty($record)){
            foreach ($record as $key => $value) {
                $record[$key]['isExist'] = $this->isTransExist($value['prrt_id']);
            }
        }
        return $record;
    }
    public function get_data_for_qrcode_print($clause, $_id){
        $rollno= ENV == PROD ? 'brmm.brmm_roll_no' : 0;
        $query ="SELECT 
                UPPER(sku.sku_name) as sku_name,
                UPPER(brmm.brmm_description) as description,
                brmm.brmm_roll_no as qrcode, 
                brmm.brmm_prt_mtr as mtr, 
                brmm.brmm_mrp as mrp, 
                brmm.brmm_prt_rate as rate,
                CONCAT('R', '', brmm.brmm_cost_char) as cost_char,
                $rollno as roll_no
                FROM barcode_readymade_master bm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = brmm.brmm_supplier_id)
                INNER JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
                WHERE brmm.brmm_delete_status = 0 
                AND ".$clause." = $_id";
        // echo "<pre>"; print_r($query); exit();
        $data['barcode_data'] = $this->db->query($query)->result_array();
        $data['company_data'] = $this->db_operations->get_record('company_master', ['company_id' => 1]);
        return $data;
    }
    public function get_name($term, $id){
        $query="SELECT UPPER(".$term."_name) as name FROM ".$term."_master WHERE ".$term."_id = $id";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? '' : $data[0]['name'];
    }
    public function get_barcode_data($id){
		$query="SELECT 
                0 as prrt_id,
                brmm.brmm_prmm_id as prrt_prmm_id,
                brmm.brmm_prmt_id as prrt_prmt_id,
                brmm.brmm_id as prrt_brmm_id,
                brmm.brmm_item_code as qrcode,
                brmm.brmm_delete_status as delete_status,
                brmm.brmm_supplier_id as supplier_id,
                prmm.prmm_bill_no as bill_no,
                prmm.prmm_bill_date as bill_date,
                1 as prrt_qty,
                brmm.brmm_prmt_rate as prrt_rate,
                ((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) - (brmm.brmm_ot_qty + brmm.brmm_et_qty)) as bal_qty,
                UPPER(supplier.supplier_name) as supplier_name,
                UPPER(sku.sku_name) as sku_name,
                0 as prrt_amt,
                0 as prrt_taxable_amt,
                0 as prrt_total_amt,
                brmm.brmm_prrt_qty,
                brmm.brmm_id as prrt_brmm_id
				FROM barcode_readymade_master brmm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = brmm.brmm_supplier_id)
                INNER JOIN purchase_readymade_master prmm ON(prmm.prmm_id = brmm.brmm_prmm_id)
                INNER JOIN purchase_readymade_trans prmt ON(prmt.prmt_id = brmm.brmm_prmt_id)
                INNER JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
                
				WHERE brmm.brmm_id = $id";
		$data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit;
        // echo "<pre>"; print_r($data); exit;
        return $data;
	}
    public function get_supplier_state($id){
        $query="SELECT supplier.supplier_state_id as state_id
                FROM supplier_master supplier
                WHERE supplier.supplier_id = $id";
        $supplier_data = $this->db->query($query)->result_array();
        if(empty($supplier_data)) return 0;

        $state_data = $this->model->get_state();
        if(empty($state_data)) return 0;
        return ($state_data[0]['state_id'] == $supplier_data[0]['state_id']) ? 0 : 1;
    }
    public function _brmm_id(){
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
            $subsql .= " AND (brmm.brmm_item_code LIKE '".$name."%' OR sku.sku_name LIKE '".$name."%')";
        }else{
            if(ENV != DEV){
                $subsql .= " AND (brmm.brmm_item_code = 'XXX') ";
            }
        }
		$query="SELECT brmm.brmm_id as id, 
				CONCAT(brmm.brmm_item_code, ' - ', UPPER(sku.sku_name)) as name
				FROM barcode_readymade_master brmm
                LEFT JOIN sku_master sku ON(sku.sku_id = brmm.brmm_sku_id)
				WHERE 1
				$subsql
				GROUP BY brmm.brmm_id
				ORDER BY brmm.brmm_item_code ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
    public function _entry_no(){
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
			$subsql .= " AND (prrm.prrm_entry_no LIKE '%".$name."%') ";
		}
		$query="SELECT prrm.prrm_entry_no as id, UPPER(prrm.prrm_entry_no) as name
				FROM purchase_readymade_return_master prrm
				WHERE 1
				$subsql
				GROUP BY prrm.prrm_entry_no ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
    public function _supplier_name(){
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
			$subsql .= " AND (supplier.supplier_name LIKE '%".$name."%') ";
		}
		$query="SELECT supplier.supplier_name as id, UPPER(supplier.supplier_name) as name
				FROM purchase_readymade_return_master prrm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = prrm.prrm_supplier_id)
				WHERE 1
				$subsql
				GROUP BY supplier.supplier_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
}
?>