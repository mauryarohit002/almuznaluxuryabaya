<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class payment_general extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'voucher'; 
        $this->sub_menu = 'payment_general'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function remove(){
        $post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

        $data = $this->db_operations->get_record($this->sub_menu.'_master', ['payment_general_id' => $id, 'payment_general_delete_status' => false]);
        if(empty($data)) return ['status' => REFRESH, 'msg' => '2. Payment not found'];

        if($this->model->isExist($id)) return ['msg' => '1. Not allowed to delete.'];
        
        $this->db->trans_begin();
        $prev_data = $this->db_operations->get_record('payment_general_payment_mode_trans', ['pgpmt_payment_general_id' => $id, 'pgpmt_delete_status' => false]);
        if(!empty($prev_data)){
            $update_data 						= [];
            $update_data['pgpmt_delete_status'] = true; 
            $update_data['pgpmt_updated_by'] 	= $_SESSION['user_id']; 
            $update_data['pgpmt_updated_at'] 	= date('Y-m-d H:i:s'); 
            if($this->db_operations->data_update('payment_general_payment_mode_trans', $update_data, 'pgpmt_payment_general_id', $id) < 1){
                $this->db->trans_rollback();
                return ['msg' => '1. Payment not deleted.'];
            }
        }

        $update_data 							        = [];
        $update_data['payment_general_entry_no'] 	    = $data[0]['payment_general_entry_no'].''.$id; 
        $update_data['payment_general_delete_status'] 	= true; 
        $update_data['payment_general_updated_by'] 	    = $_SESSION['user_id']; 
        $update_data['payment_general_updated_at'] 	    = date('Y-m-d H:i:s'); 
        if($this->db_operations->data_update($this->sub_menu.'_master', $update_data, 'payment_general_id', $id) < 1){
            $this->db->trans_rollback();
            return ['msg' => 'Payment not deleted.'];
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return ['msg' => '2. Transaction Rollback'];
        }
        $this->db->trans_commit();
        return ['status' => TRUE, 'msg' => 'Deleted successfully'];
    }
    public function get_general_data(){
        $post_data                  = $this->input->post();
        $id                         = $post_data['id'];
        $data['balance_data'] 		= $this->model->get_balance_data($id);
        return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
    }
    public function add_edit(){
        $post_data  = $this->input->post();
        // echo "<pre>"; print_r($post_data); exit;
        $id         = $post_data['id'];
        $result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
        if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
        
        // Master array
            $master_data['payment_general_uuid']	    = trim($post_data['payment_general_uuid']);
            $master_data['payment_general_entry_no']    = trim($post_data['payment_general_entry_no']);
            $master_data['payment_general_entry_date']  = $post_data['payment_general_entry_date'];				
            $master_data['payment_general_general_id'] 	= isset($post_data['payment_general_general_id']) ? $post_data['payment_general_general_id'] : $post_data['supplier_id'];
            $master_data['payment_general_notes'] 	    = trim($post_data['payment_general_notes']);
            $master_data['payment_general_amt'] 	    = trim($post_data['payment_general_amt']);
            $master_data['payment_general_updated_by']  = $_SESSION['user_id'];
            $master_data['payment_general_updated_at']  = date('Y-m-d H:i:s');
        // Master array
        
        $this->db->trans_begin();
        if($id == 0){
            $master_data['payment_general_entry_no']    = $this->model->get_max_entry_no(['entry_no' => 'payment_general_entry_no', 'delete_status' => 'payment_general_delete_status', 'fin_year' => 'payment_general_fin_year', 'branch_id' => 'payment_general_branch_id']);
            $master_data['payment_general_created_by'] 	= $_SESSION['user_id'];
            $master_data['payment_general_created_at'] 	= date('Y-m-d H:i:s');
            $master_data['payment_general_fin_year']    = $_SESSION['fin_year'];
            $master_data['payment_general_branch_id']   = $_SESSION['user_branch_id'];
            $uuidExist 								    = $this->db_operations->get_cnt($this->sub_menu.'_master', ['payment_general_uuid' => $master_data['payment_general_uuid']]);
            if($uuidExist > 0){
                $this->db->trans_rollback();
                return ['msg' => '1. Form already submitted.'];
            }
            $id  = $this->db_operations->data_insert($this->sub_menu.'_master', $master_data);
            $msg = 'Added successfully';
            if($id < 1){
                $this->db->trans_rollback();
                return ['msg' => '1. Payment not added.'];
            }
        }else{
            $msg = 'Updated successfully';
            $prev_data = $this->db_operations->get_record($this->sub_menu.'_master', ['payment_general_id' => $id, 'payment_general_delete_status' => false]);
            if(empty($prev_data)){
                $this->db->trans_rollback();
                return ['msg' => '1. Payment not found.'];
            }
            if($this->db_operations->data_update($this->sub_menu.'_master', $master_data, 'payment_general_id', $id) < 1){
                $this->db->trans_rollback();
                return ['msg' => 'Payment not updated.'];
            }
        }
        
        $result = $this->add_update_payment_mode($post_data, $id);
        if(!isset($result['status'])){
            $this->db->trans_rollback();
            return $result;
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return ['msg' => '1. Transaction Rollback.'];
        }
        $this->db->trans_commit();
        $data['id'] = $id;
        return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
    }

    // payment_general_payment_mode_trans
        public function get_payment_mode_data(){
            $post_data  = $this->input->post();
            $id         = $post_data['id'];
            $data       = $this->model->get_payment_mode_data($id);
            if(empty($data)) return ['msg' => '1. Payment mode not define.'];
            return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
        }
		public function add_update_payment_mode($post_data, $id){
			$trans_db_data = $this->db_operations->get_record('payment_general_payment_mode_trans', ['pgpmt_payment_general_id' => $id, 'pgpmt_delete_status' => false]);
			if(!empty($trans_db_data)){
				foreach ($trans_db_data as $key => $value){
					if(!in_array($value['pgpmt_id'], $post_data['pgpmt_id'])){
						$update_data 						= [];
						$update_data['pgpmt_delete_status'] 	= true;
						$update_data['pgpmt_updated_by'] 	= $_SESSION['user_id'];
						$update_data['pgpmt_updated_at'] 	= date('Y-m-d H:i:s');
						if($this->db_operations->data_update('payment_general_payment_mode_trans', $update_data, 'pgpmt_id', $value['pgpmt_id']) < 1){
							return ['msg' => '1. Payment mode not deleted.'];
						}
					}
				}
				foreach ($post_data['pgpmt_amt'] as $key => $value) {
					if($value <= 0){
						$update_data 						= [];
						$update_data['pgpmt_delete_status'] = true;
						$update_data['pgpmt_updated_by'] 	= $_SESSION['user_id'];
						$update_data['pgpmt_updated_at'] 	= date('Y-m-d H:i:s');
						if($this->db_operations->data_update('payment_general_payment_mode_trans', $update_data, 'pgpmt_id', $post_data['pgpmt_id'][$key]) < 1){
							return ['msg' => '1. Payment mode not deleted.'];
						}
					}
				}
			}
			foreach ($post_data['pgpmt_amt'] as $key => $value){
				if($value > 0){
					$trans_data							        = [];
					$trans_data['pgpmt_payment_general_id']		= $id;
					$trans_data['pgpmt_payment_general_uuid']	= $post_data['payment_general_uuid'];
					$trans_data['pgpmt_payment_mode_id']	    = $post_data['pgpmt_payment_mode_id'][$key];
					$trans_data['pgpmt_amt']				    = $post_data['pgpmt_amt'][$key];
					$trans_data['pgpmt_delete_status']	        = false;
					$trans_data['pgpmt_updated_by'] 		    = $_SESSION['user_id'];
					$trans_data['pgpmt_updated_at'] 		    = date('Y-m-d H:i:s');
					
					if(empty($post_data['pgpmt_id'][$key])){
						$trans_data['pgpmt_created_by'] 	= $_SESSION['user_id'];
						$trans_data['pgpmt_created_at'] 	= date('Y-m-d H:i:s');
						if($this->db_operations->data_insert('payment_general_payment_mode_trans', $trans_data) < 1){
							return ['msg' => '1. Payment mode not added.'];
						}
					}
				}
			}
			return ['status' => TRUE];
		}
	// payment_general_payment_mode_trans
}
?>