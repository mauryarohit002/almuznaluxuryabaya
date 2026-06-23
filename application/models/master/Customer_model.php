<?php defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class customer_model extends my_model{
	public function __construct(){ parent::__construct('master', 'customer'); }
	public function isExist($id){
		// $data = $this->db->query("SELECT pm_id FROM purchase_master WHERE pm_customer_id = $id LIMIT 1")->result_array();
		// if(!empty($data)) return true;
		

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
		
		if(isset($_GET['_name']) && !empty($_GET['_name'])){
			$subsql .=" AND customer_name = '".$_GET['_name']."'";
			$record['filter']['_name']['value'] = $_GET['_name'];
			$record['filter']['_name']['text'] = $_GET['_name'];
		}
		if(isset($_GET['_code']) && !empty($_GET['_code'])){
			$subsql .=" AND customer_code = '".$_GET['_code']."'";
			$record['filter']['_code']['value'] = $_GET['_code'];
			$record['filter']['_code']['text'] = $_GET['_code'];
		}
		if(isset($_GET['_mobile']) && !empty($_GET['_mobile'])){
			$subsql .=" AND customer_mobile = '".$_GET['_mobile']."'";
			$record['filter']['_mobile']['value'] = $_GET['_mobile'];
			$record['filter']['_mobile']['text'] = $_GET['_mobile'];
		}
		if(isset($_GET['_status'])){
			$status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
			$subsql .=" AND customer_status = ".$status;
			$record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
		}
		
		if($_SESSION['user_id'] != 1){
			$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
		}
		
		$query="SELECT customer.*,
				created.user_fullname as created_by,
				updated.user_fullname as updated_by
				FROM customer_master customer
				INNER JOIN user_master created ON(created.user_id = customer.customer_created_by)
				INNER JOIN user_master updated ON(updated.user_id = customer.customer_updated_by)
				LEFT JOIN branch_master branch ON(branch.branch_id = customer.customer_branch_id)
				WHERE 1
				$subsql
				GROUP BY customer.customer_id
				ORDER BY customer.customer_id DESC
				$limit
				$ofset";
		// echo "<pre>"; print_r($query); exit;
		if($wantCount){
			return $this->db->query($query)->num_rows();
		}
		$record['data'] = $this->db->query($query)->result_array();
		if(!empty($record['data'])){
			foreach ($record['data'] as $key => $value) {
				$record['data'][$key]['isExist'] = $this->isExist($value['customer_id']);
			}
		}
		return $record;
	}
	public function get_data_for_add(){
		$record['customer_no'] 		= $this->get_max_no();
		$record['uuid'] 			= time().''.$_SESSION['user_id'];
		$record['apparel_data'] 	= $this->get_apparel_for_add();
        return $record;
    }
	public function get_data_for_edit($id){
		$query="SELECT customer.*,
				IFNULL(UPPER(city.city_name), 'SELECT') as city_name,
				IFNULL(UPPER(state.state_name), 'SELECT') as state_name,
				IFNULL(UPPER(country.country_name), 'SELECT') as country_name,
				IFNULL(UPPER(branch.branch_name), 'SELECT') as branch_name
				FROM customer_master customer
				LEFT JOIN city_master city ON(city.city_id = customer.customer_city_id)
				LEFT JOIN state_master state ON(state.state_id = customer.customer_state_id)
				LEFT JOIN country_master country ON(country.country_id = customer.customer_country_id)
				LEFT JOIN branch_master branch ON(branch.branch_id = customer.customer_branch_id)
				WHERE customer.customer_id = $id";
		$record['master_data'] = $this->db->query($query)->result_array();
		if(!empty($record['master_data'])){
			$record['master_data'][0]['refer_name'] = $this->get_refer_name($record['master_data'][0]['customer_refer_type'], $record['master_data'][0]['customer_refer_id']);
		}
		$query="SELECT cat.*
				FROM customer_attachment_trans cat
				WHERE cat.cat_customer_id = $id";
		$record['attachment_data'] = $this->db->query($query)->result_array();
		$record['apparel_data'] = $this->get_apparel_for_edit($id);
		// echo "<pre>"; print_r($record); exit;
		return $record;
	}
	public function get_refer_name($term, $id){
		if($term == 'OTHER') return '';
		$term = strtolower($term);
		$query="SELECT UPPER(".$term."_name) as refer_name FROM ".$term."_master WHERE ".$term."_id = $id";
		$data = $this->db->query($query)->result_array();
		return empty($data) ? '' : $data[0]['refer_name'];
	}
	public function get_max_no(){
		$query="SELECT customer_no as max_no
				FROM customer_master
				WHERE 1
				ORDER BY customer_no DESC
				LIMIT 1";
		$data = $this->db->query($query)->result_array();
		return empty($data) ? 1 : $data[0]['max_no']+1;
	}
	public function get_data($id){
		$query="SELECT customer.*,
				IFNULL(CONCAT(UPPER(city.city_name), ' - ', UPPER(state.state_name), ' - ', UPPER(country.country_name), ' - ', UPPER(cpt.cpt_pincode)), '') as cpt_name,
				IFNULL(UPPER(branch.branch_name), 'SELECT') as branch_name
				FROM customer_master customer
				LEFT JOIN city_pincode_trans cpt ON(cpt.cpt_id = customer.customer_cpt_id)
				LEFT JOIN city_master city ON(city.city_id = cpt.cpt_city_id)
				LEFT JOIN state_master state ON(state.state_id = city.city_state_id)
				LEFT JOIN country_master country ON(country.country_id = city.city_country_id)
				LEFT JOIN branch_master branch ON(branch.branch_id = customer.customer_branch_id)
				WHERE customer.customer_id = $id";
		return $this->db->query($query)->result_array();
	}
	public function get_apparel_for_add(){
		$query="SELECT apparel.apparel_id,
				UPPER(apparel.apparel_name) as apparel_name,
				IFNULL(COUNT(measurement_setting.measurement_setting_id), 0) as measurement_cnt
				FROM apparel_master apparel
				LEFT JOIN measurement_setting_master measurement_setting ON(measurement_setting.measurement_setting_apparel_id = apparel.apparel_id)
				WHERE apparel.apparel_status = 1
				AND measurement_setting.measurement_setting_deleted_by IS NULL
				GROUP BY apparel.apparel_id
				HAVING (measurement_cnt > 0)
				ORDER BY apparel.apparel_name ASC";
		return $this->db->query($query)->result_array();
	}
	public function get_apparel_for_edit($id){
		$query="SELECT apparel.apparel_id,
				UPPER(apparel.apparel_name) as apparel_name
				FROM customer_measurement_trans cmt
				INNER JOIN apparel_master apparel ON(apparel.apparel_id = cmt.cmt_apparel_id)
				WHERE apparel.apparel_status = 1
				AND cmt.cmt_delete_status = 0
				AND cmt.cmt_bill_no != 0
				AND cmt.cmt_customer_id = $id
				GROUP BY apparel.apparel_id
				ORDER BY apparel.apparel_name ASC";
		return $this->db->query($query)->result_array();
	}
	public function get_measurement($id, $apparel_id){
		// echo "<pre>";print_r($apparel_id);exit;
		$data 	 = $this->get_latest_measurement($id, $apparel_id);
		$subsql  = empty($data['bill_no']) ? '' : " AND cmt.cmt_bill_no = '".$data['bill_no']."' AND cmt.cmt_bill_date = '".$data['bill_date']."'";
		$subsql .= empty($apparel_id) ? '' : " AND cmt.cmt_apparel_id = $apparel_id";
		$query="SELECT cmt.cmt_id,
				cmt.cmt_value1 as value1,
				cmt.cmt_value2 as value2,
				cmt.cmt_bill_no as bill_no, 
				DATE_FORMAT(cmt.cmt_bill_date, '%d-%m-%Y') as bill_date,
				IFNULL(UPPER(cmt.cmt_remark), '') as remark,
				measurement.measurement_id,
				UPPER(measurement.measurement_name) as measurement_name
				FROM customer_measurement_trans cmt
				INNER JOIN measurement_master measurement ON(measurement.measurement_id = cmt.cmt_measurement_id)
				WHERE cmt.cmt_delete_status = 0
				AND cmt.cmt_customer_id = $id
				$subsql
				AND ( cmt.cmt_ot_id = (SELECT cmt.cmt_ot_id FROM customer_measurement_trans cmt WHERE cmt.cmt_delete_status = 0
				AND cmt.cmt_customer_id = $id
				$subsql ORDER BY cmt.cmt_ot_id DESC LIMIT 1))

				ORDER BY measurement.measurement_id ASC";
		// echo "<pre>"; print_r($query); exit;
		return $this->db->query($query)->result_array();
	}
	public function get_latest_measurement($customer_id, $apparel_id){
		$subsql = empty($apparel_id) ? '' : " AND cmt_apparel_id = $apparel_id";
		$query="SELECT cmt_bill_no as bill_no, 
				cmt_bill_date as bill_date,
				DATE_FORMAT(cmt_bill_date, '%d-%m-%Y') as _bill_date
				
				FROM customer_measurement_trans 
				WHERE cmt_delete_status = 0
				AND cmt_bill_no != 0
				AND cmt_customer_id = $customer_id
				$subsql
				ORDER BY cmt_id DESC 
				LIMIT 1";
		$data = $this->db->query($query)->result_array();
		// echo "<pre>"; print_r($query); exit;
		if(!empty($data)) return ['bill_no' => $data[0]['bill_no'], 'bill_date' => $data[0]['bill_date'], '_bill_date' => $data[0]['_bill_date']];
		return ['bill_no' => '', 'bill_date' => '', '_bill_date' => ''];

		// if(!empty($data)) return ['om_id' => $data[0]['om_id'], 'ot_id' => $data[0]['ot_id'], 'bill_no' => $data[0]['bill_no'], 'bill_date' => $data[0]['bill_date']];
        // return ['om_id' => '', 'ot_id' => '', 'bill_no' => '', 'bill_date' => ''];
	}
    public function get_order_measurement($ot_id){
        $query="SELECT UPPER(measurement.measurement_name) as measurement_name,
                cmt.cmt_value1 as value1,
                cmt.cmt_value2 as value2
                FROM customer_measurement_trans cmt
                INNER JOIN measurement_master measurement ON(measurement.measurement_id = cmt.cmt_measurement_id)
                WHERE cmt.cmt_ot_id = $ot_id
                AND cmt.cmt_delete_status = 0
                ORDER BY measurement.measurement_id ASC";
        return $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($record); exit();
	}
	public function measurement_print($customer_id, $apparel_id){
		$subsql = empty($apparel_id) ? '' : " AND cmt.cmt_apparel_id = $apparel_id";
		$query="SELECT apparel.apparel_id,
				UPPER(apparel.apparel_name) as apparel_name,
				'' as entry_no,
				'' as entry_date
				FROM customer_measurement_trans cmt
				INNER JOIN apparel_master apparel ON(apparel.apparel_id = cmt.cmt_apparel_id)
				WHERE cmt.cmt_delete_status = 0
				AND cmt.cmt_customer_id = $customer_id
				$subsql
				GROUP BY apparel.apparel_id
				ORDER BY apparel.apparel_name ASC";
		$record['trans_data'] = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($record); exit();

        if(!empty($record['trans_data'])){
            foreach ($record['trans_data'] as $key => $value) {
                $record['trans_data'][$key]['measurement_data'] = $this->get_measurement($customer_id, $value['apparel_id']);

				$record['trans_data'][$key]['entry_no']   		= empty($record['trans_data'][$key]['measurement_data']) ? '' : $record['trans_data'][$key]['measurement_data'][0]['bill_no'];
				$record['trans_data'][$key]['entry_date'] 		= empty($record['trans_data'][$key]['measurement_data']) ? '' : $record['trans_data'][$key]['measurement_data'][0]['bill_date'];
            }
        }
        // echo "<pre>"; print_r($record); exit();
        return $record;

    }

    public function get_body_measurement_setting($customer_id,$apparel_id){
		$bill_no 	= 'NEW';
		$remark 	= 'NEW';
		$bill_date 	= date('d-m-Y');
		$subsql 	= '';
		$data 		= [];
		$data 	= $this->get_body_measurement($customer_id,$apparel_id);
		// echo "<pre>"; print_r($data); exit();
		if(!empty($data)){
			$ids 		= '';
			$bill_no 	= $data[0]['bill_no'];
			$bill_date 	= $data[0]['bill_date'];
			$remark 	= $data[0]['remark'];
			foreach ($data as $key => $value) {
				$ids .= empty($ids) ? $value['measurement_id'] : ', '.$value['measurement_id'];
			}
			$subsql = " AND measurement.measurement_id NOT IN ($ids)";
		}

		$query="SELECT 0 as cmt_id,
                measurement.measurement_id as measurement_id,measurement_setting.measurement_setting_priority as measurement_priority,
                apparel.apparel_id as apparel_id,
                UPPER(measurement.measurement_name) as measurement_name,
                UPPER(apparel.apparel_name) as apparel_name,
                '' as value1,
                '' as value2,
                '".$bill_no."' as bill_no,
				'".$bill_date."' as bill_date,
                '".$remark."' as remark
                FROM measurement_setting_master measurement_setting
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = measurement_setting.measurement_setting_apparel_id)
                INNER JOIN measurement_master measurement ON(measurement.measurement_id = measurement_setting.measurement_setting_measurement_id)
                WHERE measurement_setting.measurement_setting_apparel_id = $apparel_id
				AND measurement_setting.measurement_setting_status = 1
				$subsql
                ORDER BY measurement_setting.measurement_setting_apparel_id,measurement_setting.measurement_setting_priority ASC";
                
                // echo $query;exit;
        $temp = $this->db->query($query)->result_array();
		if(!empty($temp)){
			foreach ($temp as $key => $value) {
				array_push($data, $value);
			}
		}
		if(!empty($data)){
			usort($data, function($a, $b){
				return $a['measurement_priority'] - $b['measurement_priority'];
			});
		}
		return $data;
	}

	public function get_body_measurement($id, $apparel_id){ 
		$data 	 = $this->get_latest_body_measurement($id,$apparel_id);
		$subsql  = empty($data['bill_no']) ? '' : " AND cmt.cmt_bill_no = '".$data['bill_no']."' AND cmt.cmt_bill_date = '".$data['bill_date']."'";
		$subsql .= empty($apparel_id) ? '' : " AND cmt.cmt_apparel_id = $apparel_id"; 
		$query="SELECT cmt.cmt_id,
				cmt.cmt_value1 as value1,
				cmt.cmt_value2 as value2,
				cmt.cmt_bill_no as bill_no, 
				DATE_FORMAT(cmt.cmt_bill_date, '%d-%m-%Y') as bill_date,
				IFNULL(UPPER(cmt.cmt_remark), '') as remark,
				measurement.measurement_id,
				(SELECT measurement_setting.measurement_setting_priority FROM measurement_setting_master measurement_setting WHERE apparel.apparel_id = measurement_setting.measurement_setting_apparel_id AND measurement.measurement_id = measurement_setting.measurement_setting_measurement_id LIMIT 1) as measurement_priority,
				apparel.apparel_id,
				UPPER(measurement.measurement_name) as measurement_name,
				UPPER(apparel.apparel_name) as apparel_name
				FROM customer_measurement_trans cmt
				INNER JOIN measurement_master measurement ON(measurement.measurement_id = cmt.cmt_measurement_id)
				INNER JOIN apparel_master apparel ON(apparel.apparel_id = cmt.cmt_apparel_id)
				WHERE 1
				AND (cmt.cmt_customer_id = $id )
				$subsql
				GROUP BY cmt.cmt_customer_id, cmt.cmt_bill_no, cmt.cmt_bill_date, cmt.cmt_apparel_id, cmt.cmt_measurement_id,cmt.cmt_remark
				ORDER BY cmt.cmt_id DESC ";
// 		echo "<pre>"; print_r($query); exit;
		return $this->db->query($query)->result_array();
	}

	public function get_latest_body_measurement($customer_id, $apparel_id){
		$subsql = empty($apparel_id) ? '' : " AND cmt.cmt_apparel_id = $apparel_id";
		$customer_id = empty($customer_id) ? '-1' : $customer_id;
		$query="SELECT cmt.cmt_bill_no as bill_no, 
				cmt.cmt_bill_date as bill_date,
				DATE_FORMAT(cmt.cmt_bill_date, '%d-%m-%Y') as _bill_date,
				IFNULL(om.om_entry_no, '') as om_entry_no,
				IFNULL(em.em_entry_no, '') as em_entry_no,
				IFNULL(UPPER(cmt.cmt_remark), '') as remark 
				FROM customer_measurement_trans cmt
				LEFT JOIN order_master om ON(om.om_id = cmt.cmt_om_id)
				LEFT JOIN estimate_master em ON(em.em_id = cmt.cmt_em_id)
				WHERE 1
				AND cmt.cmt_bill_no != '0'
				AND (cmt.cmt_customer_id = $customer_id)
				$subsql
				ORDER BY cmt.cmt_id DESC 
				LIMIT 1";
		$data = $this->db->query($query)->result_array();
		// echo "<pre>"; print_r($query); exit;
		// echo "<pre>"; print_r($data); exit;
		$memo_no 	= 'XXX';
		$bill_no 	= 'XXX';
		$bill_date 	= 'XXX'; 
		$_bill_date = ''; 

		if(!empty($data)) {
			$memo_no 	= empty($data[0]['om_entry_no']) ? $data[0]['em_entry_no'] : $data[0]['om_entry_no'];
			$bill_no 	= $data[0]['bill_no'];
			$bill_date 	= $data[0]['bill_date']; 
			$_bill_date = $data[0]['_bill_date']; 
		}
		return ['memo_no' => $memo_no, 'bill_no' => $bill_no, 'bill_date' => $bill_date, '_bill_date' => $_bill_date];
		
	}

	public function _id(){
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
			$subsql .= " AND (customer.customer_name LIKE '%".$name."%' OR customer_mobile LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (customer.customer_status = $param) ";
		}
		if($_SESSION['user_id'] != 1){
			$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
		}
		$query="SELECT customer.customer_id as id, 
				CONCAT(UPPER(customer_name), ' - ', UPPER(customer_mobile)) as name
				FROM customer_master customer
				LEFT JOIN branch_master branch ON(branch.branch_id = customer.customer_branch_id)
				WHERE 1
				$subsql
				GROUP BY customer.customer_id
				ORDER BY customer.customer_name ASC
				LIMIT $limit
				OFFSET $offset";

		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _name(){
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
			$subsql .= " AND (customer.customer_name LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (customer.customer_status = $param) ";
		}
		if($_SESSION['user_id'] != 1){
			$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
		}
		$query="SELECT customer.customer_name as id, UPPER(customer.customer_name) as name
				FROM customer_master customer
				LEFT JOIN branch_master branch ON(branch.branch_id = customer.customer_branch_id)
				WHERE 1
				$subsql
				GROUP BY customer.customer_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _mobile(){
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
			$subsql .= " AND (customer.customer_mobile LIKE '%".$name."%') ";
		}
		if(isset($_GET['param']) && !empty($_GET['param'])){
			$param 	= $_GET['param'];
			$subsql .= " AND (customer.customer_status = $param) ";
		}
		if($_SESSION['user_id'] != 1){
			$subsql .=" AND branch.branch_id = '".$_SESSION['user_branch_id']."'";
		}
		$query="SELECT customer.customer_mobile as id, UPPER(customer.customer_mobile) as name
				FROM customer_master customer
				LEFT JOIN branch_master branch ON(branch.branch_id = customer.customer_branch_id)
				WHERE 1
				$subsql
				GROUP BY customer.customer_mobile ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
}
?>