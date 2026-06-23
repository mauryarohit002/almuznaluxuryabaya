<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class company extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'master'; 
        $this->sub_menu = 'company'; 
        parent::__construct($this->menu, $this->sub_menu); 
    }
    public function add_update(){
		$post_data = $this->input->post();
        $id        = $post_data['id'];
        $result    = isMenuAssigned($this->menu, $this->sub_menu, ($id == 0 ? 'add' : 'edit'));
        if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
        
        unset($post_data['func']);
        unset($post_data['id']);

        // master_data
            $post_data[$this->sub_menu.'_base_url'] 	= base_url();
            $post_data[$this->sub_menu.'_city_id'] 		= isset($post_data[$this->sub_menu.'_city_id']) ? $post_data[$this->sub_menu.'_city_id'] : 0;
            $post_data[$this->sub_menu.'_state_id'] 	= isset($post_data[$this->sub_menu.'_state_id']) ? $post_data[$this->sub_menu.'_state_id'] : 0;
            $post_data[$this->sub_menu.'_country_id'] 	= isset($post_data[$this->sub_menu.'_country_id']) ? $post_data[$this->sub_menu.'_country_id'] : 0;
            $post_data[$this->sub_menu.'_status'] 		= isset($post_data[$this->sub_menu.'_status']);
            $post_data[$this->sub_menu.'_updated_by'] 	= $_SESSION['user_id'];
        // master_data

		$temp = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id !=' => $id, $this->sub_menu.'_name' => $post_data[$this->sub_menu.'_name']]);
		if(!empty($temp)) return ['msg' => ucfirst(str_replace('_', ' ', $this->sub_menu)).' already exist.'];	
		$this->db->trans_begin();
		if($id == 0){
			$post_data[$this->sub_menu.'_created_by'] = $_SESSION['user_id'];
			$post_data[$this->sub_menu.'_created_at'] = date('Y-m-d H:i:s');

			$id     = $this->db_operations->data_insert($this->sub_menu.'_master', $post_data);
			$msg    = ucfirst(str_replace('_', ' ', $this->sub_menu)).' added successfully.';
			if($id < 1){
				$this->db->trans_rollback();
				return ['msg' => ucfirst(str_replace('_', ' ', $this->sub_menu)).' not added.'];
			}
		}else{
			$prev_data = $this->db_operations->get_record($this->sub_menu.'_master', [$this->sub_menu.'_id' => $id]);
			if(empty($prev_data)){
				$this->db->trans_rollback();
				return['status' => REFRESH, 'msg' => ucfirst(str_replace('_', ' ', $this->sub_menu)).' not found.'];
			}

			$msg = ucfirst(str_replace('_', ' ', $this->sub_menu)).' updated successfully.';
			if($this->db_operations->data_update($this->sub_menu.'_master', $post_data, $this->sub_menu.'_id', $id) < 1){
				$this->db->trans_rollback();
				return ['msg' => ucfirst(str_replace('_', ' ', $this->sub_menu)).' not updated.'];
			}
		}

		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return ['msg' => '1. Transaction Rollback.'];
		}
		$this->db->trans_commit();

		$data['id'] 	= $id;
		$data['name'] 	= strtoupper($post_data[$this->sub_menu.'_name']);
		return ['status' => TRUE, 'data' => $data,  'msg' => $msg];
	}

}
?>