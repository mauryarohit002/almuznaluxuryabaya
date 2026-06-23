<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Home extends CI_Controller {
		public function __construct(){
			parent::__construct();
			$this->load->model('Homemdl', 'model');
			$this->load->model('dashboard/delivery_schedule_model', 'delivery_model');

			$this->config->load('extra');
		}
		public function index(){ 
			$result = isLoggedIn();
			// echo "<pre>"; print_r($result);exit;
			if(!$result['session'] || !$result['status'] || !$result['active']){
				redirect('login/logout?msg='.$result['msg']);
				return;
			}

			$action_data= get_action_data('dashboard','delivery_schedule');
			$record['action_data']	= $action_data;
			$record['data']			= $this->delivery_model->get_record();
                // echo "<pre>"; print_r($record); exit;
            $record['total_rows']	= isset($record['data']['totals']['rows']) ? $record['data']['totals']['rows'] : 0;

			$record['first'] = $this->model->get_last();
			$record['fabric_stock'] = $this->model->get_fabric_stock();
			$record['other_stock'] = $this->model->get_other_stock(); 
			// echo "<pre>"; print_r($record);die;
			$this->load->view('pages/home/new_dashboard', $record);return;

		}
		public function get_data(){
			$result = isLoggedIn();
			if(!$result['session'] || !$result['status'] || !$result['active']){
				echo json_encode($result);
				return;
			}
			// $post_data = $this->input->post();
			// echo "<pre>"; print_r($post_data); exit;
			// if(empty($post_data)){
			// 	echo json_encode(['session' => TRUE, 'status' => FALSE, 'data' => [], 'msg' => 'Form data is empty.']);
			// 	return;
			// }
			$data['first_data'] 	= $this->model->get_first();
			// $data['second_data'] 	= $this->model->get_second($post_data['start_date2'], $post_data['end_date2']);
			// $data['third_data'] 	= $this->model->get_third($post_data['start_date3'], $post_data['end_date3']);
			// $data['fourth_data'] 	= $this->model->get_fourth($post_data['start_date4'], $post_data['end_date4']);
			// $data['fifth_data'] 	= $this->model->get_fifth($post_data['start_date5'], $post_data['end_date5']);
			echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $data, 'msg' => 'Data fetched successfully.']);
		}
		public function get_event_stream($func = ''){
			header('Content-Type: text/event-stream');
			header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
			$func = empty($func) ? 'temp' : $func;
			echo "data: {$func}" . PHP_EOL;
			echo PHP_EOL;
			ob_flush();
			flush();
		}
	}
?>