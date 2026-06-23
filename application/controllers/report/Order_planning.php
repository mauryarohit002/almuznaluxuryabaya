<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class order_planning extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'order_planning';
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
		$record['data']			= $this->model->get_record();
		$record['total_rows']	= $record['data']['rows'];
		// echo "<pre>"; print_r($record); exit;

		$this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/_list', $record);
	}
    public function pdf(){
		$record = $this->model->get_record();
		$this->load->view('pages/'.$this->menu.'/'.$this->sub_menu.'/pdf/report', $record);
	}

	public function excel(){	
		$result = isLoggedIn();
		// echo "<pre>"; print_r($_POST);exit;
		if(!$result['session'] || !$result['status'] || !$result['active']){
			redirect('login/logout?msg='.$result['msg']);
			return;
		}
		$result = isMenuAssigned($this->menu, $this->sub_menu, 'excel');
		if(!$result['session'] || !$result['status'] || !$result['active']) {
			$this->load->view('errors/unauthorized'); return;
		}
		$record	= $this->model->get_record();
		// echo "<pre>"; print_r($record); exit; 

		$line_no= 1;
		$this->load->library('excel'); 
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle("ORDER PLANNING");
		$this->excel->getActiveSheet()->setCellValue('A'.$line_no, "ORDER PLANNING");			 
		$this->excel->getActiveSheet()->mergeCells('A'.$line_no.":M".$line_no);			 
		$this->excel->getActiveSheet()->getStyle('A'.$line_no.":M".$line_no)->getFont()->setBold( true );	
	 	$this->excel->getActiveSheet()->getStyle('A'.$line_no.":M".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	 	$this->excel->getActiveSheet()->setCellValue('N'.$line_no, date('d-m-Y H:i:s'));			 
		$this->excel->getActiveSheet()->mergeCells('N'.$line_no.":P".$line_no);			 
		$this->excel->getActiveSheet()->getStyle('N'.$line_no.":P".$line_no)->getFont()->setBold( true );	
	 	$this->excel->getActiveSheet()->getStyle('N'.$line_no.":P".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		 $line_no++;
		 if(!empty($record['data'])){
			 $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('A'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('A'.$line_no, 'APPAAREL');
 
			 $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('B'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('B'.$line_no, 'TOTAL');

			foreach ($record['data'] as $key => $value) {
			 	$col_head='C';
			 	foreach ($value as $k => $v) { 
			 		$header='';
			 		if($k=='apparel_name'){
			 			$header="APPAAREL";
			 		}else if($k<1){
			 			$header="TOTAL";
			 		}else{
			 			$header=$k;
			 		}

			 		$this->excel->getActiveSheet()->getColumnDimension($col_head)->setAutoSize(true);	
			 		$this->excel->getActiveSheet()->getStyle($col_head.$line_no)->getFont()->setBold( true );	
			 		$this->excel->getActiveSheet()->SetCellValue($col_head.$line_no, $header);

					$col_head++;
			 	 }
			 }

 			 $line_no++;
 
			 foreach ($record['data'] as $key => $value) {
			 	$col='A';
			 	foreach ($value as $k => $v) {
			 	 	$this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);		                    
					$this->excel->getActiveSheet()->SetCellValue($col.$line_no, $v);
					$col++;
			 	 } 
				 $line_no++;
			 }
 
		 }

		 $filename='Order_planning_'.time().'.xlsx';
		 header('Content-Type: application/vnd.ms-excel');
		 header('Content-Disposition: attachment;filename="'.$filename.'"');
		 header('Cache-Control: max-age=0');
		 $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		 $objWriter->save('php://output');
	}
	
}
?>
