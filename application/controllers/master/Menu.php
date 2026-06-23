<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class menu extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'master'; 
        $this->sub_menu = 'menu'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function remove(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];

		$result = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		$data = $this->db_operations->get_record($this->sub_menu.'_master', ['menu_id' => $id]);
		if(empty($data)) return ['status' => REFRESH, 'msg' => '3. Menu not found.'];	

		$this->db->trans_begin();
		
		$trans_data = $this->db_operations->get_record($this->sub_menu.'_trans', ['mt_menu_id' => $id]);
		if(!empty($trans_data)){
			if($this->db_operations->delete_record($this->sub_menu.'_trans', ['mt_menu_id' => $id]) < 1){
				$this->db->trans_rollback();
				return ['msg' => '4. Transaction not deleted.'];
			}
		}

		$action_data = $this->db_operations->get_record('menu_action_trans', ['mat_menu_id' => $id]);
		if(!empty($action_data)){
			if($this->db_operations->delete_record('menu_action_trans', ['mat_menu_id' => $id]) < 1){
				$this->db->trans_rollback();
				return ['msg' => '2. Action not deleted.'];
			}
		}

		if($this->db_operations->delete_record($this->sub_menu.'_master', ['menu_id' => $id]) < 1){
			$this->db->trans_rollback();
			return ['msg' => '1. Menu not deleted.'];
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return ['msg' => '2. Transaction Rollback.'];
	    }
	    $this->db->trans_commit();

		return ['status' => TRUE, 'msg' => 'Menu deleted successfully'];
	}
    public function add_edit(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		$result     = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;

		// master_data
			$master_data['menu_name'] 		= trim($post_data['menu_name']);
			$master_data['menu_js'] 		= trim($post_data['menu_js']);
			$master_data['menu_status'] 	= isset($post_data['menu_status']);
			$master_data['menu_updated_by'] = $_SESSION['user_id'];
			$master_data['menu_updated_at'] = date('Y-m-d H:i:s');
		// master_data

		$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['menu_id !=' => $id, 'menu_js' => $master_data['menu_js']]);
		if(!empty($temp)) return ['msg' => '1. Menu already exist.'];	
		$this->db->trans_begin();
		if($id == 0){
			$master_data['menu_created_by'] = $_SESSION['user_id'];
			$master_data['menu_created_at'] = date('Y-m-d H:i:s');
			$id  = $this->db_operations->data_insert($this->sub_menu.'_master', $master_data);
			$msg = 'Menu added successfully.';
			if($id < 1){
				$this->db->trans_rollback();
				return ['msg' => '1. Menu not added.'];
			}
		}else{
			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', ['menu_id' => $id]);
			if(empty($prev_data)){
				$this->db->trans_rollback();
				return ['status' => REFRESH, 'msg' => '1. Menu not found.'];
			}
			$msg = 'Menu updated successfully.';
			if($this->db_operations->data_update($this->sub_menu.'_master', $master_data, 'menu_id', $id) < 1){
				$this->db->trans_rollback();
				return ['msg' => '1. Menu not updated.'];
			}
		}

		if(isset($post_data['mt_id'])){
			$result = $this->add_edit_trans($post_data, $id);
			if(!isset($result['status'])){
				$this->db->trans_rollback();
				return $result;
			}	
		}else{
			$trans_data = $this->db_operations->get_record($this->sub_menu.'_trans', ['mt_menu_id' => $id]);
			if(!empty($trans_data)){
				if($this->db_operations->delete_record($this->sub_menu.'_trans', ['mt_menu_id' => $id]) < 1){
					$this->db->trans_rollback();
					return ['msg' => '1. Transaction not deleted.'];
				}
			}

            $trans_data = $this->db_operations->get_record('menu_action_trans', ['mat_menu_id' => $id]);
			if(!empty($trans_data)){
				if($this->db_operations->delete_record('menu_action_trans', ['mat_menu_id' => $id]) < 1){
					$this->db->trans_rollback();
					return ['msg' => '2. Transaction not deleted.'];
				}
			}
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    return ['msg' => '1. Transaction Rollback.'];
	    }
	    $this->db->trans_commit();

		$data['id'] 	= $id;
		$data['name'] 	= strtoupper($master_data['menu_name']);
		return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
	}
	public function add_edit_trans($post_data, $id){
		$trans_db_data = $this->db_operations->get_record($this->sub_menu.'_trans', ['mt_menu_id' => $id]);
		if(!empty($trans_db_data)){
			foreach ($trans_db_data as $key => $value){
				if(!in_array($value['mt_id'], $post_data['mt_id'])){
                    if($this->db_operations->delete_record('menu_action_trans', ['mat_mt_id' => $value['mt_id']]) < 1) return ['msg' => '1. Action not deleted.'];
					if($this->db_operations->delete_record($this->sub_menu.'_trans', ['mt_id' => $value['mt_id']]) < 1) return ['msg' => '3. Transaction not deleted.'];
				} 
			}
		}
		foreach ($post_data['mt_id'] as $key => $value){
			$trans_data	                    = [];
			$trans_data['mt_menu_id']	    = $id;
			$trans_data['mt_name']		    = $post_data['mt_name'][$key];
			$trans_data['mt_js']	        = $post_data['mt_js'][$key];
			$trans_data['mt_url']		    = $post_data['mt_url'][$key];
			$trans_data['mt_type']		    = $post_data['mt_type'][$key];
			$trans_data['mt_status']		= isset($post_data['mt_status'][$key]);
			if($value == 0){
				$mt_id = $this->db_operations->data_insert($this->sub_menu.'_trans', $trans_data);
				if($mt_id < 1) return ['msg' => '1. Transaction not inserted.'];
			}else{
				$prev_data = $this->db_operations->get_record($this->sub_menu.'_trans', ['mt_id' => $value]);
				if(empty($prev_data)) return ['msg' => 'Transaction not found.'];
				if($this->db_operations->data_update($this->sub_menu.'_trans', $trans_data, 'mt_id', $value) < 1) return ['msg' => 'Transaction not updated.'];
                $mt_id = $value;
			}
            $result = $this->add_edit_action($mt_id, $key, $trans_data, $post_data);
            if(!isset($result['status'])) return $result;

		}
		return ['status' => TRUE];
	}
    public function add_edit_action($mt_id, $k, $t_data, $post_data){
        if(isset($post_data['mat_id'][$k])){
            foreach ($post_data['mat_id'][$k] as $key => $value){
                $trans_data = [];
                $trans_data['mat_menu_id']	    = $t_data['mt_menu_id'];
                $trans_data['mat_mt_id']	    = $mt_id;
                $trans_data['mat_action']		= $post_data['mat_action'][$k][$key];
                $trans_data['mat_status']		= $post_data['mat_status'][$k][$key];
                $trans_data['mat_type']			= $post_data['mat_type'][$k][$key];
                if($value == 0){
                    $mat_id = $this->db_operations->data_insert('menu_action_trans', $trans_data);
                    if($mat_id < 1) return ['msg' => '1. Action not inserted.'];
                }else{
                    $prev_data = $this->db_operations->get_record('menu_action_trans', ['mat_id' => $value]);
                    if(empty($prev_data)) return ['msg' => '1. Action not found.'];
                    if($this->db_operations->data_update('menu_action_trans', $trans_data, 'mat_id', $value) < 1) return ['msg' => '1. Action not updated.'];
                }
            }
        }
		return ['status' => TRUE];
	}
}
?>