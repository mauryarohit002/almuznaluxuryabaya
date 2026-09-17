<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class daily_profit extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'daily_profit';
		parent::__construct($this->menu, $this->sub_menu); 
	}

	public function index(){ 	
		$result = isLoggedIn();
		// echo "<pre>"; print_r($_POST);exit;
		if(!$result['session'] || !$result['status'] || !$result['active']){
			redirect('login/logout?msg='.$result['msg']);
			return;
		}
		$result     = isMenuAssigned($this->menu, $this->sub_menu);
		$action_data= get_action_data($this->menu, $this->sub_menu);
		$menu_data  = get_submenu_data($this->menu, $this->sub_menu);
		if(!$result['session'] || !$result['status'] || !$result['active']){
			$this->load->view('errors/unauthorized'); return;
		}
		$record['menu']		    = $this->menu;
		$record['sub_menu']		= $this->sub_menu;
		$record['action_data']	= $action_data;
		$record['menu_name']    = $menu_data['menu_name'];
		$record['sub_menu_name']= $menu_data['sub_menu_name'];
		$record['data']	= $this->model->get_record(); 
		// $record['total_rows']	= 0;
		// echo "<pre>"; print_r($record); exit;  

		$this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/list/_body', $record);
	}

	public function get_custom_order(){
        $post_data  = $this->input->post();
        $id         = $post_data['id'];
        $result = isLoggedIn();
		if(!$result['session'] || !$result['status'] || !$result['active']){
			redirect('login/logout?msg='.$result['msg']);
			return;
		}
		$query="SELECT sku.sku_name,ot.ot_id,ot.ot_sku_cp 
				FROM order_trans ot
				INNER JOIN sku_master sku ON (ot.ot_sku_id=sku.sku_id)
				WHERE ot.ot_om_id=$id";
		$data = $this->db->query($query)->result_array();		
        if(empty($data)) return['msg' => 'Data not found.'];	
        return['status' => TRUE, 'data' => $data, 'msg' => 'Data fetched successfully.'];
    }

    public function update_price(){
		$post_data  = $this->input->post();
		$id         = $post_data['id'];
		
		$result = isLoggedIn();
		if(!$result['session'] || !$result['status'] || !$result['active']){
			redirect('login/logout?msg='.$result['msg']);
			return;
		}

		if(empty($post_data['ot_id'])) return['msg' => 'Item not found.'];
		
		
		$this->db->trans_begin();

		foreach ($post_data['ot_id'] as $key => $value) {
			$trans_data =[];
			$trans_data['ot_sku_cp'] = $post_data['ot_sku_cp'][$key];
			$trans_data['ot_updated_by'] = $_SESSION['user_id'];
			if($this->db_operations->data_update('order_trans', $trans_data, 'ot_id', $value) < 1){
				$this->db->trans_rollback();
				return ['msg' => 'Transaction not updated.'];
			}
		}
		
		$msg = 'Order updated successfully.';
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return ['msg' => '1. Transaction Rollback.'];
		}
		$this->db->trans_commit();

		$data['id'] 	= $id;
		return['session' => TRUE, 'status' => TRUE, 'data' => $data,  'msg' => $msg];
	}
}
?>
