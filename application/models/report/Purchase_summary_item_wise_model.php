<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class purchase_summary_item_wise_model extends my_model{
    public function __construct(){ parent::__construct('report', 'purchase_summary_item_wise'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        $having 	= '';
        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){
            $subsql .=" AND pm.pm_entry_no = '".$_REQUEST['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];
            $record['filter']['_entry_no']['text']  = $_REQUEST['_entry_no'];
        }
        if(isset($_REQUEST['_supplier_name']) && !empty($_REQUEST['_supplier_name'])){
            $subsql .=" AND supplier.supplier_name = '".$_REQUEST['_supplier_name']."'";
            $record['filter']['_supplier_name']['value'] = $_REQUEST['_supplier_name'];
            $record['filter']['_supplier_name']['text']  = $_REQUEST['_supplier_name'];
        }
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
        if(isset($_REQUEST['_hsn_name']) && !empty($_REQUEST['_hsn_name'])){
            $subsql .=" AND hsn.hsn_name = '".$_REQUEST['_hsn_name']."'";
            $record['filter']['_hsn_name']['value'] = $_REQUEST['_hsn_name'];
            $record['filter']['_hsn_name']['text']  = $_REQUEST['_hsn_name'];
        }
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND pm.pm_entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND pm.pm_entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }
        if(isset($_REQUEST['_qty_from'])){
            if($_REQUEST['_qty_from'] != ''){
                $subsql .=" AND pt.pt_qty >= ".$_REQUEST['_qty_from'];
                $record['filter']['_qty_from'] = $_REQUEST['_qty_from'];
            }
        }
        if(isset($_REQUEST['_qty_to'])){
            if($_REQUEST['_qty_to'] != ''){
                $subsql .=" AND pt.pt_qty <= ".$_REQUEST['_qty_to'];
                $record['filter']['_qty_to'] = $_REQUEST['_qty_to'];
            }
        }
        if(isset($_REQUEST['_mtr_from'])){
            if($_REQUEST['_mtr_from'] != ''){
                $subsql .=" AND pt.pt_mtr >= ".$_REQUEST['_mtr_from'];
                $record['filter']['_mtr_from'] = $_REQUEST['_mtr_from'];
            }
        }
        if(isset($_REQUEST['_mtr_to'])){
            if($_REQUEST['_mtr_to'] != ''){
                $subsql .=" AND pt.pt_mtr <= ".$_REQUEST['_mtr_to'];
                $record['filter']['_mtr_to'] = $_REQUEST['_mtr_to'];
            }
        }
        if(isset($_REQUEST['_total_mtr_from'])){
            if($_REQUEST['_total_mtr_from'] != ''){
                $subsql .=" AND pt.pt_total_mtr >= ".$_REQUEST['_total_mtr_from'];
                $record['filter']['_total_mtr_from'] = $_REQUEST['_total_mtr_from'];
            }
        }
        if(isset($_REQUEST['_total_mtr_to'])){
            if($_REQUEST['_total_mtr_to'] != ''){
                $subsql .=" AND pt.pt_total_mtr <= ".$_REQUEST['_total_mtr_to'];
                $record['filter']['_total_mtr_to'] = $_REQUEST['_total_mtr_to'];
            }
        }
        if(isset($_REQUEST['_rate_from'])){
            if($_REQUEST['_rate_from'] != ''){
                $subsql .=" AND pt.pt_rate >= ".$_REQUEST['_rate_from'];
                $record['filter']['_rate_from'] = $_REQUEST['_rate_from'];
            }
        }
        if(isset($_REQUEST['_rate_to'])){
            if($_REQUEST['_rate_to'] != ''){
                $subsql .=" AND pt.pt_rate <= ".$_REQUEST['_rate_to'];
                $record['filter']['_rate_to'] = $_REQUEST['_rate_to'];
            }
        }
        if(isset($_REQUEST['_sub_amt_from'])){
            if($_REQUEST['_sub_amt_from'] != ''){
                $subsql .=" AND pt.pt_amt >= ".$_REQUEST['_sub_amt_from'];
                $record['filter']['_sub_amt_from'] = $_REQUEST['_sub_amt_from'];
            }
        }
        if(isset($_REQUEST['_sub_amt_to'])){
            if($_REQUEST['_sub_amt_to'] != ''){
                $subsql .=" AND pt.pt_amt <= ".$_REQUEST['_sub_amt_to'];
                $record['filter']['_sub_amt_to'] = $_REQUEST['_sub_amt_to'];
            }
        }
        if(isset($_REQUEST['_disc_amt_from'])){
            if($_REQUEST['_disc_amt_from'] != ''){
                $subsql .=" AND pt.pt_disc_amt >= ".$_REQUEST['_disc_amt_from'];
                $record['filter']['_disc_amt_from'] = $_REQUEST['_disc_amt_from'];
            }
        }
        if(isset($_REQUEST['_disc_amt_to'])){
            if($_REQUEST['_disc_amt_to'] != ''){
                $subsql .=" AND pt.pt_disc_amt <= ".$_REQUEST['_disc_amt_to'];
                $record['filter']['_disc_amt_to'] = $_REQUEST['_disc_amt_to'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_from'])){
            if($_REQUEST['_taxable_amt_from'] != ''){
                $subsql .=" AND pt.pt_taxable_amt >= ".$_REQUEST['_taxable_amt_from'];
                $record['filter']['_taxable_amt_from'] = $_REQUEST['_taxable_amt_from'];
            }
        }
        if(isset($_REQUEST['_taxable_amt_to'])){
            if($_REQUEST['_taxable_amt_to'] != ''){
                $subsql .=" AND pt.pt_taxable_amt <= ".$_REQUEST['_taxable_amt_to'];
                $record['filter']['_taxable_amt_to'] = $_REQUEST['_taxable_amt_to'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_from'])){
            if($_REQUEST['_sgst_amt_from'] != ''){
                $subsql .=" AND pt.pt_sgst_amt >= ".$_REQUEST['_sgst_amt_from'];
                $record['filter']['_sgst_amt_from'] = $_REQUEST['_sgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_sgst_amt_to'])){
            if($_REQUEST['_sgst_amt_to'] != ''){
                $subsql .=" AND pt.pt_sgst_amt <= ".$_REQUEST['_sgst_amt_to'];
                $record['filter']['_sgst_amt_to'] = $_REQUEST['_sgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_from'])){
            if($_REQUEST['_cgst_amt_from'] != ''){
                $subsql .=" AND pt.pt_cgst_amt >= ".$_REQUEST['_cgst_amt_from'];
                $record['filter']['_cgst_amt_from'] = $_REQUEST['_cgst_amt_from'];
            }
        }
        if(isset($_REQUEST['_cgst_amt_to'])){
            if($_REQUEST['_cgst_amt_to'] != ''){
                $subsql .=" AND pt.pt_cgst_amt <= ".$_REQUEST['_cgst_amt_to'];
                $record['filter']['_cgst_amt_to'] = $_REQUEST['_cgst_amt_to'];
            }
        }
        if(isset($_REQUEST['_igst_amt_from'])){
            if($_REQUEST['_igst_amt_from'] != ''){
                $subsql .=" AND pt.pt_igst_amt >= ".$_REQUEST['_igst_amt_from'];
                $record['filter']['_igst_amt_from'] = $_REQUEST['_igst_amt_from'];
            }
        }
        if(isset($_REQUEST['_igst_amt_to'])){
            if($_REQUEST['_igst_amt_to'] != ''){
                $subsql .=" AND pt.pt_igst_amt <= ".$_REQUEST['_igst_amt_to'];
                $record['filter']['_igst_amt_to'] = $_REQUEST['_igst_amt_to'];
            }
        }
        if(isset($_REQUEST['_total_amt_from'])){
            if($_REQUEST['_total_amt_from'] != ''){
                $subsql .=" AND pt.pt_total_amt >= ".$_REQUEST['_total_amt_from'];
                $record['filter']['_total_amt_from'] = $_REQUEST['_total_amt_from'];
            }
        }
        if(isset($_REQUEST['_total_amt_to'])){
            if($_REQUEST['_total_amt_to'] != ''){
                $subsql .=" AND pt.pt_total_amt <= ".$_REQUEST['_total_amt_to'];
                $record['filter']['_total_amt_to'] = $_REQUEST['_total_amt_to'];
            }
        }
        $query="SELECT 'PURCHAE' as module_name,
                pm.pm_entry_no as entry_no,
                DATE_FORMAT(pm.pm_entry_date, '%d-%m-%Y') as entry_date,
                UPPER(supplier.supplier_name) as supplier_name,
                supplier.supplier_mobile as supplier_mobile,
                pt.pt_qty as qty,
                pt.pt_mtr as mtr,
                pt.pt_total_mtr as total_mtr,
                pt.pt_rate as rate,
                pt.pt_mrp as mrp,
                pt.pt_amt as sub_amt,
                pt.pt_disc_per as disc_per,
                pt.pt_disc_amt as disc_amt,
                pt.pt_taxable_amt as taxable_amt,
                pt.pt_sgst_per as sgst_per,
                pt.pt_sgst_amt as sgst_amt,
                pt.pt_cgst_per as cgst_per,
                pt.pt_cgst_amt as cgst_amt,
                pt.pt_igst_per as igst_per,
                pt.pt_igst_amt as igst_amt,
                pt.pt_total_amt as total_amt,
                UPPER(fabric.fabric_name) as fabric_name,
                UPPER(design.design_name) as design_name,
                IFNULL(UPPER(color.color_name), '') as color_name,
                IFNULL(UPPER(width.width_name), '') as width_name,
                IFNULL(UPPER(hsn.hsn_name), '') as hsn_name,
                design.design_image,
                pm.pm_created_at as created_at
                FROM purchase_master pm
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = pm.pm_supplier_id)
                INNER JOIN purchase_trans pt ON(pt.pt_pm_id = pm.pm_id)
                INNER JOIN fabric_master fabric ON(fabric.fabric_id = pt.pt_fabric_id)
                LEFT JOIN design_master design ON(design.design_id = pt.pt_design_id)
                LEFT JOIN color_master color ON(color.color_id = pt.pt_color_id)
                LEFT JOIN width_master width ON(width.width_id = pt.pt_width_id)
                LEFT JOIN hsn_master hsn ON(hsn.hsn_id = pt.pt_hsn_id)
                WHERE pm.pm_delete_status = 0
                AND pt.pt_delete_status = 0
                $subsql
                ORDER BY created_at DESC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']           = count($data);
        $record['totals']['qty']            = 0;
        $record['totals']['total_mtr']      = 0;
        $record['totals']['sub_amt']        = 0;
        $record['totals']['disc_amt']       = 0;
        $record['totals']['taxable_amt']    = 0;
        $record['totals']['sgst_amt']       = 0;
        $record['totals']['cgst_amt']       = 0;
        $record['totals']['igst_amt']       = 0;
        $record['totals']['total_amt']      = 0;
        $record['data']                     = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                                                'module_name'       => $value['module_name'],
                                                'entry_no'          => (int)$value['entry_no'],
                                                'entry_date' 	    => (int)strtotime($value['entry_date']),
                                                'entry_date1' 	    => $value['entry_date'],
                                                'supplier_name'     => $value['supplier_name'],
                                                'supplier_mobile'   => (int)$value['supplier_mobile'],
                                                'qty' 		        => (int)$value['qty'],
                                                'mtr' 		        => (int)$value['mtr'],
                                                'rate' 		        => (float)$value['rate'],
                                                'mrp' 		        => (float)$value['mrp'],
                                                'total_mtr' 		=> (float)$value['total_mtr'],
                                                'sub_amt' 		    => (float)$value['sub_amt'],
                                                'disc_amt' 		    => (float)$value['disc_amt'],
                                                'taxable_amt' 		=> (float)$value['taxable_amt'],
                                                'sgst_amt' 		    => (float)$value['sgst_amt'],
                                                'cgst_amt' 		    => (float)$value['cgst_amt'],
                                                'igst_amt' 		    => (float)$value['igst_amt'],
                                                'total_amt'         => (float)$value['total_amt'],
                                                'fabric_name'       => $value['fabric_name'],
                                                'design_name'       => $value['design_name'],
                                                'color_name'        => $value['color_name'],
                                                'width_name'        => $value['width_name'],
                                                'hsn_name'          => $value['hsn_name'],
                                                'created_at'        => strtotime($value['created_at']),
                                            ]);

                $record['totals']['total_mtr'] 	    = $record['totals']['total_mtr'] 		+ $value['total_mtr'];
                $record['totals']['sub_amt'] 	    = $record['totals']['sub_amt'] 		    + $value['sub_amt'];
                $record['totals']['disc_amt'] 	    = $record['totals']['disc_amt'] 		+ $value['disc_amt'];
                $record['totals']['taxable_amt'] 	= $record['totals']['taxable_amt'] 		+ $value['taxable_amt'];
                $record['totals']['sgst_amt'] 	    = $record['totals']['sgst_amt'] 		+ $value['sgst_amt'];
                $record['totals']['cgst_amt'] 	    = $record['totals']['cgst_amt'] 		+ $value['cgst_amt'];
                $record['totals']['igst_amt'] 	    = $record['totals']['igst_amt'] 		+ $value['igst_amt'];
                $record['totals']['total_amt'] 	    = $record['totals']['total_amt'] 		+ $value['total_amt'];
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _entry_no(){
        $subsql = '';
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
            $subsql .= " AND (pm.pm_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT pm.pm_entry_no as id , 
                UPPER(pm.pm_entry_no) as name 
                FROM purchase_master pm 
                WHERE pm.pm_delete_status = 0
                GROUP BY pm.pm_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _supplier_name(){
        $subsql = '';
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
            $subsql .= " AND (supplier.supplier_name LIKE '%".$name."%') ";
        }
        $query="SELECT supplier.supplier_name as id , 
                UPPER(supplier.supplier_name) as name 
                FROM purchase_master pm 
                INNER JOIN supplier_master supplier ON(supplier.supplier_id = pm.pm_supplier_id)
                WHERE pm.pm_delete_status = 0
                $subsql
                GROUP BY supplier.supplier_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _fabric_name(){
        $subsql = '';
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
        $query="SELECT fabric.fabric_name as id, 
                UPPER(fabric.fabric_name) as name 
                FROM purchase_master pm 
                INNER JOIN purchase_trans pt ON(pt.pt_pm_id = pm.pm_id)
                INNER JOIN fabric_master fabric ON(fabric.fabric_id = pt.pt_fabric_id)
                WHERE pm.pm_delete_status = 0
                AND pt.pt_delete_status = 0
                $subsql
                GROUP BY fabric.fabric_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _design_name(){
        $subsql = '';
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
        $query="SELECT design.design_name as id, 
                UPPER(design.design_name) as name 
                FROM purchase_master pm 
                INNER JOIN purchase_trans pt ON(pt.pt_pm_id = pm.pm_id)
                LEFT JOIN design_master design ON(design.design_id = pt.pt_design_id)
                WHERE pm.pm_delete_status = 0
                AND pt.pt_delete_status = 0
                $subsql
                GROUP BY design.design_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _color_name(){
        $subsql = '';
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
        $query="SELECT color.color_name as id, 
                UPPER(color.color_name) as name 
                FROM purchase_master pm 
                INNER JOIN purchase_trans pt ON(pt.pt_pm_id = pm.pm_id)
                INNER JOIN color_master color ON(color.color_id = pt.pt_color_id)
                WHERE pm.pm_delete_status = 0
                AND pt.pt_delete_status = 0
                $subsql
                GROUP BY color.color_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _width_name(){
        $subsql = '';
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
        $query="SELECT width.width_name as id, 
                UPPER(width.width_name) as name 
                FROM purchase_master pm 
                INNER JOIN purchase_trans pt ON(pt.pt_pm_id = pm.pm_id)
                INNER JOIN width_master width ON(width.width_id = pt.pt_width_id)
                WHERE pm.pm_delete_status = 0
                AND pt.pt_delete_status = 0
                $subsql
                GROUP BY width.width_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _hsn_name(){
        $subsql = '';
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
            $subsql .= " AND (hsn.hsn_name LIKE '%".$name."%') ";
        }
        $query="SELECT hsn.hsn_name as id, 
                UPPER(hsn.hsn_name) as name 
                FROM purchase_master pm 
                INNER JOIN purchase_trans pt ON(pt.pt_pm_id = pm.pm_id)
                INNER JOIN hsn_master hsn ON(hsn.hsn_id = pt.pt_hsn_id)
                WHERE pm.pm_delete_status = 0
                AND pt.pt_delete_status = 0
                $subsql
                GROUP BY hsn.hsn_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>