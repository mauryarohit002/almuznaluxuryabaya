<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class Purchase_readymade extends my_controller{ 
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'transaction'; 
        $this->sub_menu = 'purchase_readymade'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function get_supplier_data(){
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        $data 		= 0;
		$supplier_data = $this->model->get_supplier_state($id);
        if(!empty($supplier_data) && ($supplier_data[0]['state_id'] != 0)){
			$state_data = $this->model->get_state();
			if(!empty($state_data)){
				// echo "<pre>"; print_r($state_data);die;
				$data = ($state_data[0]['state_id'] == $supplier_data[0]['state_id']) ? 0 : 1;
			}
		}
        return['status' => TRUE, 'data' => $data, 'msg' => 'Supplier fetched successfully.'];
	}
    public function remove(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$data = $this->db_operations->get_record('purchase_readymade_master', ['prmm_id' => $id, 'prmm_delete_status' => false]);
		if(empty($data)) return ['status' => REFRESH, 'msg' => '3. Purchase not found.'];	

		if($this->model->isExist($id)) return ['msg' => '1. Not allowed to delete.'];	

		$trans_data = $this->db_operations->get_record('purchase_readymade_trans', ['prmt_prmm_id' => $id, 'prmt_delete_status' => false]);
		if(empty($trans_data)) return ['msg' => '2. Transaction not found.'];
		
		$this->db->trans_begin();
		
		foreach ($trans_data as $key => $value) {
			if($this->model->isTransExist($value['prmt_id'])){
				$this->db->trans_rollback();
				return ['msg' => '2. Not allowed to delete transaction.'];
			}
			$result = $this->delete_barcode($value['prmt_id']);
			if(!isset($result['status'])) return $result;

			$update_data 						= [];
			$update_data['prmt_delete_status'] 	= true; 
			$update_data['prmt_updated_by'] 	= $_SESSION['user_id']; 
			$update_data['prmt_updated_at'] 	= date('Y-m-d H:i:s'); 
			if($this->db_operations->data_update('purchase_readymade_trans', $update_data, 'prmt_id', $value['prmt_id']) < 1){
				$this->db->trans_rollback();
				return ['msg' => '2. Transaction not deleted.'];
			}
		}

		$update_data 						= [];
		$update_data['prmm_entry_no'] 		= $data[0]['prmm_entry_no'].''.$id; 
		$update_data['prmm_delete_status'] 	= true; 
		$update_data['prmm_updated_by'] 	= $_SESSION['user_id']; 
		$update_data['prmm_updated_at'] 	= date('Y-m-d H:i:s'); 
		if($this->db_operations->data_update('purchase_readymade_master', $update_data, 'prmm_id', $id) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Purchase not deleted.'];
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return ['msg' => '2. Transaction Rollback.'];
		}
		$this->db->trans_commit();

		return ['status' => TRUE, 'msg' => 'Purchase deleted successfully'];
	}
    // purchase_readymade_master
        public function add_edit(){
			$post_data  = $this->input->post();
            $id         = $post_data['id'];
			if(!isset($post_data['trans_data']) || (isset($post_data['trans_data']) && empty($post_data['trans_data']))) return ['msg' => '1. Item not aded in list.'];
			$post_data['trans_data'] = json_decode($post_data['trans_data'], true);

			// master_data
                $master_data['prmm_uuid'] 				    = trim($post_data['prmm_uuid']);
                $master_data['prmm_entry_no'] 				= trim($post_data['prmm_entry_no']);
                $master_data['prmm_entry_date'] 			= date('Y-m-d', strtotime($post_data['prmm_entry_date']));
                $master_data['prmm_bill_no'] 				= trim($post_data['prmm_bill_no']);
                $master_data['prmm_bill_date'] 				= date('Y-m-d', strtotime($post_data['prmm_bill_date']));
                $master_data['prmm_supplier_id'] 			= trim($post_data['prmm_supplier_id']);
                $master_data['prmm_total_qty'] 				= trim($post_data['prmm_total_qty']);
                $master_data['prmm_notes'] 					= trim($post_data['prmm_notes']);
                $master_data['prmm_sub_amt'] 				= trim($post_data['prmm_sub_amt']);
                $master_data['prmm_taxable_amt'] 			= trim($post_data['prmm_taxable_amt']);
                $master_data['prmm_extra_amt'] 				= trim($post_data['prmm_extra_amt']);
                $master_data['prmm_total_amt']				= trim($post_data['prmm_total_amt']);
                $master_data['prmm_updated_by'] 			= $_SESSION['user_id'];
            // master_data

            $temp = $this->db_operations->get_record($this->sub_menu.'_master', ['prmm_id !=' => $id, 'prmm_bill_no' => $master_data['prmm_bill_no'],'prmm_supplier_id'=>$master_data['prmm_supplier_id'], 'prmm_delete_status' => false]);
			if(!empty($temp)) return ['msg' => 'Bill no already exists.'];

			$this->db->trans_begin();
			if($id == 0){
				$master_data['prmm_entry_no'] 	= $this->model->get_max_entry_no(['entry_no' => 'prmm_entry_no', 'delete_status' => 'prmm_delete_status', 'fin_year' => 'prmm_fin_year']);
				$master_data['prmm_created_by'] = $_SESSION['user_id'];
				$master_data['prmm_created_at'] = date('Y-m-d H:i:s');
				$master_data['prmm_fin_year'] 	= $_SESSION['fin_year'];
				$master_data['prmm_branch_id'] 	= $_SESSION['user_branch_id'];
				$uuidExist 						= $this->db_operations->get_cnt($this->sub_menu.'_master', ['prmm_uuid' => $master_data['prmm_uuid']]);
				if($uuidExist > 0){
					$this->db->trans_rollback();
					return ['msg' => 'Form already submited.'];
				}
				$id = $this->db_operations->data_insert('purchase_readymade_master', $master_data);
				$msg = 'Purchase added successfully.';
				if($id < 1){
					$this->db->trans_rollback();
					return ['msg' => '1. Purchase not added.'];
				}
			}else{
				$prev_data = $this->db_operations->get_record('purchase_readymade_master', ['prmm_id' => $id, 'prmm_delete_status' => false]);
				if(empty($prev_data)){
					$this->db->trans_rollback();
					return ['status' => REFRESH, 'msg' => '1. Purchase not found.'];
				}
				$msg = 'Purchase updated successfully.';
				if($this->db_operations->data_update('purchase_readymade_master', $master_data, 'prmm_id', $id) < 1){
					$this->db->trans_rollback();
					return ['msg' => '1. Purchase not updated.'];
				}
			}
			$result = $this->add_update_trans($post_data, $id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				return $result;
			}

			if ($this->db->trans_status() === FALSE){
			    $this->db->trans_rollback();
			    return ['msg' => '1. Transaction Rollback.'];
		    }
		    $this->db->trans_commit();

			$data['id'] 	= encrypt_decrypt("encrypt", $id, SECRET_KEY);
			$data['name'] 	= strtoupper($master_data['prmm_entry_no']);
			return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
		}
    // purchase_readymade_master

    // purchase_readymade_trans
        public function add_transaction(){
            $post_data  = $this->input->post();
            $id         = $post_data['id'];
            
			if(!isset($post_data['sku_id']) || (isset($post_data['sku_id']) && empty($post_data['sku_id']))) return ['msg' => '1. SKU is required.'];

			if(!isset($post_data['qty']) || (isset($post_data['qty']) && empty($post_data['qty']))){
                return ['msg' => '1. Qty is required.'];
            }else{
                if($post_data['qty'] <= 0) return ['msg' => '1. Invalid Qty.'];
            }
            if(!isset($post_data['rate']) || (isset($post_data['rate']) && empty($post_data['rate']))){
                return ['msg' => '1. Rate is required.'];
            }else{
                if($post_data['rate'] <= 0) return ['msg' => '1. Invalid Rate.'];	
            }
            
            $trans_data 					= [];
            $trans_data['prmt_sku_id'] 	= isset($post_data['sku_id']) ? $post_data['sku_id'] : 0;

			$trans_data['prmt_size_id'] 	= isset($post_data['size_id']) ? $post_data['size_id'] : 0;
           
            $trans_data['prmt_cost_char'] 	= trim($post_data['cost_char']);
            $trans_data['prmt_mrp'] 		= trim($post_data['mrp']);
            $trans_data['prmt_qty'] 		= trim($post_data['qty']);
            $trans_data['prmt_rate'] 		= trim($post_data['rate']);
            $trans_data['prmt_amt'] 		= trim($post_data['amt']);
            $trans_data['prmt_taxable_amt'] = trim($post_data['taxable_amt']);
            $trans_data['prmt_extra_amt'] 	= trim($post_data['extra_amt']);
            $trans_data['prmt_actual_taxable_amt'] 	= trim($post_data['actual_taxable_amt']);
            $trans_data['prmt_total_amt'] 	= trim($post_data['total_amt']);
            $trans_data['prmt_description'] = trim($post_data['description']);
            $trans_data['prmt_created_by'] 	= $_SESSION['user_id'];
            $trans_data['prmt_updated_by'] 	= $_SESSION['user_id'];
            $trans_data['prmt_created_at'] 	= date('Y-m-d H:i:s');
            $trans_data['prmt_updated_at'] 	= date('Y-m-d H:i:s');
			
			if(empty($post_data['prmt_id'])){
				$trans_data['prmt_id'] = $this->db_operations->data_insert('purchase_readymade_trans', $trans_data);
				if($trans_data['prmt_id'] < 1) return ['msg' => '1. Purchase Transaction not added.'];
				$trans_data['isExist'] = false;
			}else{
				$trans_data['prmt_id'] = $post_data['prmt_id'];
			}
			
            $trans_data['encrypt_prmt_id']= encrypt_decrypt("encrypt", $trans_data['prmt_id'], SECRET_KEY);
            $trans_data['sku_name'] 	= $this->model->get_name('sku', $trans_data['prmt_sku_id']);
			$trans_data['size_name'] 	= $this->model->get_name('size', $trans_data['prmt_size_id']);
			
            return ['status' => TRUE, 'data' => $trans_data,  'msg' => 'Purchase Transaction added successfully.'];
        }
        public function add_update_trans($post_data, $id){
			$trans_db_data = $this->db_operations->get_record('purchase_readymade_trans', ['prmt_prmm_id' => $id, 'prmt_delete_status' => false]);
			$ids 	   	   = $this->get_id($post_data);
			if(!empty($trans_db_data)){
				foreach ($trans_db_data as $key => $value){
					if(!in_array($value['prmt_id'], $ids)){
						if($this->model->isTransExist($value['prmt_id'])) return ['msg' => '1. Not allowed to delete transaction.'];
						$result = $this->delete_barcode($value['prmt_id']);
						if(!isset($result['status'])) return $result;
	
						$update_data 						= [];
						$update_data['prmt_delete_status'] 	= true; 
						$update_data['prmt_updated_by'] 		= $_SESSION['user_id']; 
						$update_data['prmt_updated_at'] 		= date('Y-m-d H:i:s'); 
						if($this->db_operations->data_update('purchase_readymade_trans', $update_data, 'prmt_id', $value['prmt_id']) < 1) return ['msg' => '1. Transaction not deleted.'];
					}
				}
			}
			foreach ($post_data['trans_data'] as $key => $value){
				if($value['prmt_id'] != 0){
					$prev_data = $this->db_operations->get_record('purchase_readymade_trans', ['prmt_id' => $value['prmt_id'], 'prmt_delete_status' => false]);
					if(empty($prev_data)) return ['msg' => '1. Transaction not found.'];

					$trans_data 					= [];
					$trans_data['prmt_prmm_id'] 	= $id;
					$trans_data['prmt_prmm_uuid'] 	= $post_data['prmm_uuid'];
					$trans_data['prmt_sku_id'] 	= isset($value['prmt_sku_id']) ? $value['prmt_sku_id'] : 0;
					$trans_data['prmt_size_id'] 	= isset($value['prmt_size_id']) ? $value['prmt_size_id'] : 0;
					
					$trans_data['prmt_cost_char'] 	= trim($value['prmt_cost_char']);
					$trans_data['prmt_mrp'] 		= trim($value['prmt_mrp']);
					$trans_data['prmt_qty'] 		= trim($value['prmt_qty']);
					$trans_data['prmt_rate'] 		= trim($value['prmt_rate']);
					$trans_data['prmt_amt'] 		= trim($value['prmt_amt']);
					
					$trans_data['prmt_taxable_amt'] = trim($value['prmt_taxable_amt']);
					$trans_data['prmt_extra_amt'] 	= trim($value['prmt_extra_amt']);
					$trans_data['prmt_actual_taxable_amt'] 	= trim($value['prmt_actual_taxable_amt']);
					
					$trans_data['prmt_total_amt'] 	= trim($value['prmt_total_amt']);
					$trans_data['prmt_description'] = trim($value['prmt_description']);
					$trans_data['prmt_updated_by'] 	= $_SESSION['user_id'];
					$trans_data['prmt_updated_at'] 	= date('Y-m-d H:i:s');
					if($this->db_operations->data_update('purchase_readymade_trans', $trans_data, 'prmt_id', $value['prmt_id']) < 1){
						return ['msg' => 'Transaction not updated.'];
					}
					$trans_data['prmm_supplier_id'] = isset($post_data['prmm_supplier_id']) ? $post_data['prmm_supplier_id'] : 0;
					if(empty($prev_data[0]['prmt_prmm_id'])){  
						$result = $this->add_barcode($id, $value['prmt_id'], $trans_data);
						if(!isset($result['status'])) return $result;
					}else{
						$result = $this->update_barcode($id, $value['prmt_id'], $trans_data);
						if(!isset($result['status'])) return $result;
					}
				}
			}
			return ['status' => TRUE];
		}
		public function get_id($post_data){
			$record = [];
			foreach ($post_data['trans_data'] as $key => $value) {
				array_push($record, $value['prmt_id']);
			}
			return $record;
		}
    // purchase_readymade_trans

    // barcode_master
        public function add_barcode($prmm_id, $prmt_id, $trans_data){  
        	$prmt_qty = $trans_data['prmt_qty'];
        	$brmm_prmt_qty = 1;
        	$trans_data['prmt_qty'] = $prmt_qty;

			for ($i = 1; $i <= $prmt_qty ; $i++) {   
				$year  									= date('y')+70;
				$month 									= date('m');
				$barcode_master 						= [];	
				$barcode_master['brmm_barcode_year'] 	= date('Y');
				$barcode_master['brmm_barcode_month'] 	= $month;
				$barcode_master['brmm_counter']			= $this->model->generate_barcode();
				$barcode_master['brmm_item_code'] 		= $year.''.$month.''.$barcode_master['brmm_counter'];
				$barcode_master['brmm_roll_no'] 		= $barcode_master['brmm_item_code'];
				$barcode_master['brmm_prmm_id']			= $prmm_id;
				$barcode_master['brmm_prmt_id']			= $prmt_id;
				$barcode_master['brmm_supplier_id']		= $trans_data['prmm_supplier_id'];
				$barcode_master['brmm_sku_id']			= $trans_data['prmt_sku_id'];
				$barcode_master['brmm_size_id']			= $trans_data['prmt_size_id'];

				$barcode_master['brmm_cost_char']		= $trans_data['prmt_cost_char'];
				$barcode_master['brmm_description']		= $trans_data['prmt_description'];
				$barcode_master['brmm_prmt_qty']		= $brmm_prmt_qty;
				$barcode_master['brmm_mrp']			    = $trans_data['prmt_mrp'];
				$barcode_master['brmm_prmt_rate']		= $trans_data['prmt_rate'];
				$barcode_master['brmm_prmt_amt']		= $trans_data['prmt_rate'] * $trans_data['prmt_qty'];
				// $barcode_master['brmm_taxable_amt']	= $trans_data['prmt_taxable_amt'] / $trans_data['prmt_qty'];
				$barcode_master['brmm_taxable_amt']		= $trans_data['prmt_actual_taxable_amt'] / $trans_data['prmt_qty'];
				$barcode_master['brmm_total_amt']		= $trans_data['prmt_total_amt'] / $trans_data['prmt_qty'];
				$barcode_master['brmm_delete_status'] 	= false;
				$barcode_master['brmm_branch_id'] 		= $_SESSION['user_branch_id'];
				$barcode_master['brmm_fin_year'] 		= $_SESSION['fin_year'];
				$barcode_master['brmm_created_by'] 		= $_SESSION['user_id'];
				$barcode_master['brmm_created_at'] 		= date('Y-m-d H:i:s');
				$barcode_master['brmm_updated_by'] 		= $_SESSION['user_id'];
				$barcode_master['brmm_updated_at'] 		= date('Y-m-d H:i:s');

				if($this->db_operations->data_insert('barcode_readymade_master', $barcode_master) < 1) return ['msg' => '1. Barcode readymade not added.'];
			}
			return ['status' => TRUE];
		}
		public function update_barcode($prmm_id, $prmt_id, $trans_data){
			$prev_data = $this->db_operations->get_record('barcode_readymade_master', ['brmm_prmt_id' => $prmt_id, 'brmm_delete_status' => false]);
			if(empty($prev_data)) return ['msg' => '1. Barcode readymade not found.'];
			
			$brm_qty = $this->model->get_barcode_qty($prmt_id);
			$qty_cnt = 0;

			foreach ($prev_data as $key => $value) {
				$qty_cnt++;
				$barcode_master 						= [];	
				$barcode_master['brmm_supplier_id']		= $trans_data['prmm_supplier_id'];
				$barcode_master['brmm_sku_id']			= $trans_data['prmt_sku_id'];
				$barcode_master['brmm_size_id']			= $trans_data['prmt_size_id'];

				$barcode_master['brmm_cost_char']		= $trans_data['prmt_cost_char'];
				$barcode_master['brmm_description']		= $trans_data['prmt_description'];
				$barcode_master['brmm_mrp']			    = $trans_data['prmt_mrp'];
				$barcode_master['brmm_prmt_rate']		= $trans_data['prmt_rate'];
				$barcode_master['brmm_prmt_amt']		= $trans_data['prmt_rate'] * $trans_data['prmt_qty'];
				// $barcode_master['brnm_taxable_amt']	= $trans_data['prmt_taxable_amt'] / $trans_data['prmt_qty'];
				$barcode_master['brmm_taxable_amt']		= $trans_data['prmt_actual_taxable_amt'] / $trans_data['prmt_qty'];
				$barcode_master['brmm_total_amt']		= $trans_data['prmt_total_amt'] / $trans_data['prmt_qty'];
				$barcode_master['brmm_updated_by'] 		= $_SESSION['user_id'];
				$barcode_master['brmm_updated_at'] 		= date('Y-m-d H:i:s');
				if($qty_cnt <= $trans_data['prmt_qty']){
					if($this->db_operations->data_update('barcode_readymade_master', $barcode_master, 'brmm_id', $value['brmm_id']) < 1) return ['msg' => '1. Barcode readymade not update.'];
				}else{
					$update_data 						= [];
					$update_data['brmm_delete_status'] 	= true; 
					$update_data['brmm_updated_by'] 	= $_SESSION['user_id']; 
					$update_data['brmm_updated_at'] 	= date('Y-m-d H:i:s');

					if($this->db_operations->data_update('barcode_readymade_master', $update_data, 'brmm_id', $value['brmm_id']) < 1) return ['msg' => '1. Barcode readymade delete status not set as true'];
				}
			}
			if($trans_data['prmt_qty'] > $brm_qty){
				for($i=1; $i <= ($trans_data['prmt_qty'] - $brm_qty); $i++){
					$year  									= date('y')+70;
					$month 									= date('m');

					$barcode_master 						= [];	
					$barcode_master['brmm_barcode_year'] 		= date('Y');
					$barcode_master['brmm_barcode_month'] 	= $month;
					$barcode_master['brmm_counter']			= $this->model->generate_barcode();
					$barcode_master['brmm_item_code'] 		= $year.''.$month.''.$barcode_master['brmm_counter'];
					$barcode_master['brmm_roll_no'] 		= $barcode_master['brmm_item_code'];
					$barcode_master['brmm_prmm_id']			= $prmm_id;
					$barcode_master['brmm_prmt_id']			= $prmt_id;
					$barcode_master['brmm_supplier_id']		= $trans_data['prmm_supplier_id'];
					$barcode_master['brmm_sku_id']			= $trans_data['prmt_sku_id'];
					$barcode_master['brmm_size_id']			= $trans_data['prmt_size_id'];

					$barcode_master['brmm_cost_char']		= $trans_data['prmt_cost_char'];
					$barcode_master['brmm_description']		= $trans_data['prmt_description'];
					$barcode_master['brmm_prmt_qty']		= 1;
					$barcode_master['brmm_mrp']			    = $trans_data['prmt_mrp'];
					$barcode_master['brmm_prmt_rate']		= $trans_data['prmt_rate'];
					$barcode_master['brmm_prmt_amt']		= $trans_data['prmt_rate'] * $trans_data['prmt_qty'];
					// $barcode_master['brmm_taxable_amt']	= $trans_data['prmt_taxable_amt'] / $trans_data['prmt_qty'];
					$barcode_master['brmm_taxable_amt']		= $trans_data['prmt_actual_taxable_amt'] / $trans_data['prmt_qty'];
					$barcode_master['brmm_total_amt']		= $trans_data['prmt_total_amt'] / $trans_data['prmt_qty'];
					$barcode_master['brmm_delete_status'] 	= false;
					$barcode_master['brmm_branch_id'] 		= $_SESSION['user_branch_id'];
					$barcode_master['brmm_fin_year'] 		= $_SESSION['fin_year'];
					$barcode_master['brmm_created_by'] 		= $_SESSION['user_id'];
					$barcode_master['brmm_created_at'] 		= date('Y-m-d H:i:s');
					$barcode_master['brmm_updated_by'] 		= $_SESSION['user_id'];
					$barcode_master['brmm_updated_at'] 		= date('Y-m-d H:i:s');
					if($this->db_operations->data_insert('barcode_readymade_master', $barcode_master) < 1) return ['msg' => '1. Barcode readymade not added.'];
				}
			}
			return ['status' => TRUE];
		}
		public function delete_barcode($prmt_id){
			$data = $this->db_operations->get_record('barcode_readymade_master', ['brmm_prmt_id' => $prmt_id, 'brmm_delete_status' => false]);
			if(empty($data)) return ['msg' => '2. Barcode readymade not found.'];

			foreach ($data as $key => $value){
				if($this->model->isBarcodeExist($value['brmm_id'])) return ['msg' => '2. Not allowed to delete barcode readymade.'];
				$update_data 						= [];
				$update_data['brmm_delete_status'] 	= true; 
				$update_data['brmm_updated_by'] 	= $_SESSION['user_id']; 
				$update_data['brmm_updated_at'] 	= date('Y-m-d H:i:s'); 
				if($this->db_operations->data_update('barcode_readymade_master', $update_data, 'brmm_id', $value['brmm_id']) < 1){
					return ['msg' => '2. Barcode readymade delete status not set as true'];
				}
			}
			return ['status' => TRUE];
		}
    // barcode_master

		public function get_data_from_sku()
        {
            $sku_id = $this->input->post('sku_id');

            if (empty($sku_id)) {
                echo json_encode([]);
                return;
            }

            $data = $this->model->get_data_from_sku($sku_id);

            if (!empty($data)) {
                echo json_encode([
                    'sku_mrp'       => $data['sku_mrp'],
                    'sku_cp'        => $data['sku_cp']
                ]);
            } else {
                echo json_encode([]);
            }
        }

		public function save_sku()
		{
			if (!$this->input->is_ajax_request()) {
				show_404();
			}

			$post = $this->input->post(NULL, TRUE);
				// echo "<pre>"; print_r($post);die;

			// Required validation
			if (empty($post['sku_name'])) {
				echo json_encode([
					'status' => 0,
					'message' => 'SKU Name is required'
				]);
				exit;
			}

			if (empty($post['sku_apparel_id'])) {
				echo json_encode([
					'status' => 0,
					'message' => 'Apparel is required'
				]);
				exit;
			}

			if (empty($post['sku_supplier_id'])) {
				echo json_encode([
					'status' => 0,
					'message' => 'Supplier is required'
				]);
				exit;
			}

			// Duplicate check (only by name)
			$duplicate = $this->db
				->where('sku_name', $post['sku_name'])
				->get('sku_master')
				->row();

			if ($duplicate) {
				echo json_encode([
					'status' => 0,
					'message' => 'SKU already exists'
				]);
				exit;
			}

			// Insert Data (Exact Fields)
			$insert_data = array(
				'sku_uuid'   	   => $_SESSION['user_id'].''.time(),
				'sku_apparel_id'   => $post['sku_apparel_id'],
				'sku_name'         => strtoupper($post['sku_name']),
				'sku_supplier_id'  => $post['sku_supplier_id'],

				'sku_fabric'       => isset($post['sku_fabric']) ? $post['sku_fabric'] : 0,
				'sku_cutting'      => isset($post['sku_cutting']) ? $post['sku_cutting'] : 0,
				'sku_silai'        => isset($post['sku_silai']) ? $post['sku_silai'] : 0,
				'sku_stone'        => isset($post['sku_stone']) ? $post['sku_stone'] : 0,
				'sku_lagwayi'      => isset($post['sku_lagwayi']) ? $post['sku_lagwayi'] : 0,
				'sku_hand_work'    => isset($post['sku_hand_work']) ? $post['sku_hand_work'] : 0,
				'sku_material'     => isset($post['sku_material']) ? $post['sku_material'] : 0,
				'sku_exp'          => isset($post['sku_exp']) ? $post['sku_exp'] : 0,
				'sku_cp'           => isset($post['sku_cp']) ? $post['sku_cp'] : 0,
				'sku_mrp'          => isset($post['sku_mrp']) ? $post['sku_mrp'] : 0,
				'sku_offer_price'  => isset($post['sku_offer_price']) ? $post['sku_offer_price'] : 0,
				'sku_last_price'   => isset($post['sku_last_price']) ? $post['sku_last_price'] : 0,
				'sku_piece'        => isset($post['sku_piece']) ? $post['sku_piece'] : 1,

				'sku_notes'        => isset($post['sku_notes']) ? $post['sku_notes'] : NULL,

				'sku_created_by'       => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL,
				'sku_created_at'       => date('Y-m-d H:i:s')
			);


			$this->db->trans_start();

			$insert_id = $this->db_operations->data_insert('sku_master', $insert_data);

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {

				echo json_encode([
					'status' => 0,
					'message' => 'Database error'
				]);

			} else {

				echo json_encode([
					'status' => 1,
					'id'     => $insert_id,
					'name'   => strtoupper($post['sku_name'])
				]);
			}
		}
    /************************************* custom stock update ************************/
        public function update_purchase_entry_based_on_sku_supplier(){
            // print_r($_SESSION);exit;
            //get entry data of having another suppler in sku  
            $purchase_data_old = $this->db->query("SELECT prm.*
                FROM purchase_readymade_master prm
                WHERE EXISTS (
                    SELECT 1
                    FROM purchase_readymade_trans prt
                    LEFT JOIN sku_master sku ON sku.sku_id = prt.prmt_sku_id
                    WHERE prt.prmt_prmm_id = prm.prmm_id
                    AND sku.sku_supplier_id != prm.prmm_supplier_id
                )
                ORDER BY prm.prmm_id ASC")->result_array();
            $this->db->trans_begin();
            if(!empty($purchase_data_old)){
                foreach($purchase_data_old as $key => $value){
                    $purchase_trans_old = $this->db->query("SELECT 
                            prt.prmt_id,
                            prt.prmt_prmm_id,
                            prt.prmt_sku_id,
                            prm.prmm_supplier_id AS master_supplier_id,
                            sku.sku_supplier_id AS sku_supplier_id
                        FROM purchase_readymade_trans prt
                        JOIN purchase_readymade_master prm ON prm.prmm_id = prt.prmt_prmm_id
                        LEFT JOIN sku_master sku ON sku.sku_id = prt.prmt_sku_id
                        WHERE prt.prmt_prmm_id = '".$value['prmm_id']."'
                        AND sku.sku_supplier_id != prm.prmm_supplier_id
                        ORDER BY sku.sku_supplier_id ASC")->result_array();
                    $supplier_id = 0;
                    $prmm_id = 0;
                    if(!empty($purchase_trans_old)){
                        foreach($purchase_trans_old as $ptkey => $ptvalue){
                            if(empty($supplier_id)){
                                $supplier_id = $ptvalue['sku_supplier_id'];
                                $purchase_data = [];
                                $purchase_data['prmm_entry_no']             = $this->model->get_max_entry_no(['table'=>'purchase_readymade_master','entry_no' => 'prmm_entry_no', 'delete_status' => 'prmm_delete_status', 'fin_year' => 'prmm_fin_year']);
                                $purchase_data['prmm_uuid'] 				= time().$ptvalue['prmt_id'];
                                $purchase_data['prmm_entry_date'] 			= date('Y-m-d');
                                $purchase_data['prmm_bill_no'] 				= "upgrade";
                                $purchase_data['prmm_bill_date'] 			= date('Y-m-d');
                                $purchase_data['prmm_supplier_id'] 			= trim($supplier_id);
                                $purchase_data['prmm_total_qty'] 			= trim(1);
                                $purchase_data['prmm_notes'] 				= "";
                                $purchase_data['prmm_sub_amt'] 				= trim(1);
                                $purchase_data['prmm_taxable_amt'] 			= trim(1);
                                $purchase_data['prmm_extra_amt'] 			= 0;
                                $purchase_data['prmm_total_amt']			= 1;
                                $purchase_data['prmm_updated_by'] 			= 1;
                                $purchase_data['prmm_created_by']           = 1;
                				$purchase_data['prmm_created_at']           = date('Y-m-d H:i:s');
                				$purchase_data['prmm_fin_year'] 	        = "2026-2027";
                				$purchase_data['prmm_branch_id'] 	        = 1;
                                
                                $prmm_id = $this->db_operations->data_insert('purchase_readymade_master',$purchase_data);
                                if($prmm_id<0){
                                    echo "1. purchase_readymade_master insert issue.";
                                    $this->db->trans_rollback();
                                    exit;
                                }
                            }
                            if($supplier_id != $ptvalue['sku_supplier_id']){
                                if(!empty($prmm_id)){
                                    $get_trans_data = $this->db->query("
                                        SELECT 
                                            SUM(prmt_qty) as total_qty,
                                            SUM(prmt_amt) as total_amt,
                                            SUM(prmt_taxable_amt) as total_taxable_amt,
                                            SUM(prmt_extra_amt) as total_extra_amt,
                                            SUM(prmt_actual_taxable_amt) as total_actual_taxable_amt,
                                            SUM(prmt_total_amt) as total_total_amt
                                        FROM purchase_readymade_trans 
                                        WHERE prmt_prmm_id = '".$prmm_id."'"
                                    )->row();
                                    if(!empty($get_trans_data)){
                                        if($this->db_operations->data_update('purchase_readymade_master', [
                                            'prmm_total_qty' => $get_trans_data->total_qty,
                                            'prmm_sub_amt' => $get_trans_data->total_amt,
                                            'prmm_taxable_amt' => $get_trans_data->total_taxable_amt,
                                            'prmm_extra_amt' => $get_trans_data->total_extra_amt,
                                            'prmm_total_amt' => $get_trans_data->total_total_amt,
                                            'prmm_round_off' => 0
                                        ],'prmm_id',$prmm_id)<1){
                                            echo "2. purchase_readymade_master update issue.";
                                            $this->db->trans_rollback();
                                            exit;
                                        }
                                    }
                                }
                                
                                $supplier_id = $ptvalue['sku_supplier_id'];
                                $purchase_data = [];
                                $purchase_data['prmm_entry_no']             = $this->model->get_max_entry_no(['table'=>'purchase_readymade_master','entry_no' => 'prmm_entry_no', 'delete_status' => 'prmm_delete_status', 'fin_year' => 'prmm_fin_year']);
                                $purchase_data['prmm_uuid'] 				= time().$ptvalue['prmt_id'];
                                $purchase_data['prmm_entry_date'] 			= date('Y-m-d');
                                $purchase_data['prmm_bill_no'] 				= "upgrade";
                                $purchase_data['prmm_bill_date'] 			= date('Y-m-d');
                                $purchase_data['prmm_supplier_id'] 			= trim($supplier_id);
                                $purchase_data['prmm_total_qty'] 			= trim(1);
                                $purchase_data['prmm_notes'] 				= "";
                                $purchase_data['prmm_sub_amt'] 				= trim(1);
                                $purchase_data['prmm_taxable_amt'] 			= trim(1);
                                $purchase_data['prmm_extra_amt'] 			= 0;
                                $purchase_data['prmm_total_amt']			= 1;
                                $purchase_data['prmm_updated_by'] 			= 1;
                                $purchase_data['prmm_created_by']           = 1;
                				$purchase_data['prmm_created_at']           = date('Y-m-d H:i:s');
                				$purchase_data['prmm_fin_year'] 	        = "2026-2027";
                				$purchase_data['prmm_branch_id'] 	        = 1;
                                
                                $prmm_id = $this->db_operations->data_insert('purchase_readymade_master',$purchase_data);
                                if($prmm_id<0){
                                    echo "3. purchase_readymade_master insert issue.";
                                    $this->db->trans_rollback();
                                    exit;
                                }
                            }
                            if($this->db_operations->data_update('purchase_readymade_trans', ['prmt_prmm_id'=>$prmm_id],'prmt_id',$ptvalue['prmt_id'])<1){
                                echo "4. purchase_readymade_trans update issue.";
                                $this->db->trans_rollback();
                                exit;
                            }
                            $barcode_data = $this->db_operations->get_record('barcode_readymade_master',['brmm_prmt_id'=>$ptvalue['prmt_id']]);
                            if(!empty($barcode_data)){
                                if($this->db_operations->data_update('barcode_readymade_master', ['brmm_prmm_id'=>$prmm_id],'brmm_prmt_id',$ptvalue['prmt_id'])<1){
                                    echo "5. barcode_readymade_master update issue.";
                                    $this->db->trans_rollback();
                                    exit;
                                }
                                foreach($barcode_data as $brkey => $brvalue){
                                    if(!empty($brvalue['brmm_outward_id'])){
                                        if($this->db_operations->data_update('outward_trans', ['ot_prmm_id'=>$prmm_id],'ot_brmm_id',$brvalue['brmm_id'])<1){
                                            echo "6. outward_trans update issue.";
                                            $this->db->trans_rollback();
                                            exit;
                                        }       
                                    }
                                    if(!empty($brvalue['brmm_gt_id'])){
                                        if($this->db_operations->data_update('grn_trans', ['gt_prmm_id'=>$prmm_id],'gt_brmm_id',$brvalue['brmm_id'])<1){
                                            echo "7. grn_trans update issue.";
                                            $this->db->trans_rollback();
                                            exit;
                                        }       
                                    }
                                    if((float)$brvalue['brmm_ot_qty'] > 0){
                                        if($this->db_operations->data_update('order_trans', ['ot_prmm_id'=>$prmm_id],'ot_brmm_id',$brvalue['brmm_id'])<1){
                                            echo "8. order_trans update issue.";
                                            $this->db->trans_rollback();
                                            exit;
                                        }       
                                    }
                                }
                            }
                        }
                        if(!empty($prmm_id)){
                            $get_trans_data1 = $this->db->query("
                                SELECT 
                                    SUM(prmt_qty) as total_qty,
                                    SUM(prmt_amt) as total_amt,
                                    SUM(prmt_taxable_amt) as total_taxable_amt,
                                    SUM(prmt_extra_amt) as total_extra_amt,
                                    SUM(prmt_actual_taxable_amt) as total_actual_taxable_amt,
                                    SUM(prmt_total_amt) as total_total_amt
                                FROM purchase_readymade_trans 
                                WHERE prmt_prmm_id = '".$prmm_id."'"
                            )->row();
                            if(!empty($get_trans_data1)){
                                if($this->db_operations->data_update('purchase_readymade_master', [
                                    'prmm_total_qty' => $get_trans_data1->total_qty,
                                    'prmm_sub_amt' => $get_trans_data1->total_amt,
                                    'prmm_taxable_amt' => $get_trans_data1->total_taxable_amt,
                                    'prmm_extra_amt' => $get_trans_data1->total_extra_amt,
                                    'prmm_total_amt' => $get_trans_data1->total_total_amt,
                                    'prmm_round_off' => 0
                                ],'prmm_id',$prmm_id)<1){
                                    echo "9. purchase_readymade_master update issue.";
                                    $this->db->trans_rollback();
                                    exit;
                                }
                            }
                        }
                    }
                }
            }
            $this->db->trans_commit();
            echo "purchase custom upgrade done";
        }
        
    /************************************* custom stock update ************************/
}
?>