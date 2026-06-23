<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/MY_Model.php';

class daily_profit_model extends my_model {

    public function __construct() {

        parent::__construct('report', 'daily_profit');
    }

    public function get_record(){

        $record = [];

        $subsql = '';

        /* =========================================================
           CUSTOMER FILTER
        ========================================================= */

        if(isset($_REQUEST['_customer']) && !empty($_REQUEST['_customer'])){

            $subsql .= "
                AND customer.customer_name = '".$_REQUEST['_customer']."'
            ";

            $record['filter']['_customer']['value']
                = $_REQUEST['_customer'];

            $record['filter']['_customer']['text']
                = $_REQUEST['_customer'];
        }

        /* =========================================================
           BRANCH FILTER
        ========================================================= */

        if(isset($_REQUEST['_branch']) && !empty($_REQUEST['_branch'])){

            $subsql .= "
                AND branch.branch_name = '".$_REQUEST['_branch']."'
            ";

            $record['filter']['_branch']['value']
                = $_REQUEST['_branch'];

            $record['filter']['_branch']['text']
                = $_REQUEST['_branch'];
        }

        /* =========================================================
           DATE FROM
        ========================================================= */

        if(isset($_GET['_entry_date_from']) && !empty($_GET['_entry_date_from'])){

            $subsql .= "
                AND om.om_em_entry_date >= '"
                .date(
                    'Y-m-d',
                    strtotime($_GET['_entry_date_from'])
                )
                ."'
            ";

            $record['filter']['_entry_date_from']
                = $_REQUEST['_entry_date_from'];

        }else{

            $today = date('d-m-Y');

            $subsql .= "
                AND om.om_em_entry_date >= '".date('Y-m-d')."'
            ";

            $record['filter']['_entry_date_from']
                = $today;
        }

        /* =========================================================
           DATE TO
        ========================================================= */

        if(isset($_GET['_entry_date_to']) && !empty($_GET['_entry_date_to'])){

            $subsql .= "
                AND om.om_em_entry_date <= '"
                .date(
                    'Y-m-d',
                    strtotime($_GET['_entry_date_to'])
                )
                ."'
            ";

            $record['filter']['_entry_date_to']
                = $_REQUEST['_entry_date_to'];

        }else{

            $today = date('d-m-Y');

            $subsql .= "
                AND om.om_em_entry_date <= '".date('Y-m-d')."'
            ";

            $record['filter']['_entry_date_to']
                = $today;
        }

        /* =========================================================
           BRANCH SESSION FILTER
        ========================================================= */

        if($_SESSION['user_id'] != 1){

            $subsql .= "
                AND om.om_branch_id = '"
                .$_SESSION['user_branch_id']
                ."'
            ";
        }

        /* =========================================================
           MAIN PROFIT QUERY
        ========================================================= */

        $query = "

        SELECT

    om.om_id,

    om.om_em_entry_no as entry_no,

    DATE_FORMAT(
        om.om_em_entry_date,
        '%d-%m-%Y'
    ) as entry_date,

    DATE_FORMAT(
        om.om_trial_date,
        '%d-%m-%Y'
    ) as trial_date,

    DATE_FORMAT(
        om.om_delivery_date,
        '%d-%m-%Y'
    ) as delivery_date,

    CONCAT(

        UPPER(customer.customer_name),

        ' - ',

        customer.customer_mobile

    ) as customer_name,

    GROUP_CONCAT(
        DISTINCT UPPER(sku.sku_name)
        SEPARATOR ', '
    ) as sku_name,

    GROUP_CONCAT(
        DISTINCT UPPER(apparel.apparel_name)
        SEPARATOR ', '
    ) as apparel_name,

    UPPER(branch.branch_name)
        as branch_name,

            IFNULL(

                UPPER(ot.ot_trans_type),

                'READYMADE'

            ) as order_type,

            COUNT(ot.ot_id)
                as total_items,

            SUM(
                IFNULL(ot.ot_qty,0)
            ) as total_qty,

            SUM(
                IFNULL(ot.ot_amt,0)
            ) as selling_price,

            SUM(

                (
                    IFNULL(sku.sku_cp,0)
                )

            ) as cost_price,

            (

                SUM(
                    IFNULL(ot.ot_amt,0)
                )

                -

                SUM(

                    (
                        IFNULL(sku.sku_cp,0)
                    )

                )

            ) as profit_amt,

            ROUND(

                (

                    (

                        SUM(
                            IFNULL(ot.ot_amt,0)
                        )

                        -

                        SUM(

                            (
                                IFNULL(sku.sku_cp,0)
                            )

                        )

                    )

                    /

                    NULLIF(

                        SUM(
                            IFNULL(ot.ot_amt,0)
                        ),

                        0

                    )

                ) * 100,

                2

            ) as profit_percent,

            om.om_total_amt,

            om.om_advance_amt,

            om.om_allocated_amt,

            (

                om.om_total_amt

                -

                (

                    om.om_advance_amt
                    +
                    om.om_allocated_amt

                )

            ) as balance_amt

        FROM order_master om

        INNER JOIN order_trans ot
            ON(
                ot.ot_om_id = om.om_id
            )

        INNER JOIN customer_master customer
            ON(
                customer.customer_id = om.om_customer_id
            )

        INNER JOIN branch_master branch
            ON(
                branch.branch_id = om.om_branch_id
            )

        LEFT JOIN sku_master sku
            ON(
                sku.sku_id = ot.ot_sku_id
            )

        LEFT JOIN apparel_master apparel
            ON(
                apparel.apparel_id = ot.ot_apparel_id
            )

        WHERE

            om.om_delete_status = 0

            AND ot.ot_delete_status = 0

            $subsql

        GROUP BY om.om_id

        ORDER BY om.om_em_entry_date DESC

        ";

        // echo "<pre>";
        // print_r($query);
        // exit;

        $record['profit_data']
            = $this->db->query($query)->result_array();

        /* =========================================================
           SUMMARY
        ========================================================= */

        $record['summary'] = [

            'total_sales'      => 0,
            'total_cost'       => 0,
            'gross_profit'     => 0,
            'pending_balance'  => 0,
            'custom_profit'    => 0,
            'readymade_profit' => 0,
            'net_profit'       => 0

        ];

        /* =========================================================
           CALCULATE SUMMARY
        ========================================================= */

        if(!empty($record['profit_data'])){

            foreach($record['profit_data'] as $value){

                $record['summary']['total_sales']
                    += $value['selling_price'];

                $record['summary']['total_cost']
                    += $value['cost_price'];

                $record['summary']['gross_profit']
                    += $value['profit_amt'];

                $record['summary']['pending_balance']
                    += $value['balance_amt'];

                if(
                    strtoupper($value['order_type'])
                    == 'CUSTOM'
                ){

                    $record['summary']['custom_profit']
                        += $value['profit_amt'];

                }else{

                    $record['summary']['readymade_profit']
                        += $value['profit_amt'];
                }
            }
        }

        /* =========================================================
           PAYMENT MODE SUMMARY
        ========================================================= */

        $payment_modes = $this->db->query("

            SELECT

                payment_mode_id,

                UPPER(payment_mode_name)
                    as payment_mode_name

            FROM payment_mode_master

            WHERE payment_mode_status = 1

        ")->result_array();

        $record['payment_summary'] = [];

        if(!empty($payment_modes)){

            foreach($payment_modes as $key => $value){

                $payment_query = "

                    SELECT

                        SUM(
                            IFNULL(opmt.opmt_amt,0)
                        ) as amount

                    FROM order_payment_mode_trans opmt

                    INNER JOIN order_master om
                        ON(
                            om.om_id = opmt.opmt_om_id
                        )

                    INNER JOIN customer_master customer
                        ON(
                            customer.customer_id
                            = om.om_customer_id
                        )

                    INNER JOIN branch_master branch
                        ON(
                            branch.branch_id
                            = om.om_branch_id
                        )

                    WHERE

                        om.om_delete_status = 0

                        AND opmt.opmt_delete_status = 0

                        AND opmt.opmt_payment_mode_id
                            = '".$value['payment_mode_id']."'

                        $subsql

                ";

                $payment_result
                    = $this->db
                    ->query($payment_query)
                    ->row_array();

                $record['payment_summary'][$key]
                    ['payment_mode_name']
                    = $value['payment_mode_name'];

                $record['payment_summary'][$key]
                    ['payment_mode_amt']
                    = !empty($payment_result['amount'])
                    ? (float)$payment_result['amount']
                    : 0;
            }
        }

        /* =========================================================
           CUSTOM DATA
        ========================================================= */

        $record['custom_data']
            = array_filter(

                $record['profit_data'],

                function($row){

                    return strtoupper(
                        $row['order_type']
                    ) == 'CUSTOM';
                }
            );

        /* =========================================================
           READYMADE DATA
        ========================================================= */

        $record['readymade_data']
            = array_filter(

                $record['profit_data'],

                function($row){

                    return strtoupper(
                        $row['order_type']
                    ) != 'CUSTOM';
                }
            );

        /* =========================================================
           GENERAL EXPENSE
        ========================================================= */

        $general_query = "

            SELECT

                SUM(
                    IFNULL(payment_general_amt,0)
                ) as total

            FROM payment_general_master

            WHERE payment_general_delete_status = 0

        ";

        $general_result
            = $this->db
            ->query($general_query)
            ->row_array();

        $general_expense
            = !empty($general_result['total'])
            ? $general_result['total']
            : 0;

        /* =========================================================
           PAYMENT EXPENSE
        ========================================================= */

        $payment_query = "

            SELECT

                SUM(
                    IFNULL(pprt.pprt_adjust_amt,0)
                ) as total

            FROM payment_purchase_readymade_trans pprt

            INNER JOIN payment_master payment
                ON(
                    payment.payment_id
                    = pprt.pprt_payment_id
                )

            WHERE

                pprt.pprt_delete_status = 0

        ";

        $payment_result
            = $this->db
            ->query($payment_query)
            ->row_array();

        $payment_expense
            = !empty($payment_result['total'])
            ? $payment_result['total']
            : 0;

        /* =========================================================
           FINAL NET PROFIT
        ========================================================= */

        $record['summary']['general_expense']
            = $general_expense;

        $record['summary']['payment_expense']
            = $payment_expense;

        $record['summary']['net_profit']
            = (

                $record['summary']['gross_profit']

                -

                $general_expense

                -

                $payment_expense

            );

        return $record;
    }

    /* =========================================================
       CUSTOMER SELECT2
    ========================================================= */

    public function _customer_name(){

        $subsql = '';

        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;

        if(isset($_GET['limit']) && !empty($_GET['limit'])){

            $limit = $_GET['limit'];
        }

        if(isset($_GET['page']) && !empty($_GET['page'])){

            $page = $_GET['page'];

            $offset = $limit * ($page - 1);
        }

        if(isset($_GET['name']) && !empty($_GET['name'])){

            $subsql .= "

                AND customer.customer_name
                LIKE '%".$_GET['name']."%'

            ";
        }

        if($_SESSION['user_id'] != 1){

            $subsql .= "

                AND om.om_branch_id
                = '".$_SESSION['user_branch_id']."'

            ";
        }

        $query = "

            SELECT id,name

            FROM(

                SELECT

                    customer.customer_name as id,

                    CONCAT(

                        UPPER(customer.customer_name),

                        ' - ',

                        customer.customer_mobile

                    ) as name

                FROM order_master om

                INNER JOIN customer_master customer
                    ON(
                        customer.customer_id
                        = om.om_customer_id
                    )

                WHERE om.om_delete_status = 0

                $subsql

            ) temp

            GROUP BY id

            LIMIT $limit

            OFFSET $offset

        ";

        return $this->db
            ->query($query)
            ->result_array();
    }

    /* =========================================================
       BRANCH SELECT2
    ========================================================= */

    public function _branch_name(){

        $subsql = '';

        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;

        if(isset($_GET['limit']) && !empty($_GET['limit'])){

            $limit = $_GET['limit'];
        }

        if(isset($_GET['page']) && !empty($_GET['page'])){

            $page = $_GET['page'];

            $offset = $limit * ($page - 1);
        }

        if(isset($_GET['name']) && !empty($_GET['name'])){

            $subsql .= "

                AND branch.branch_name
                LIKE '%".$_GET['name']."%'

            ";
        }

        $query = "

            SELECT id,name

            FROM(

                SELECT

                    branch.branch_name as id,

                    UPPER(branch.branch_name)
                        as name

                FROM branch_master branch

                WHERE branch.branch_status = 1

                $subsql

            ) temp

            GROUP BY id

            LIMIT $limit

            OFFSET $offset

        ";
        // echo "<pre>"; print_r($query); exit;

        return $this->db
            ->query($query)
            ->result_array();
    }
}