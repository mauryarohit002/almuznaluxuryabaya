<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class production_report extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'production_report';
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
		$record['total_rows']	= $record['data']['totals']['rows'];
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
		$this->excel->getActiveSheet()->setTitle("Production Report");
		$this->excel->getActiveSheet()->setCellValue('A'.$line_no, "Production Report");			 
		$this->excel->getActiveSheet()->mergeCells('A'.$line_no.":E".$line_no);			 
		$this->excel->getActiveSheet()->getStyle('A'.$line_no.":E".$line_no)->getFont()->setBold( true );	
	 	$this->excel->getActiveSheet()->getStyle('A'.$line_no.":E".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 

	 	$this->excel->getActiveSheet()->setCellValue('F'.$line_no, date('d-m-Y H:i:s'));			 
		$this->excel->getActiveSheet()->mergeCells('F'.$line_no.":J".$line_no);			 
		$this->excel->getActiveSheet()->getStyle('F'.$line_no.":J".$line_no)->getFont()->setBold( true );	
	 	$this->excel->getActiveSheet()->getStyle('F'.$line_no.":J".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		 $line_no++;
		 if(!empty($record['data'])){
			 $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('A'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('A'.$line_no, 'ENTRY NO');

			 $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('B'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('B'.$line_no, 'ENTRY DATE');

			 $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('C'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('C'.$line_no, 'ORDER NO');
 
			 $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('D'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('D'.$line_no, 'ORDER DATE');
 
			 $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('E'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('E'.$line_no, 'BARCODE');
 
			 $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('F'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('F'.$line_no, 'CUSTOMER');
 
			 $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('G'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('G'.$line_no, 'PROCESS');
 
			 $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('H'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('H'.$line_no, 'KARIGAR');
 
			 $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('I'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, 'APPAREL');
 
			 $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('J'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('J'.$line_no, 'STATUS');
			 $line_no++;
 
			 foreach ($record['data'] as $key => $value) {
				 $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('A'.$line_no, $value['entry_no']);

				 $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('B'.$line_no, date('d-m-Y',strtotime($value['entry_date1'])));

				 $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('C'.$line_no, $value['order_no']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('D'.$line_no, $value['order_date1']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('E'.$line_no, $value['obt_item_code']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('F'.$line_no, $value['customer_name']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('G'.$line_no, $value['proces_name']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('H'.$line_no, $value['karigar_name']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, $value['apparel_name']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('J'.$line_no, $value['job_status']);
				 $line_no++;
			 }
 
			
		 }

		 $filename='Production_report_'.time().'.xlsx';
		 header('Content-Type: application/vnd.ms-excel');
		 header('Content-Disposition: attachment;filename="'.$filename.'"');
		 header('Cache-Control: max-age=0');
		 $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		 $objWriter->save('php://output');
	}
}
?>
