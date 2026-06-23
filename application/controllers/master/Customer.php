<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class customer extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'master'; 
        $this->sub_menu = 'customer'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
	public function get_measurement(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$apparel_id = $post_data['apparel_id'];
		
		$data['latest_measurement'] = $this->model->get_latest_measurement($id, $apparel_id);
		$data['measurement_data'] 	= $this->model->get_measurement($id, $apparel_id);
		return['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
	}
	public function upload_document(){
		$files = $_FILES;
		// echo "<pre>"; print_r($files);exit;
		if(empty($files)) return ['msg' => 'Document is empty.'];			
		$cnt = isset($files['customer_attachment']['name']) ? count($files['customer_attachment']['name']) : 0;
		$data=[];
		for($i = 0; $i < $cnt; $i++){
			if($files['customer_attachment']['error'][$i] != 0) return ['msg' => 'Error in Image.'];

			$_FILES['customer_attachment']['name']		= $files['customer_attachment']['name'][$i];
			$_FILES['customer_attachment']['type']		= $files['customer_attachment']['type'][$i];
	        $_FILES['customer_attachment']['tmp_name']	= $files['customer_attachment']['tmp_name'][$i];
	        $_FILES['customer_attachment']['error']		= $files['customer_attachment']['error'][$i];
	        $_FILES['customer_attachment']['size']		= $files['customer_attachment']['size'][$i];

	        unset($config);
			$config 					= array();
			$config['upload_path'] 		= 'public/uploads/customer/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg|pdf';
	      	$file_name 					= $files['customer_attachment']['name'][$i];

	      	$ext 						= strtolower(substr($file_name, strrpos($file_name, '.') + 1));
	      	$filename 					= $i.''.time().'.'.$ext;
	      	$config['file_name'] 		= $filename;
	      	if(!file_exists($config['upload_path'])){
	      		mkdir($config['upload_path'], 0777);
	      	}
	  		$this->upload->initialize($config);
			if(!$this->upload->do_upload('customer_attachment')) return ['msg' => 'Document not uploaded.'];
			$imageinfo = $this->upload->data();
			$full_path = $imageinfo['full_path'];
				
			// check EXIF and autorotate if needed
			// $this->db_operations->image_autorotate_resize(array('filepath' => $full_path), TRUE);		
			$customer_attachment_trans 					= [];
			$customer_attachment_trans['cat_path'] 		= uploads('customer/'.$filename);
			$customer_attachment_trans['cat_created_by'] 	= $_SESSION['user_id'];
			$customer_attachment_trans['cat_updated_by'] 	= $_SESSION['user_id'];
			$customer_attachment_trans['cat_created_at']	= date('Y-m-d H:i:s');
			$id = $this->db_operations->data_insert('customer_attachment_trans', $customer_attachment_trans);
			if($id < 1) return ['msg' => 'Document not inserted in database.'];
			array_push($data, ['cat_id' => $id, 'cat_customer_id' => 0, 'cat_path' => uploads('customer/'.$filename)]);
		}
		return ['status' => TRUE, 'data' => $data,  'msg' => 'Document added successfully.'];
	}
	public function remove_attachment($value){
		if($this->db_operations->delete_record('customer_attachment_trans', ['cat_id' => $value['cat_id']]) < 1){
			return ['msg' => 'Attachment not deleted.'];
		}
		$explode   = explode('/', $value['cat_path']);
		$file_name = 'public/uploads/customer/'.end($explode);
		// echo "<pre>";print_r($file_name); exit();
		if(file_exists($file_name)){
			unlink($file_name);
		}
		return ['status' => TRUE];
	}
	public function measurement_print($customer_id, $apparel_id = 0){
        $record = $this->model->measurement_print($customer_id, $apparel_id);
        // echo "<pre>"; print_r($record); exit;
        $this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/pdf/measurement', $record);
    }

	public function remove(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];

		$result = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$data = $this->db_operations->get_record($this->sub_menu.'_master', ['customer_id' => $id]);
		if(empty($data)) return ['status' => REFRESH, 'msg' => '1. Customer not found.'];

		if($this->model->isExist($id)) return ['msg' => 'Not allowed to delete.'];

		$this->db->trans_begin();
		
		$attachment = $this->db_operations->get_record('customer_attachment_trans', ['cat_customer_id' => $id]);
		if(!empty($attachment)){
			foreach ($attachment as $key => $value) {
				$result = $this->remove_attachment($value);
				if(!isset($result['status'])){
					$this->db->trans_rollback();
					return $result;
				}
			}
		}		
		if($this->db_operations->delete_record($this->sub_menu.'_master', ['customer_id' => $id]) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Customer not deleted.'];
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return ['msg' => 'Transaction Rollback.'];
	    }
	    $this->db->trans_commit();

		return ['status' => TRUE, 'msg' => 'Customer deleted successfully'];
	}
	public function add(){
		$post_data 	= $this->input->post();
		$files 		= $_FILES;
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		unset($post_data['func']);
		unset($post_data['id']);

		// echo "<pre>"; print_r($post_data);die;
		
		// master_data
			$post_data['customer_city_id'] 			= isset($post_data['customer_city_id']) ? $post_data['customer_city_id'] : 0;
			$post_data['customer_state_id'] 		= isset($post_data['customer_state_id']) ? $post_data['customer_state_id'] : 0;
			$post_data['customer_country_id'] 		= isset($post_data['customer_country_id']) ? $post_data['customer_country_id'] : 0;
			$post_data['customer_branch_id'] 		= isset($post_data['customer_branch_id']) ? $post_data['customer_branch_id'] : $_SESSION['user_branch_id'];
			$post_data['customer_updated_by'] 		= $_SESSION['user_id'];
			$post_data['customer_updated_at']       = date('Y-m-d H:i:s');
		// master_data

		$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['customer_id !=' => $id, 'customer_mobile' => $post_data['customer_mobile'], 'customer_branch_id' => $post_data['customer_branch_id']]);
		if(!empty($temp)) return ['msg' => 'Customer already exist.'];	

		$post_data['customer_no'] 			= $this->model->get_max_no();
		$post_data['customer_created_by'] 	= $_SESSION['user_id'];
		$post_data['customer_created_at'] 	= date('Y-m-d H:i:s');

		$id = $this->db_operations->data_insert($this->sub_menu.'_master', $post_data);
		if($id < 1) return ['msg' => 'Customer not added.'];
		
		$data['id'] 	= $id;
		$data['name'] 	= strtoupper($post_data['customer_name']).' - '.$post_data['customer_mobile'];
		return ['status' => TRUE, 'data' => $data,  'msg' => 'Customer added successfully.'];
	}
	public function add_edit(){
		$post_data 	= $this->input->post();
		$files 		= $_FILES;
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
		
		// master_data
			$master_data 								= [];
			$master_data['customer_uuid'] 				= trim($post_data['customer_uuid']);
			$master_data['customer_no'] 				= trim($post_data['customer_no']);
			$master_data['customer_name'] 				= trim($post_data['customer_name']);
			$master_data['customer_mobile'] 			= trim($post_data['customer_mobile']);
			$master_data['customer_same_as_mobile'] 	= isset($post_data['customer_same_as_mobile']);
			$master_data['customer_whatsapp'] 			= trim($post_data['customer_whatsapp']);
			$master_data['customer_phone1'] 			= trim($post_data['customer_phone1']);
			$master_data['customer_phone2'] 			= trim($post_data['customer_phone2']);
			$master_data['customer_email'] 				= trim($post_data['customer_email']);
			$master_data['customer_birth_date']         = trim($post_data['customer_birth_date']);
			$master_data['customer_anniversary_date']   = trim($post_data['customer_anniversary_date']);
			$master_data['customer_gst_no'] 			= trim($post_data['customer_gst_no']);
			$master_data['customer_refer_type'] 		= trim($post_data['customer_refer_type']);
			if(isset($post_data['customer_refer_by'])){
				$master_data['customer_refer_by'] 		= $post_data['customer_refer_by'];
				$master_data['customer_refer_id'] 		= 0;
			}
			if(isset($post_data['customer_refer_id'])){
				$master_data['customer_refer_id'] 		= $post_data['customer_refer_id'];
				$master_data['customer_refer_by'] 		= '';
			}
			$master_data['customer_status'] 			= isset($post_data['customer_status']);
			$master_data['customer_disc_per'] 			= trim($post_data['customer_disc_per']);
			$master_data['customer_credit_amt'] 		= trim($post_data['customer_credit_amt']);
			$master_data['customer_credit_day'] 		= trim($post_data['customer_credit_day']);
			$master_data['customer_opening_amt'] 		= trim($post_data['customer_opening_amt']);
			$master_data['customer_type'] 		        = trim($post_data['customer_type']);
			$master_data['customer_sms_service'] 		= isset($post_data['customer_sms_service']);
			$master_data['customer_whatsapp_service'] 	= isset($post_data['customer_whatsapp_service']);
			$master_data['customer_email_service'] 		= isset($post_data['customer_email_service']);
			$master_data['customer_dnd_service'] 		= isset($post_data['customer_dnd_service']);
			$master_data['customer_address'] 			= trim($post_data['customer_address']);
			$master_data['customer_city_id'] 			= isset($post_data['customer_city_id']) ? $post_data['customer_city_id'] : 0;
			$master_data['customer_state_id'] 			= isset($post_data['customer_state_id']) ? $post_data['customer_state_id'] : 0;
			$master_data['customer_country_id'] 		= isset($post_data['customer_country_id']) ? $post_data['customer_country_id'] : 0;
			$master_data['customer_branch_id'] 		= isset($post_data['customer_branch_id']) ? $post_data['customer_branch_id'] : $_SESSION['user_branch_id'];
			$master_data['customer_updated_by'] 		= $_SESSION['user_id'];
			$master_data['customer_updated_at']         = date('Y-m-d H:i:s');
		// master_data

		$past_date 	= strtotime(date('Y-m-d', strtotime('-50 YEARS')));
		$today_date = strtotime(date('Y-m-d'));
		$temp 		= $this->db_operations->get_record($this->sub_menu.'_master', ['customer_id !=' => $id, 'customer_mobile' => $master_data['customer_mobile'], 'customer_branch_id' => $master_data['customer_branch_id']]);
		if(!empty($temp)) return ['msg' => 'Customer already exist.'];	

		if($master_data['customer_mobile'] != ''){
			$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['customer_id !=' => $id, 'customer_mobile' => $master_data['customer_mobile'], 'customer_branch_id' => $master_data['customer_branch_id']]);
			if(!empty($temp)) return ['msg' => 'Mobile no. already exist.'];	
		}
		if($master_data['customer_whatsapp'] != ''){
			$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['customer_id !=' => $id, 'customer_whatsapp' => $master_data['customer_whatsapp'], 'customer_branch_id' => $master_data['customer_branch_id']]);
			if(!empty($temp)) return ['msg' => 'Whatsapp no. already exist.'];	
		}
		if($master_data['customer_email'] != ''){
			$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['customer_id !=' => $id, 'customer_email' => $master_data['customer_email'], 'customer_branch_id' => $master_data['customer_branch_id']]);
			if(!empty($temp)) return ['msg' => 'Email already exist.'];	
		}
		if($master_data['customer_gst_no'] != ''){
			$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['customer_id !=' => $id, 'customer_gst_no' => $master_data['customer_gst_no'], 'customer_branch_id' => $master_data['customer_branch_id']]);
			if(!empty($temp)) return ['msg' => 'Gst no. already exist.'];	
		}
		if($master_data['customer_birth_date'] != ''){
			$date = strtotime($master_data['customer_birth_date']);
			if(($date <= $past_date) || ($date >= $today_date)) return ['msg' => 'Invalid birthdate.'];	
		}
		if($master_data['customer_anniversary_date'] != ''){
			$date = strtotime($master_data['customer_anniversary_date']);
			if(($date <= $past_date) || ($date >= $today_date)) return ['msg' => 'Invalid anniversary date.'];	
		}
		
		$this->db->trans_begin();
		if($id == 0){
			$master_data['customer_no'] 		= $this->model->get_max_no();
			$master_data['customer_created_by'] = $_SESSION['user_id'];
			$master_data['customer_created_at'] = date('Y-m-d H:i:s');
			$uuidExist 							= $this->db_operations->get_cnt($this->sub_menu.'_master', ['customer_uuid' => $master_data['customer_uuid']]);
			if($uuidExist > 0){
				$this->db->trans_rollback();
				return ['msg' => '1. Form already submitted.'];
			}
			$id = $this->db_operations->data_insert($this->sub_menu.'_master', $master_data);
			$msg = 'Customer added successfully.';
			if($id < 1){
				$this->db->trans_rollback();
				return ['msg' => 'Customer not added.'];
			}
		}else{
			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', ['customer_id' => $id]);
			if(empty($prev_data)){
				$this->db->trans_rollback();
				return ['status' => REFRESH, 'msg' => 'Customer not found.'];
			}
			$msg = 'Customer updated successfully.';
			if($this->db_operations->data_update($this->sub_menu.'_master', $master_data, 'customer_id', $id) < 1){
				$this->db->trans_rollback();
				return ['msg' => 'Customer not updated.'];
			}
		}

		if(isset($post_data['cat_id'])){
			$result = $this->add_edit_attachment($post_data, $id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				return $result;
			}	
		}else{
			$attachment = $this->db_operations->get_record('customer_attachment_trans', ['cat_customer_id' => $id]);
			if(!empty($attachment)){
				foreach ($attachment as $key => $value) {
					$result = $this->remove_attachment($value);
					if(!isset($result['status'])){
						$this->db->trans_rollback();
						return $result;
					}
				}
			}
		}
		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return ['msg' => 'Transaction Rollback.'];
	    }
	    $this->db->trans_commit();

		$data['id'] 	= $id;
		$data['name'] 	= strtoupper($master_data['customer_name']).' - '.$master_data['customer_mobile'];
		return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
	}
	public function add_edit_attachment($post_data, $id){
		$trans_db_data = $this->db_operations->get_record('customer_attachment_trans', ['cat_customer_id' => $id]);
		if(!empty($trans_db_data)){
			foreach ($trans_db_data as $key => $value){
				if(!in_array($value['cat_id'], $post_data['cat_id'])){
					$result = $this->remove_attachment($value);
					if(!isset($result['status'])) return $result;

					if($this->db_operations->delete_record('customer_attachment_trans', ['cat_id' => $value['cat_id']]) < 1){
						return ['msg' => 'Attachment not deleted.'];
					}
				} 
			}
		}
		foreach ($post_data['cat_id'] as $key => $value){
			$trans_data['cat_customer_id']		= $id;
			$trans_data['cat_path']				= $post_data['cat_path'][$key];
			$trans_data['cat_updated_by'] 		= $_SESSION['user_id'];
			if($value == 0){
				$trans_data['cat_created_by'] 	= $_SESSION['user_id'];
				$trans_data['cat_created_at'] 	= date('Y-m-d H:i:s');
				$cat_id = $this->db_operations->data_insert('customer_attachment_trans', $trans_data);
				if($cat_id < 1) return ['msg' => 'Attachment not added.'];
			}else{
				$prev_data = $this->db_operations->get_record('customer_attachment_trans', ['cat_id' => $value]);
				if(empty($prev_data)) return ['msg' => 'Attachment not found.'];

				if($this->db_operations->data_update('customer_attachment_trans', $trans_data, 'cat_id', $value) < 1){
					return ['msg' => 'Attachment not updated.'];
				}
			}
		}
		return ['status' => TRUE];
	}
		
}
?>
