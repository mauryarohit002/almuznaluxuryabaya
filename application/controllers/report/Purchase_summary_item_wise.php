<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class purchase_summary_item_wise extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'purchase_summary_item_wise';
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

	public function excel() {	
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

		    $record = $this->model->get_record();

		    $line_no = 1;
		    $this->load->library('excel');
		    $this->excel->setActiveSheetIndex(0);
		    $this->excel->getActiveSheet()->setTitle("Purchase Summary Item Wise");
		    $this->excel->getActiveSheet()->setCellValue('A'.$line_no, "Purchase Summary Item Wise");			 
		    $this->excel->getActiveSheet()->mergeCells('A'.$line_no.":R".$line_no);			 
		    $this->excel->getActiveSheet()->getStyle('A'.$line_no.":R".$line_no)->getFont()->setBold(true);	
		    $this->excel->getActiveSheet()->getStyle('A'.$line_no.":R".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		    $this->excel->getActiveSheet()->setCellValue('Q'.$line_no, date('d-m-Y H:i:s'));			 
		    $this->excel->getActiveSheet()->mergeCells('Q'.$line_no.":R".$line_no);			 
		    $this->excel->getActiveSheet()->getStyle('Q'.$line_no.":R".$line_no)->getFont()->setBold(true);	
		    $this->excel->getActiveSheet()->getStyle('Q'.$line_no.":R".$line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		    $line_no++;
		    if(!empty($record['data'])) {
		        $headers = [
		            'A' => 'ENTRY NO',
		            'B' => 'ENTRY DATE',
		            'C' => 'SUPPLIER',
		            'D' => 'FABRIC',
		            'E' => 'DESIGN NO',
		            'F' => 'COLOR',
		            'G' => 'HSN',
		            'H' => 'QTY',
		            'I' => 'MTR',
		            'J' => 'TOTAL MTR',
		            'K' => 'RATE',
		            'L' => 'SUB AMT',
		            'M' => 'DISC AMT',
		            'N' => 'TAXABLE AMT',
		            'O' => 'SGST AMT',
		            'P' => 'CGST AMT',
		            'Q' => 'IGST AMT',
		            'R' => 'TOTAL AMT'
		        ];

		        foreach ($headers as $col => $label) {
		            $this->excel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		            $this->excel->getActiveSheet()->getStyle($col.$line_no)->getFont()->setBold(true);
		            $this->excel->getActiveSheet()->SetCellValue($col.$line_no, $label);
		        }

		        $line_no++;

		        foreach ($record['data'] as $value) {
		            $this->excel->getActiveSheet()->SetCellValue('A'.$line_no, $value['entry_no']);
		            $this->excel->getActiveSheet()->SetCellValue('B'.$line_no, $value['entry_date1']);
		            $this->excel->getActiveSheet()->SetCellValue('C'.$line_no, $value['supplier_name']);
		            $this->excel->getActiveSheet()->SetCellValue('D'.$line_no, $value['fabric_name']);
		            $this->excel->getActiveSheet()->SetCellValue('E'.$line_no, $value['design_name']);
		            $this->excel->getActiveSheet()->SetCellValue('F'.$line_no, $value['color_name']);
		            $this->excel->getActiveSheet()->SetCellValue('G'.$line_no, $value['hsn_name']);
		            $this->excel->getActiveSheet()->SetCellValue('H'.$line_no, $value['qty']);
		            $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, $value['mtr']);
		            $this->excel->getActiveSheet()->SetCellValue('J'.$line_no, $value['total_mtr']);
		            $this->excel->getActiveSheet()->SetCellValue('K'.$line_no, $value['rate']);
		            $this->excel->getActiveSheet()->SetCellValue('L'.$line_no, $value['sub_amt']);
		            $this->excel->getActiveSheet()->SetCellValue('M'.$line_no, $value['disc_amt']);
		            $this->excel->getActiveSheet()->SetCellValue('N'.$line_no, $value['taxable_amt']);
		            $this->excel->getActiveSheet()->SetCellValue('O'.$line_no, $value['sgst_amt']);
		            $this->excel->getActiveSheet()->SetCellValue('P'.$line_no, $value['cgst_amt']);
		            $this->excel->getActiveSheet()->SetCellValue('Q'.$line_no, $value['igst_amt']);
		            $this->excel->getActiveSheet()->SetCellValue('R'.$line_no, $value['total_amt']);
		            $line_no++;
		        }

		        // Totals row
		        $this->excel->getActiveSheet()->getStyle('I'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('I'.$line_no, 'TOTAL');

		        $this->excel->getActiveSheet()->getStyle('J'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('J'.$line_no, $record['totals']['total_mtr']);

		        $this->excel->getActiveSheet()->getStyle('L'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('L'.$line_no, $record['totals']['sub_amt']);

		        $this->excel->getActiveSheet()->getStyle('M'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('M'.$line_no, $record['totals']['disc_amt']);

		        $this->excel->getActiveSheet()->getStyle('N'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('N'.$line_no, $record['totals']['taxable_amt']);

		        $this->excel->getActiveSheet()->getStyle('O'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('O'.$line_no, $record['totals']['sgst_amt']);

		        $this->excel->getActiveSheet()->getStyle('P'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('P'.$line_no, $record['totals']['cgst_amt']);

		        $this->excel->getActiveSheet()->getStyle('Q'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('Q'.$line_no, $record['totals']['igst_amt']);

		        $this->excel->getActiveSheet()->getStyle('R'.$line_no)->getFont()->setBold(true);	
		        $this->excel->getActiveSheet()->SetCellValue('R'.$line_no, $record['totals']['total_amt']);
		    }

		    $filename = 'Purchase_summary_item_wise_'.time().'.xlsx';
		    header('Content-Type: application/vnd.ms-excel');
		    header('Content-Disposition: attachment;filename="'.$filename.'"');
		    header('Cache-Control: max-age=0');
		    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		    $objWriter->save('php://output');
		}
}
?>
