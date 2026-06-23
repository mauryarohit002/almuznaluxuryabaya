<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class hisab extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'transaction'; 
        $this->sub_menu = 'hisab'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function remove(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$data = $this->db_operations->get_record('hisab_master', ['hm_id' => $id, 'hm_delete_status' => false]);
		if(empty($data)) return ['status' => REFRESH, 'msg' => '3. Hisab not found.'];	

		if($this->model->isExist($id)) return ['msg' => '1. Not allowed to delete.'];	

		$trans_data = $this->db_operations->get_record('hisab_trans', ['ht_hm_id' => $id, 'ht_delete_status' => false]);
		if(empty($trans_data)) return ['msg' => '2. Transaction not found.'];
		
		$this->db->trans_begin();
		
		foreach ($trans_data as $key => $value) {
			if($this->model->isTransExist($value['ht_id'])){
				$this->db->trans_rollback();
				return ['msg' => '2. Not allowed to delete transaction.'];
			}

            if($this->db_operations->data_update('job_issue_master', ['jim_hm_id' => 0, 'jim_ht_id' => 0], 'jim_id', $value['ht_jim_id']) < 1){
                return ['msg' => 'Job issue not updated.'];
            }
            
			$update_data 						= [];
			$update_data['ht_delete_status'] 	= true; 
			$update_data['ht_updated_by'] 		= $_SESSION['user_id']; 
			$update_data['ht_updated_at'] 		= date('Y-m-d H:i:s'); 
			if($this->db_operations->data_update('hisab_trans', $update_data, 'ht_id', $value['ht_id']) < 1){
				$this->db->trans_rollback();
				return ['msg' => '2. Transaction not deleted.'];
			}
		}

		$update_data 						= [];
		$update_data['hm_entry_no'] 		= $data[0]['hm_entry_no'].''.$id; 
		$update_data['hm_delete_status'] 	= true; 
		$update_data['hm_updated_by'] 		= $_SESSION['user_id']; 
		$update_data['hm_updated_at'] 		= date('Y-m-d H:i:s'); 
		if($this->db_operations->data_update('hisab_master', $update_data, 'hm_id', $id) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Hisab not deleted.'];
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return ['msg' => '2. Transaction Rollback.'];
		}
		$this->db->trans_commit();

		return ['status' => TRUE, 'msg' => 'Hisab deleted successfully'];
	}
    public function get_job_data(){
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        $data       = $this->model->get_job_data($id);
        return['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
	}
    
    
    // hisab_master
        public function add_edit(){
            $post_data  = $this->input->post();
            $id         = $post_data['id'];
			$post_data['trans_data'] = json_decode($post_data['trans_data'], true);
            if(!isset($post_data['trans_data']) || (isset($post_data['trans_data']) && empty($post_data['trans_data']))) return ['msg' => '1. Item not aded in list.'];
            // echo "<pre>"; print_r($post_data); exit;
            // master_data
                $master_data['hm_uuid'] 				    = trim($post_data['hm_uuid']);
                $master_data['hm_entry_no'] 				= trim($post_data['hm_entry_no']);
                $master_data['hm_entry_date'] 				= date('Y-m-d', strtotime($post_data['hm_entry_date']));
                $master_data['hm_karigar_id'] 				= trim($post_data['hm_karigar_id']);
                $master_data['hm_notes'] 			        = trim($post_data['hm_notes']);
                $master_data['hm_total_qty'] 				= trim($post_data['hm_total_qty']);
                $master_data['hm_total_amt']				= trim($post_data['hm_total_amt']);
                $master_data['hm_updated_by'] 				= $_SESSION['user_id'];
            // master_data

            $this->db->trans_begin();
            if($id == 0){
                $master_data['hm_entry_no'] 	= $this->model->get_max_entry_no(['entry_no' => 'hm_entry_no', 'delete_status' => 'hm_delete_status', 'fin_year' => 'hm_fin_year']);
                $master_data['hm_created_by'] 	= $_SESSION['user_id'];
                $master_data['hm_created_at'] 	= date('Y-m-d H:i:s');
                $master_data['hm_fin_year'] 	= $_SESSION['fin_year'];
                $master_data['hm_branch_id'] 	= $_SESSION['user_branch_id'];
                $uuidExist 						= $this->db_operations->get_cnt($this->sub_menu.'_master', ['hm_uuid' => $master_data['hm_uuid']]);
                if($uuidExist > 0){
                    $this->db->trans_rollback();
                    return ['msg' => 'Form already submited.'];
                }
                $id = $this->db_operations->data_insert('hisab_master', $master_data);
                $msg = 'Hisab added successfully.';
                if($id < 1){
                    $this->db->trans_rollback();
                    return ['msg' => '1. Hisab not added.'];
                }
            }else{
                $prev_data = $this->db_operations->get_record('hisab_master', ['hm_id' => $id, 'hm_delete_status' => false]);
                if(empty($prev_data)){
                    $this->db->trans_rollback();
                    return ['status' => REFRESH, 'msg' => '1. Hisab not found.'];
                }
                $msg = 'Hisab updated successfully.';
                if($this->db_operations->data_update('hisab_master', $master_data, 'hm_id', $id) < 1){
                    $this->db->trans_rollback();
                    return ['msg' => '1. Hisab not updated.'];
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
            $data['name'] 	= strtoupper($master_data['hm_entry_no']);
            return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
        }
    // hisab_master
    
    // hisab_trans
        public function add_update_trans($post_data, $id){
            $trans_db_data = $this->db_operations->get_record($this->sub_menu.'_trans', ['ht_hm_id' => $id, 'ht_delete_status' => false]);
            $ids 	   	   = $this->get_id($post_data);
            if(!empty($trans_db_data)){
                foreach ($trans_db_data as $key => $value){
                    if(!in_array($value['ht_id'], $ids)){
                        if($this->model->isTransExist($value['ht_id'])) return ['msg' => '4. Not allowed to delete.'];

                        if($this->db_operations->data_update('job_issue_master', ['jim_hm_id' => 0, 'jim_ht_id' => 0], 'jim_id', $value['ht_jim_id']) < 1){
                            return ['msg' => 'Job issue not updated.'];
                        }

                        $update_data 						= [];
                        $update_data['ht_delete_status'] 	= true;
                        $update_data['ht_updated_by'] 		= $_SESSION['user_id'];
                        $update_data['ht_updated_at'] 		= date('Y-m-d H:i:s');
                        if($this->db_operations->data_update($this->sub_menu.'_trans', $update_data, 'ht_id', $value['ht_id']) < 1){
                            return ['msg' => '4. Transaction not deleted.'];
                        }
                    } 
                }
            }
            foreach ($post_data['trans_data'] as $key => $value){
                $trans_data							= [];
                $trans_data['ht_hm_id']				= $id;
                $trans_data['ht_jim_id'] 		    = $value['ht_jim_id'];

                $trans_data['ht_obt_id']         = $value['ht_obt_id'];
                $trans_data['ht_jit_id']         = $value['ht_jit_id'];
                $trans_data['ht_jrt_id']         = $value['ht_jrt_id']; 

                $trans_data['ht_apparel_id'] 		= $value['ht_apparel_id'];
                $trans_data['ht_rate']				= $value['ht_rate'];
                $trans_data['ht_delete_status']		= false;
                $trans_data['ht_updated_by'] 		= $_SESSION['user_id'];
                $trans_data['ht_updated_at'] 		= date('Y-m-d H:i:s');
                
                if(empty($value['ht_id'])){
                    $trans_data['ht_created_by'] 	= $_SESSION['user_id'];
                    $trans_data['ht_created_at'] 	= date('Y-m-d H:i:s');
                    $ht_id = $this->db_operations->data_insert($this->sub_menu.'_trans', $trans_data);
                    if($ht_id < 1) return ['msg' => 'Transaction not added.'];

                    if($this->db_operations->data_update('job_issue_master', ['jim_hm_id' => $id, 'jim_ht_id' => $ht_id], 'jim_id', $trans_data['ht_jim_id']) < 1){
                        return ['msg' => 'Job issue not updated.'];
                    }
                }
            }
            return ['status' => TRUE];
        }
        public function get_id($post_data){
			$record = [];
			foreach ($post_data['trans_data'] as $key => $value) {
				array_push($record, $value['ht_id']);
			}
			return $record;
		}
    // hisab_trans
}
?>