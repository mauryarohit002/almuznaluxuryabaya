<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Model.php';

class pending_delivery_model extends my_model{

    public function __construct(){ parent::__construct('report', 'pending_delivery'); }

    public function get_record(){

        $record     = [];

        $subsql 	= '';

        $having 	= '';     

       

        if(isset($_REQUEST['_customer_name']) && !empty($_REQUEST['_customer_name'])){

            $subsql .=" AND customer.customer_name = '".$_REQUEST['_customer_name']."'";

            $record['filter']['_customer_name']['value'] = $_REQUEST['_customer_name'];

            $record['filter']['_customer_name']['text']  = $_REQUEST['_customer_name'];

        }

        

        if(isset($_REQUEST['_entry_no']) && !empty($_REQUEST['_entry_no'])){

            $inList = implode(',', $_REQUEST['_entry_no']);

            // print_r($inList);exit();

            $subsql .= " AND om.om_em_entry_no IN ($inList)";

            $record['filter']['_entry_no']['value'] = $_REQUEST['_entry_no'];

            $record['filter']['_entry_no']['text']  =  $_REQUEST['_entry_no'];

        }

        if(isset($_REQUEST['_entry_date_from'])){

            if($_REQUEST['_entry_date_from'] != ''){

                $subsql .=" AND om.om_em_entry_date >= '".$_REQUEST['_entry_date_from']."'";

                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];

            }

        }

        if(isset($_REQUEST['_entry_date_to'])){

            if($_REQUEST['_entry_date_to'] != ''){

                $subsql .=" AND om.om_em_entry_date <= '".$_REQUEST['_entry_date_to']."'";

                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];

            }

        }



        if(isset($_REQUEST['_branch']) && !empty($_REQUEST['_branch'])){

            $subsql .= " AND branch.branch_name = '".$_REQUEST['_branch']."'";

            $record['filter']['_branch']['value'] = $_REQUEST['_branch'];

            $record['filter']['_branch']['text']  = $_REQUEST['_branch'];

        }



        if($_SESSION['branch_default'] != 1){ 

            $subsql .= " AND om.om_branch_id = '".$_SESSION['user_branch_id']."'";

        }

       

        $query = "

			SELECT

			    om.om_em_entry_no AS entry_no,

			    DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') AS entry_date,

			    UPPER(customer.customer_name) AS customer_name,

			    UPPER(customer.customer_mobile) AS customer_mobile,

			    DATE_FORMAT(om.om_trial_date, '%d-%m-%Y') AS trial_date,

			    DATE_FORMAT(om.om_delivery_date, '%d-%m-%Y') AS delivery_date,

			    om.om_total_amt as total_amt,

			    om.om_advance_amt as advance_amt,

			    om.om_allocated_amt as allocated_amt,

			    (om.om_total_amt - om.om_advance_amt - om.om_allocated_amt) AS balance_amt,

			    IF(om.om_delivery_done=1,'DONE','PENDING') AS status

				FROM order_master om

				INNER JOIN customer_master customer ON customer.customer_id = om.om_customer_id

                INNER JOIN branch_master branch ON(branch.branch_id = om.om_branch_id) 

				INNER JOIN order_barcode_trans obt

				    ON obt.obt_om_id = om.om_id

				    AND obt.obt_delete_status = 0

				INNER JOIN job_receive_trans jrt

				    ON jrt.jrt_obt_id = obt.obt_id

				    AND jrt.jrt_delete_status = 0

				WHERE om.om_delete_status = 0

				AND om.om_delivery_done=0

                AND jrt.jrt_rfd = 1

				$subsql

				GROUP BY jrt_obt_id

				ORDER BY om.om_em_entry_no DESC

				";



        $data = $this->db->query($query)->result_array();

        // echo "<pre>"; print_r($query); exit();

        // echo "<pre>"; print_r($data); exit();

        

        $record['totals']['rows']   = count($data);

        $record['data']             = [];

        if(!empty($data)){

            foreach ($data as $key => $value) {

                array_push($record['data'], [

                'entry_no'      => (int)$value['entry_no'],

                'entry_date1'   => $value['entry_date'],

                'entry_date' 	=> (int)strtotime($value['entry_date']),

                'customer_name'  => $value['customer_name'],

                'customer_mobile'  => $value['customer_mobile'],

                'trial_date'   => $value['trial_date'],

                'delivery_date'   => $value['delivery_date'],

                'total_amt'   => $value['total_amt'],

                'advance_amt'   => $value['advance_amt'],

                'allocated_amt'   => $value['allocated_amt'],

                'balance_amt'   => $value['balance_amt'],

                'status'   => $value['status'],



            ]);

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

            $subsql .= " AND (om.om_em_entry_no LIKE '".$name."%') ";

        }

        $query="SELECT om.om_em_entry_no as id, 

                UPPER(om.om_em_entry_no) as name

                FROM order_master om

                WHERE om.om_delete_status = 0

                $subsql

                GROUP BY om.om_em_entry_no ASC

                LIMIT $limit

                OFFSET $offset";

        // echo $query; exit();

        return $this->db->query($query)->result_array();

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

            $subsql .= " AND (customer.customer_name LIKE '".$name."%') ";

        }

        $query="SELECT customer.customer_name as id, 

                UPPER(customer.customer_name) as name

                FROM job_issue_master jim

                INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)

                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)

                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)

                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)

                WHERE jim.jim_delete_status = 0

                AND jit.jit_delete_status = 0

                AND obt.obt_delete_status = 0

                AND om.om_delete_status = 0

                $subsql 

                GROUP BY customer.customer_name ASC

                LIMIT $limit

                OFFSET $offset";

        // echo $query; exit();

        return $this->db->query($query)->result_array();

    }



    public function _branch_name(){

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

            $subsql .= " AND (branch.branch_name = '".$name."') ";

        }

        $query="SELECT id, name

                FROM (

                        SELECT branch.branch_name as id , UPPER(branch.branch_name) as name 

                        FROM branch_master branch 

                        WHERE branch.branch_status = 1

                        $subsql

                    ) temp

                WHERE 1

                GROUP BY id ASC

                LIMIT $limit

                OFFSET $offset";

        // echo $query; exit();

        return $this->db->query($query)->result_array();

    }

   

}

?>