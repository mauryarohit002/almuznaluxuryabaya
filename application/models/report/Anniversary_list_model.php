<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class anniversary_list_model extends my_model{
    public function __construct(){ parent::__construct('report', 'anniversary_list'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        if(isset($_REQUEST['_customer_name']) && !empty($_REQUEST['_customer_name'])){
            $subsql .=" AND customer.customer_name = '".$_REQUEST['_customer_name']."'";
            $record['filter']['_customer_name']['value'] = $_REQUEST['_customer_name'];
            $record['filter']['_customer_name']['text']  = $_REQUEST['_customer_name'];
        }
        if(isset($_REQUEST['_anniversary_date_from'])){
            if($_REQUEST['_anniversary_date_from'] != ''){
                $subsql .=" AND DATE_FORMAT(customer.customer_anniversary_date, '%m-%d') >= '".date('m-d', strtotime($_REQUEST['_anniversary_date_from']))."'";
                $record['filter']['_anniversary_date_from'] = $_REQUEST['_anniversary_date_from'];
            }
        }else{
            $subsql .=" AND DATE_FORMAT(customer.customer_anniversary_date, '%m-%d') >= '".date('m-d')."'";
        }
        if(isset($_REQUEST['_anniversary_date_to'])){
            if($_REQUEST['_anniversary_date_to'] != ''){
                $subsql .=" AND DATE_FORMAT(customer.customer_anniversary_date, '%m-%d') <= '".date('m-d', strtotime($_REQUEST['_anniversary_date_to']))."'";
                $record['filter']['_anniversary_date_to'] = $_REQUEST['_anniversary_date_to'];
            }
        }else{
            $subsql .=" AND DATE_FORMAT(customer.customer_anniversary_date, '%m-%d') <= '".date('m-d')."'";
        }
        $query="SELECT 
                UPPER(customer.customer_name) as customer_name,
                UPPER(customer.customer_contact_person) as contact_person,
                customer.customer_mobile as customer_mobile,
                customer.customer_phone1 as customer_phone1,
                LOWER(customer.customer_email) as customer_email,
                UPPER(customer.customer_address) as customer_address,
                IFNULL(UPPER(city.city_name), '') as city_name,
                IFNULL(UPPER(state.state_name), '') as state_name,
                IFNULL(UPPER(country.country_name), '') as country_name,
                customer.customer_pincode,
                customer.customer_status,
                DATE_FORMAT(customer.customer_anniversary_date, '%d-%m-%Y') as anniversary_date,
                IF(customer.customer_anniversary_date = '', 0, customer.customer_anniversary_date) as customer_anniversary_date,
                '' as created_at
                FROM customer_master customer
                LEFT JOIN city_master city ON(city.city_id = customer.customer_city_id)
                LEFT JOIN state_master state ON(state.state_id = customer.customer_state_id)
                LEFT JOIN country_master country ON(country.country_id = customer.customer_country_id)
                WHERE customer.customer_status = 1
                AND customer.customer_name != '' 
                AND (customer.customer_anniversary_date > 0)
                $subsql
                ORDER BY customer.customer_name ASC";
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']   = count($data);
        $record['data']             = [];
        if(!empty($data)){
            foreach ($data as $key => $value) {
                array_push($record['data'], [
                                                'customer_name'         => $value['customer_name'],
                                                'contact_person'        => $value['contact_person'],
                                                'anniversary_date'            => $value['anniversary_date'],
                                                'customer_anniversary_date'   => (int)strtotime($value['customer_anniversary_date']),
                                                'customer_mobile'       => $value['customer_mobile'],
                                                'customer_phone'        => $value['customer_phone1'],
                                                'customer_email'        => $value['customer_email'],
                                                'customer_address'      => $value['customer_address'],
                                                'city_name'             => $value['city_name'],
                                                'state_name'            => $value['state_name'],
                                                'country_name'          => $value['country_name'],
                                                'customer_pincode'      => $value['customer_pincode'],
                                                'customer_status'       => $value['customer_status'],
                                            ]);
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _customer_name(){
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
            $subsql .= " AND (customer.customer_name LIKE '%".$name."%') ";
        }
        $query="SELECT customer.customer_name as id, 
                UPPER(customer.customer_name) as name
                FROM customer_master customer
                WHERE customer.customer_status = 1
                AND customer.customer_name != '' 
                AND customer.customer_mobile = ''
                GROUP BY customer.customer_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _contact_person(){
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
            $subsql .= " AND (customer.customer_contact_person LIKE '%".$name."%') ";
        }
        $query="SELECT customer.customer_contact_person as id, 
                UPPER(customer.customer_contact_person) as name
                FROM customer_master customer
                WHERE customer.customer_status = 1
                AND customer.customer_contact_person != '' 
                AND customer.customer_mobile = ''
                GROUP BY customer.customer_contact_person ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>