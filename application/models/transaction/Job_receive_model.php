<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class job_receive_model extends my_model{
    public function __construct(){ parent::__construct('transaction', 'job_receive'); }
    public function isExist($id){
          $data = $this->db->query("SELECT ht.ht_jrt_id 
          	FROM hisab_trans ht  
          	INNER JOIN hisab_master hm ON(hm.hm_id=ht.ht_hm_id)
          	INNER JOIN job_receive_trans jrt ON(jrt.jrt_id=ht.ht_jrt_id)
          	WHERE jrt.jrt_jrm_id = $id AND jrt.jrt_delete_status = 0 AND ht.ht_delete_status = 0 LIMIT 1")->result_array();
          
        if(!empty($data)) return true;
        return false;
    }
	public function isTransExist($id){ 
        $data = $this->db->query("SELECT ht_jrt_id FROM hisab_trans WHERE ht_jrt_id = $id AND ht_delete_status = 0 LIMIT 1")->result_array();
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
            $subsql .=" AND jrm.jrm_entry_no = '".$_GET['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
            $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
        }
		if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND jrm.jrm_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND jrm.jrm_entry_date <= '".$_entry_date_to."'";
        } 
         if(isset($_GET['_order_no']) && !empty($_GET['_order_no'])){ 
            $subsql .=" AND om.om_id = '".$_GET['_order_no']."'";
            $record['filter']['_order_no']['value'] = $_GET['_order_no'];
            $record['filter']['_order_no']['text'] = $_GET['_order_no'];
        }
		if(isset($_GET['_proces_name']) && !empty($_GET['_proces_name'])){
            $subsql .=" AND proces.proces_name = '".$_GET['_proces_name']."'";
            $record['filter']['_proces_name']['value'] = $_GET['_proces_name'];
            $record['filter']['_proces_name']['text'] = $_GET['_proces_name'];
        }
        if(isset($_GET['_customer_name']) && !empty($_GET['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_GET['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_GET['_customer_name'];
            $record['filter']['_customer_name']['text'] = $_GET['_customer_name'];
        }
        if(isset($_GET['_item_code']) && !empty($_GET['_item_code'])){
            $subsql .=" AND obt.obt_item_code = '".$_GET['_item_code']."'";
            $record['filter']['_item_code']['value'] = $_GET['_item_code'];
            $record['filter']['_item_code']['text'] = $_GET['_item_code'];
        }
        if(isset($_GET['_karigar_name']) && !empty($_GET['_karigar_name'])){
            $subsql .=" AND karigar.karigar_name = '".$_GET['_karigar_name']."'";
            $record['filter']['_karigar_name']['value'] = $_GET['_karigar_name'];
            $record['filter']['_karigar_name']['text'] = $_GET['_karigar_name'];
        }

        if(isset($_GET['_order_date_from']) && !empty($_GET['_order_date_from'])){
            $_order_date_from = date('Y-m-d', strtotime($_GET['_order_date_from']));
            $subsql .= " AND om.om_em_entry_date >= '".$_order_date_from."'";
        }
        if(isset($_GET['_order_date_to']) && !empty($_GET['_order_date_to'])){
            $_order_date_to = date('Y-m-d', strtotime($_GET['_order_date_to']));
            $subsql .= " AND om.om_em_entry_date <= '".$_order_date_to."'";
        }  

		$query="SELECT jrm.*, 
                jrt.jrt_id,
				UPPER(karigar.karigar_name) as karigar_name,
                UPPER(apparel.apparel_name) as apparel_name,
				obt.obt_item_code,
                om.om_em_entry_no as order_no,
                DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as order_date,
                UPPER(sku.sku_name) as sku_name,
                UPPER(proces.proces_name) as proces_name 
                FROM job_receive_master jrm
                INNER JOIN job_receive_trans jrt ON(jrt.jrt_jrm_id=jrm.jrm_id)
                INNER JOIN job_issue_master jim ON(jim.jim_id = jrt.jrt_jim_id)
                INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                INNER JOIN job_issue_trans jit ON(jit.jit_id = jrt.jrt_jit_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jrt.jrt_obt_id)
                INNER JOIN order_trans ot ON(ot.ot_id = obt.obt_ot_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN sku_master sku ON(sku.sku_id = ot.ot_sku_id)
                WHERE jrm.jrm_delete_status = 0 AND jrt.jrt_delete_status = 0
				$subsql
                GROUP BY  jrt.jrt_id
                ORDER BY jrm.jrm_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['jrm_id']);
            }
        }
        return $record;
    }
    public function get_data_for_add(){}
	public function get_data_for_edit($id){
        $query="SELECT jrm.*
                FROM job_receive_master jrm
                WHERE jrm.jrm_delete_status = 0
				AND jrm.jrm_id = $id";
        $record['master_data'] = $this->db->query($query)->result_array();
        if(!empty($record['master_data'])){
            $record['master_data'][0]['isExist'] = $this->isExist($id);
        }
        return $record;
    }
	public function get_transaction($jrm_id){ 
        $query="SELECT jrt.jrt_id,
				jrt.jrt_jim_id as jim_id,
				jrt.jrt_jit_id as jit_id,
                jrt.jrt_rfd,
				obt.*,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(sku.sku_name) as sku_name,
                UPPER(customer.customer_name) as customer_name,
				UPPER(proces.proces_name) as proces_name,
                UPPER(karigar.karigar_name) as karigar_name,
                om.om_em_entry_no as entry_no,
                DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as entry_date
                FROM job_receive_trans jrt
				INNER JOIN order_barcode_trans obt ON(obt.obt_id = jrt.jrt_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                INNER JOIN order_trans ot ON(ot.ot_id = obt.obt_ot_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = ot.ot_apparel_id)
                LEFT JOIN sku_master sku ON(sku.sku_id = ot.ot_sku_id)
                INNER JOIN job_issue_master jim ON(jim.jim_id = jrt.jrt_jim_id)
				INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                WHERE jrt.jrt_delete_status = 0
				AND jrt.jrt_jrm_id = $jrm_id
                GROUP BY jrt.jrt_id";
        $record = $this->db->query($query)->result_array();
        if(!empty($record)){
            foreach ($record as $key => $value) {
                $record[$key]['isExist'] = $this->isTransExist($value['jrt_id']);
            }
        }
        // echo "<pre>"; print_r($record);die;
        return $record;
    }
    public function get_barcode_data($id){
        $query="SELECT 0 as jrt_id,
				obt.*,
                jim.*,
				jit.jit_id,
                0 as jrt_rfd,
				IFNULL(jrt.jrt_jit_id, 0) as jrt_jit_id,
                UPPER(apparel.apparel_name) as apparel_name,
                UPPER(sku.sku_name) as sku_name,
                UPPER(customer.customer_name) as customer_name,
                om.om_em_entry_no as entry_no,
                DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(proces.proces_name) as proces_name,
                UPPER(karigar.karigar_name) as karigar_name
                FROM job_issue_master jim
				INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                LEFT JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                LEFT JOIN order_master om ON(om.om_id = obt.obt_om_id)
                LEFT JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                LEFT JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                INNER JOIN order_trans ot ON(ot.ot_id = obt.obt_ot_id)
                LEFT JOIN sku_master sku ON(sku.sku_id = ot.ot_sku_id)
                LEFT JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                LEFT JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
				LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                WHERE jim.jim_delete_status = 0
				AND jit.jit_delete_status = 0
				AND IFNULL(jrt.jrt_jit_id, 0)=0
				AND obt.obt_id = $id
				ORDER BY jit.jit_id DESC
				LIMIT 1";
        return $this->db->query($query)->result_array();
    }

    public function update_previous_hisab($jim_id,$obt_id){
        $jim_data=$this->db_operations->get_record('job_issue_master',['jim_id'=>$jim_id]);
        if(empty($jim_data)) return 0;
        $proces_id = $jim_data[0]['jim_proces_id'];
        $query = "UPDATE job_receive_trans jrt
              INNER JOIN job_issue_master jim
                    ON jim.jim_id = jrt.jrt_jim_id
              SET jrt.jrt_not_hisab = 1
              WHERE jrt.jrt_delete_status = 0
                AND jim.jim_delete_status = 0
                AND jim.jim_ht_id=0
                AND jim.jim_proces_id = ?
                AND jrt.jrt_obt_id = ?
                AND jrt.jrt_not_hisab = 0";
    	return $this->db->query($query, [$proces_id, $obt_id]);
    }

    public function delete_previous_hisab($jim_id,$obt_id,$jrt_id){
            $jim_data=$this->db_operations->get_record('job_issue_master',['jim_id'=>$jim_id]);
            if(empty($jim_data)) return 0;
            $proces_id = $jim_data[0]['jim_proces_id'];
            $query = "UPDATE job_receive_trans jrt
                  INNER JOIN job_issue_master jim
                        ON jim.jim_id = jrt.jrt_jim_id
                  SET jrt.jrt_not_hisab = 0
                  WHERE jrt.jrt_delete_status = 0
                    AND jim.jim_delete_status = 0
                    AND jim.jim_ht_id=0
                    AND jim.jim_proces_id = ?
                    AND jrt.jrt_obt_id = ?
                    AND jrt.jrt_id != ?
                    AND jrt.jrt_not_hisab=1";
            return $this->db->query($query, [$proces_id, $obt_id,$jrt_id]);

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
				UPPER(obt.obt_item_code) as name,
				IFNULL(jrt.jrt_jit_id, 0) as jrt_jit_id
				FROM job_issue_trans jit
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
				LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
				WHERE jit.jit_delete_status = 0
				$subsql
				AND IFNULL(jrt.jrt_jit_id, 0)=0
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
			$subsql .= " AND (jrm.jrm_entry_no LIKE '%".$name."%') ";
		}
		$query="SELECT jrm.jrm_entry_no as id, UPPER(jrm.jrm_entry_no) as name
				FROM job_receive_master jrm
				WHERE jrm.jrm_delete_status = 0
				$subsql
				GROUP BY jrm.jrm_entry_no ASC
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
            $subsql .= " AND (om.om_em_entry_no LIKE '".$name."%') ";
            
		}
		$query="SELECT om.om_id as id, 
                om.om_em_entry_no as name
				FROM job_issue_trans jit
				INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
				WHERE jit.jit_delete_status = 0 AND jrt.jrt_delete_status = 0
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
			$subsql .= " AND (proces.proces_name LIKE '".$name."%') ";
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
			$subsql .= " AND (obt.obt_item_code LIKE '".$name."%') ";
		}
		$query="SELECT obt.obt_item_code as id, UPPER(obt.obt_item_code) as name
				FROM job_issue_trans jit
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                INNER JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
				WHERE jit.jit_delete_status = 0
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
				FROM job_receive_master jrm
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jrm.jrm_obt_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
				WHERE jrm.jrm_delete_status = 0
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
			$subsql .= " AND (karigar.karigar_name LIKE '".$name."%') ";
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
				FROM job_receive_master jrm
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jrm.jrm_obt_id)
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
				WHERE jrm.jrm_delete_status = 0
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