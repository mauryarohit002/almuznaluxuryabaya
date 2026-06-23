<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class qrcode_stock extends my_controller{
	protected $menu;
    protected $sub_menu; 
	public function __construct(){ 
		$this->menu     = 'report';
        $this->sub_menu = 'qrcode_stock';
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
		$this->excel->getActiveSheet()->setTitle("Fabric Barcode Stock");
		$this->excel->getActiveSheet()->setCellValue('A'.$line_no, "Fabric Barcode Stock");			 
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
			 $this->excel->getActiveSheet()->SetCellValue('A'.$line_no, 'ENTRY NO');
 
			 $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('B'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('B'.$line_no, 'ENTRY DATE');
 
			 $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('C'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('C'.$line_no, 'NOD');
 
			 $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('D'.$line_no)->getFont()->setBold( true );
			 $this->excel->getActiveSheet()->SetCellValue('D'.$line_no, 'ITEM CODE');

			 $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('E'.$line_no)->getFont()->setBold( true );
			 $this->excel->getActiveSheet()->SetCellValue('E'.$line_no, 'SUPPLIER NAME');

			 $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('F'.$line_no)->getFont()->setBold( true );
			 $this->excel->getActiveSheet()->SetCellValue('F'.$line_no, 'FABRIC');
 
			 $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('G'.$line_no)->getFont()->setBold( true );
			 $this->excel->getActiveSheet()->SetCellValue('G'.$line_no, 'FABRIC CODE');
 
			 $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('H'.$line_no)->getFont()->setBold( true );
			 $this->excel->getActiveSheet()->SetCellValue('H'.$line_no, 'CATEGORY');
 
			 $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('I'.$line_no)->getFont()->setBold( true );
			 $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, 'COLOR');
 
			 $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('J'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('J'.$line_no, 'WIDTH');
 
			 $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('K'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('K'.$line_no, 'DESCRIPTION');
 
			 $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('L'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('L'.$line_no, 'RATE');
 
			 $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('M'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('M'.$line_no, 'MRP');
 
			 $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('N'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('N'.$line_no, 'PURCHASE QTY');
 
			 $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);	
			 $this->excel->getActiveSheet()->getStyle('O'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('O'.$line_no, 'ORDER QTY');

			 $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('P'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('P'.$line_no, 'BALANCE QTY');

			 $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('Q'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('Q'.$line_no, 'BALANCE AMT');

			 $line_no++;
 
			 foreach ($record['data'] as $key => $value) {
				 $this->excel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('A'.$line_no, $value['entry_no']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('B'.$line_no, $value['entry_date']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);		                    
				 $this->excel->getActiveSheet()->SetCellValue('C'.$line_no, $value['nod']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('D'.$line_no, $value['item_code']);

				 $this->excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('E'.$line_no, $value['supplier_name']);

				 $this->excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('F'.$line_no, $value['fabric_name']);

				 $this->excel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('G'.$line_no, $value['design_name']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('H'.$line_no, $value['category_name']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, $value['color_name']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('J'.$line_no, $value['width_name']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('K'.$line_no, $value['description']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true); 
				 $this->excel->getActiveSheet()->SetCellValue('L'.$line_no, $value['rate']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('M'.$line_no, $value['mrp']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
				 $this->excel->getActiveSheet()->SetCellValue('N'.$line_no, $value['pt_mtr']);
 
				 $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);	
				 $this->excel->getActiveSheet()->SetCellValue('O'.$line_no, $value['ot_mtr']);

				 $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);	
				 $this->excel->getActiveSheet()->SetCellValue('P'.$line_no, $value['bal_mtr']);

				 $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);	
				 $this->excel->getActiveSheet()->SetCellValue('Q'.$line_no, $value['bal_amt']);
 
				 $line_no++;
			 }
 
			 $this->excel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);		                    
			 $this->excel->getActiveSheet()->getStyle('M'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('M'.$line_no, 'TOTAL');
 
			 $this->excel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('N'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('N'.$line_no, $record['totals']['pt_mtr']);

			 $this->excel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('O'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('O'.$line_no, $record['totals']['ot_mtr']);

			 $this->excel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('P'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('P'.$line_no, $record['totals']['bal_mtr']);

			 $this->excel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			 $this->excel->getActiveSheet()->getStyle('Q'.$line_no)->getFont()->setBold( true );	
			 $this->excel->getActiveSheet()->SetCellValue('Q'.$line_no, $record['totals']['bal_amt']);

		 }

		 $filename='fabric_barcode_summary_'.time().'.xlsx';
		 header('Content-Type: application/vnd.ms-excel');
		 header('Content-Disposition: attachment;filename="'.$filename.'"');
		 header('Cache-Control: max-age=0');
		 $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		 $objWriter->save('php://output');
	}

}
?>
