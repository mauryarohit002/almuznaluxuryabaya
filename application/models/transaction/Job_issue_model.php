<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class job_issue_model extends my_model{
    public function __construct(){ parent::__construct('transaction', 'job_issue'); }
    public function isExist($id){
        $data = $this->db->query("SELECT jrt_id FROM job_receive_trans WHERE jrt_jim_id = $id AND jrt_delete_status = 0 LIMIT 1")->result_array();
        if(!empty($data)) return true;

        return false;
    }
	public function isTransExist($id){ 
        $data = $this->db->query("SELECT jrt_id FROM job_receive_trans WHERE jrt_jit_id = $id AND jrt_delete_status = 0 LIMIT 1")->result_array();
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
            $subsql .=" AND jim.jim_entry_no = '".$_GET['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
            $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
        }
		if(isset($_GET['_proces_name']) && !empty($_GET['_proces_name'])){
            $subsql .=" AND proces.proces_name = '".$_GET['_proces_name']."'";
            $record['filter']['_proces_name']['value'] = $_GET['_proces_name'];
            $record['filter']['_proces_name']['text'] = $_GET['_proces_name'];
        }
        if(isset($_GET['_karigar_name']) && !empty($_GET['_karigar_name'])){
            $subsql .=" AND karigar.karigar_name = '".$_GET['_karigar_name']."'";
            $record['filter']['_karigar_name']['value'] = $_GET['_karigar_name'];
            $record['filter']['_karigar_name']['text'] = $_GET['_karigar_name'];
        }
		if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND jim.jim_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND jim.jim_entry_date <= '".$_entry_date_to."'";
        }
		$query="SELECT jim.*,
                DATE_FORMAT(jim.jim_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(proces.proces_name) as proces_name,
                UPPER(karigar.karigar_name) as karigar_name
                FROM job_issue_master jim
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                WHERE jim.jim_delete_status = 0
                $subsql
                ORDER BY jim.jim_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['jim_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){}
	public function get_data_for_edit($id){
        $query="SELECT jim.*,
                UPPER(proces.proces_name) as proces_name,
                UPPER(karigar.karigar_name) as karigar_name
                FROM job_issue_master jim
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                WHERE jim.jim_delete_status = 0
				AND jim.jim_id = $id";
        $record['master_data'] = $this->db->query($query)->result_array();
        if(!empty($record['master_data'])){
            $record['master_data'][0]['isExist'] = $this->isExist($id);
        }
        return $record;
    }
	public function get_transaction($jim_id){
        $query="SELECT jit.jit_id,
				obt.*,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(customer.customer_name) as customer_name,
                om.om_em_entry_no as entry_no,
                DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as entry_date
                FROM job_issue_trans jit
				INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                INNER JOIN order_trans ot ON(ot.ot_id = obt.obt_ot_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                WHERE jit.jit_delete_status = 0
				AND jit.jit_jim_id = $jim_id";
        $record = $this->db->query($query)->result_array();
        if(!empty($record)){
            foreach ($record as $key => $value) {
                $record[$key]['isExist'] = $this->isTransExist($value['jit_id']);
            }
        }
        return $record;
    }
    public function get_barcode_data($id){
        $query="SELECT 0 as jit_id,
				obt.*,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(customer.customer_name) as customer_name,
                om.om_em_entry_no as entry_no,
                DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as entry_date
                FROM order_barcode_trans obt
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                INNER JOIN order_trans ot ON(ot.ot_id = obt.obt_ot_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                WHERE obt.obt_id = $id";
        return $this->db->query($query)->result_array();
    }
    public function get_latest_data($id){
        $query="SELECT jim.*,
				IFNULL(jrt.jrt_id, 0) as jrt_id,
				UPPER(proces.proces_name) as proces_name
                FROM job_issue_master jim
				INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
				LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                WHERE jim.jim_delete_status = 0
				AND jit.jit_delete_status = 0
				AND jit.jit_obt_id = $id
                ORDER BY jit.jit_id DESC
                LIMIT 1";
        return $this->db->query($query)->result_array();
    }
    public function get_entry_no(){
        $query="SELECT jit_entry_no as max_no
                FROM job_issue_trans
                WHERE jit_delete_status = 0
                AND jit_fin_year = '".$_SESSION['fin_year']."'
                ORDER BY jit_entry_no DESC
                LIMIT 1";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? 1 : $data[0]['max_no']+1;
    }
    public function _proces_id(){
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
            $subsql .= " AND (proces.proces_name LIKE '".$name."%')";
        }
        if((isset($_GET['param']) && !empty($_GET['param']))){
            $param 	= $_GET['param'];
            $subsql .= " AND (proces.proces_id = '".$param."')";
        }
		$query="SELECT proces.proces_id as id, 
				UPPER(proces.proces_name) as name
				FROM proces_master proces
				WHERE 1
				$subsql
				GROUP BY proces.proces_id
				ORDER BY proces.proces_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
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
        if((isset($_GET['param']) && !empty($_GET['param']))){
            $param 	= $_GET['param'];
            $subsql .= " AND (proces.proces_id = '".$param."')";
        }else{
            return [0 => ['id' => 0, 'name' => 'SELECT PROCESS FIRST']];
        }
		$query="SELECT karigar.karigar_id as id, 
				UPPER(karigar.karigar_name) as name
				FROM karigar_master karigar
                INNER JOIN karigar_proces_trans kpt ON(kpt.kpt_karigar_id = karigar.karigar_id)
                INNER JOIN proces_master proces ON(proces.proces_id = kpt.kpt_proces_id)
				WHERE 1
				$subsql
				GROUP BY karigar.karigar_id
				ORDER BY karigar.karigar_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
    public function _obt_id(){
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
            $subsql .= " AND (obt.obt_item_code LIKE '".$name."%')";
        }else{
            if(ENV == PROD){
                $subsql .= " AND (obt.obt_item_code = 'XXX')";
            }
        }
		$query="SELECT obt.obt_id as id, 
				UPPER(obt.obt_item_code) as name
				FROM order_barcode_trans obt
				WHERE 1
				$subsql
				GROUP BY obt.obt_id
				ORDER BY obt.obt_item_code ASC
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
			$subsql .= " AND (jim.jim_entry_no LIKE '%".$name."%') ";
		}
		$query="SELECT jim.jim_entry_no as id, UPPER(jim.jim_entry_no) as name
				FROM job_issue_master jim
				WHERE jim.jim_delete_status = 0
				$subsql
				GROUP BY jim.jim_entry_no ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
    public function _order_no(){
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
			$subsql .= " AND (om.om_em_entry_no LIKE '%".$name."%') ";
		}
		$query="SELECT om.om_em_entry_no as id, UPPER(om.om_em_entry_no) as name
				FROM job_issue_master jim
				INNER JOIN order_barcode_trans obt ON(obt.obt_id = jim.jim_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
				WHERE jim.jim_delete_status = 0
				AND om.om_delete_status = 0
				$subsql
				GROUP BY om.om_em_entry_no ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
    public function _proces_name(){
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
			$subsql .= " AND (proces.proces_name LIKE '%".$name."%') ";
		}
		$query="SELECT proces.proces_name as id, UPPER(proces.proces_name) as name
				FROM job_issue_master jim
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
				WHERE jim.jim_delete_status = 0
				$subsql
				GROUP BY proces.proces_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _item_code(){
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
			$subsql .= " AND (obt.obt_item_code LIKE '%".$name."%') ";
		}
		$query="SELECT obt.obt_item_code as id, UPPER(obt.obt_item_code) as name
				FROM job_issue_master jim
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jim.jim_obt_id)
				WHERE jim.jim_delete_status = 0
				AND obt.obt_delete_status = 0
				$subsql
				GROUP BY obt.obt_item_code ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _apparel_name(){
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
			$subsql .= " AND (apparel.apparel_name LIKE '%".$name."%') ";
		}
		$query="SELECT apparel.apparel_name as id, UPPER(apparel.apparel_name) as name
				FROM job_issue_master jim
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jim.jim_obt_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
				WHERE jim.jim_delete_status = 0
                AND obt.obt_delete_status = 0
				$subsql
				GROUP BY apparel.apparel_name ASC
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
				FROM job_issue_master jim
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
				WHERE jim.jim_delete_status = 0
				$subsql
				GROUP BY karigar.karigar_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
	public function _customer_name(){
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
		$query="SELECT customer.customer_name as id, UPPER(customer.customer_name) as name
				FROM job_issue_master jim
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jim.jim_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
				WHERE jim.jim_delete_status = 0
				AND om.om_delete_status = 0
                AND obt.obt_delete_status = 0
				$subsql
				GROUP BY customer.customer_name ASC
				LIMIT $limit
				OFFSET $offset";
		// echo $query; exit();
		return $this->db->query($query)->result_array();
	}
}
?>