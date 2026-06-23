<?php defined('BASEPATH') OR exit('No direct script access allowed');
class theme extends CI_Controller{
	protected $menu;
	protected $sub_menu;
	public function __construct(){
		parent::__construct();

		$this->menu     = 'master';
		$this->sub_menu = 'theme';

		$this->load->model($this->menu.'/'.$this->sub_menu.'mdl', 'model');
		$this->load->library('pagination');
		$this->config->load('extra');
	}
	public function index(){	
		$result = isLoggedIn();
		// echo "<pre>"; print_r($result);exit;
		if(!$result['session'] || !$result['status'] || !$result['active']){
			redirect('login/logout?msg='.$result['msg']);
			return;
		}
		$result = isMenuAssigned($this->menu, $this->sub_menu);
        if(!$result['session'] || !$result['status'] || !$result['active']){
			$this->load->view('errors/unauthorized'); return;
		}
		if($_GET['action'] == 'add'){
			$result = isMenuAssigned($this->menu, $this->sub_menu, 'add');
			if(!$result['session'] || !$result['status'] || !$result['active']){
				$this->load->view('errors/unauthorized'); return;
			}
			$this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/_form'); return ;
		}
		if($_GET['action'] == 'edit' || $_GET['action'] == 'view'){
			$result = isMenuAssigned($this->menu, $this->sub_menu, $_GET['action']);
			if(!$result['session'] || !$result['status'] || !$result['active']){
				$this->load->view('errors/unauthorized'); return;
			}
			if(!isset($_GET['id']) || (isset($_GET['id']) && empty($_GET['id']))){
				$this->load->view('errors/not_found'); return;
			}
			$id = encrypt_decrypt("decrypt", $_GET['id'], SECRET_KEY);
			if(empty($id)){
				$this->load->view('errors/not_found');return;	
			}
			$record = $this->model->get_data_for_edit($id);
			// echo "<pre>"; print_r($record); exit;
			$this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/_form', $record);	return;	
		}
		$config 				= array();
		$config 				= $this->config->item('pagination');	
		$config['total_rows'] 	= $this->model->get_list(true);
		$config['base_url'] 	= base_url($this->menu."/".$this->sub_menu."?search=true");

		foreach ($_GET as $key => $value){
			if($key != 'search' && $key != 'offset'){
				$config['base_url'] .= "&" . $key . "=" .$value;
			}
		}

		$offset = (!empty($_GET['offset'])) ? $_GET['offset'] : 0;
		$this->pagination->initialize($config);

		$record['count']		= $offset;
		$record['total_rows'] 	= $config['total_rows'];
		$record['data']			= $this->model->get_list(false, $config['per_page'], $offset);
		// echo "<pre>"; print_r($record); exit;
		
		$this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/_list', $record);
	}
	public function add_update($id){
		$result = isLoggedIn();
		if(!$result['session'] || !$result['status'] || !$result['active']){
			echo json_encode($result);
			return;
		}
		$result = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
		if(!$result['session'] || !$result['status'] || !$result['active']){
			echo json_encode($result);
			return;
		}
		$post_data = $this->input->post();
		// echo "<pre>"; print_r($post_data); exit;
		if(empty($post_data)){
			echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Form data is empty.']);
			return;
		}
		$master_data['theme_name'] 		= trim($post_data['theme_name']);
		$master_data['theme_status'] 	= isset($post_data['theme_status']);
		$master_data['theme_updated_by'] = $_SESSION['user_id'];
        $master_data['theme_updated_at'] = date('Y-m-d H:i:s');
		$temp = $this->db_operations->get_record($this->sub_menu.'_master', ['theme_id !=' => $id, 'theme_name' => $master_data['theme_name']]);
		if(!empty($temp)){
			echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Theme already exist.']);
			return;	
		}
		$this->db->trans_begin();
		if($id == 0){
			$master_data['theme_created_by'] = $_SESSION['user_id'];
			$master_data['theme_created_at'] = date('Y-m-d H:i:s');
			$id = $this->db_operations->data_insert($this->sub_menu.'_master', $master_data);
			$msg = 'Theme added successfully.';
			if($id < 1){
				$this->db->trans_rollback();
				echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Theme not added.']);
				return;
			}
		}else{
			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', ['theme_id' => $id]);
			if(empty($prev_data)){
				$this->db->trans_rollback();
				echo json_encode(['session' => TRUE, 'status' => REFRESH, 'data' => [], 'msg' => 'Theme not found.']);
				return;
			}
			$msg = 'Menu updated successfully.';
			if($this->db_operations->data_update($this->sub_menu.'_master', $master_data, 'theme_id', $id) < 1){
				$this->db->trans_rollback();
				echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Menu not updated.']);
				return;
			}
		}

		if(isset($post_data['tt_id'])){
			$result = $this->add_update_trans($post_data, $id);
			if(!$result['status']){
				$this->db->trans_rollback();
				echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => $result['msg']]);
				return;
			}	
		}else{
			$trans_data = $this->db_operations->get_record('theme_trans', ['tt_theme_id' => $id]);
			if(!empty($trans_data)){
				if($this->db_operations->delete_record('theme_trans', ['tt_theme_id' => $id]) < 1){
					$this->db->trans_rollback();
					echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction not deleted.']);
					return;
				}
			}
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction Rollback.']);
			return;
	    }
	    $this->db->trans_commit();

		$data['id'] 	= $id;
		$data['name'] 	= strtoupper($master_data['theme_name']);
		echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $data,  'msg' => $msg]);
	}
	public function add_update_trans($post_data, $id){
		$trans_db_data = $this->db_operations->get_record('theme_trans', ['tt_theme_id' => $id]);
		if(!empty($trans_db_data)){
			foreach ($trans_db_data as $key => $value){
				if(!in_array($value['tt_id'], $post_data['tt_id'])){
                    if($this->db_operations->delete_record('theme_trans', ['tt_id' => $value['tt_id']]) < 1){
						return ['status' => FALSE, 'data' => FALSE, 'msg' => 'Transaction not deleted.'];
					}
				} 
			}
		}
		foreach ($post_data['tt_id'] as $key => $value){
			$trans_data	                    = [];
			$trans_data['tt_theme_id']	    = $id;
			$trans_data['tt_variable']		= $post_data['tt_variable'][$key];
			$trans_data['tt_value']	        = $post_data['tt_value'][$key];
			$trans_data['tt_status']		= isset($post_data['tt_status'][$key]);
			if($value == 0){
				$tt_id = $this->db_operations->data_insert('theme_trans', $trans_data);
				if($tt_id < 1){
					return ['status' => FALSE, 'data' => FALSE, 'msg' => 'Transaction not inserted.'];
				}
			}else{
				$prev_data = $this->db_operations->get_record('theme_trans', ['tt_id' => $value]);
				if(empty($prev_data)){
					return ['status' => FALSE, 'data' => FALSE, 'msg' => 'Transaction not found.'];
				}
				if($this->db_operations->data_update('theme_trans', $trans_data, 'tt_id', $value) < 1){
					return ['status' => FALSE, 'data' => FALSE, 'msg' => 'Transaction not updated.'];
				}
                $tt_id = $value;
			}
		}
		return ['status' => TRUE, 'data' => TRUE, 'msg' => ''];
	}
    public function get_data($id){
		$result = isLoggedIn();
		if(!$result['session'] || !$result['status'] || !$result['active']){
			echo json_encode($result);
			return;
		}
		$data = $this->model->get_data_for_edit($id);
		if(empty($data)){
			echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Menu not found.']);
			return;	
		}
		echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $data, 'msg' => 'Menu fetched successfully.']);
	}
    public function get_transaction($id){
        $result = isLoggedIn();
        if(!$result['session'] || !$result['status'] || !$result['active']){
            echo json_encode($result);
            return;
        }
        $id = encrypt_decrypt("decrypt", $id, SECRET_KEY);
        if(empty($id)){
            echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Id not found.']);
            return;	
        }
        $data = $this->model->get_transaction($id);
        echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $data, 'msg' => 'Menu fetched successfully.']);
    }
	public function remove($id){
		$result = isLoggedIn();
		if(!$result['session'] || !$result['status'] || !$result['active']){
			echo json_encode($result);
			return;
		}
		$result = isMenuAssigned($this->menu, $this->sub_menu, 'delete');
		if(!$result['session'] || !$result['status'] || !$result['active']){
			echo json_encode($result);
			return;
		}
		$data = $this->db_operations->get_record($this->sub_menu.'_master', ['theme_id' => $id]);
		if(empty($data)){
			echo json_encode(['session' => TRUE, 'status' => REFRESH, 'data' => [], 'msg' => 'Menu not found.']);
			return;	
		}
		$this->db->trans_begin();
		
		$trans_data = $this->db_operations->get_record('theme_trans', ['tt_theme_id' => $id]);
		if(!empty($trans_data)){
			if($this->db_operations->delete_record('theme_trans', ['tt_theme_id' => $id]) < 1){
				$this->db->trans_rollback();
				echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction not deleted.']);
				return;
			}
		}

		if($this->db_operations->delete_record($this->sub_menu.'_master', ['menu_id' => $id]) < 1){
			$this->db->trans_rollback();
			echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Menu not deleted.']);
			return;
		}

		if ($this->db->trans_status() === FALSE){
		    $this->db->trans_rollback();
		    echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Transaction Rollback.']);
			return;
	    }
	    $this->db->trans_commit();

		echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => [], 'msg' => 'Menu deleted successfully']);
	}
	public function get_select2($func){
		$json = [];
		$data = $this->model->$func();
		foreach ($data as $key => $value){
			$json[] = ['id'=>$value['id'], 'text'=>$value['name']];
		}
		echo json_encode($json);
	}	
}
?>
