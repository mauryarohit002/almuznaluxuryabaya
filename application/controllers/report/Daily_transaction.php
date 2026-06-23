<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class daily_transaction extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'daily_transaction';
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
	public function update_daily_transaction_status(){

    if(!$this->input->is_ajax_request()){

        echo json_encode([
            'status' => false,
            'msg'    => 'Invalid Request'
        ]);

        return false;
    }

    $entry_date_from = trim($this->input->post('entry_date_from'));

    $entry_date_to   = trim($this->input->post('entry_date_to'));

    // currently sending NAME from js
    $customer_name   = trim($this->input->post('customer_id'));

    // currently sending NAME from js
    $branch_name     = trim($this->input->post('branch_id'));

    $status          = (int)$this->input->post('status');


    /* =========================================
       VALIDATION
    ========================================= */

    if(
        empty($entry_date_from) ||
        empty($entry_date_to)
    ){

        echo json_encode([
            'status' => false,
            'msg'    => 'Entry Date Required'
        ]);

        return false;
    }


    /* =========================================
       CUSTOMER ID (OPTIONAL)
    ========================================= */

    $customer_id = 0;

    if(!empty($customer_name)){

        $customer = $this->db
            ->select('customer_id')
            ->where('customer_name', $customer_name)
            ->get('customer_master')
            ->row_array();

        if(empty($customer)){

            echo json_encode([
                'status' => false,
                'msg'    => 'Customer Not Found'
            ]);

            return false;
        }

        $customer_id = (int)$customer['customer_id'];
    }


    /* =========================================
       BRANCH ID
    ========================================= */

    if($_SESSION['user_id'] == 1){

        if(empty($branch_name)){

            echo json_encode([
                'status' => false,
                'msg'    => 'Branch Required'
            ]);

            return false;
        }

        $branch = $this->db
            ->select('branch_id')
            ->where('branch_name', $branch_name)
            ->get('branch_master')
            ->row_array();

        if(empty($branch)){

            echo json_encode([
                'status' => false,
                'msg'    => 'Branch Not Found'
            ]);

            return false;
        }

        $branch_id = (int)$branch['branch_id'];

    }else{

        $branch_id = (int)$_SESSION['user_branch_id'];
    }


    /* =========================================
       FORMATTED DATES
    ========================================= */

    $entry_date_from = date('Y-m-d', strtotime($entry_date_from));

    $entry_date_to   = date('Y-m-d', strtotime($entry_date_to));


    /* =========================================
       ALREADY EXISTS CHECK
    ========================================= */

    $this->db->where('dtrm_entry_date_from', $entry_date_from);

    $this->db->where('dtrm_entry_date_to', $entry_date_to);

    $this->db->where('dtrm_branch_id', $branch_id);

    $this->db->where('dtrm_customer_id', $customer_id);

    $check = $this->db
        ->get('daily_transaction_report_master')
        ->row_array();

    if(!empty($check)){

        echo json_encode([
            'status' => false,
            'msg'    => 'Already Marked Received'
        ]);

        return false;
    }


    /* =========================================
       INSERT
    ========================================= */

    $insert_data = [

        'dtrm_entry_date_from' => $entry_date_from,

        'dtrm_entry_date_to'   => $entry_date_to,

        'dtrm_customer_id'     => $customer_id,

        'dtrm_branch_id'       => $branch_id,

        'dtrm_status'          => $status,

        'dtrm_created_at'      => date('Y-m-d H:i:s'),

        'dtrm_created_by'      => $_SESSION['user_id']
    ];

    $this->db->insert(
        'daily_transaction_report_master',
        $insert_data
    );


    /* =========================================
       RESPONSE
    ========================================= */

    if($this->db->affected_rows() > 0){

        echo json_encode([
            'status' => true,
            'msg'    => 'Marked as RECEIVED Successfully'
        ]);

    }else{

        echo json_encode([
            'status' => false,
            'msg'    => 'Database Error'
        ]);
    }
}
}
?>
