<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class Purchase_readymade_return extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'transaction'; 
        $this->sub_menu = 'purchase_readymade_return'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function get_barcode_data(){
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        $supplier_id= $post_data['supplier_id'];
        // echo "<pre>"; print_r($post_data); exit;

        $data = $this->model->get_barcode_data($id);
        if((empty($data))) return ['msg' => '1. Barcode not found.'];
        // echo "<pre>"; print_r($data); exit;
        if($data[0]['delete_status'] == 1) return ['msg' => '1. Barcode is deleted.'];
		if($supplier_id != 0 && ($supplier_id != $data[0]['supplier_id'])) return ['msg' => '1. Scanned barcode is from different supplier.'];
        if($data[0]['bal_qty'] <= 0) return ['msg' => '1. Barcode not available.'];
        
        return['status' => TRUE, 'data' => $data, 'msg' => 'Barcode scanned successfully.'];
	}
    public function remove(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$data = $this->db_operations->get_record('purchase_readymade_return_master', ['prrm_id' => $id, 'prrm_delete_status' => false]);
		if(empty($data)) return ['status' => REFRESH, 'msg' => '3. Purchase return not found.'];	

		if($this->model->isExist($id)) return ['msg' => '1. Not allowed to delete.'];	
		$trans_data = $this->db_operations->get_record('purchase_readymade_return_trans', ['prrt_prrm_id' => $id, 'prrt_delete_status' => false]);
		if(empty($trans_data)) return ['msg' => '2. Transaction not found.'];
		$this->db->trans_begin();
		foreach ($trans_data as $key => $value) {
			if($this->model->isTransExist($value['prrt_id'])){
				$this->db->trans_rollback();
				return ['msg' => '2. Not allowed to delete transaction.'];
			}
			$result = $this->delete_barcode($value['prrt_brmm_id'], $value['prrt_qty']);
			if(!isset($result['status'])) {
				$this->db->trans_rollback();
				return $result;
			}

			$update_data 						= [];
			$update_data['prrt_delete_status'] 	= true; 
			$update_data['prrt_updated_by'] 		= $_SESSION['user_id']; 
			$update_data['prrt_updated_at'] 		= date('Y-m-d H:i:s'); 
			if($this->db_operations->data_update('purchase_readymade_return_trans', $update_data, 'prrt_id', $value['prrt_id']) < 1){
				$this->db->trans_rollback();
				return ['msg' => '2. Transaction not deleted.'];
			}
		}

		$update_data 						= [];
		$update_data['prrm_entry_no'] 		= $data[0]['prrm_entry_no'].''.$id; 
		$update_data['prrm_delete_status'] 	= true; 
		$update_data['prrm_updated_by'] 		= $_SESSION['user_id']; 
		$update_data['prrm_updated_at'] 		= date('Y-m-d H:i:s'); 
		if($this->db_operations->data_update('purchase_readymade_return_master', $update_data, 'prrm_id', $id) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Purchase return not deleted.'];
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return ['msg' => '2. Transaction Rollback.'];
		}
		$this->db->trans_commit();

		return ['status' => TRUE, 'msg' => 'Purchase return deleted successfully'];
	}
    // purchase_readymade_return_master
        public function add_edit(){
			$post_data  = $this->input->post();
            $id         = $post_data['id'];
			if(!isset($post_data['trans_data']) || (isset($post_data['trans_data']) && empty($post_data['trans_data']))) return ['msg' => '1. Barcode not added in list.'];
			$post_data['trans_data'] = json_decode($post_data['trans_data'], true);

			// echo "<pre>"; print_r($post_data); exit;
			// master_data
                $master_data['prrm_uuid'] 				    = trim($post_data['prrm_uuid']);
                $master_data['prrm_entry_no'] 				= trim($post_data['prrm_entry_no']);
                $master_data['prrm_entry_date'] 				= date('Y-m-d', strtotime($post_data['prrm_entry_date']));
                $master_data['prrm_supplier_id'] 			= trim($post_data['prrm_supplier_id']);
                $master_data['prrm_total_qty'] 				= trim($post_data['prrm_total_qty']);
                $master_data['prrm_notes'] 					= trim($post_data['prrm_notes']);
                $master_data['prrm_sub_amt'] 				= trim($post_data['prrm_sub_amt']);
                $master_data['prrm_taxable_amt'] 			= trim($post_data['prrm_taxable_amt']);
                
                $master_data['prrm_total_amt']				= trim($post_data['prrm_total_amt']);
                $master_data['prrm_updated_by'] 				= $_SESSION['user_id'];
				$master_data['prrm_updated_at'] 				= date('Y-m-d H:i:s');
            // master_data

			$this->db->trans_begin();
			if($id == 0){
				$master_data['prrm_entry_no'] 	= $this->model->get_max_entry_no(['entry_no' => 'prrm_entry_no', 'delete_status' => 'prrm_delete_status', 'fin_year' => 'prrm_fin_year']);
				$master_data['prrm_created_by'] 	= $_SESSION['user_id'];
				$master_data['prrm_created_at'] 	= date('Y-m-d H:i:s');
				$master_data['prrm_fin_year'] 	= $_SESSION['fin_year'];
				$master_data['prrm_branch_id'] 	= $_SESSION['user_branch_id'];
				$uuidExist 						= $this->db_operations->get_cnt($this->sub_menu.'_master', ['prrm_uuid' => $master_data['prrm_uuid']]);
				if($uuidExist > 0){
					$this->db->trans_rollback();
					return ['msg' => 'Form already submited.'];
				}
				$id = $this->db_operations->data_insert('purchase_readymade_return_master', $master_data);
				$msg = 'Purchase return added successfully.';
				if($id < 1){
					$this->db->trans_rollback();
					return ['msg' => '1. Purchase return not added.'];
				}
			}else{
				$prev_data = $this->db_operations->get_record('purchase_readymade_return_master', ['prrm_id' => $id, 'prrm_delete_status' => false]);
				if(empty($prev_data)){
					$this->db->trans_rollback();
					return ['status' => REFRESH, 'msg' => '1. Purchase return not found.'];
				}
				$msg = 'Purchase return updated successfully.';
				if($this->db_operations->data_update('purchase_readymade_return_master', $master_data, 'prrm_id', $id) < 1){
					$this->db->trans_rollback();
					return ['msg' => '1. Purchase return not updated.'];
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

			$data['name'] 	= strtoupper($master_data['prrm_entry_no']);
			return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
		}
    // purchase_readymade_return_master

    // purchase_readymade_return_trans
        public function add_update_trans($post_data, $id){
			$trans_db_data = $this->db_operations->get_record('purchase_readymade_return_trans', ['prrt_prrm_id' => $id, 'prrt_delete_status' => false]);
			$ids 	   	   = $this->get_id($post_data);
			if(!empty($trans_db_data)){
				foreach ($trans_db_data as $key => $value){
					if(!in_array($value['prrt_id'], $ids)){
						if($this->model->isTransExist($value['prrt_id'])) return ['msg' => '1. Not allowed to delete transaction.'];
						$result = $this->delete_barcode($value['prrt_brmm_id'], $value['prrt_qty']);
						if(!isset($result['status'])) return $result;
	
						$update_data 						= [];
						$update_data['prrt_delete_status'] 	= true; 
						$update_data['prrt_updated_by'] 		= $_SESSION['user_id']; 
						$update_data['prrt_updated_at'] 		= date('Y-m-d H:i:s'); 
						if($this->db_operations->data_update('purchase_readymade_return_trans', $update_data, 'prrt_id', $value['prrt_id']) < 1) return ['msg' => '1. Transaction not deleted.'];
					}
				}
			}
			foreach ($post_data['trans_data'] as $key => $value){
				$trans_data 					= [];
				$trans_data['prrt_prrm_id'] 	= $id;
				$trans_data['prrt_prrm_uuid'] 	= $post_data['prrm_uuid'];
				$trans_data['prrt_prmm_id'] 	= isset($value['prrt_prmm_id']) ? $value['prrt_prmm_id'] : 0;
				$trans_data['prrt_prmt_id'] 	= isset($value['prrt_prmt_id']) ? $value['prrt_prmt_id'] : 0;
				$trans_data['prrt_brmm_id'] 	= isset($value['prrt_brmm_id']) ? $value['prrt_brmm_id'] : 0;
				$trans_data['prrt_qty'] 		= trim($value['prrt_qty']);
				$trans_data['prrt_rate'] 		= trim($value['prrt_rate']);
				$trans_data['prrt_amt'] 		= trim($value['prrt_amt']);
				$trans_data['prrt_taxable_amt'] = trim($value['prrt_taxable_amt']);
				$trans_data['prrt_total_amt'] 	= trim($value['prrt_total_amt']);
				$trans_data['prrt_updated_by'] 	= $_SESSION['user_id'];
				$trans_data['prrt_updated_at'] 	= date('Y-m-d H:i:s');

				if($value['prrt_id'] == 0){
					$trans_data['prrt_created_by'] 	= $_SESSION['user_id'];
					$trans_data['prrt_created_at'] 	= date('Y-m-d H:i:s');

					if($this->db_operations->data_insert('purchase_readymade_return_trans', $trans_data) < 1){
						return ['msg' => 'Transaction not added.'];
					}
				}else{
					$prev_data = $this->db_operations->get_record('purchase_readymade_return_trans', ['prrt_id' => $value['prrt_id'], 'prrt_delete_status' => false]);
					if(empty($prev_data)) return ['msg' => '2. Transaction not found.'];

					if($this->db_operations->data_update('purchase_readymade_return_trans', $trans_data, 'prrt_id', $value['prrt_id']) < 1){
						return ['msg' => 'Transaction not updated.'];
					}

					$result = $this->delete_barcode($trans_data['prrt_brmm_id'], $trans_data['prrt_qty']);
					if(!isset($result['status'])) return $result;
				}
				$result = $this->update_barcode($trans_data['prrt_brmm_id'], $trans_data['prrt_qty']);
				if(!isset($result['status'])) return $result;
			}
			return ['status' => TRUE];
		}
		public function get_id($post_data){
			$record = [];
			foreach ($post_data['trans_data'] as $key => $value) {
				array_push($record, $value['prrt_id']);
			}
			return $record;
		}
    // purchase_readymade_return_trans

    // barcode_readymade_master
		public function update_barcode($brmm_id, $qty){
			$data = $this->model->get_barcode_data($brmm_id);
			if(empty($data)) return ['status' => FALSE, 'data' => FALSE, 'msg' => '2. Barcode not found.'];

			if($data[0]['delete_status'] == 1) return ['status' => FALSE, 'data' => FALSE, 'msg' => '2. Barcode is deleted.'];
			if($data[0]['bal_qty'] < 1) return ['status' => FALSE, 'data' => FALSE, 'msg' => '2. Barcode not available for purchase return'];

			$prrt_qty = $data[0]['brmm_prrt_qty'] + $qty;
			if($this->db_operations->data_update('barcode_readymade_master', ['brmm_prrt_qty' => $prrt_qty], 'brmm_id', $brmm_id) < 1){
				return ['status' => FALSE, 'data' => FALSE, 'msg' => '1.Barcode not updated.'];	
			}
			return ['status' => TRUE, 'data' => TRUE, 'msg' => ''];	
		}
		public function delete_barcode($brmm_id, $qty){
			$data = $this->model->get_barcode_data($brmm_id);
			if(empty($data)) return ['status' => FALSE, 'data' => FALSE, 'msg' => '3. Barcode not found'];
			if($data[0]['brmm_prrt_qty'] <= 0) return ['status' => FALSE, 'data' => FALSE, 'msg' => 'Barcode not available.'];
			
			$prrt_qty = $data[0]['brmm_prrt_qty'] - $qty;
			if($this->db_operations->data_update('barcode_readymade_master', ['brmm_prrt_qty' => $prrt_qty], 'brmm_id', $brmm_id) < 1){
				return ['status' => FALSE, 'data' => FALSE, 'msg' => '2.Barcode not updated.'];	
			}
			return ['status' => TRUE, 'data' => TRUE, 'msg' => ''];	
		}
    // barcode_readymade_master
}
?>