<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class job_work extends my_controller{
    protected $menu;
    protected $sub_menu;
	public function __construct(){
        $this->menu     = 'transaction'; 
        $this->sub_menu = 'job_work'; 
        parent::__construct($this->menu, $this->sub_menu); 
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
        if($_GET['action'] == 'issue'){
            $result = isMenuAssigned($this->menu, $this->sub_menu, $_GET['action']);
            if(!$result['session'] || !$result['status'] || !$result['active']){
                $this->load->view('errors/unauthorized'); return;
            }
            redirect(base_url('/transaction/job_issue?action=add'));
        }
        $this->load->view('errors/unauthorized'); return;
    }
}
?>