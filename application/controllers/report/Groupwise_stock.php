<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';
class groupwise_stock extends my_controller{
	protected $menu;
    protected $sub_menu;
	public function __construct(){
		$this->menu     = 'report';
        $this->sub_menu = 'groupwise_stock';
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
		    if (!$result['session'] || !$result['status'] || !$result['active']) {
		        redirect('login/logout?msg=' . $result['msg']);
		        return;
		    }

		    $result = isMenuAssigned($this->menu, $this->sub_menu, 'excel');
		    if (!$result['session'] || !$result['status'] || !$result['active']) {
		        $this->load->view('errors/unauthorized');
		        return;
		    }

		    $record = $this->model->get_record();
		    $line_no = 1;
		    $this->load->library('excel');
		    $sheet = $this->excel->setActiveSheetIndex(0);
		    $sheet->setTitle("GROUP WISE STOCK");

		    // Title row
		    $sheet->setCellValue('A' . $line_no, "GROUP WISE STOCK");
		    $sheet->mergeCells('A' . $line_no . ':H' . $line_no);
		    $sheet->getStyle('A' . $line_no . ':H' . $line_no)->getFont()->setBold(true);
		    $sheet->getStyle('A' . $line_no . ':H' . $line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		    // Date cell
		    $sheet->setCellValue('I' . $line_no, date('d-m-Y H:i:s'));
		    $sheet->mergeCells('I' . $line_no . ':J' . $line_no);
		    $sheet->getStyle('I' . $line_no . ':J' . $line_no)->getFont()->setBold(true);
		    $sheet->getStyle('I' . $line_no . ':J' . $line_no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		    $line_no++;

		    if (!empty($record['data'])) {
		        // Header Row
		        $headers = [
		            'A' => 'CATEGORY',
		            'B' => 'MRP',
		            'C' => 'PURCHASE QTY',
		            'D' => 'PURCHASE RETURN QTY',
		            'E' => 'ORDER QTY',
		            'F' => 'BAL QTY',
		            'G' => 'PURCHASE VALUE',
		            'H' => 'MRP VALUE'
		        ];

		        foreach ($headers as $col => $label) {
		            $sheet->getColumnDimension($col)->setAutoSize(true);
		            $sheet->setCellValue($col . $line_no, $label);
		            $sheet->getStyle($col . $line_no)->getFont()->setBold(true);
		        }

		        $line_no++;

		        // Data Rows
		        foreach ($record['data'] as $value) {
		            $sheet->setCellValue('A' . $line_no, $value['category_name']);
		            $sheet->setCellValue('B' . $line_no, $value['mrp']);
		            $sheet->setCellValue('C' . $line_no, $value['pt_mtr']);
		            $sheet->setCellValue('D' . $line_no, $value['prt_mtr']);
		            $sheet->setCellValue('E' . $line_no, $value['ot_mtr']);
		            $sheet->setCellValue('F' . $line_no, $value['bal_mtr']);
		            $sheet->setCellValue('G' . $line_no, $value['bal_amt']);
		            $sheet->setCellValue('H' . $line_no, $value['bal_mrp']);
		            $line_no++;
		        }

		        // Total Row
		        $sheet->setCellValue('A' . $line_no, 'TOTAL');
		        $sheet->getStyle('A' . $line_no . ':H' . $line_no)->getFont()->setBold(true);
		         $sheet->setCellValue('B' . $line_no, $record['totals']['mrp']);
		        $sheet->setCellValue('C' . $line_no, $record['totals']['pt_mtr']);
		        $sheet->setCellValue('D' . $line_no, $record['totals']['prt_mtr']);
		        $sheet->setCellValue('E' . $line_no, $record['totals']['ot_mtr']);
		        $sheet->setCellValue('F' . $line_no, $record['totals']['bal_mtr']);
		        $sheet->setCellValue('G' . $line_no, $record['totals']['bal_amt']);
		        $sheet->setCellValue('H' . $line_no, $record['totals']['bal_mrp']);
		    }

		    // Output the Excel file
		    $filename = 'groupwise_stock_' . time() . '.xlsx';
		    header('Content-Type: application/vnd.ms-excel');
		    header('Content-Disposition: attachment;filename="' . $filename . '"');
		    header('Cache-Control: max-age=0');
		    $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		    $writer->save('php://output');
		}


}
?>
