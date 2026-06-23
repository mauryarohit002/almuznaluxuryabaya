<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class sku extends my_controller{
	public function __construct(){ parent::__construct('master', 'sku'); }
	
	public function add_edit(){
		$post_data  = $this->input->post();
		$files 		= $_FILES;
		$id         = $post_data['id'];

		$result    = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$result = $this->get_image($post_data, $files);
		if(!isset($result['status'])) return $result;
		$post_data['sku_image'] = $result['data'];

		// echo "<pre>"; print_r($post_data);
		// echo "<pre>"; print_r($_FILES); exit;
		
		$this->db->trans_begin();
			$result = $this->add_edit_master($post_data, $id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				return $result;
			}
			$id  = $result['data'];
			$msg = $result['msg'];

			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return ['msg' => '1. Transaction Rollback.'];
			}
		$this->db->trans_commit();
		
		$data['id'] 	    = encrypt_decrypt("encrypt", $id, SECRET_KEY);
		$data['name'] 	    = $post_data['sku_name'];

		return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
	}
	public function remove(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$this->db->trans_begin();

        $result = $this->delete_master(['sku_id' => $id, 'sku_delete_status' => false]);
		if(!isset($result['status'])){
			$this->db->trans_rollback();
			return $result;
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return ['msg' => '2. Transaction Rollback.'];
		}
		$this->db->trans_commit();

		return ['status' => TRUE, 'msg' => 'Sku deleted successfully'];
	}
	public function get_image($post_data, $files){
		if($files['sku_photo']['error'] == 0){
			$_FILES['sku_photo']['name']		= $files['sku_photo']['name'];
			$_FILES['sku_photo']['type']		= $files['sku_photo']['type'];
			$_FILES['sku_photo']['tmp_name']	= $files['sku_photo']['tmp_name'];
			$_FILES['sku_photo']['error']		= $files['sku_photo']['error'];
			$_FILES['sku_photo']['size']		= $files['sku_photo']['size'];

			// echo "<pre>"; print_r($_FILES); exit;
			unset($config);
			$config 					= array();
			$config['upload_path'] 		= 'public/uploads/'.$this->sub_menu.'/';
			$config['allowed_types'] 	= 'gif|jpg|png|jpeg';
			$file_name 					= $files['sku_photo']['name'];
			if(!file_exists($config['upload_path'])){
				mkdir($config['upload_path'], 0777);
			}
			$ext 						= strtolower(substr($file_name, strrpos($file_name, '.') + 1));
			$filename 					= time().'.'.$ext;
			$config['file_name'] 		= $filename;

			$this->upload->initialize($config);
			if(!$this->upload->do_upload('sku_photo')) return ['msg' => $this->upload->display_errors()];
			$imageinfo = $this->upload->data();
			$full_path = $imageinfo['full_path'];
			
			// check EXIF and autorotate if needed
			// $this->db_operations->image_autorotate_resize(array('filepath' => $full_path), TRUE);		
			return ['status' => TRUE, 'data' => uploads($this->sub_menu.'/'.$filename)];
		}
		return ['status' => TRUE, 'data' => $post_data['sku_pic']];
	}
	public function get_barcode_data(){
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        // echo "<pre>"; print_r($post_data); exit;

        $data = $this->model->get_barcode_data($id);
        if((empty($data))) return ['msg' => '1. Barcode not found.'];
        // echo "<pre>"; print_r($data); exit;
        return['status' => TRUE, 'data' => $data, 'msg' => 'Barcode scanned.'];
	}
	// sku_master
		public function add_edit_master($post_data, $id){
			// master_data
				$master_data['sku_uuid'] 		= trim($post_data['sku_uuid']);
				$master_data['sku_apparel_id'] 	= isset($post_data['sku_apparel_id']) ? $post_data['sku_apparel_id'] : 0;
				$master_data['sku_name'] 		= trim($post_data['sku_name']);
				$master_data['sku_supplier_id'] 	= isset($post_data['sku_supplier_id']) ? $post_data['sku_supplier_id'] : 0;

				$master_data['sku_fabric'] 		= trim($post_data['sku_fabric']);
				$master_data['sku_cutting'] 	= trim($post_data['sku_cutting']);
				$master_data['sku_silai'] 		= trim($post_data['sku_silai']);
				$master_data['sku_stone'] 		= trim($post_data['sku_stone']);
				$master_data['sku_lagwayi'] 	= trim($post_data['sku_lagwayi']);
				$master_data['sku_hand_work'] 	= trim($post_data['sku_hand_work']);
				$master_data['sku_material'] 	= trim($post_data['sku_material']);
				$master_data['sku_exp'] 		= trim($post_data['sku_exp']);

				$master_data['sku_mrp'] 		= trim($post_data['sku_mrp']);
				$master_data['sku_offer_price'] = trim($post_data['sku_offer_price']);
				$master_data['sku_last_price'] 	= trim($post_data['sku_last_price']);
				$master_data['sku_cp'] 			= trim($post_data['sku_cp']);
				$master_data['sku_piece'] 		= trim($post_data['sku_piece']);
				$master_data['sku_image'] 		= trim($post_data['sku_image']);
				$master_data['sku_notes'] 	    = trim($post_data['sku_notes']);
				$master_data['sku_status'] 		= isset($post_data['sku_status']);
				$master_data['sku_updated_by'] 	= $_SESSION['user_id'];
				$master_data['sku_updated_at'] 	= date('Y-m-d H:i:s');
			// master_data

			$temp = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id !=' => $id, $this->sub_menu.'_name'  => $master_data[$this->sub_menu.'_name'], $this->sub_menu.'_delete_status'  => false]);
			if(!empty($temp)) return ['msg' => 'Sku already exist.'];	

			if($id == 0){
				$master_data['sku_created_by'] 	= $_SESSION['user_id'];
				$master_data['sku_created_at'] 	= date('Y-m-d H:i:s');
				$uuidExist 						= $this->db_operations->get_cnt($this->sub_menu.'_master', ['sku_uuid' => $master_data['sku_uuid']]);
				if($uuidExist > 0) return ['msg' => 'Form already submited.'];
				$id = $this->db_operations->data_insert($this->sub_menu.'_master', $master_data);
				if($id < 1) return ['msg' => '1. Sku not added.'];
				return ['status' => TRUE, 'data' => $id, 'msg' => 'Sku added successfully.'];
			}

			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', ['sku_id' => $id, 'sku_delete_status' => false]);
			if(empty($prev_data)) return ['msg' => '1. Sku not found.'];
			
			if($this->db_operations->data_update($this->sub_menu.'_master', $master_data, 'sku_id', $id) < 1) return ['msg' => '1. Sku not updated.'];

			return ['status' => TRUE, 'data' => $id, 'msg' => 'Sku updated successfully.'];
		}
		public function delete_master($clause){
            $data = $this->db_operations->get_record($this->sub_menu.'_master', $clause);
			if(empty($data)) return ['msg' => '2. Sku not found.'];

			foreach ($data as $key => $value){
				if($this->model->isMasterExist($value['sku_id'])) return ['msg' => '1. Not allowed to delete.'];
				$update_data 						= [];
				$update_data['sku_name'] 			= $value['sku_name'].''.$value['sku_id'].''.$value['sku_uuid']; 
				$update_data['sku_delete_status'] 	= true; 
				$update_data['sku_updated_by'] 		= $_SESSION['user_id']; 
				$update_data['sku_updated_at'] 		= date('Y-m-d H:i:s');
				if($this->db_operations->data_update($this->sub_menu.'_master', $update_data, 'sku_id', $value['sku_id']) < 1) return ['msg' => '1. Sku not deleted.'];
			}
			return ['status' => TRUE];
        }
	// sku_master

        public function get_ids($data,$id){
			$record = [];
			foreach ($data as $key => $value) {
				array_push($record, $value[$id]);
			}
			return $record;
		}
}
?>
