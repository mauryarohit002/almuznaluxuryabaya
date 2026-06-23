<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class sales_summary extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'sales_summary';
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
		    if(!$result['session'] || !$result['status'] || !$result['active']){
		        redirect('login/logout?msg='.$result['msg']);
		        return;
		    }

		    $result = isMenuAssigned($this->menu, $this->sub_menu, 'excel');
		    if(!$result['session'] || !$result['status'] || !$result['active']) {
		        $this->load->view('errors/unauthorized'); 
		        return;
		    }

		    $record	= $this->model->get_record();

		    $line_no = 1;
		    $this->load->library('excel');
		    $this->excel->setActiveSheetIndex(0);
		    $sheet = $this->excel->getActiveSheet();
		    $sheet->setTitle("Sales Summary");

		    // Title Row
		    $sheet->setCellValue('A'.$line_no, "Sales Summary");			 
		    $sheet->mergeCells('A'.$line_no.":Q".$line_no);			 
		    $sheet->getStyle('A'.$line_no.":Q".$line_no)->getFont()->setBold(true);	
		    $sheet->getStyle('A'.$line_no.":Q".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		    $line_no++;

		    // Timestamp
		    $sheet->setCellValue('N'.$line_no, date('d-m-Y H:i:s'));			 
		    $sheet->mergeCells('N'.$line_no.":Q".$line_no);			 
		    $sheet->getStyle('N'.$line_no.":Q".$line_no)->getFont()->setBold(true);	
		    $sheet->getStyle('N'.$line_no.":Q".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		    $line_no++;

		    if(!empty($record['data'])){
		        // Column Headers
		        $headers = [
		            'A' => 'MODULE NAME',
		            'B' => 'ENTRY NO',
		            'C' => 'ENTRY DATE',
		            'D' => 'BILLING NAME',
		            'E' => 'MOBILE NUMBER',
		            'F' => 'SALES MAN',
		            'G' => 'TOTAL MTR',
		            'H' => 'SUB AMT',
		            'I' => 'DISC AMT',
		            'J' => 'TAXABLE AMT',
		            'K' => 'SGST AMT',
		            'L' => 'CGST AMT',
		            'M' => 'IGST AMT',
		            'N' => 'BILL DISC AMT',
		            'O' => 'TOTAL AMT',
		            'P' => 'ADVANCE AMT',
		            'Q' => 'BALANCE AMT',
		        ];

		        foreach ($headers as $col => $name) {
		            $sheet->getColumnDimension($col)->setAutoSize(true);
		            $sheet->getStyle($col.$line_no)->getFont()->setBold(true);
		            $sheet->setCellValue($col.$line_no, $name);
		        }

		        $line_no++;

		        // Data Rows
		        foreach ($record['data'] as $key => $value) {
		            $sheet->setCellValue('A'.$line_no, $value['module_name']);
		            $sheet->setCellValue('B'.$line_no, $value['entry_no']);
		            $sheet->setCellValue('C'.$line_no, $value['entry_date1']);
		            $sheet->setCellValue('D'.$line_no, $value['billing_name']);
		            $sheet->setCellValue('E'.$line_no, $value['billing_mobile']);
		            $sheet->setCellValue('F'.$line_no, $value['user_name']);
		            $sheet->setCellValue('G'.$line_no, $value['total_mtr']);
		            $sheet->setCellValue('H'.$line_no, $value['sub_amt']);
		            $sheet->setCellValue('I'.$line_no, $value['disc_amt']);
		            $sheet->setCellValue('J'.$line_no, $value['taxable_amt']);
		            $sheet->setCellValue('K'.$line_no, $value['sgst_amt']);
		            $sheet->setCellValue('L'.$line_no, $value['cgst_amt']);
		            $sheet->setCellValue('M'.$line_no, $value['igst_amt']);
		            $sheet->setCellValue('N'.$line_no, $value['bill_disc_amt']);
		            $sheet->setCellValue('O'.$line_no, $value['total_amt']);
		            $sheet->setCellValue('P'.$line_no, $value['advance_amt']);
		            $sheet->setCellValue('Q'.$line_no, $value['balance_amt']);
		            $line_no++;
		        }

		        // Totals Row
		        $sheet->getStyle('F'.$line_no.":Q".$line_no)->getFont()->setBold(true);
		        $sheet->setCellValue('E'.$line_no, 'TOTAL');
		        $sheet->setCellValue('G'.$line_no, $record['totals']['total_mtr']);
		        $sheet->setCellValue('H'.$line_no, $record['totals']['sub_amt']);
		        $sheet->setCellValue('I'.$line_no, $record['totals']['disc_amt']);
		        $sheet->setCellValue('J'.$line_no, $record['totals']['taxable_amt']);
		        $sheet->setCellValue('K'.$line_no, $record['totals']['sgst_amt']);
		        $sheet->setCellValue('L'.$line_no, $record['totals']['cgst_amt']);
		        $sheet->setCellValue('M'.$line_no, $record['totals']['igst_amt']);
		        $sheet->setCellValue('N'.$line_no, $record['totals']['bill_disc_amt']);
		        $sheet->setCellValue('O'.$line_no, $record['totals']['total_amt']);
		        $sheet->setCellValue('P'.$line_no, $record['totals']['advance_amt']);
		        $sheet->setCellValue('Q'.$line_no, $record['totals']['balance_amt']);
		    }

		    // Output Excel file
		    $filename = 'Sales_summary_'.time().'.xlsx';
		    header('Content-Type: application/vnd.ms-excel');
		    header('Content-Disposition: attachment;filename="'.$filename.'"');
		    header('Cache-Control: max-age=0');

		    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		    $objWriter->save('php://output');
		}


}
?>
