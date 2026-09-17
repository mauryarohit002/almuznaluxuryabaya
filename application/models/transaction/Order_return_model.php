<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class order_return_model extends my_model{
    public function __construct(){ parent::__construct('transaction', 'order_return'); }
    public function isExist($id){  
        return false;  
    }
    public function isTransExist($id){    
        return false;
    }

    public function isBarcodeExist($obt_id)
    {
       return false;
    }
    
    public function get_list($wantCount, $per_page = 20, $offset = 0){ 
        $record     = [];
        $subsql     = '';
        $limit      = '';
        $ofset      = '';
        if(!$wantCount){ 
            $limit .= " LIMIT $per_page";
            $ofset .= " OFFSET $offset";
        }
        
        if(isset($_GET['_entry_no']) && !empty($_GET['_entry_no'])){
            $subsql .=" AND orm.orm_entry_no = '".$_GET['_entry_no']."'";
            $record['filter']['_entry_no']['value'] = $_GET['_entry_no'];
            $record['filter']['_entry_no']['text'] = $_GET['_entry_no'];
        }
        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){
            $_entry_date_from = date('Y-m-d', strtotime($_GET['_entry_date_from']));
            $subsql .= " AND orm.orm_entry_date >= '".$_entry_date_from."'";
        }
        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){
            $_entry_date_to = date('Y-m-d', strtotime($_GET['_entry_date_to']));
            $subsql .= " AND orm.orm_entry_date <= '".$_entry_date_to."'";
        }
        if(isset($_GET['_customer_name']) && !empty($_GET['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_GET['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_GET['_customer_name'];
            $record['filter']['_customer_name']['text'] = $_GET['_customer_name'];
        }
       
        $query="SELECT orm.*,
                UPPER(customer.customer_name) as customer_name,
                customer.customer_mobile 
                FROM order_return_master orm
                LEFT JOIN customer_master customer ON(customer.customer_id = orm.orm_customer_id)
                WHERE orm.orm_delete_status = 0
                AND orm.orm_branch_id = ".$_SESSION['user_branch_id']."
                AND orm.orm_fin_year = '".$_SESSION['fin_year']."'  
                $subsql
                ORDER BY orm.orm_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){ 
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist'] = $this->isExist($value['orm_id']);
            }
        }
        //  echo "<pre>"; print_r($record); exit;
        return $record;
    }

    public function get_data_for_add(){ 
        $record['orm_entry_no'] = $this->get_max_entry_no([
            'entry_no'=>'orm_entry_no',
            'delete_status'=>'orm_delete_status',
            'fin_year'=>'orm_fin_year'
        ]);
        $record['orm_uuid'] = $_SESSION['user_id'].time();
        return $record;
    }

    public function get_data_for_edit($id){
        $record['master_data'] = $this->db->query("
            SELECT orm.*,
            CONCAT(UPPER(customer.customer_name), ' - ', customer.customer_mobile) as customer_name
            FROM order_return_master orm 
            INNER JOIN customer_master customer ON customer.customer_id = orm.orm_customer_id
            WHERE orm.orm_id = $id AND orm.orm_delete_status = 0
        ")->result_array();
        // echo "<pre>"; print_r($record); exit;
        return $record;
    }

    public function get_transaction($orm_id)
    { 
        $query = "
            SELECT ort.*,
            brmm.brmm_item_code as item_code
            FROM order_return_trans ort 
            LEFT JOIN barcode_readymade_master brmm ON(brmm.brmm_id = ort.ort_brmm_id)
            WHERE ort.ort_orm_id = $orm_id
            AND ort.ort_delete_status = 0";
        // print_r($query);die;    
        $record= $this->db->query($query)->result_array();
        if(!empty($record)){
            foreach ($record as $key => $value) { 
                $record[$key]['isExist'] = $this->isTransExist($value['ort_id']);
            }
        }  
        // echo "<pre>"; print_r($record);die;
        return $record;
    } 
   
    public function get_readymade_item_code($id){
        $query="SELECT brmm_item_code as name FROM barcode_readymade_master WHERE brmm_id = $id";
        $data = $this->db->query($query)->result_array();
        return empty($data) ? '' : $data[0]['name'];
    }

    public function get_readymade_barcode_data($id){      
        $query="SELECT brmm.*,
            (((brmm.brmm_ot_qty) - (brmm.brmm_ort_qty))) as bal_qty,
            ot.*,
            customer.customer_id,
            CONCAT(UPPER(customer.customer_name),' - ',customer.customer_mobile) as customer_name 
            FROM barcode_readymade_master brmm 
            INNER JOIN order_trans ot ON(ot.ot_brmm_id = brmm.brmm_id)
            INNER JOIN order_master om ON(om.om_id = ot.ot_om_id)
            INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
            WHERE ot.ot_delete_status=0 
            AND om.om_delete_status=0
            AND brmm.brmm_id = $id";
        // print_r($query);die;        
        return $this->db->query($query)->result_array();
    }

    public function get_data_for_print($orm_id){          
            $branch_id = $_SESSION['user_branch_id'];
            $query="SELECT  UPPER(company.company_name) as company_name,
                UPPER(company.company_gstin) as gst_no,
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
                WHERE 1 AND company.company_id =$branch_id ";
            $record['company_data'] = $this->db->query($query)->result_array();        
            $query="SELECT *
                FROM branch_master
                WHERE branch_id = '".$_SESSION['user_branch_id']."' ";
            $record['branch_data'] = $this->db->query($query)->result_array();
       
            $query="SELECT 
                orm.orm_uuid,
                orm.orm_entry_no as entry_no, 
                DATE_FORMAT(orm.orm_entry_date, '%d-%m-%Y') as entry_date,
                orm.orm_total_qty as total_qty,
                orm.orm_sub_amt as sub_amt,
                orm.orm_disc_amt as disc_amt,
                orm.orm_taxable_amt as taxable_amt,
                orm.orm_sgst_amt as sgst_amt,
                orm.orm_cgst_amt as cgst_amt,
                orm.orm_igst_amt as igst_amt,
                (orm.orm_sgst_amt + orm.orm_cgst_amt + orm.orm_igst_amt) as gst_amt,
                (orm.orm_taxable_amt + orm.orm_sgst_amt + orm.orm_cgst_amt + orm.orm_igst_amt) as net_amt,
                0 as round_off,
                orm.orm_total_amt as total_amt,
                orm.orm_notes as notes,
                UPPER(customer.customer_name) as customer_name,
                UPPER(customer.customer_mobile) as customer_mobile,
                UPPER(customer.customer_gst_no) as customer_gst_no,
                UPPER(customer.customer_address) as customer_address,
                customer.customer_pincode as customer_pincode,
                IFNULL(UPPER(city.city_name), '') as city_name,
                IFNULL(UPPER(state.state_name), '') as state_name,
                IFNULL(UPPER(country.country_name), '') as country_name
                FROM order_return_master orm
                INNER JOIN customer_master customer ON(customer.customer_id = orm.orm_customer_id)
                LEFT JOIN city_master city ON(city.city_id = customer.customer_city_id)
                LEFT JOIN state_master state ON(state.state_id = customer.customer_state_id)
                LEFT JOIN country_master country ON(country.country_id = customer.customer_country_id)
                WHERE orm.orm_delete_status = 0 
                AND orm.orm_id = $orm_id";
            // echo "<pre>"; print_r($query); exit();
            $record['master_data'] = $this->db->query($query)->result_array();
            $query="SELECT 
                    brmm.brmm_item_code as item_code,
                    UPPER(readymade_category.readymade_category_name) as readymade_category_name,
                    (ort.ort_qty) as qty,
                    (ort.ort_rate) as rate,
                    (ort.ort_amt) as amt,
                    ort.ort_disc_amt as disc_amt,
                    (ort.ort_taxable_amt) as taxable_amt,
                    (ort.ort_sgst_per) as sgst_per,
                    (ort.ort_sgst_amt) as sgst_amt,
                    ort.ort_cgst_per as cgst_per,
                    (ort.ort_cgst_amt) as cgst_amt,
                    ort.ort_igst_per as igst_per,
                    (ort.ort_igst_amt) as igst_amt,
                    (ort.ort_sgst_amt + ort.ort_cgst_amt + ort.ort_igst_amt) as gst_amt,
                    (ort.ort_total_amt) as total_amt
                    FROM order_return_trans ort
                    LEFT JOIN barcode_readymade_master brmm ON(brmm.brmm_id = ort.ort_brmm_id)
                    LEFT JOIN readymade_category_master readymade_category ON(readymade_category.readymade_category_id = brmm.brmm_readymade_category_id)
                    WHERE ort.ort_delete_status = 0 
                    AND ort.ort_orm_id = $orm_id
                    GROUP BY ort.ort_id";
            $record['trans_data'] = $this->db->query($query)->result_array();
            // echo "<pre>"; print_r($record); exit();
            return $record;
    }

    public function _brmm_id(){ 
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
        if((isset($_GET['name']) && !empty($_GET['name']))){
            $name   = $_GET['name'];
            $subsql .= " AND (brmm_item_code LIKE '".$name."%' )";
        }else{
            $subsql .= " AND (brmm_item_code = 'XXX') ";
        } 
        
        $query="SELECT brmm_id as id, 
                brmm_item_code as name
                FROM barcode_readymade_master
                WHERE brmm_delete_status=0
                AND brmm_branch_id = '".$_SESSION['user_branch_id']."' 
                $subsql
                GROUP BY brmm_id
                ORDER BY brmm_item_code ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }

    public function _entry_no(){
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
            $subsql .= " AND (orm.orm_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT orm.orm_entry_no as id, UPPER(orm.orm_entry_no) as name
                FROM order_return_master orm
                WHERE orm.orm_delete_status = 0
                $subsql
                GROUP BY orm.orm_entry_no ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _customer_name(){
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
            $subsql .= " AND (customer.customer_name LIKE '%".$name."%') ";
        }
        $query="SELECT customer.customer_name as id, UPPER(customer.customer_name) as name
                FROM order_return_master orm
                INNER JOIN customer_master customer ON(customer.customer_id = orm.orm_customer_id)
                WHERE orm.orm_delete_status = 0
                $subsql
                GROUP BY customer.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
   


}
?>