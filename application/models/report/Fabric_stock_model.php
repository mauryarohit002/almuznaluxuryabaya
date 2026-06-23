<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class fabric_stock_model extends my_model{
    public function __construct(){ parent::__construct('report', 'fabric_stock'); }
    public function get_record(){
        $record     = [];
        $subsql 	= ''; 
        $having 	= '';
        if(isset($_REQUEST['_fabric_name']) && !empty($_REQUEST['_fabric_name'])){
            $subsql .=" AND fabric.fabric_name = '".$_REQUEST['_fabric_name']."'";
            $record['filter']['_fabric_name']['value'] = $_REQUEST['_fabric_name'];
            $record['filter']['_fabric_name']['text']  = $_REQUEST['_fabric_name'];
        }
        if(isset($_REQUEST['_design_name']) && !empty($_REQUEST['_design_name'])){
            $subsql .=" AND design.design_name = '".$_REQUEST['_design_name']."'";
            $record['filter']['_design_name']['value'] = $_REQUEST['_design_name'];
            $record['filter']['_design_name']['text']  = $_REQUEST['_design_name'];
        }
        if(isset($_REQUEST['_color_name']) && !empty($_REQUEST['_color_name'])){
            $subsql .=" AND color.color_name = '".$_REQUEST['_color_name']."'";
            $record['filter']['_color_name']['value'] = $_REQUEST['_color_name'];
            $record['filter']['_color_name']['text']  = $_REQUEST['_color_name'];
        }
        if(isset($_REQUEST['_width_name']) && !empty($_REQUEST['_width_name'])){
            $subsql .=" AND width.width_name = '".$_REQUEST['_width_name']."'";
            $record['filter']['_width_name']['value'] = $_REQUEST['_width_name'];
            $record['filter']['_width_name']['text']  = $_REQUEST['_width_name'];
        }
        if(isset($_REQUEST['_description']) && !empty($_REQUEST['_description'])){
            $subsql .=" AND bm.bm_description = '".$_REQUEST['_description']."'";
            $record['filter']['_description']['value'] = $_REQUEST['_description'];
            $record['filter']['_description']['text']  = $_REQUEST['_description'];
        }
        if(isset($_REQUEST['_rate_from'])){
            if($_REQUEST['_rate_from'] != ''){
                $subsql .=" AND bm.bm_pt_rate >= ".$_REQUEST['_rate_from'];
                $record['filter']['_rate_from'] = $_REQUEST['_rate_from'];
            }
        }
        if(isset($_REQUEST['_rate_to'])){
            if($_REQUEST['_rate_to'] != ''){
                $subsql .=" AND bm.bm_pt_rate <= ".$_REQUEST['_rate_to'];
                $record['filter']['_rate_to'] = $_REQUEST['_rate_to'];
            }
        }
        if(isset($_REQUEST['_mrp_from'])){
            if($_REQUEST['_mrp_from'] != ''){
                $subsql .=" AND bm.bm_mrp >= ".$_REQUEST['_mrp_from'];
                $record['filter']['_mrp_from'] = $_REQUEST['_mrp_from'];
            }
        }
        if(isset($_REQUEST['_mrp_to'])){
            if($_REQUEST['_mrp_to'] != ''){
                $subsql .=" AND bm.bm_mrp <= ".$_REQUEST['_mrp_to'];
                $record['filter']['_mrp_to'] = $_REQUEST['_mrp_to'];
            }
        }
        if(isset($_REQUEST['_pt_mtr_from'])){
            if($_REQUEST['_pt_mtr_from'] != ''){
                $having .=" AND pt_mtr >= ".$_REQUEST['_pt_mtr_from'];
                $record['filter']['_pt_mtr_from'] = $_REQUEST['_pt_mtr_from'];
            }
        }
        if(isset($_REQUEST['_pt_mtr_to'])){
            if($_REQUEST['_pt_mtr_to'] != ''){
                $having .=" AND pt_mtr <= ".$_REQUEST['_pt_mtr_to'];
                $record['filter']['_pt_mtr_to'] = $_REQUEST['_pt_mtr_to'];
            }
        }
        if(isset($_REQUEST['_ot_mtr_from'])){
            if($_REQUEST['_ot_mtr_from'] != ''){
                $having .=" AND ot_mtr >= ".$_REQUEST['_ot_mtr_from'];
                $record['filter']['_ot_mtr_from'] = $_REQUEST['_ot_mtr_from'];
            }
        }
        if(isset($_REQUEST['_ot_mtr_to'])){
            if($_REQUEST['_ot_mtr_to'] != ''){
                $having .=" AND ot_mtr <= ".$_REQUEST['_ot_mtr_to'];
                $record['filter']['_ot_mtr_to'] = $_REQUEST['_ot_mtr_to'];
            }
        }
        if(isset($_REQUEST['_bal_mtr_from'])){
            if($_REQUEST['_bal_mtr_from'] != ''){
                $having .=" AND bal_mtr >= ".$_REQUEST['_bal_mtr_from'];
                $record['filter']['_bal_mtr_from'] = $_REQUEST['_bal_mtr_from'];
            }
        }
        if(isset($_REQUEST['_bal_mtr_to'])){
            if($_REQUEST['_bal_mtr_to'] != ''){
                $having .=" AND bal_mtr <= ".$_REQUEST['_bal_mtr_to'];
                $record['filter']['_bal_mtr_to'] = $_REQUEST['_bal_mtr_to'];
            }
        }
        if(isset($_REQUEST['_bal_amt_from'])){
            if($_REQUEST['_bal_amt_from'] != ''){
                $having .=" AND bal_amt >= ".$_REQUEST['_bal_amt_from'];
                $record['filter']['_bal_amt_from'] = $_REQUEST['_bal_amt_from'];
            }
        }
        if(isset($_REQUEST['_bal_amt_to'])){ 
            if($_REQUEST['_bal_amt_to'] != ''){
                $having .=" AND bal_amt <= ".$_REQUEST['_bal_amt_to'];
                $record['filter']['_bal_amt_to'] = $_REQUEST['_bal_amt_to'];
            }
        } 
        
        $query="SELECT 
                UPPER(fabric.fabric_name) as fabric_name,
                UPPER(design.design_name) as design_name,
                UPPER(category.category_name) as category_name,
                UPPER(color.color_name) as color_name,
                UPPER(width.width_name) as width_name,
                UPPER(bm.bm_description) as description,
                bm.bm_pt_rate as rate,
                bm.bm_mrp as mrp,
                SUM(bm.bm_pt_mtr) as pt_mtr,
                SUM(bm.bm_prt_mtr) as prt_mtr,
                SUM(bm.bm_ot_mtr) as ot_mtr,
                SUM((bm.bm_pt_mtr-bm.bm_prt_mtr) - bm.bm_ot_mtr) as bal_mtr,
                SUM((bm.bm_pt_mtr-bm.bm_prt_mtr) * bm.bm_pt_rate) as bal_amt,
                (bm.bm_mrp * SUM(bm.bm_pt_mtr - bm.bm_ot_mtr)) as bal_mrp
                FROM barcode_master bm
                INNER JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                LEFT JOIN design_master design ON(design.design_id = bm.bm_design_id)
                LEFT JOIN category_master category ON(category.category_id = bm.bm_category_id)
                LEFT JOIN color_master color ON(color.color_id = bm.bm_color_id)
                LEFT JOIN width_master width ON(width.width_id = bm.bm_width_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY bm.bm_fabric_id, bm.bm_design_id,bm.bm_category_id, bm.bm_color_id, bm.bm_width_id, bm.bm_pt_rate, bm.bm_mrp
                HAVING 1
                $having
                ORDER BY bal_mtr DESC, bal_amt DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        $record['totals']['rows']   = count($data);
        $record['totals']['mrp'] = 0;
        $record['totals']['pt_mtr'] = 0;
        $record['totals']['prt_mtr'] = 0;
        $record['totals']['ot_mtr'] = 0;
        $record['totals']['bal_mtr']= 0;
        $record['totals']['bal_amt']= 0;
        $record['totals']['bal_mrp']= 0;
        $record['data']             = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                                                'fabric_name'   => $value['fabric_name'],
                                                'design_name'   => $value['design_name'],
                                                'category_name'   => $value['category_name'],
                                                'color_name' 	=> $value['color_name'],
                                                'width_name' 	=> $value['width_name'],
                                                'description' 	=> $value['description'],
                                                'pt_mtr' 		=> (float)$value['pt_mtr'],
                                                'prt_mtr'        => (float)$value['prt_mtr'],
                                                'ot_mtr' 		=> (float)$value['ot_mtr'],
                                                'rate' 		    => (float)$value['rate'],
                                                'mrp' 		    => (float)$value['mrp'],
                                                'bal_mtr' 		=> (float)$value['bal_mtr'],
                                                'bal_amt' 		=> (float)$value['bal_amt'],
                                                'bal_mrp'       => (float)$value['bal_mrp'],
                                            ]);

                $record['totals']['mrp'] 	    = $record['totals']['mrp'] 		+ $value['mrp'];
                $record['totals']['pt_mtr']     = $record['totals']['pt_mtr']       + $value['pt_mtr'];
                 $record['totals']['prt_mtr']     = $record['totals']['prt_mtr']       + $value['prt_mtr'];
                $record['totals']['ot_mtr'] 	= $record['totals']['ot_mtr'] 		+ $value['ot_mtr'];
                $record['totals']['bal_mtr'] 	= $record['totals']['bal_mtr'] 		+ $value['bal_mtr'];
                $record['totals']['bal_amt'] 	= $record['totals']['bal_amt'] 		+ $value['bal_amt'];
                $record['totals']['bal_mrp']    = $record['totals']['bal_mrp']      + $value['bal_mrp'];

            }
        }
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _fabric_name(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (fabric.fabric_name LIKE '%".$name."%') ";
        }
        $query="SELECT fabric.fabric_name as id, UPPER(fabric.fabric_name) as name
                FROM barcode_master bm
                INNER JOIN fabric_master fabric ON(fabric.fabric_id = bm.bm_fabric_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY fabric.fabric_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _design_name(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (design.design_name LIKE '%".$name."%') ";
        }
        $query="SELECT design.design_name as id, UPPER(design.design_name) as name
                FROM barcode_master bm
                LEFT JOIN design_master design ON(design.design_id = bm.bm_design_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY design.design_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _category_name(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (category.category_name LIKE '%".$name."%') ";
        }
        $query="SELECT category.category_name as id, UPPER(category.category_name) as name
                FROM barcode_master bm
                LEFT JOIN category_master category ON(category.category_id = bm.bm_category_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY category.category_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

    public function _color_name(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (color.color_name LIKE '%".$name."%') ";
        }
        $query="SELECT color.color_name as id, UPPER(color.color_name) as name
                FROM barcode_master bm
                INNER JOIN color_master color ON(color.color_id = bm.bm_color_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY color.color_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _width_name(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (width.width_name LIKE '%".$name."%') ";
        }
        $query="SELECT width.width_name as id, UPPER(width.width_name) as name
                FROM barcode_master bm
                INNER JOIN width_master width ON(width.width_id = bm.bm_width_id)
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY width.width_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _description(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (bm.bm_description LIKE '%".$name."%') ";
        }
        $query="SELECT bm.bm_description as id, UPPER(bm.bm_description) as name
                FROM barcode_master bm
                WHERE bm.bm_delete_status = 0
                $subsql
                GROUP BY bm.bm_description ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>