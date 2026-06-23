<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class hisab_model extends my_model{
    public function __construct(){ parent::__construct('transaction', 'hisab'); }
    public function isExist($id){
        $data = $this->db->query("SELECT hm_id FROM hisab_master WHERE hm_delete_status = 0 AND hm_is_paid > 0 AND hm_id = $id LIMIT 1")->result_array();
        if(!empty($data)) return true;
        
        return false;
    }
    public function isTransExist($id){ 
        $query="SELECT hm.hm_id 
                FROM hisab_master hm
                INNER JOIN hisab_trans ht ON(ht.ht_hm_id = hm.hm_id)
                WHERE hm.hm_delete_status = 0 
                AND hm.hm_is_paid > 0
                AND ht.ht_id = $id 
                LIMIT 1";
        $data = $this->db->query($query)->result_array();
        if(!empty($data)) return true;

        return false;
    }
    public function get_list($wantCount, $per_page = 20, $offset = 0){
        $record 	= [];
        $subsql 	= '';
        $limit  	= '';
        $ofset  	= '';
        
        if(!$wantCount){
            $limit .= " LIMIT $per_page";
            $ofset .= " OFFSET $offset";
        }
        
        if(isset($_GET['_entry_no']) && !empty($_GET['_entry_no'])){
            $subsql .=" AND hm.hm_entry_no = '".$_GET['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
            $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
        }
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND hm.hm_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND hm.hm_entry_date <= '".$_entry_date_to."'";
        }
        if(isset($_GET['_karigar_name']) && !empty($_GET['_karigar_name'])){
            $subsql .=" AND karigar.karigar_name = '".$_GET['_karigar_name']."'";
            $record['filter']['_karigar_name']['value'] = $_GET['_karigar_name'];
            $record['filter']['_karigar_name']['text'] = $_GET['_karigar_name'];
        }
        $query="SELECT hm.*,
                UPPER(karigar.karigar_name) as karigar_name
                FROM hisab_master hm
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = hm.hm_karigar_id)
                WHERE hm.hm_delete_status = 0
                $subsql
                ORDER BY hm.hm_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['hm_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){
        $record['hm_entry_no'] 	= $this->get_max_entry_no(['entry_no' => 'hm_entry_no', 'delete_status' => 'hm_delete_status', 'fin_year' => 'hm_fin_year']);
        $record['hm_uuid'] 	    = $_SESSION['user_id'].''.time();
        return $record;
    }
    public function get_data_for_edit($id){
        $query="SELECT hm.*,
                UPPER(karigar.karigar_name) as karigar_name
                FROM hisab_master hm
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = hm.hm_karigar_id)
                WHERE hm.hm_id = $id
                AND hm.hm_delete_status = 0";
        $record['master_data'] = $this->db->query($query)->result_array();
        if(!empty($record['master_data'])){
            $record['master_data'][0]['isExist'] = $this->isExist($id);
        }
        // echo "<pre>"; print_r($record); exit;
        return $record;
    }
    public function get_transaction($hm_id){ 
        $query="SELECT ht.*,
                jim.jim_entry_no as entry_no,
                DATE_FORMAT(jim.jim_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(apparel.apparel_name) as apparel_name,
                obt.obt_item_code as qrcode
                FROM hisab_trans ht
                INNER JOIN job_issue_master jim ON(jim.jim_id = ht.ht_jim_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = ht.ht_obt_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = ht.ht_apparel_id)
                WHERE ht.ht_hm_id = $hm_id
                AND ht.ht_delete_status = 0
                ORDER BY ht.ht_id DESC";
        $record = $this->db->query($query)->result_array();
        if(!empty($record)){
            foreach ($record as $key => $value) {
                $record[$key]['isExist'] = $this->isTransExist($value['ht_id']);
            }
        }
        return $record;
    }
    public function get_job_data($id){ 
		$query="SELECT 0 as ht_id,
                jrt.jrt_id as ht_jrt_id,
                jrt.jrt_jit_id as ht_jit_id,
                obt.obt_id as ht_obt_id,
                jim.jim_id as ht_jim_id,
                jim.jim_entry_no as entry_no,
                DATE_FORMAT(jim.jim_entry_date, '%d-%m-%Y') as entry_date,
                apparel.apparel_id as ht_apparel_id,
                UPPER(apparel.apparel_name) as apparel_name,
                obt.obt_item_code as qrcode,
                -- IFNULL((SELECT kapt.kapt_rate FROM karigar_apparel_trans kapt WHERE kapt.kapt_karigar_id = $id AND kapt.kapt_apparel_id = apparel.apparel_id), 0) as ht_rate
                sku.sku_fabric,
                sku.sku_cutting,
                sku.sku_silai,
                sku.sku_stone,
                sku.sku_lagwayi,
                sku.sku_hand_work,
                jim.jim_proces_id,
                CASE jim.jim_proces_id
                    WHEN 1 THEN IFNULL(sku.sku_fabric, 0)
                    WHEN 2 THEN IFNULL(sku.sku_cutting, 0)
                    WHEN 3 THEN IFNULL(sku.sku_silai, 0)
                    WHEN 4 THEN IFNULL(sku.sku_stone, 0)
                    WHEN 5 THEN IFNULL(sku.sku_lagwayi, 0)
                    WHEN 6 THEN IFNULL(sku.sku_hand_work, 0)
                    ELSE 0
                END as ht_rate

				FROM job_receive_trans jrt
                INNER JOIN job_issue_master jim ON(jim.jim_id = jrt.jrt_jim_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jrt.jrt_obt_id)
                INNER JOIN apparel_master apparel ON(obt.obt_apparel_id = apparel.apparel_id)
				WHERE jim.jim_delete_status = 0
                AND jrt.jrt_delete_status = 0
                AND jim.jim_hm_id = 0
                AND jim.jim_karigar_id = $id
                ORDER BY jim.jim_entry_no ASC";
		$data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query);
        // echo "<pre>"; print_r($data);exit;
        if(!empty($data)){
             foreach ($data as $key => $value) {
                # code...
             }
        }
        return $data;
	}
    public function get_data_for_print($hm_id){
        $query="SELECT UPPER(company.company_name) as company_name,
                UPPER(company.company_gstin) as gstin,
                LOWER(company.company_email) as email,
                LOWER(company.company_mobile) as mobile,
                UPPER(company.company_address) as address,
                company.company_pincode as pincode,
                IFNULL(UPPER(city.city_name), '') as city_name,
                IFNULL(UPPER(state.state_name), '') as state_name,
                IFNULL(UPPER(state.state_code), '') as state_code,
                IFNULL(UPPER(country.country_name), '') as country_name
                FROM company_master company
                LEFT JOIN city_master city ON(city.city_id = company.company_city_id)
				LEFT JOIN state_master state ON(state.state_id = company.company_state_id)
				LEFT JOIN country_master country ON(country.country_id = company.company_country_id)
                WHERE company.company_constant != ''";
        // echo "<pre>"; print_r($query); exit();
        $record['company_data'] = $this->db->query($query)->result_array();

        $query="SELECT UPPER(payment_mode.payment_mode_name) as payment_mode_name,
                epmt.epmt_amt as payment_mode_amt
                FROM hisab_payment_mode_trans epmt
                INNER JOIN payment_mode_master payment_mode ON(payment_mode.payment_mode_id = epmt.epmt_payment_mode_id)
                WHERE epmt.epmt_hm_id = $hm_id
                AND epmt.epmt_delete_status = 0
                AND epmt.epmt_amt > 0
                ORDER BY epmt.epmt_amt DESC";
        $payment_mode_data = $this->db->query($query)->result_array();

        $payment_mode = '';
        if(!empty($payment_mode_data)){
            foreach ($payment_mode_data as $key => $value) {
                $gst_amt = $this->get_gst_amt($hm_id);
                $payment_mode = empty($payment_mode) ? ($value['payment_mode_name'].' = '.$value['payment_mode_amt']) : $payment_mode.'<br/>'.($value['payment_mode_name'].' = '.$value['payment_mode_amt']);  
            }
        }

        $query="SELECT hm.hm_entry_no as entry_no, 
                DATE_FORMAT(hm.hm_entry_date, '%d-%m-%Y') as entry_date,
                hm.hm_notes as notes,
                hm.hm_total_qty as total_qty,
                hm.hm_total_mtr as total_mtr,
                hm.hm_sub_amt as sub_amt,
                hm.hm_disc_amt as disc_amt,
                hm.hm_taxable_amt as taxable_amt,
                hm.hm_sgst_amt as sgst_amt,
                hm.hm_cgst_amt as cgst_amt,
                hm.hm_igst_amt as igst_amt,
                (hm.hm_sgst_amt + hm.hm_cgst_amt + hm.hm_igst_amt) as gst_amt,
                (hm.hm_taxable_amt + hm.hm_sgst_amt + hm.hm_cgst_amt + hm.hm_igst_amt) as net_amt,
                hm.hm_bill_disc_per as bill_disc_per,
                hm.hm_bill_disc_amt as bill_disc_amt,
                hm.hm_round_off as round_off,
                hm.hm_total_amt as total_amt,
                hm.hm_advance_amt as advance_amt,
                hm.hm_balance_amt as balance_amt,
                UPPER(hm.hm_notes) as notes,
                UPPER(hm.hm_karigar_name) as hm_karigar_name,
                UPPER(hm.hm_karigar_mobile) as hm_karigar_mobile,
                UPPER(karigar.karigar_name) as karigar_name,
                UPPER(karigar.karigar_mobile) as karigar_mobile,
                UPPER(karigar.karigar_gst_no) as karigar_gst_no,
                UPPER(karigar.karigar_address) as karigar_address,
                karigar.karigar_pincode as pincode,
                IFNULL(UPPER(city.city_name), '') as city_name,
                IFNULL(UPPER(state.state_name), '') as state_name,
                IFNULL(UPPER(country.country_name), '') as country_name
                FROM hisab_master hm
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = hm.hm_karigar_id)
                LEFT JOIN city_master city ON(city.city_id = karigar.karigar_city_id)
				LEFT JOIN state_master state ON(state.state_id = karigar.karigar_state_id)
				LEFT JOIN country_master country ON(country.country_id = karigar.karigar_country_id)
                WHERE hm.hm_delete_status = 0 
                AND hm.hm_id = $hm_id";
        // echo "<pre>"; print_r($query); exit();
        $record['master_data'] = $this->db->query($query)->result_array();
        if(!empty($record['master_data'])){
            $record['master_data'][0]['payment_mode'] = $payment_mode;
        }

        $query="SELECT et.et_trans_type as trans_type,
                IFNULL(UPPER(apparel.apparel_name), '') as apparel_name,
                IFNULL(UPPER(fabric.fabric_name), '') as fabric_name,
                IFNULL(UPPER(design.design_name), '') as design_name,
                (et.et_qty) as qty,
                (et.et_mtr) as mtr,
                (et.et_total_mtr) as total_mtr,
                et.et_rate as rate,
                (et.et_amt) as amt,
                et.et_disc_per as disc_per,
                (et.et_disc_amt) as disc_amt,
                (et.et_taxable_amt) as taxable_amt,
                (et.et_sgst_per) as sgst_per,
                (et.et_sgst_amt) as sgst_amt,
                et.et_cgst_per as cgst_per,
                (et.et_cgst_amt) as cgst_amt,
                et.et_igst_per as igst_per,
                (et.et_igst_amt) as igst_amt,
                IF(et.et_igst_amt > 0, (et.et_igst_per), (et.et_sgst_per + et.et_cgst_per)) as gst_per,
                (et.et_sgst_amt + et.et_cgst_amt + et.et_igst_amt) as gst_amt,
                (et.et_total_amt) as total_amt
                FROM hisab_trans et
                LEFT JOIN apparel_master apparel ON(apparel.apparel_id = et.et_apparel_id)
                LEFT JOIN barcode_master bm ON(bm.bm_id = et.et_bm_id)
                LEFT JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                LEFT JOIN design_master design ON(design.design_id = bm.bm_design_id)
                WHERE et.et_delete_status = 0 
                AND et.et_hm_id = $hm_id
                ORDER BY et.et_trans_type, et.et_rate ASC";
        $record['trans_data'] = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();

        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _karigar_id(){
		$subsql = "";
		$limit  = PER_PAGE;
		$offset = OFFSET;
		$page 	= 1;
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = $_GET['limit'];
		}
		if(isset($_GET['page']) && !empty($_GET['page'])){
			$page 	= $_GET['page'];
			$offset = $limit * ($page - 1);
		}
		if((isset($_GET['name']) && !empty($_GET['name']))){
            $name 	= $_GET['name'];
            $subsql .= " AND (karigar.karigar_name LIKE '".$name."%')";
        }
        
		$query="SELECT karigar.karigar_id as id, 
				UPPER(karigar.karigar_name) as name
				FROM job_receive_trans jrt
                INNER JOIN job_issue_master jim ON(jim.jim_id = jrt.jrt_jim_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
				WHERE jim.jim_delete_status = 0
                AND jrt.jrt_delete_status=0
                AND jim.jim_hm_id = 0
				$subsql
				GROUP BY karigar.karigar_id
				ORDER BY karigar.karigar_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
    public function _entry_no(){
		$subsql = "";
		$limit  = PER_PAGE;
		$offset = OFFSET;
		$page 	= 1;
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = $_GET['limit'];
		}
		if(isset($_GET['page']) && !empty($_GET['page'])){
			$page 	= $_GET['page'];
			$offset = $limit * ($page - 1);
		}
		if(isset($_GET['name']) && !empty($_GET['name'])){
			$name 	= $_GET['name'];
			$subsql .= " AND (hm.hm_entry_no LIKE '%".$name."%') ";
		}
		$query="SELECT hm.hm_entry_no as id, UPPER(hm.hm_entry_no) as name
				FROM hisab_master hm
				WHERE hm.hm_delete_status = 0
				$subsql
				GROUP BY hm.hm_entry_no ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
    public function _karigar_name(){
		$subsql = "";
		$limit  = PER_PAGE;
		$offset = OFFSET;
		$page 	= 1;
		if(isset($_GET['limit']) && !empty($_GET['limit'])){
			$limit = $_GET['limit'];
		}
		if(isset($_GET['page']) && !empty($_GET['page'])){
			$page 	= $_GET['page'];
			$offset = $limit * ($page - 1);
		}
		if(isset($_GET['name']) && !empty($_GET['name'])){
			$name 	= $_GET['name'];
			$subsql .= " AND (karigar.karigar_name LIKE '%".$name."%') ";
		}
		$query="SELECT karigar.karigar_name as id, UPPER(karigar.karigar_name) as name
				FROM hisab_master hm
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = hm.hm_karigar_id)
				WHERE hm.hm_delete_status = 0
				$subsql
				GROUP BY karigar.karigar_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
}
?>