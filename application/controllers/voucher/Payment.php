<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class payment extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'voucher'; 
        $this->sub_menu = 'payment'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function add_edit(){
        $post_data  = $this->input->post();
        // echo "<pre>"; print_r($post_data); exit;
        $id         = $post_data['id'];
        $result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
        if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
        
        // Master array
            $master_data['payment_uuid']			= trim($post_data['payment_uuid']);
            $master_data['payment_entry_no']		= trim($post_data['payment_entry_no']);
            $master_data['payment_entry_date'] 		= $post_data['payment_entry_date'];				
            $master_data['payment_supplier_id'] 	= isset($post_data['payment_supplier_id']) ? $post_data['payment_supplier_id'] : $post_data['supplier_id'];
            $master_data['payment_notes'] 			= trim($post_data['payment_notes']);
            $master_data['payment_purchase_amt'] 	= trim($post_data['payment_purchase_amt']);
            $master_data['payment_purchase_readymade_amt']   = trim($post_data['payment_purchase_readymade_amt']);
            $master_data['payment_amt'] 			= trim($post_data['payment_amt']);
          
            $master_data['payment_updated_by'] 		= $_SESSION['user_id'];
            $master_data['payment_updated_at'] 		= date('Y-m-d H:i:s');
        // Master array
        
        $this->db->trans_begin();
        if($id == 0){
            $master_data['payment_entry_no'] 		= $this->model->get_max_entry_no(['entry_no' => 'payment_entry_no', 'delete_status' => 'payment_delete_status', 'fin_year' => 'payment_fin_year']);
            $master_data['payment_created_by'] 		= $_SESSION['user_id'];
            $master_data['payment_created_at'] 		= date('Y-m-d H:i:s');
            $master_data['payment_fin_year'] 		= $_SESSION['fin_year'];
            $master_data['payment_branch_id'] 		= $_SESSION['user_branch_id'];
            $uuidExist 								= $this->db_operations->get_cnt($this->sub_menu.'_master', ['payment_uuid' => $master_data['payment_uuid']]);
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
            $prev_data = $this->db_operations->get_record($this->sub_menu.'_master', ['payment_id' => $id, 'payment_delete_status' => false]);
            if(empty($prev_data)){
                $this->db->trans_rollback();
                return ['msg' => '1. Payment not found.'];
            }
            if($this->db_operations->data_update($this->sub_menu.'_master', $master_data, 'payment_id', $id) < 1){
                $this->db->trans_rollback();
                return ['msg' => 'Payment not updated.'];
            }
        }
        
        $result = $this->add_update_purchase($post_data, $id);
        if(!isset($result['status'])){
            $this->db->trans_rollback();
            return $result;
        }

        $result = $this->add_update_purchase_readymade($post_data, $id);
        if(!isset($result['status'])){
            $this->db->trans_rollback();
            return $result;
        }
        
        $result = $this->add_update_payment_mode($post_data, $id);
        if(!isset($result['status'])){
            $this->db->trans_rollback();
            return $result;
        }

        if($this->db_operations->data_update($this->sub_menu.'_master', ['payment_adjust_status' => $this->model->isAdjusted($id)], 'payment_id', $id) < 1){
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
    public function remove(){
        $post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

        $data = $this->db_operations->get_record($this->sub_menu.'_master', ['payment_id' => $id, 'payment_delete_status' => false]);
        if(empty($data)) return ['status' => REFRESH, 'msg' => '2. Payment not found'];

        if($this->model->isExist($id)) return ['msg' => '1. Not allowed to delete.'];
        
        $this->db->trans_begin();
        $purchase_data = $this->db_operations->get_record($this->sub_menu.'_purchase_trans', ['ppt_payment_id' => $id, 'ppt_delete_status' => false]);
        if(!empty($purchase_data)){
            foreach ($purchase_data as $key => $value) {
                if($this->model->isOrderTransExist($value['ppt_id'])){
                    $this->db->trans_rollback();
                    return ['msg' => '2. Purchase transaction not allowed to delete.'];
                }
            
                $result = $this->delete_purchase($value);
                if(!isset($result['status'])){
                    $this->db->trans_rollback();
                    return $result;
                }
            
                $update_data 						= [];
                $update_data['ppt_delete_status'] 	= true;
                $update_data['ppt_updated_by'] 		= $_SESSION['user_id'];
                $update_data['ppt_updated_at'] 		= date('Y-m-d H:i:s');;
                if($this->db_operations->data_update($this->sub_menu.'_purchase_trans', $update_data, 'ppt_id', $value['ppt_id']) < 1){
                    $this->db->trans_rollback();
                    return ['msg' => '1. Purchase transaction not deleted.'];
                }
            }
        }

        $purchase_readymade_data = $this->db_operations->get_record($this->sub_menu.'_purchase_readymade_trans', ['pprt_payment_id' => $id, 'pprt_delete_status' => false]);
        if(!empty($purchase_readymade_data)){
            foreach ($purchase_readymade_data as $key => $value) {
                if($this->model->isOrderTransExist($value['pprt_id'])){
                    $this->db->trans_rollback();
                    return ['msg' => '2. Purchase_readymade transaction not allowed to delete.'];
                }
            
                $result = $this->delete_purchase_readymade($value);
                if(!isset($result['status'])){
                    $this->db->trans_rollback();
                    return $result;
                }
            
                $update_data                        = [];
                $update_data['pprt_delete_status']   = true;
                $update_data['pprt_updated_by']      = $_SESSION['user_id'];
                $update_data['pprt_updated_at']      = date('Y-m-d H:i:s');;
                if($this->db_operations->data_update($this->sub_menu.'_purchase_readymade_trans', $update_data, 'pprt_id', $value['pprt_id']) < 1){
                    $this->db->trans_rollback();
                    return ['msg' => '1. Purchase_readymade transaction not deleted.'];
                }
            }
        }

        $prev_data = $this->db_operations->get_record('payment_payment_mode_trans', ['ppmt_payment_id' => $id, 'ppmt_delete_status' => false]);
        if(!empty($prev_data)){
            $update_data 						= [];
            $update_data['ppmt_delete_status'] 	= true; 
            $update_data['ppmt_updated_by'] 	= $_SESSION['user_id']; 
            $update_data['ppmt_updated_at'] 	= date('Y-m-d H:i:s'); 
            if($this->db_operations->data_update('payment_payment_mode_trans', $update_data, 'ppmt_payment_id', $id) < 1){
                $this->db->trans_rollback();
                return ['msg' => '1. Payment not deleted.'];
            }
        }

        $update_data 							= [];
        $update_data['payment_entry_no'] 		= $data[0]['payment_entry_no'].''.$id; 
        $update_data['payment_delete_status'] 	= true; 
        $update_data['payment_updated_by'] 		= $_SESSION['user_id']; 
        $update_data['payment_updated_at'] 		= date('Y-m-d H:i:s'); 
        if($this->db_operations->data_update($this->sub_menu.'_master', $update_data, 'payment_id', $id) < 1){
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
    public function get_supplier_from_purchase(){
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        $data       = $this->model->get_supplier_from_purchase($id);
        return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
    }
    public function get_supplier_data(){
        $post_data                  = $this->input->post();
        $id                         = $post_data['id'];
        $data['purchase_data'] 		= $this->model->get_purchase_data($id);
        $data['purchase_readymade_data'] = $this->model->get_purchase_readymade_data($id);
        $data['balance_data'] 		= $this->model->get_balance_data($id);
        return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
    }
    public function get_data_for_adjustment(){
        $post_data                  = $this->input->post();
        $id                         = $post_data['id'];
        $data['purchase_data'] 		= $this->model->get_purchase_data($id);
        $data['purchase_readymade_data'] = $this->model->get_purchase_readymade_data($id);
        $data['balance_data'] 		= $this->model->get_balance_data($id);
        return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
    }

    // payment_purchase_trans
		public function add_update_purchase($post_data, $id){
			$trans_db_data = $this->db_operations->get_record('payment_purchase_trans', ['ppt_payment_id' => $id, 'ppt_delete_status' => false]);
			if(!empty($trans_db_data)){
				foreach ($trans_db_data as $key => $value){
					$flag = false;
					if(isset($post_data['ppt_checked'])){
						if(!array_key_exists($value['ppt_pm_id'], $post_data['ppt_checked'])){
							$flag = true;
						}
					}else{
						$flag = true;
					}
					if($flag){
						$result = $this->delete_purchase($value);
						if(!isset($result['status'])) return $result;

						$update_data 						= [];
						$update_data['ppt_delete_status'] 	= true;
						$update_data['ppt_updated_by'] 		= $_SESSION['user_id'];
						$update_data['ppt_updated_at'] 		= date('Y-m-d H:i:s');
						if($this->db_operations->data_update('payment_purchase_trans', $update_data, 'ppt_id', $value['ppt_id']) < 1){
							return ['msg' => '4. Purchase not deleted.'];
						}
					}
				}
			}
			if(isset($post_data['ppt_checked']) && !empty($post_data['ppt_checked'])){
				foreach ($post_data['ppt_checked'] as $key => $value){
					if($post_data['ppt_adjust_amt'][$key] > 0){
						$trans_data							= [];
						$trans_data['ppt_payment_id']		= $id;
						$trans_data['ppt_payment_uuid']		= $post_data['payment_uuid'];
						$trans_data['ppt_pm_id']			= $post_data['ppt_pm_id'][$key];
						$trans_data['ppt_entry_no']			= $post_data['ppt_entry_no'][$key];
						$trans_data['ppt_entry_date']		= $post_data['ppt_entry_date'][$key];
						$trans_data['ppt_total_amt']		= $post_data['ppt_total_amt'][$key];
						$trans_data['ppt_adjust_amt']		= $post_data['ppt_adjust_amt'][$key];
						$trans_data['ppt_delete_status']	= false;
						$trans_data['ppt_updated_by'] 		= $_SESSION['user_id'];
						$trans_data['ppt_updated_at'] 		= date('Y-m-d H:i:s');
						
						if(empty($post_data['ppt_id'][$key])){
							$trans_data['ppt_created_by'] 	= $_SESSION['user_id'];
							$trans_data['ppt_created_at'] 	= date('Y-m-d H:i:s');
							if($this->db_operations->data_insert('payment_purchase_trans', $trans_data) < 1) return ['msg' => '1. Purchase not added.'];

							$result = $this->update_purchase($trans_data);
							if(!isset($result['status'])) return $result;
						}else{
							$prev_data = $this->db_operations->get_record('payment_purchase_trans', ['ppt_id' => $post_data['ppt_id'][$key], 'ppt_delete_status' => false]);
							if(empty($prev_data)) return ['msg' => '1. Purchase transaction not found.'];
						
							if($this->db_operations->data_update('payment_purchase_trans', $trans_data, 'ppt_id', $prev_data[0]['ppt_id']) < 1){
								return ['msg' => '1. Purchase transaction not updated.'];
							}
						
							$result = $this->delete_purchase($prev_data[0]);
							if(!isset($result['status'])) return $result;
						
							$result = $this->update_purchase($trans_data);
							if(!isset($result['status'])) return $result;
						}
					}
				}
			}
			return ['status' => TRUE];
		}
	// payment_purchase_trans

    // payment_purchase_readymade_trans
        public function add_update_purchase_readymade($post_data, $id){
            $trans_db_data = $this->db_operations->get_record('payment_purchase_readymade_trans', ['pprt_payment_id' => $id, 'pprt_delete_status' => false]);
            if(!empty($trans_db_data)){
                foreach ($trans_db_data as $key => $value){
                    $flag = false;
                    if(isset($post_data['pprt_checked'])){
                        if(!array_key_exists($value['pprt_prmm_id'], $post_data['pprt_checked'])){
                            $flag = true;
                        }
                    }else{
                        $flag = true;
                    }
                    if($flag){
                        $result = $this->delete_purchase_readymade($value);
                        if(!isset($result['status'])) return $result;

                        $update_data                        = [];
                        $update_data['pprt_delete_status']   = true;
                        $update_data['pprt_updated_by']      = $_SESSION['user_id'];
                        $update_data['pprt_updated_at']      = date('Y-m-d H:i:s');
                        if($this->db_operations->data_update('payment_purchase_readymade_trans', $update_data, 'pprt_id', $value['pprt_id']) < 1){
                            return ['msg' => '4. Purchase_readymade not deleted.'];
                        }
                    }
                }
            }
            if(isset($post_data['pprt_checked']) && !empty($post_data['pprt_checked'])){
                foreach ($post_data['pprt_checked'] as $key => $value){
                    if($post_data['pprt_adjust_amt'][$key] > 0){
                        $trans_data                         = [];
                        $trans_data['pprt_payment_id']       = $id;
                        $trans_data['pprt_payment_uuid']     = $post_data['payment_uuid'];
                        $trans_data['pprt_prmm_id']            = $post_data['pprt_prmm_id'][$key];
                        $trans_data['pprt_entry_no']         = $post_data['pprt_entry_no'][$key];
                        $trans_data['pprt_entry_date']       = $post_data['pprt_entry_date'][$key];
                        $trans_data['pprt_total_amt']        = $post_data['pprt_total_amt'][$key];
                        $trans_data['pprt_adjust_amt']       = $post_data['pprt_adjust_amt'][$key];
                        $trans_data['pprt_delete_status']    = false;
                        $trans_data['pprt_updated_by']       = $_SESSION['user_id'];
                        $trans_data['pprt_updated_at']       = date('Y-m-d H:i:s');
                        
                        if(empty($post_data['pprt_id'][$key])){
                            $trans_data['pprt_created_by']   = $_SESSION['user_id'];
                            $trans_data['pprt_created_at']   = date('Y-m-d H:i:s');
                            if($this->db_operations->data_insert('payment_purchase_readymade_trans', $trans_data) < 1) return ['msg' => '1. Purchase_readymade not added.'];

                            $result = $this->update_purchase_readymade($trans_data);
                            if(!isset($result['status'])) return $result;
                        }else{
                            $prev_data = $this->db_operations->get_record('payment_purchase_readymade_trans', ['pprt_id' => $post_data['pprt_id'][$key], 'pprt_delete_status' => false]);
                            if(empty($prev_data)) return ['msg' => '1. Purchase_readymade transaction not found.'];
                        
                            if($this->db_operations->data_update('payment_purchase_readymade_trans', $trans_data, 'pprt_id', $prev_data[0]['pprt_id']) < 1){
                                return ['msg' => '1. Purchase_readymade transaction not updated.'];
                            }
                        
                            $result = $this->delete_purchase_readymade($prev_data[0]);
                            if(!isset($result['status'])) return $result;
                        
                            $result = $this->update_purchase_readymade($trans_data);
                            if(!isset($result['status'])) return $result;
                        }
                    }
                }
            }
            return ['status' => TRUE];
        }
    // payment_purchase_readymade_trans
    
    // payment_payment_mode_trans
        public function get_payment_mode_data(){
            $post_data                  = $this->input->post();
            $id                         = $post_data['id'];
            $data['payment_mode_data']  = $this->model->get_payment_mode_data($id);
            // $data['cash_data']          = $this->model->get_cash_data($id);
            // $data['bank_data']          = $this->model->get_bank_data($id);
            return ['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
        }
		public function add_update_payment_mode($post_data, $id){
            $trans_db_data = $this->db_operations->get_record('payment_payment_mode_trans', ['ppmt_payment_id' => $id, 'ppmt_delete_status' => false]);
            if(!empty($trans_db_data)){
                foreach ($trans_db_data as $key => $value){
                    if(!in_array($value['ppmt_id'], $post_data['ppmt_id'])){
                        $update_data                        = [];
                        $update_data['ppmt_delete_status']  = true;
                        $update_data['ppmt_updated_by']     = $_SESSION['user_id'];
                        $update_data['ppmt_updated_at']     = date('Y-m-d H:i:s');
                        if($this->db_operations->data_update('payment_payment_mode_trans', $update_data, 'ppmt_id', $value['ppmt_id']) < 1){
                            return ['msg' => '1. Payment mode not deleted.'];
                        }
                    }
                }
                foreach ($post_data['ppmt_amt'] as $key => $value) {
                    if($value <= 0){
                        $update_data                        = [];
                        $update_data['ppmt_delete_status']  = true;
                        $update_data['ppmt_updated_by']     = $_SESSION['user_id'];
                        $update_data['ppmt_updated_at']     = date('Y-m-d H:i:s');
                        if($this->db_operations->data_update('payment_payment_mode_trans', $update_data, 'ppmt_id', $post_data['ppmt_id'][$key]) < 1){
                            return ['msg' => '1. Payment mode not deleted.'];
                        }
                    }
                }
            }
            foreach ($post_data['ppmt_amt'] as $key => $value){
                if($value > 0){
                    $trans_data                         = [];
                    $trans_data['ppmt_payment_id']      = $id;
                    $trans_data['ppmt_payment_uuid']    = $post_data['payment_uuid'];
                    $trans_data['ppmt_payment_mode_id'] = $post_data['ppmt_payment_mode_id'][$key];
                    $trans_data['ppmt_amt']             = $post_data['ppmt_amt'][$key];
                    $trans_data['ppmt_delete_status']   = false;
                    $trans_data['ppmt_updated_by']      = $_SESSION['user_id'];
                    $trans_data['ppmt_updated_at']      = date('Y-m-d H:i:s');
                    
                    if(empty($post_data['ppmt_id'][$key])){
                        $trans_data['ppmt_created_by']  = $_SESSION['user_id'];
                        $trans_data['ppmt_created_at']  = date('Y-m-d H:i:s');
                        if($this->db_operations->data_insert('payment_payment_mode_trans', $trans_data) < 1){
                            return ['msg' => '1. Payment mode not added.'];
                        }
                    }
                }
            }
            return ['status' => TRUE];
        }
	// payment_payment_mode_trans

    // purchase_master
		public function update_purchase($temp){
			$data = $this->model->get_purchase($temp['ppt_pm_id']);
			if(empty($data)) return ['msg' => '1. Purchase not found.'];

			if($temp['ppt_adjust_amt'] > $data[0]['balance_amt']) return ['msg' => '1. Purchase adjusted amt should be less than purchase balance amt.'];

			$master_data = [];
			$master_data['pm_allocated_amt'] = $data[0]['pm_allocated_amt'] + $temp['ppt_adjust_amt'];

			if($this->db_operations->data_update('purchase_master', $master_data, 'pm_id', $data[0]['pm_id']) < 1) return ['msg' => '1. Purchase not updated.'];

			return ['status' => TRUE];
		}
		public function delete_purchase($temp){
			$data = $this->db_operations->get_record('purchase_master', ['pm_id' => $temp['ppt_pm_id'], 'pm_delete_status' => false]);
			if(empty($data)) return ['msg' => '2. Purchase not found.'];

			if($temp['ppt_adjust_amt'] > $data[0]['pm_allocated_amt']) return ['msg' => '1. Purchase adjusted amt should be less than purchase allocated amt.'];

			$master_data = [];
			$master_data['pm_allocated_amt'] = $data[0]['pm_allocated_amt'] - $temp['ppt_adjust_amt'];

			if($this->db_operations->data_update('purchase_master', $master_data, 'pm_id', $data[0]['pm_id']) < 1) return ['msg' => '2. Purchase not updated.'];

			return ['status' => TRUE];
		}
	// purchase_master

    // purchase_readymade_master
        public function update_purchase_readymade($temp){
            $data = $this->model->get_purchase_readymade($temp['pprt_prmm_id']);
            if(empty($data)) return ['msg' => '1. Purchase_readymade not found.'];

            if($temp['pprt_adjust_amt'] > $data[0]['balance_amt']) return ['msg' => '1. Purchase_readymade adjusted amt should be less than purchase_readymade balance amt.'];
            $master_data = [];
            $master_data['prmm_allocated_amt'] = $data[0]['prmm_allocated_amt'] + $temp['pprt_adjust_amt'];
            if($this->db_operations->data_update('purchase_readymade_master', $master_data, 'prmm_id', $data[0]['prmm_id']) < 1) return ['msg' => '1. Purchase_readymade not updated.'];

            return ['status' => TRUE];
        }
        public function delete_purchase_readymade($temp){
            $data = $this->db_operations->get_record('purchase_readymade_master', ['prmm_id' => $temp['pprt_prmm_id'], 'prmm_delete_status' => false]);
            if(empty($data)) return ['msg' => '2. Purchase_readymade not found.'];

            if($temp['pprt_adjust_amt'] > $data[0]['prmm_allocated_amt']) return ['msg' => '1. Purchase_readymade adjusted amt should be less than purchase_readymade allocated amt.'];

            $master_data = [];
            $master_data['prmm_allocated_amt'] = $data[0]['prmm_allocated_amt'] - $temp['pprt_adjust_amt'];

            if($this->db_operations->data_update('purchase_readymade_master', $master_data, 'prmm_id', $data[0]['prmm_id']) < 1) return ['msg' => '2. Purchase_readymade not updated.'];

            return ['status' => TRUE];
        }
    // purchase_readymade_master
}
?>