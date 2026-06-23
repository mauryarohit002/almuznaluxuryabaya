<?php defined('BASEPATH') OR exit('No direct script access allowed');
class patch extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('excel');
	}
	public function upload_measurement(){
		$path = "./public/assets/import/master/cmt_7.csv";
		// echo "<pre>";print_r($path);exit;
		$inputFileType 	= PHPExcel_IOFactory::identify($path);
		$objReader 		= PHPExcel_IOFactory::createReader($inputFileType);
    	$objPHPExcel 	= $objReader->load($path);
    	$allDataInSheet = $objPHPExcel->getActiveSheet();
		$arrayCount 	= count($allDataInSheet);
		$excel 			= [];

		foreach ($allDataInSheet->getRowIterator() as $row){
			if($row->getRowIndex() >= 0){
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				if(isRowEmpty($cellIterator)){
					foreach ($cellIterator as $cell){
					   	$excel[$cell->getRow()][$cell->getColumn()] = trim($cell->getFormattedValue(), " ");
					}
                    
				}
			}
		}
        // echo "<pre>"; print_r($excel); exit;

		if(empty($excel)){
		 	echo json_encode(['msg' => 'File is empty.']); return;
		}
		foreach ($excel as $key => $value) {
			// *****************************************
				// echo "<pre>"; print_r($value); exit;
                $data                       = [];
				$data['cmt_id']             = trim($value['A']);
				$data['cmt_measurement_id'] = trim($value['B']);
				$data['cmt_customer_id']    = trim($value['C']);
				$data['cmt_apparel_id']     = trim($value['D']);
				$data['cmt_value1']         = trim($value['E']);
				$data['cmt_value2']         = trim($value['F']);
				$data['cmt_deleted_by']     = trim($value['G']);
				$data['cmt_remark']         = trim($value['H']);
				$data['cmt_is_exist']       = trim($value['I']);

				$data['cmt_om_uuid']        = $key.''.time();
				$data['cmt_created_by'] 	= 1;
				$data['cmt_updated_by'] 	= 1;
				$data['cmt_created_at'] 	= date('Y-m-d H:i:s');
				$data['cmt_updated_at'] 	= date('Y-m-d H:i:s');
				$data['cmt_updated_at'] 	= $data['cmt_deleted_by'] == 'NULL' ? 'NULL' : date('Y-m-d H:i:s');
                if(is_numeric($data['cmt_id']) && is_numeric($data['cmt_customer_id']) && is_numeric($data['cmt_apparel_id'])){
                    $isExist = $this->db_operations->get_record('customer_measurement_trans', ['cmt_id' => $data['cmt_id']]);
                    if(empty($isExist)){
                        if($this->db_operations->data_insert('customer_measurement_trans', $data) > 0){
							echo $key." = ".$data['cmt_id']."<br/>";
						}
                    }
                }
            }
	}
	public function upload_style($no){
		$path = "./public/assets/import/master/cst_".$no.".xlsx";
		// echo "<pre>";print_r($path);exit;
		$inputFileType 	= PHPExcel_IOFactory::identify($path);
		$objReader 		= PHPExcel_IOFactory::createReader($inputFileType);
    	$objPHPExcel 	= $objReader->load($path);
    	$allDataInSheet = $objPHPExcel->getActiveSheet();
		$arrayCount 	= count($allDataInSheet);
		$excel 			= [];

		foreach ($allDataInSheet->getRowIterator() as $row){
			if($row->getRowIndex() >= 0){
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				if(isRowEmpty($cellIterator)){
					foreach ($cellIterator as $cell){
					   	$excel[$cell->getRow()][$cell->getColumn()] = trim($cell->getFormattedValue(), " ");
					}
                    
				}
			}
		}
        // echo "<pre>"; print_r($excel); exit;

		if(empty($excel)){
		 	echo json_encode(['msg' => 'File is empty.']); return;
		}
		foreach ($excel as $key => $value) {
			// *****************************************
				// echo "<pre>"; print_r($value); exit;
                $data                       = [];
				$data['cst_id']             = trim($value['A']);
				$data['cst_om_id'] 			= trim($value['B']);
				$data['cst_style_id']    	= trim($value['C']);
				$data['cst_apparel_id']     = trim($value['D']);
				$data['cst_customer_id']    = trim($value['E']);
				$data['cst_deleted_by']     = trim($value['F']);
				
				$data['cst_om_uuid']        = $key.''.time();
				$data['cst_value']          = 1;
				$data['cst_created_by'] 	= 1;
				$data['cst_updated_by'] 	= 1;
				$data['cst_created_at'] 	= date('Y-m-d H:i:s');
				$data['cst_updated_at'] 	= date('Y-m-d H:i:s');
				$data['cst_updated_at'] 	= $data['cst_deleted_by'] == 'NULL' ? 'NULL' : date('Y-m-d H:i:s');
                if(is_numeric($data['cst_id']) && is_numeric($data['cst_customer_id']) && is_numeric($data['cst_apparel_id'])){
                    $isExist = $this->db_operations->get_record('customer_style_trans', ['cst_id' => $data['cst_id']]);
                    if(empty($isExist)){
                        if($this->db_operations->data_insert('customer_style_trans', $data) > 0){
							echo $key." = ".$data['cst_id']."<br/>";
						}
                    }
                }
            }
	}
	public function upload_bill($no){
		$path = "./public/assets/import/master/cmt_".$no.".xlsx";
		// echo "<pre>";print_r($path);exit;
		$inputFileType 	= PHPExcel_IOFactory::identify($path);
		$objReader 		= PHPExcel_IOFactory::createReader($inputFileType);
    	$objPHPExcel 	= $objReader->load($path);
    	$allDataInSheet = $objPHPExcel->getActiveSheet();
		$arrayCount 	= count($allDataInSheet);
		$excel 			= [];

		foreach ($allDataInSheet->getRowIterator() as $row){
			if($row->getRowIndex() >= 0){
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				if(isRowEmpty($cellIterator)){
					foreach ($cellIterator as $cell){
					   	$excel[$cell->getRow()][$cell->getColumn()] = trim($cell->getFormattedValue(), " ");
					}
                    
				}
			}
		}
        // echo "<pre>"; print_r($excel); exit;

		if(empty($excel)){
		 	echo json_encode(['msg' => 'File is empty.']); return;
		}
		foreach ($excel as $key => $value) {
			$data                       = [];
			$cmt_id             		= trim($value['A']);
			$data['cmt_bill_no'] 		= trim($value['B']);
			$data['cmt_bill_date']    	= trim($value['C']);
			if(is_numeric($cmt_id)){
				$isExist = $this->db_operations->get_record('customer_measurement_trans', ['cmt_id' => $cmt_id]);
				if(!empty($isExist)){
					$this->db_operations->data_update('customer_measurement_trans', $data, 'cmt_id', $cmt_id);
				}
			}
		}
		echo "updated";
	}
	public function upload_bill_id(){
		$path = "./public/assets/import/master/bill.xlsx";
		// echo "<pre>";print_r($path);exit;
		$inputFileType 	= PHPExcel_IOFactory::identify($path);
		$objReader 		= PHPExcel_IOFactory::createReader($inputFileType);
    	$objPHPExcel 	= $objReader->load($path);
    	$allDataInSheet = $objPHPExcel->getActiveSheet();
		$arrayCount 	= count($allDataInSheet);
		$excel 			= [];

		foreach ($allDataInSheet->getRowIterator() as $row){
			if($row->getRowIndex() >= 0){
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);
				if(isRowEmpty($cellIterator)){
					foreach ($cellIterator as $cell){
					   	$excel[$cell->getRow()][$cell->getColumn()] = trim($cell->getFormattedValue(), " ");
					}
                    
				}
			}
		}
        // echo "<pre>"; print_r($excel); exit;

		if(empty($excel)){
		 	echo json_encode(['msg' => 'File is empty.']); return;
		}
		foreach ($excel as $key => $value) {
			$data                       = [];
			$bill_id             		= trim($value['A']);
			$data['cst_bill_no'] 		= trim($value['B']);
			$data['cst_bill_date']    	= trim($value['C']);
			$isExist = $this->db_operations->get_record('customer_style_trans', ['cst_om_id' => $bill_id]);
			if(!empty($isExist)){
				$this->db_operations->data_update('customer_style_trans', $data, 'cst_om_id', $bill_id);
			}
		}
		echo "updated";
	}
}
?>
