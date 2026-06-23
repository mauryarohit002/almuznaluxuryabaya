<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class receipt extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'voucher'; 
        $this->sub_menu = 'receipt'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function remove(){
        $post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

        $data = $this->db_operations->get_record($this->sub_menu.'_master', ['receipt_id' => $id, 'receipt_delete_status' => false]);
        if(empty($data)) return ['status' => REFRESH, 'msg' => '2. Receipt not found'];

        if($this->model->isExist($id)) return ['msg' => '1. Not allowed to delete.'];
        
        $this->db->trans_begin();
        $order_data = $this->db_operations->get_record($this->sub_menu.'_order_trans', ['rot_receipt_id' => $id, 'rot_delete_status' => false]);
        if(!empty($order_data)){
            foreach ($order_data as $key => $value) {
                if($this->model->isOrderTransExist($value['rot_id'])){
                    $this->db->trans_rollback();
                    return ['msg' => '2. Order transaction not allowed to delete.'];
                }
            
                $result = $this->delete_order($value);
                if(!isset($result['status'])){
                    $this->db->trans_rollback();
                    return $result;
                }
            
                $update_data 						= [];
                $update_data['rot_delete_status'] 	= true;
                $update_data['rot_updated_by'] 		= $_SESSION['user_id'];
                $update_data['rot_updated_at'] 		= date('Y-m-d H:i:s');;
                if($this->db_operations->data_update($this->sub_menu.'_order_trans', $update_data, 'rot_id', $value['rot_id']) < 1){
                    $this->db->trans_rollback();
                    return ['msg' => '1. Order transaction not deleted.'];
                }
            }
        }

        $prev_data = $this->db_operations->get_record('receipt_payment_mode_trans', ['rpmt_receipt_id' => $id, 'rpmt_delete_status' => false]);
        if(!empty($prev_data)){
            $update_data 						= [];
            $update_data['rpmt_delete_status'] 	= true; 
            $update_data['rpmt_updated_by'] 	= $_SESSION['user_id']; 
            $update_data['rpmt_updated_at'] 	= date('Y-m-d H:i:s'); 
            if($this->db_operations->data_update('receipt_payment_mode_trans', $update_data, 'rpmt_receipt_id', $id) < 1){
                $this->db->trans_rollback();
                return ['msg' => '1. Payment not deleted.'];
            }
        }

        $update_data 							= [];
        $update_data['receipt_entry_no'] 		= $data[0]['receipt_entry_no'].''.$id; 
        $update_data['receipt_delete_status'] 	= true; 
        $update_data['receipt_updated_by'] 		= $_SESSION['user_id']; 
        $update_data['receipt_updated_at'] 		= date('Y-m-d H:i:s'); 
        if($this->db_operations->data_update($this->sub_menu.'_master', $update_data, 'receipt_id', $id) < 1){
            $this->db->trans_rollback();
            return ['msg' => 'Receipt not deleted.'];
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return ['msg' => '2. Transaction Rollback'];
        }
        $this->db->trans_commit();
        return ['status' => TRUE, 'msg' => 'Deleted successfully'];
    }
    public function get_customer_from_order(){
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        $data       = $this->model->get_customer_from_order($id);
        return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
    }
    public function get_customer_data(){
        $post_data                  = $this->input->post();
        $id                         = $post_data['id'];
        $om_id                      = $post_data['om_id'];
        $data['order_data'] 		= $this->model->get_order_data($id, $om_id);
        $data['balance_data'] 		= $this->model->get_balance_data($id, $om_id);
        return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
    }
    public function get_data_for_adjustment(){
        $post_data                  = $this->input->post();
        $id                         = $post_data['id'];
        $om_id                      = $post_data['om_id'];
        $data['order_data'] 		= $this->model->get_order_data($id, $om_id);
        $data['balance_data'] 		= $this->model->get_balance_data($id, $om_id);
        return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
    }

    public function add_edit(){
        $post_data  = $this->input->post();
        // echo "<pre>"; print_r($post_data); exit;
        $id         = $post_data['id'];
        $result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
        if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
        
        // Master array
            $master_data['receipt_uuid']			= trim($post_data['receipt_uuid']);
            $master_data['receipt_entry_no']		= trim($post_data['receipt_entry_no']);
            $master_data['receipt_entry_date'] 		= $post_data['receipt_entry_date'];				
            $master_data['receipt_customer_id'] 	= isset($post_data['receipt_customer_id']) ? $post_data['receipt_customer_id'] : $post_data['customer_id'];
            $master_data['receipt_notes'] 			= trim($post_data['receipt_notes']);
            $master_data['receipt_opening_amt'] 	= trim($post_data['receipt_opening_amt']);
            $master_data['receipt_order_amt'] 		= trim($post_data['receipt_order_amt']);
            $master_data['receipt_amt'] 			= trim($post_data['receipt_amt']);
            $master_data['receipt_updated_by'] 		= $_SESSION['user_id'];
            $master_data['receipt_updated_at'] 		= date('Y-m-d H:i:s');
        // Master array
        
        $this->db->trans_begin();
        if($id == 0){
            $master_data['receipt_entry_no'] 		= $this->model->get_max_entry_no(['entry_no' => 'receipt_entry_no', 'delete_status' => 'receipt_delete_status', 'fin_year' => 'receipt_fin_year']);
            $master_data['receipt_created_by'] 		= $_SESSION['user_id'];
            $master_data['receipt_created_at'] 		= date('Y-m-d H:i:s');
            $master_data['receipt_fin_year'] 		= $_SESSION['fin_year'];
            $master_data['receipt_branch_id'] 		= $_SESSION['user_branch_id'];
            $uuidExist 								= $this->db_operations->get_cnt($this->sub_menu.'_master', ['receipt_uuid' => $master_data['receipt_uuid']]);
            if($uuidExist > 0){
                $this->db->trans_rollback();
                return ['msg' => '1. Form already submitted.'];
            }
            $id  = $this->db_operations->data_insert($this->sub_menu.'_master', $master_data);
            $msg = 'Added successfully';
            if($id < 1){
                $this->db->trans_rollback();
                return ['msg' => '1. Receipt not added.'];
            }
        }else{
            $msg = 'Updated successfully';
            $prev_data = $this->db_operations->get_record($this->sub_menu.'_master', ['receipt_id' => $id, 'receipt_delete_status' => false]);
            if(empty($prev_data)){
                $this->db->trans_rollback();
                return ['msg' => '1. Receipt not found.'];
            }
            if($this->db_operations->data_update($this->sub_menu.'_master', $master_data, 'receipt_id', $id) < 1){
                $this->db->trans_rollback();
                return ['msg' => 'Receipt not updated.'];
            }
        }
        
        $result = $this->add_update_order($post_data, $id);
        if(!isset($result['status'])){
            $this->db->trans_rollback();
            return $result;
        }
        
        $result = $this->add_update_payment_mode($post_data, $id);
        if(!isset($result['status'])){
            $this->db->trans_rollback();
            return $result;
        }

        if($this->db_operations->data_update($this->sub_menu.'_master', ['receipt_adjust_status' => $this->model->isAdjusted($id)], 'receipt_id', $id) < 1){
            $this->db->trans_rollback();
            return ['msg' => 'Adjusted status not updated.'];
        }

        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return ['msg' => '1. Transaction Rollback.'];
        }
        $this->db->trans_commit();
        $data['id'] = $id;
        return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
    }

    // receipt_order_trans
		public function add_update_order($post_data, $id){
			$trans_db_data = $this->db_operations->get_record('receipt_order_trans', ['rot_receipt_id' => $id, 'rot_delete_status' => false]);
			if(!empty($trans_db_data)){
				foreach ($trans_db_data as $key => $value){
					$flag = false;
					if(isset($post_data['rot_checked'])){
						if(!array_key_exists($value['rot_om_id'], $post_data['rot_checked'])){
							$flag = true;
						}
					}else{
						$flag = true;
					}
					if($flag){
						$result = $this->delete_order($value);
						if(!isset($result['status'])) return $result;

						$update_data 						= [];
						$update_data['rot_delete_status'] 	= true;
						$update_data['rot_updated_by'] 		= $_SESSION['user_id'];
						$update_data['rot_updated_at'] 		= date('Y-m-d H:i:s');
						if($this->db_operations->data_update('receipt_order_trans', $update_data, 'rot_id', $value['rot_id']) < 1){
							return ['msg' => '4. Order not deleted.'];
						}
					}
				}
			}
			if(isset($post_data['rot_checked']) && !empty($post_data['rot_checked'])){
				foreach ($post_data['rot_checked'] as $key => $value){
					if($post_data['rot_adjust_amt'][$key] > 0){
						$trans_data							= [];
						$trans_data['rot_receipt_id']		= $id;
						$trans_data['rot_receipt_uuid']		= $post_data['receipt_uuid'];
						$trans_data['rot_om_id']			= $post_data['rot_om_id'][$key];
						$trans_data['rot_entry_no']			= $post_data['rot_entry_no'][$key];
                        $trans_data['rot_type']         = $post_data['rot_type'][$key];
						$trans_data['rot_entry_date']		= $post_data['rot_entry_date'][$key];
						$trans_data['rot_total_amt']		= $post_data['rot_total_amt'][$key];
						$trans_data['rot_adjust_amt']		= $post_data['rot_adjust_amt'][$key];
						$trans_data['rot_delete_status']	= false;
						$trans_data['rot_updated_by'] 		= $_SESSION['user_id'];
						$trans_data['rot_updated_at'] 		= date('Y-m-d H:i:s');
						
						if(empty($post_data['rot_id'][$key])){
							$trans_data['rot_created_by'] 	= $_SESSION['user_id'];
							$trans_data['rot_created_at'] 	= date('Y-m-d H:i:s');
							if($this->db_operations->data_insert('receipt_order_trans', $trans_data) < 1) return ['msg' => '1. Order not added.'];

							$result = $this->update_order($trans_data);
							if(!isset($result['status'])) return $result;
						}else{
							$prev_data = $this->db_operations->get_record('receipt_order_trans', ['rot_id' => $post_data['rot_id'][$key], 'rot_delete_status' => false]);
							if(empty($prev_data)) return ['msg' => '1. Order transaction not found.'];
						
							if($this->db_operations->data_update('receipt_order_trans', $trans_data, 'rot_id', $prev_data[0]['rot_id']) < 1){
								return ['msg' => '1. Order transaction not updated.'];
							}
						
							$result = $this->delete_order($prev_data[0]);
							if(!isset($result['status'])) return $result;
						
							$result = $this->update_order($trans_data);
							if(!isset($result['status'])) return $result;
						}
					}
				}
			}
			return ['status' => TRUE];
		}
	// receipt_order_trans
    
    // receipt_payment_mode_trans
        public function get_payment_mode_data(){
            $post_data  = $this->input->post();
            $id         = $post_data['id'];
            $data       = $this->model->get_payment_mode_data($id);
            if(empty($data)) return ['msg' => '1. Payment mode not define.'];
            return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
        }
		public function add_update_payment_mode($post_data, $id){
			$trans_db_data = $this->db_operations->get_record('receipt_payment_mode_trans', ['rpmt_receipt_id' => $id, 'rpmt_delete_status' => false]);
			if(!empty($trans_db_data)){
				foreach ($trans_db_data as $key => $value){
					if(!in_array($value['rpmt_id'], $post_data['rpmt_id'])){
						$update_data 						= [];
						$update_data['rpmt_delete_status'] 	= true;
						$update_data['rpmt_updated_by'] 	= $_SESSION['user_id'];
						$update_data['rpmt_updated_at'] 	= date('Y-m-d H:i:s');
						if($this->db_operations->data_update('receipt_payment_mode_trans', $update_data, 'rpmt_id', $value['rpmt_id']) < 1){
							return ['msg' => '1. Payment mode not deleted.'];
						}
					}
				}
				foreach ($post_data['rpmt_amt'] as $key => $value) {
					if($value <= 0){
						$update_data 						= [];
						$update_data['rpmt_delete_status'] 	= true;
						$update_data['rpmt_updated_by'] 	= $_SESSION['user_id'];
						$update_data['rpmt_updated_at'] 	= date('Y-m-d H:i:s');
						if($this->db_operations->data_update('receipt_payment_mode_trans', $update_data, 'rpmt_id', $post_data['rpmt_id'][$key]) < 1){
							return ['msg' => '1. Payment mode not deleted.'];
						}
					}
				}
			}
			foreach ($post_data['rpmt_amt'] as $key => $value){
				if($value > 0){
					$trans_data							= [];
					$trans_data['rpmt_receipt_id']		= $id;
					$trans_data['rpmt_receipt_uuid']	= $post_data['receipt_uuid'];
					$trans_data['rpmt_payment_mode_id']	= $post_data['rpmt_payment_mode_id'][$key];
					$trans_data['rpmt_amt']				= $post_data['rpmt_amt'][$key];
					$trans_data['rpmt_delete_status']	= false;
					$trans_data['rpmt_updated_by'] 		= $_SESSION['user_id'];
					$trans_data['rpmt_updated_at'] 		= date('Y-m-d H:i:s');
					
					if(empty($post_data['rpmt_id'][$key])){
						$trans_data['rpmt_created_by'] 	= $_SESSION['user_id'];
						$trans_data['rpmt_created_at'] 	= date('Y-m-d H:i:s');
						if($this->db_operations->data_insert('receipt_payment_mode_trans', $trans_data) < 1){
							return ['msg' => '1. Payment mode not added.'];
						}
					}
				}
			}
			return ['status' => TRUE];
		}
	// receipt_payment_mode_trans

    // order_master
		public function update_order($temp){
			$data = $this->model->get_order($temp['rot_om_id']);
			if(empty($data)) return ['msg' => '1. Order not found.'];

			if($temp['rot_adjust_amt'] > $data[0]['balance_amt']) return ['msg' => '1. Order adjusted amt should be less than order balance amt.'];

			$master_data = [];
			$master_data['om_allocated_amt'] = $data[0]['om_allocated_amt'] + $temp['rot_adjust_amt'];

			if($this->db_operations->data_update('order_master', $master_data, 'om_id', $data[0]['om_id']) < 1) return ['msg' => '1. Order not updated.'];

			return ['status' => TRUE];
		}
		public function delete_order($temp){
			$data = $this->db_operations->get_record('order_master', ['om_id' => $temp['rot_om_id'], 'om_delete_status' => false]);
			if(empty($data)) return ['msg' => '2. Order not found.'];

			if($temp['rot_adjust_amt'] > $data[0]['om_allocated_amt']) return ['msg' => '1. Order adjusted amt should be less than order allocated amt.'];

			$master_data = [];
			$master_data['om_allocated_amt'] = $data[0]['om_allocated_amt'] - $temp['rot_adjust_amt'];

			if($this->db_operations->data_update('order_master', $master_data, 'om_id', $data[0]['om_id']) < 1) return ['msg' => '2. Order not updated.'];

			return ['status' => TRUE];
		}
	// order_master
}
?>