<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class karigar extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'master'; 
        $this->sub_menu = 'karigar'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function upload_document(){
		$files = $_FILES;
		// echo "<pre>"; print_r($files);exit;
		if(empty($files)) return ['msg' => 'Document is empty.'];			
		$cnt = isset($files['karigar_attachment']['name']) ? count($files['karigar_attachment']['name']) : 0;
		$data=[];
		for($i = 0; $i < $cnt; $i++){
			if($files['karigar_attachment']['error'][$i] != 0) return ['msg' => 'Error in Image.'];

			$_FILES['karigar_attachment']['name']		= $files['karigar_attachment']['name'][$i];
			$_FILES['karigar_attachment']['type']		= $files['karigar_attachment']['type'][$i];
	        $_FILES['karigar_attachment']['tmp_name']	= $files['karigar_attachment']['tmp_name'][$i];
	        $_FILES['karigar_attachment']['error']		= $files['karigar_attachment']['error'][$i];
	        $_FILES['karigar_attachment']['size']		= $files['karigar_attachment']['size'][$i];

	        unset($config);
			$config 					= array();
			$config['upload_path'] 		= 'public/uploads/karigar/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg|pdf';
	      	$file_name 					= $files['karigar_attachment']['name'][$i];

	      	$ext 						= strtolower(substr($file_name, strrpos($file_name, '.') + 1));
	      	$filename 					= $i.''.time().'.'.$ext;
	      	$config['file_name'] 		= $filename;
	      	if(!file_exists($config['upload_path'])){
	      		mkdir($config['upload_path'], 0777);
	      	}
	  		$this->upload->initialize($config);
			if(!$this->upload->do_upload('karigar_attachment')) return ['msg' => 'Document not uploaded.'];
			$imageinfo = $this->upload->data();
			$full_path = $imageinfo['full_path'];
				
			// check EXIF and autorotate if needed
			// $this->db_operations->image_autorotate_resize(array('filepath' => $full_path), TRUE);		
			$karigar_attachment_trans 					= [];
			$karigar_attachment_trans['kat_path'] 		= uploads('karigar/'.$filename);
			$karigar_attachment_trans['kat_created_by'] = $_SESSION['user_id'];
			$karigar_attachment_trans['kat_updated_by'] = $_SESSION['user_id'];
			$karigar_attachment_trans['kat_created_at']	= date('Y-m-d H:i:s');
			$id = $this->db_operations->data_insert('karigar_attachment_trans', $karigar_attachment_trans);
			if($id < 1) return ['msg' => 'Document not inserted in database.'];
			array_push($data, ['kat_id' => $id, 'kat_karigar_id' => 0, 'kat_path' => uploads('karigar/'.$filename)]);
		}
		return ['status' => TRUE, 'data' => $data,  'msg' => 'Document added successfully.'];
	}
    public function remove_attachment($value){
		if($this->db_operations->delete_record('karigar_attachment_trans', ['kat_id' => $value['kat_id']]) < 1){
			return ['msg' => 'Attachment not deleted.'];
		}
		$explode   = explode('/', $value['kat_path']);
		$file_name = 'public/uploads/karigar/'.end($explode);
		// echo "<pre>";print_r($file_name); exit();
		if(file_exists($file_name)){
			unlink($file_name);
		}
		return ['status' => TRUE];
	}
    public function get_image($post_data, $files){
		if($files['karigar_image']['error'] == 0){
			$_FILES['karigar_image']['name']		= $files['karigar_image']['name'];
			$_FILES['karigar_image']['type']		= $files['karigar_image']['type'];
	        $_FILES['karigar_image']['tmp_name']	= $files['karigar_image']['tmp_name'];
	        $_FILES['karigar_image']['error']		= $files['karigar_image']['error'];
	        $_FILES['karigar_image']['size']		= $files['karigar_image']['size'];

			// echo "<pre>"; print_r($_FILES); exit;
			unset($config);
			$config 					= array();
			$config['upload_path'] 		= 'public/uploads/karigar/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg';
	      	$file_name 					= $files['karigar_image']['name'];
	      	if(!file_exists($config['upload_path'])){
	      		mkdir($config['upload_path'], 0777);
	      	}
	      	$ext 						= strtolower(substr($file_name, strrpos($file_name, '.') + 1));
	      	$filename 					= time().'.'.$ext;
	      	$config['file_name'] 		= $filename;

	      	$this->upload->initialize($config);
	      	if(!$this->upload->do_upload('karigar_image')){
	      		return ['status' => FALSE, 'data' => '', 'msg' => $this->upload->display_errors()];
	      	}
	      	$imageinfo = $this->upload->data();
			$full_path = $imageinfo['full_path'];
			
			// check EXIF and autorotate if needed
			// $this->db_operations->image_autorotate_resize(array('filepath' => $full_path), TRUE);		
			return ['status' => TRUE, 'data' => uploads('karigar/'.$filename), 'msg' => ''];
		}
		return ['status' => TRUE, 'data' => $post_data['karigar_pic'], 'msg' => ''];
	}
	public function get_proces(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		
		$data = $this->model->get_proces($id);
		return['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
	}
	public function get_apparel(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		
		$data = $this->model->get_apparel($id);
		return['status' => TRUE, 'data' => $data, 'msg' => 'Record fetched successfully.'];
	}

	public function remove(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];

		$result = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$data = $this->db_operations->get_record($this->sub_menu.'_master', ['karigar_id' => $id]);
		if(empty($data)) return ['status' => REFRESH, 'msg' => '1. Karigar not found.'];

		if($this->model->isExist($id)) return ['msg' => 'Not allowed to delete.'];

		$this->db->trans_begin();
		
		$attachment = $this->db_operations->get_record('karigar_attachment_trans', ['kat_karigar_id' => $id]);
		if(!empty($attachment)){
			foreach ($attachment as $key => $value) {
				$result = $this->remove_attachment($value);
				if(!isset($result['status'])){
					$this->db->trans_rollback();
					return $result;
				}
			}
		}		
		if($this->db_operations->delete_record($this->sub_menu.'_proces_trans', ['kpt_karigar_id' => $id]) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Process not deleted.'];
		}
        if($this->db_operations->delete_record($this->sub_menu.'_apparel_trans', ['kapt_karigar_id' => $id]) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Apparel not deleted.'];
		}
        if($this->db_operations->delete_record($this->sub_menu.'_master', ['karigar_id' => $id]) < 1){
			$this->db->trans_rollback();
			return ['msg' => 'Karigar not deleted.'];
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return ['msg' => 'Transaction Rollback.'];
	    }
	    $this->db->trans_commit();

		return ['status' => TRUE, 'msg' => 'Karigar deleted successfully'];
	}
    public function add_edit(){
		$post_data 	= $this->input->post();
		$files 		= $_FILES;
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

        // echo "<pre>"; print_r($post_data);
        // echo "<pre>"; print_r($files);exit;

        $result = $this->get_image($post_data, $files);
		if(!isset($result['status'])) return $result;
		
		// master_data
			$master_data 							= [];
			$master_data['karigar_code'] 			= trim($post_data['karigar_code']);
			$master_data['karigar_name'] 			= trim($post_data['karigar_name']);
			$master_data['karigar_mobile'] 			= trim($post_data['karigar_mobile']);
			$master_data['karigar_type_id'] 		= trim($post_data['karigar_type_id']);
			$master_data['karigar_email'] 			= trim($post_data['karigar_email']);
            $master_data['karigar_refer_by'] 		= trim($post_data['karigar_refer_by']);
			$master_data['karigar_status'] 			= isset($post_data['karigar_status']);
			$master_data['karigar_aadhaar'] 		= trim($post_data['karigar_aadhaar']);
			$master_data['karigar_pan'] 			= trim($post_data['karigar_pan']);
			$master_data['karigar_address'] 	    = trim($post_data['karigar_address']);
			$master_data['karigar_pincode'] 	    = trim($post_data['karigar_pincode']);
			$master_data['karigar_city_id'] 		= isset($post_data['karigar_city_id']) ? $post_data['karigar_city_id'] : 0;
			$master_data['karigar_state_id'] 		= isset($post_data['karigar_state_id']) ? $post_data['karigar_state_id'] : 0;
			$master_data['karigar_country_id'] 		= isset($post_data['karigar_country_id']) ? $post_data['karigar_country_id'] : 0;

			$master_data['karigar_bank_name'] 		= trim($post_data['karigar_bank_name']);
			$master_data['karigar_account_no'] 		= trim($post_data['karigar_account_no']);
			$master_data['karigar_account_name'] 	= trim($post_data['karigar_account_name']);
			$master_data['karigar_branch_name'] 	= trim($post_data['karigar_branch_name']);
			$master_data['karigar_ifsc_code'] 		= trim($post_data['karigar_ifsc_code']);

            $master_data['karigar_image'] 			= $result['data'];
			$master_data['karigar_updated_by'] 		= $_SESSION['user_id'];
			$master_data['karigar_updated_at']      = date('Y-m-d H:i:s');
		// master_data

        $temp = $this->db_operations->get_record($this->sub_menu.'_master', ['karigar_id !=' => $id, 'karigar_code' => $master_data['karigar_code']]);
		if(!empty($temp)) return ['msg' => 'Karigar already exist.'];	

		if($master_data['karigar_mobile'] != ''){
			$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['karigar_id !=' => $id, 'karigar_mobile' => $master_data['karigar_mobile']]);
			if(!empty($temp)) return ['msg' => 'Mobile no. already exist.'];	
		}
		if($master_data['karigar_email'] != ''){
			$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['karigar_id !=' => $id, 'karigar_email' => $master_data['karigar_email']]);
			if(!empty($temp)) return ['msg' => 'Email already exist.'];	
		}
		
        $this->db->trans_begin();
		if($id == 0){
			$master_data['karigar_password'] = (!empty($post_data['karigar_password']))?md5(trim($post_data['karigar_password'])):''; 
			$master_data['karigar_created_by'] = $_SESSION['user_id'];
			$master_data['karigar_created_at'] = date('Y-m-d H:i:s');
			$id = $this->db_operations->data_insert($this->sub_menu.'_master', $master_data);
			$msg = 'Karigar added successfully.';
			if($id < 1){
				$this->db->trans_rollback();
				return ['msg' => 'Karigar not added.'];
			}
		}else{
			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', ['karigar_id' => $id]);
			if(empty($prev_data)){
				$this->db->trans_rollback();
				return ['status' => REFRESH, 'msg' => 'Karigar not found.'];
			}

			if(!empty($post_data['karigar_password'])){
				$master_data['karigar_password'] 	=  md5(trim($post_data['karigar_password']));
			}else{
				unset($post_data['karigar_password']);
			}

			$msg = 'Karigar updated successfully.';
			if($this->db_operations->data_update($this->sub_menu.'_master', $master_data, 'karigar_id', $id) < 1){
				$this->db->trans_rollback();
				return ['msg' => 'Karigar not updated.'];
			}
		}

		if(isset($post_data['kat_id'])){
			$result = $this->add_edit_attachment($post_data, $id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				return $result;
			}	
		}else{
			$attachment = $this->db_operations->get_record('karigar_attachment_trans', ['kat_karigar_id' => $id]);
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

        if(isset($post_data['kpt_id'])){
			$result = $this->add_update_proces($post_data, $id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				return $result;
			}	
		}else{
			$process = $this->db_operations->get_record('karigar_proces_trans', ['kpt_proces_id' => $id]);
			if(!empty($process)){
				if($this->db_operations->delete_record('karigar_proces_trans', ['kpt_proces_id' => $id]) < 1){
					$this->db->trans_rollback();
					return ['msg' => 'Process not deleted.'];
				}
			}
		}

        if(isset($post_data['kapt_id'])){
			$result = $this->add_update_apparel($post_data, $id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				return $result;
			}	
		}else{
			$apparel = $this->db_operations->get_record('karigar_apparel_trans', ['kapt_apparel_id' => $id]);
			if(!empty($apparel)){
				if($this->db_operations->delete_record('karigar_apparel_trans', ['kapt_apparel_id' => $id]) < 1){
					$this->db->trans_rollback();
					return ['msg' => 'Apparel not deleted.'];
				}
			}
		}


/* ================= FAMILY DETAILS ================= */

// ALWAYS delete old records first (even if no rows submitted)
$this->db->where('kft_karigar_id', $id)
         ->delete('karigar_family_trans');

// Now insert only if rows are submitted
if (
    !empty($post_data['family_relation']) &&
    !empty($post_data['family_name']) &&
    !empty($post_data['family_mobile'])
) {

    $relations = $post_data['family_relation'];
    $names     = $post_data['family_name'];
    $mobiles   = $post_data['family_mobile'];

    for ($i = 0; $i < count($relations); $i++) {

        // Skip completely empty rows
        if (
            trim($relations[$i]) === '' &&
            trim($names[$i]) === '' &&
            trim($mobiles[$i]) === ''
        ) {
            continue;
        }

        $insert = [
            'kft_karigar_id' => $id,
            'kft_relation'  => trim($relations[$i]),
            'kft_name'      => trim($names[$i]),
            'kft_mobile'    => trim($mobiles[$i]),
            'created_at'    => date('Y-m-d H:i:s')
        ];

        if (!$this->db->insert('karigar_family_trans', $insert)) {
            $this->db->trans_rollback();
            return ['msg' => 'Family details not saved.'];
        }
    }
}

/* ================= FAMILY DETAILS ================= */


		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return ['msg' => 'Transaction Rollback.'];
	    }
	    $this->db->trans_commit();

		$data['id'] 	= $id;
		$data['name'] 	= strtoupper($master_data['karigar_name']).' - '.$master_data['karigar_mobile'];
		return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
	}
	public function add_edit_attachment($post_data, $id){
		$trans_db_data = $this->db_operations->get_record('karigar_attachment_trans', ['kat_karigar_id' => $id]);
		if(!empty($trans_db_data)){
			foreach ($trans_db_data as $key => $value){
				if(!in_array($value['kat_id'], $post_data['kat_id'])){
					$result = $this->remove_attachment($value);
					if(!isset($result['status'])) return $result;

					if($this->db_operations->delete_record('karigar_attachment_trans', ['kat_id' => $value['kat_id']]) < 1){
						return ['msg' => 'Attachment not deleted.'];
					}
				} 
			}
		}
		foreach ($post_data['kat_id'] as $key => $value){
			$trans_data['kat_karigar_id']		= $id;
			$trans_data['kat_path']				= $post_data['kat_path'][$key];
			$trans_data['kat_updated_by'] 		= $_SESSION['user_id'];
			if($value == 0){
				$trans_data['kat_created_by'] 	= $_SESSION['user_id'];
				$trans_data['kat_created_at'] 	= date('Y-m-d H:i:s');
				$kat_id = $this->db_operations->data_insert('karigar_attachment_trans', $trans_data);
				if($kat_id < 1) return ['msg' => 'Attachment not added.'];
			}else{
				$prev_data = $this->db_operations->get_record('karigar_attachment_trans', ['kat_id' => $value]);
				if(empty($prev_data)) return ['msg' => 'Attachment not found.'];

				if($this->db_operations->data_update('karigar_attachment_trans', $trans_data, 'kat_id', $value) < 1){
					return ['msg' => 'Attachment not updated.'];
				}
			}
		}
		return ['status' => TRUE];
	}

    public function add_update_proces($post_data, $id){
		$trans_db_data = $this->db_operations->get_record('karigar_proces_trans', ['kpt_karigar_id' => $id]);
		if(!empty($trans_db_data)){
			foreach ($trans_db_data as $key => $value){
				if(!in_array($value['kpt_id'], $post_data['kpt_id'])){
					if($this->db_operations->delete_record('karigar_proces_trans', ['kpt_id' => $value['kpt_id']]) < 1){
						return ['msg' => 'Process not deleted.'];
					}
				} 
			}
		}
		foreach ($post_data['kpt_id'] as $key => $value){
			$trans_data['kpt_karigar_id']	= $id;
			$trans_data['kpt_proces_id']	= $post_data['kpt_proces_id'][$key];

			if($value == 0){
				$kpt_id = $this->db_operations->data_insert('karigar_proces_trans', $trans_data);
				if($kpt_id < 1) return ['msg' => 'Process not inserted.'];
			}else{
				$prev_data = $this->db_operations->get_record('karigar_proces_trans', ['kpt_id' => $value]);
				if(empty($prev_data)) return ['msg' => 'Process not found.'];
				if($this->db_operations->data_update('karigar_proces_trans', $trans_data, 'kpt_id', $value) < 1) return ['msg' => 'Process not updated.'];
			}
		}
		return ['status' => TRUE];
	}

    public function add_update_apparel($post_data, $id){
        $trans_db_data = $this->db_operations->get_record('karigar_apparel_trans', ['kapt_karigar_id' => $id]);
        if(!empty($trans_db_data)){
            foreach ($trans_db_data as $key => $value){
                if(!in_array($value['kapt_id'], $post_data['kapt_id'])){
                    if($this->db_operations->delete_record('karigar_apparel_trans', ['kapt_id' => $value['kapt_id']]) < 1){
                        return ['msg' => 'Apparel not deleted.'];
                    }
                } 
            }
        }
        foreach ($post_data['kapt_id'] as $key => $value){
            $trans_data['kapt_karigar_id']	= $id;
            $trans_data['kapt_apparel_id']	= $post_data['kapt_apparel_id'][$key];
            $trans_data['kapt_qty']	        = $post_data['kapt_qty'][$key];
            $trans_data['kapt_rate']	    = $post_data['kapt_rate'][$key];
    
            if($value == 0){
                $kapt_id = $this->db_operations->data_insert('karigar_apparel_trans', $trans_data);
                if($kapt_id < 1) return ['msg' => 'Apparel not inserted.'];
            }else{
                $prev_data = $this->db_operations->get_record('karigar_apparel_trans', ['kapt_id' => $value]);
                if(empty($prev_data)) return ['msg' => 'Apparel not found.'];
                if($this->db_operations->data_update('karigar_apparel_trans', $trans_data, 'kapt_id', $value) < 1) return ['msg' => 'Apparel not updated.'];
            }
        }
        return ['status' => TRUE];
    }
}
?>