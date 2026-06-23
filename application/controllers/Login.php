<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Login extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->config->load('extra');
		}
		public function index(){
			if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
				redirect(base_url('/home'));
				return;
			}
			$record['year'] 		= $this->get_year_range();
			$record['title'] 		= $this->config->item('title');
			$record['branch'] 		= $this->get_branch_dropdown();
			$record['company'] 		= $this->get_default_company();
			$record['msg'] 			= isset($_GET['msg']) ? $_GET['msg'] : '';;
			// echo "<pre>"; print_r($record); exit();
			$this->load->view('login', $record);
		}
		public function get_branch_dropdown(){
			$data = $this->db_operations->get_record('branch_master', ['branch_status' => true]);
			$record = [];
			if(!empty($data)){
				if(count($data) == 1){
					foreach ($data as $key => $value) {
						$record[$value['branch_id']] = strtoupper($value['branch_name']);
					}
				}else{
					$record[0] = 'SELECT';
					foreach ($data as $key => $value) {
						$record[$value['branch_id']] = strtoupper($value['branch_name']);
					}
				}
			}else{
				$record[0] = 'NO BRANCH ADDED';
			}
			return $record;
		}
		public function get_fin_year($fin_year, $start = true){
			$explode = explode('-', $fin_year);
			if($start) return $explode[0].'-04-01'; // 2020-04-01
			return $explode[1].'-03-31'; // 2021-03-31
		}
		public function get_year_range(){
			$today_date 			= date('d-m-Y');	
			$financial_start_date 	= date('01-04-Y');
			if(strtotime($today_date) >= strtotime($financial_start_date)){
				$years = range('2018', date('Y')+1);	
			}else{
				$years = range('2018', date('Y'));					
			}
			$range 	= array();
			$size 	= sizeOf($years);

			foreach ($years as $key => $value){
				if($key != $size - 1)			
					$range[$value."-".$years[$key+1]] = $value."-".$years[$key+1];
			}
			return array_reverse($range);
		}
		public function login_action(){
			$post_data = $this->input->post();
			$user = $this->db_operations->get_record('user_master', ['user_name' => $post_data['user_name'], 'user_branch_id' => $post_data['user_branch_id']]);
			// echo "<pre>"; print_r($post_data); exit;
			// $user = $this->db_operations->get_record('user_master', ['user_name' => $post_data['user_name']]);
			if(empty($user)){
				echo json_encode(['status' => FALSE, 'msg' => 'Invalid Credentials']);
				return ;
			}
			if($user[0]['user_status'] != 1){
				echo json_encode(['status' => FALSE, 'msg' => 'Account has been deactivated.']);
				return ;
			}
    		if($user[0]['user_password'] != md5($post_data['user_password'])){
				echo json_encode(['status' => FALSE, 'msg' => 'Invalid Credentials']);
				return ;
    		}
    		$branch_data = $this->db_operations->get_record('branch_master', ['branch_id' => $user[0]['user_branch_id'], 'branch_status' => true]);
    		if(empty($branch_data)){
    			echo json_encode(['status' => REFRESH, 'msg' => 'Branch not found.']);
				return ;	
    		}
			// $role_data = $this->db_operations->get_record('role_master', ['role_id' => $user[0]['user_role_id']]); 
    		// if(empty($branch_data)){
    		// 	echo json_encode(['status' => REFRESH, 'msg' => 'Role not found.']);
			// 	return ;	
    		// }
    		
    		$branch_data = $this->db_operations->get_record('branch_master', [
				'branch_id' => $user[0]['user_branch_id'], 
				'branch_status' => true
			]);

			if(empty($branch_data)){
				echo json_encode(['status' => REFRESH, 'msg' => 'Branch not found.']);
				return;	
			}

			// echo "<pre>"; print_r($user); exit;
			$session_data['user_id'] 			= $user[0]['user_id'];
			$session_data['user_role'] 			= $user[0]['user_role'];
			$session_data['user_role_id'] 		= $user[0]['user_role_id'];
			$session_data['user_name'] 			= $user[0]['user_name'];
			$session_data['user_fullname'] 		= $user[0]['user_fullname'];
			$session_data['user_branch_id'] 	= $user[0]['user_branch_id'];
			$session_data['user_type'] 			= $user[0]['user_type'];
			$session_data['user_branch'] 		= 'main';
			$session_data['user_branch_name']   = $branch_data[0]['branch_name'];
			$session_data['branch_default'] 	= 1;
			$session_data['company_name'] 		= $this->get_default_company();
			$session_data['fin_year'] 			= $post_data['fin_year'];
			$session_data['start_year'] 		= $this->get_fin_year($post_data['fin_year']);
			$session_data['end_year'] 			= $this->get_fin_year($post_data['fin_year'], false);
			// $session_data['fin_year'] 		= '2021-2022';
			// $session_data['start_year'] 	= '2021-04-01';
			// $session_data['end_year'] 		= '2022-03-31';

			$this->session->set_userdata($session_data);
			$session_id = $this->session->session_id;
			$arr = array(
					'user_token' 		=> $session_id,
					'user_log_status' 	=> true,
					'user_ip' 			=> $_SERVER["REMOTE_ADDR"]
				);
		// echo "<pre>"; print_r($session_data); exit;

			$this->db_operations->data_update("user_master", $arr, 'user_id', $user[0]['user_id']);
			echo json_encode(['status' => TRUE, 'msg'=>'Login successfully']);
		}
		public function get_default_company(){
			$company_data = $this->db_operations->get_record('company_master', ['company_constant' => 'SOURCE_COMPANY']);
    		if(empty($company_data)){
    			$title = $this->config->item('title');
				return $title[1].' '. $title[2];
    		}
			return strtoupper($company_data[0]['company_name']);
		}
		public function logout(){
			$arr = array(
				'user_token' 		=> false,
				'user_log_status' 	=> false,
				'user_ip' 			=> $_SERVER["REMOTE_ADDR"]
			);
			
			$user_id = $this->session->userdata('user_id');
			$this->db_operations->data_update("user_master", $arr, 'user_id', $user_id);

			$this->session->sess_destroy();
			unset($_SESSION);
			// echo "<pre>"; print_r($_SESSION); exit();
			$msg = isset($_GET['msg']) ? '?msg='.$_GET['msg'] : '';
			// echo "<pre>";print_r(base_url('?msg=text')); exit;
			redirect(base_url($msg));
		}
	}
?>