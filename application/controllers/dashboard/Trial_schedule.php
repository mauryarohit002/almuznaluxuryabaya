<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Report.php'; 
class trial_schedule extends my_report{
	protected $menu;
	protected $sub_menu;
	public function __construct(){ 
		$this->menu 	= 'dashboard';
		$this->sub_menu = 'trial_schedule';
		parent::__construct($this->menu, $this->sub_menu); 
	}
	public function add_reschedule_trial_date(){  
		$post_data  = $this->input->post();
		// echo "<pre>"; print_r($post_data); exit;
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'reschedule');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
		$error = [];
		foreach ($post_data['om_reschedule_trial_date'] as $om_id => $value) {
			$order_data = $this->model->get_order_data($om_id);
			if(!empty($order_data)){
				if(strtotime($post_data['reschedule_trial_date']) < strtotime($order_data[0]['entry_date'])){
					array_push($error, $om_id);
				}else{
					$this->db_operations->data_update('order_master', ['om_reschedule_trial_date' => $post_data['reschedule_trial_date']], 'om_id', $om_id);
				}
			}
		}

        return ['status' => TRUE, 'data' => $error, 'msg' => 'Trial date rescheduled successfully'];
	}

	public function send_trial_reminder(){ 
		$post_data  = $this->input->post();
		// echo "<pre>"; print_r($post_data); exit;
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'trial_reminder');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
		$error = [];
		$cnt   = 0;
		foreach ($post_data['trial_reminder_date'] as $om_id => $value) {
			$order_data = $this->model->get_order_data($om_id);
			if(!empty($order_data)){
				$customer_name 	= $order_data[0]['customer_name'];
				$memo_no 		= $order_data[0]['memo_no'];
				$trial_date 	= date('d-m-Y', strtotime($order_data[0]['trial_date']));
				// $insta_link 	= 'https://www.instagram.com/zoopfashions/?utm_source=qr&igsh=MTFlaGp0c2lucms4dg%3D%3D';
				$trans_data = [];
				$trans_data['wmt_entry_date'] 	= date('Y-m-d');
				$trans_data['wmt_type'] 		= 'TRIAL_REMINDER';
				$trans_data['wmt_ref_id'] 		= $om_id;
				$trans_data['wmt_api_type'] 	= 'sendText';
				// $trans_data['wmt_mobile'] 		= ENV == DEV ? '9722229533' : $order_data[0]['customer_mobile'];
				$trans_data['wmt_mobile'] 		= $order_data[0]['customer_mobile'];
				$trans_data['wmt_msg'] 			= "Dear $customer_name,\nwelcome to the Rajkamal clothing.\nYour order $memo_no is scheduled for trial $trial_date. \nFor any assistance please call/watsapp at 9137014146 / 9324289191. \nThanks & Regards.\nRajkamal clothing";
				$trans_data['wmt_created_by'] 	= $_SESSION['user_id'];
				$trans_data['wmt_created_at'] 	= date('Y-m-d H:i:s'); 
				$trans_data['wmt_updated_by'] 	= $_SESSION['user_id'];
				$trans_data['wmt_updated_at'] 	= date('Y-m-d H:i:s'); 

				$result = send_whatsapp($trans_data['wmt_mobile'],$trans_data['wmt_msg']);
				$trans_data['wmt_status'] 	= isset($result['status']);
				$trans_data['wmt_response'] = $result['msg'];

				if($trans_data['wmt_status'] == 1) {
					$cnt++;
				}

				$this->db_operations->data_insert('whatsapp_message_trans', $trans_data);
			}
		}
		$msg = $cnt.' out of '.count($post_data['trial_reminder_date']).' trial reminder send.';
        return ['status' => TRUE, 'data' => $error, 'msg' => $msg];
	}

	public function send_reschedule_reminder(){
		$post_data  = $this->input->post();
		// echo "<pre>"; print_r($post_data); exit;
		$result     = isMenuAssigned($this->menu, $this->sub_menu, 'reschedule_reminder');
		if(!$result['session'] || !$result['status'] || !$result['active']) return $result;
		$error = [];
		$cnt   = 0;
		foreach ($post_data['reschedule_reminder_date'] as $om_id => $value) {
			$order_data = $this->model->get_order_data($om_id);
			if(!empty($order_data)){
				$customer_name 	= $order_data[0]['customer_name'];
				$memo_no 		= $order_data[0]['memo_no'];
				$trial_date 	= date('d-m-Y', strtotime($order_data[0]['trial_date']));
				$insta_link 	= 'https://www.instagram.com/zoopfashions/?utm_source=qr&igsh=MTFlaGp0c2lucms4dg%3D%3D';
				$trans_data = [];
				$trans_data['wmt_entry_date'] 	= date('Y-m-d');
				$trans_data['wmt_type'] 		= 'TRIAL_RESCHEDULE_REMINDER';
				$trans_data['wmt_ref_id'] 		= $om_id;
				$trans_data['wmt_api_type'] 	= 'sendText';
				$trans_data['wmt_mobile'] 		= ENV == DEV ? '9722229533' : $order_data[0]['customer_mobile'];
				$trans_data['wmt_msg'] 			= "Dear $customer_name,\nyour order $memo_no Due to unavoidable circumstances has been rescheduled on $trial_date.\nSorry for the inconvienience. \nFor any assisstance call/watsapp 9137014146 / 9324289191.\nThanks & Regards.\nRajkamal clothing";
				$trans_data['wmt_created_by'] 	= $_SESSION['user_id'];
				$trans_data['wmt_created_at'] 	= date('Y-m-d H:i:s'); 
				$trans_data['wmt_updated_by'] 	= $_SESSION['user_id'];
				$trans_data['wmt_updated_at'] 	= date('Y-m-d H:i:s'); 

				$result = send_whatsapp($trans_data['wmt_mobile'],$trans_data['wmt_msg']);
				$trans_data['wmt_status'] 	= isset($result['status']);
				$trans_data['wmt_response'] = $result['msg'];

				if($trans_data['wmt_status'] == 1) {
					$cnt++;
				}

				$this->db_operations->data_insert('whatsapp_message_trans', $trans_data);
			}
		}
		$msg = $cnt.' out of '.count($post_data['reschedule_reminder_date']).' reschedule reminder send.';
        return ['status' => TRUE, 'data' => $error, 'msg' => $msg];
	}
}
?>
