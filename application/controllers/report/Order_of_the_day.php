<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class Order_of_the_day extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'order_of_the_day';
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
		$this->excel->getActiveSheet()->setTitle("ORDER OF THE DAY");
		$this->excel->getActiveSheet()->setCellValue('A'.$line_no, "ORDER OF THE DAY");			 
		$this->excel->getActiveSheet()->mergeCells('A'.$line_no.":D".$line_no);			 
		$this->excel->getActiveSheet()->getStyle('A'.$line_no.":D".$line_no)->getFont()->setBold( true );	
	 	$this->excel->getActiveSheet()->getStyle('A'.$line_no.":D".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	 	$this->excel->getActiveSheet()->setCellValue('E'.$line_no, date('d-m-Y H:i:s'));			 
		$this->excel->getActiveSheet()->mergeCells('E'.$line_no.":I".$line_no);			 
		$this->excel->getActiveSheet()->getStyle('E'.$line_no.":I".$line_no)->getFont()->setBold( true );	
	 	$this->excel->getActiveSheet()->getStyle('E'.$line_no.":I".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		 $line_no++;
		 if(!empty($record['data'])){ 
			$this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);		                    
			$this->excel->getActiveSheet()->getStyle('A'.$line_no)->getFont()->setBold( true );	
			$this->excel->getActiveSheet()->SetCellValue('A'.$line_no, 'TYPE');

			$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$this->excel->getActiveSheet()->getStyle('B'.$line_no)->getFont()->setBold( true );	
			$this->excel->getActiveSheet()->SetCellValue('B'.$line_no, 'APPAREL/FABRIC');

			$this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$this->excel->getActiveSheet()->getStyle('C'.$line_no)->getFont()->setBold( true );	
			$this->excel->getActiveSheet()->SetCellValue('C'.$line_no, 'QTY');
			 
			 $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('D'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('D'.$line_no, 'RATE');
 
			 $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('E'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('E'.$line_no, 'AMOUNT');
 
			 $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('F'.$line_no)->getFont()->setBold(true);	
			 $this->excel->getActiveSheet()->SetCellValue('F'.$line_no, 'DISCOUNT');

			 $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('G'.$line_no)->getFont()->setBold(true);	
			 $this->excel->getActiveSheet()->SetCellValue('G'.$line_no, 'TAXABLE AMOUNT');

			 $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('H'.$line_no)->getFont()->setBold(true);	
			 $this->excel->getActiveSheet()->SetCellValue('H'.$line_no, 'GST AMOUNT');

			 $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('I'.$line_no)->getFont()->setBold(true);	
			 $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, 'TOTAL AMOUNT');
 
			 $line_no++;

			 foreach ($record['data'] as $key => $value) { 
				 $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('A'.$line_no, $value['type']); 
 
 				$this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);		                   
				 $this->excel->getActiveSheet()->SetCellValue('B'.$line_no, $value['apparel_name']);

				 $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('C'.$line_no, $value['qty']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('D'.$line_no, $value['rate']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('E'.$line_no, $value['amt']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('F'.$line_no, $value['disc_amt']);
				 
				 $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('G'.$line_no, $value['taxable_amt']);

				 $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('H'.$line_no, $value['gst_amt']);

				 $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, $value['total_amt']);

				 $line_no++;
			 } 
 
			 $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('B'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('B'.$line_no, 'TOTAL');
 
			 $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('C'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('C'.$line_no, $record['totals']['qty']);

			 $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('F'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('F'.$line_no, $record['totals']['disc_amt']);

			 $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('G'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('G'.$line_no, $record['totals']['taxable_amt']);

			 $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('H'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('H'.$line_no, $record['totals']['gst_amt']);

			 $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('I'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, $record['totals']['total_amt']);
		 }

		 $filename='Order_of_the day_'.time().'.xlsx';
		 header('Content-Type: application/vnd.ms-excel');
		 header('Content-Disposition: attachment;filename="'.$filename.'"');
		 header('Cache-Control: max-age=0');
		 $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		 $objWriter->save('php://output');
	}
	
}
?>
