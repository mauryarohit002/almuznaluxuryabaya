<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class stock_movement_model extends my_model{
    public function __construct(){ parent::__construct('report', 'stock_movement'); }
    public function get_record(){
            $record = [];
            $record['filter'] = [];
            $record['data'] = [];
            
            // Date handling
            $date_from = date('Y-m-01');
            $date_to = date('Y-m-d');
            
            if(isset($_REQUEST['_entry_date_from']) && !empty($_REQUEST['_entry_date_from'])){
                $date_from = date('Y-m-d', strtotime($_REQUEST['_entry_date_from']));
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
            if(isset($_REQUEST['_entry_date_to']) && !empty($_REQUEST['_entry_date_to'])){
                $date_to = date('Y-m-d', strtotime($_REQUEST['_entry_date_to']));
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
            
            if(isset($_GET['_branch_name']) && !empty($_GET['_branch_name'])){
                $record['filter']['_branch_name']['text'] = $_GET['_branch_name'];
                $record['filter']['_branch_name']['value'] = $_GET['_branch_name'];
            }else{
                $record['filter']['_branch_name']['text'] = $_SESSION['user_branch'];
                $record['filter']['_branch_name']['value'] = $_SESSION['user_branch'];
            }
            
            $day_before_start = $date_from;
         
            $purchase_before = $this->get_purchase_data($day_before_start, '', true);
            $inward_before = $this->get_inward_data($day_before_start, '', true);
            $sreturn_before = $this->get_sreturn_data($day_before_start, '', true);
            $sales_before = $this->get_sales_data($day_before_start, '', true);
            $outward_before = $this->get_outward_data($day_before_start, '', true);
            $preturn_before = $this->get_preturn_data($day_before_start, '', true);
            
           // echo "purchase:";echo " <pre>"; print_r($purchase_before);
           // echo "inward:"; echo "<pre>"; print_r($inward_before);
           // echo "sale return:"; echo "<pre>"; print_r($sreturn_before);
           // echo "sale:"; echo "<pre>"; print_r($sales_before);
           // echo "outward:"; echo "<pre>"; print_r($outward_before);
           // echo "pur return:";  echo "<pre>"; print_r($preturn_before);


            $opening_in_qty = $purchase_before['qty'] + $inward_before['qty'] + $sreturn_before['qty'];
            $opening_out_qty = $sales_before['qty'] + $outward_before['qty'] + $preturn_before['qty'];
            $initial_open_qty = $opening_in_qty - $opening_out_qty;
          
            $temp_date = $date_from;
            while(strtotime($temp_date) <= strtotime($date_to)){
                $month_end = date('Y-m-t', strtotime($temp_date));
                if(strtotime($month_end) > strtotime($date_to)){
                    $month_end = $date_to;
                }
                
                $record['data'][] = [
                    'start_date' => $temp_date,
                    'end_date' => $month_end,
                    'month' => date('M-Y', strtotime($temp_date))
                ];
                
                $temp_date = date('Y-m-d', strtotime('+1 day', strtotime($month_end)));
            }
            $carry_open_qty = $initial_open_qty;
            $totals = [
                'pur_qty' => 0,
                'pur_return_qty' => 0,
                'outward_qty' => 0,
                'inward_qty' => 0,
                'sale_qty' => 0,
                'rows' => count($record['data'])
            ];
            
            foreach ($record['data'] as $key => $value){
                $purchase_data = $this->get_purchase_data($value['start_date'], $value['end_date'], false);
                $inward_data = $this->get_inward_data($value['start_date'], $value['end_date'], false);
                $sreturn_data = $this->get_sreturn_data($value['start_date'], $value['end_date'], false);
                $sales_data = $this->get_sales_data($value['start_date'], $value['end_date'], false);
                $outward_data = $this->get_outward_data($value['start_date'], $value['end_date'], false);
                $preturn_data = $this->get_preturn_data($value['start_date'], $value['end_date'], false);
                $in_qty=$purchase_data['qty'] + $inward_data['qty'] + $sreturn_data['qty'];
                $out_qty=$sales_data['qty'] + $outward_data['qty'] + $preturn_data['qty'];
                $open_qty = $carry_open_qty;
                $close_qty = $open_qty + $in_qty - $out_qty;

                $record['data'][$key]['open_qty'] = $open_qty;
                $record['data'][$key]['pur_qty'] = $purchase_data['qty'];
                $record['data'][$key]['pur_return_qty'] = $preturn_data['qty'];
                $record['data'][$key]['outward_qty'] = $outward_data['qty'];
                $record['data'][$key]['inward_qty'] = $inward_data['qty'];
                $record['data'][$key]['sale_qty'] = $sales_data['qty'];
                $record['data'][$key]['close_qty'] = $close_qty;
                
                $totals['pur_qty'] += $purchase_data['qty'];
                $totals['pur_return_qty'] += $preturn_data['qty'];
                $totals['outward_qty'] += $outward_data['qty'];
                $totals['inward_qty'] += $inward_data['qty'];
                $totals['sale_qty'] += $sales_data['qty'];
                $carry_open_qty = $close_qty;
            }
            
            $record['totals'] = $totals;
            return $record;
    }

    public function get_purchase_data($start_date, $end_date, $open = false){
        $subsql = "";
        if(isset($_GET['_branch_name']) && !empty($_GET['_branch_name'])){
            $subsql .= " AND branch.branch_name = '".$_GET['_branch_name']."'";
            $record['filter']['_branch_name']['text'] = $_GET['_branch_name'];
            $record['filter']['_branch_name']['value'] = $_GET['_branch_name'];
        }else{
            $subsql .= " AND branch.branch_name = '".$_SESSION['user_branch']."'";
            $record['filter']['_branch_name']['text'] = $_SESSION['user_branch'];
            $record['filter']['_branch_name']['value'] = $_SESSION['user_branch'];  
        }
       
        if($open){
            if(!empty($start_date)){
                $subsql .= " AND prmm.prmm_entry_date < '".$start_date."'";
            }
        }else{
            if(!empty($start_date)){
                $subsql .= " AND prmm.prmm_entry_date >= '".$start_date."'";
            }
            if(!empty($end_date)){
                $subsql .= " AND prmm.prmm_entry_date <= '".$end_date."'";
            }
        }
        $query  ="
                    SELECT SUM(prmt.prmt_qty) as qty, ROUND(SUM(prmt.prmt_qty * prmt.prmt_rate)) as amt
                    FROM purchase_readymade_master prmm
                    INNER JOIN supplier_master supplier ON(supplier.supplier_id = prmm.prmm_supplier_id)
                    INNER JOIN branch_master branch ON(branch.branch_id = prmm.prmm_branch_id)
                    INNER JOIN purchase_readymade_trans prmt ON(prmt.prmt_prmm_id = prmm.prmm_id)
                    WHERE prmm.prmm_delete_status = 0
                    AND prmt.prmt_delete_status = 0
                    $subsql
                ";
        // echo "<pre>"; print_r($query); exit();
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($data); exit();
        if(!empty($data)){
            return ['qty' => !empty($data[0]['qty']) ? $data[0]['qty'] : 0, 'amt' => !empty($data[0]['amt']) ? $data[0]['amt'] : 0];
        }
        return ['qty' => 0, 'amt' => 0];
    }
   
    public function get_sreturn_data($start_date, $end_date, $open = false){
        return ['qty' => 0, 'amt' => 0];
    }
    public function get_sales_data($start_date, $end_date, $open = false){
        $subsql = "";
        if(isset($_GET['_branch_name']) && !empty($_GET['_branch_name'])){
            $subsql .= " AND branch.branch_name = '".$_GET['_branch_name']."'";
            $record['filter']['_branch_name']['text'] = $_GET['_branch_name'];
            $record['filter']['_branch_name']['value'] = $_GET['_branch_name'];
        }else{
            $subsql .= " AND branch.branch_name = '".$_SESSION['user_branch']."'";
            $record['filter']['_branch_name']['text'] = $_SESSION['user_branch'];
            $record['filter']['_branch_name']['value'] = $_SESSION['user_branch'];  
        }
      
        if($open){
            if(!empty($start_date)){
                $subsql .= " AND om.om_em_entry_date < '".$start_date."'";
            }
        }else{
            if(!empty($start_date)){
                $subsql .= " AND om.om_em_entry_date >= '".$start_date."'";
            }
            if(!empty($end_date)){
                $subsql .= " AND om.om_em_entry_date <= '".$end_date."'";
            }
        }
        $query  ="
                    SELECT SUM(ot.ot_qty) as qty, ROUND(SUM(ot.ot_qty * brmm.brmm_prmt_rate)) as amt
                    FROM order_master om
                    INNER JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
                    INNER JOIN order_trans ot ON(ot.ot_om_id = om.om_id)
                    INNER JOIN barcode_readymade_master brmm ON(brmm.brmm_id = ot.ot_brmm_id)
                    WHERE om.om_delete_status = 0
                    AND ot.ot_delete_status = 0
                    AND brmm.brmm_delete_status = 0
                    $subsql
                ";
        // echo "<pre>"; print_r($query); exit();
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($data); exit();
        if(!empty($data)){
            return ['qty' => !empty($data[0]['qty']) ? $data[0]['qty'] : 0, 'amt' => !empty($data[0]['amt']) ? $data[0]['amt'] : 0];
        }
        return ['qty' => 0, 'amt' => 0];
    }
    
    public function get_preturn_data($start_date, $end_date, $open = false){
        $subsql = "";
        if(isset($_GET['_branch_name']) && !empty($_GET['_branch_name'])){
            $subsql .= " AND branch.branch_name = '".$_GET['_branch_name']."'";
            $record['filter']['_branch_name']['text'] = $_GET['_branch_name'];
            $record['filter']['_branch_name']['value'] = $_GET['_branch_name'];
        }else{
            $subsql .= " AND branch.branch_name = '".$_SESSION['user_branch']."'";
            $record['filter']['_branch_name']['text'] = $_SESSION['user_branch'];
            $record['filter']['_branch_name']['value'] = $_SESSION['user_branch'];  
        }
       
        if($open){
            if(!empty($start_date)){
                $subsql .= " AND prrm.prrm_entry_date < '".$start_date."'";
            }
        }else{
            if(!empty($start_date)){
                $subsql .= " AND prrm.prrm_entry_date >= '".$start_date."'";
            }
            if(!empty($end_date)){
                $subsql .= " AND prrm.prrm_entry_date <= '".$end_date."'";
            }
        }
        $query  ="
                    SELECT SUM(prrt.prrt_qty) as qty, ROUND(SUM(prrt.prrt_qty * brmm.brmm_prmt_rate)) as amt
                    FROM purchase_readymade_return_master prrm
                    INNER JOIN branch_master branch ON(branch.branch_id = prrm.prrm_branch_id)
                    INNER JOIN purchase_readymade_return_trans prrt ON(prrt.prrt_prrm_id = prrm.prrm_id)
                    INNER JOIN barcode_readymade_master brmm ON(brmm.brmm_id = prrt.prrt_brmm_id)
                    WHERE prrm.prrm_delete_status = 0
                    AND prrt.prrt_delete_status = 0
                    AND brmm.brmm_delete_status = 0
                    $subsql
                ";
        // echo "<pre>"; print_r($query); exit();
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($data); exit();
        if(!empty($data)){
            return ['qty' => !empty($data[0]['qty']) ? $data[0]['qty'] : 0, 'amt' => !empty($data[0]['amt']) ? $data[0]['amt'] : 0];
        }
        return ['qty' => 0, 'amt' => 0];
    }

    public function get_inward_data($start_date, $end_date, $open = false){
        $subsql = "";
        if(isset($_GET['_branch_name']) && !empty($_GET['_branch_name'])){
            $subsql .= " AND branch.branch_name = '".$_GET['_branch_name']."'";
            $record['filter']['_branch_name']['text'] = $_GET['_branch_name'];
            $record['filter']['_branch_name']['value'] = $_GET['_branch_name'];
        }else{
            $subsql .= " AND branch.branch_name = '".$_SESSION['user_branch']."'";
            $record['filter']['_branch_name']['text'] = $_SESSION['user_branch'];
            $record['filter']['_branch_name']['value'] = $_SESSION['user_branch'];  
        }
        
        if($open){
            if(!empty($start_date)){
                $subsql .= " AND gm.gm_entry_date < '".$start_date."'";
            }
        }else{
            if(!empty($start_date)){
                $subsql .= " AND gm.gm_entry_date >= '".$start_date."'";
            }
            if(!empty($end_date)){
                $subsql .= " AND gm.gm_entry_date <= '".$end_date."'";
            }
        }
        $query  ="
                    SELECT SUM(gt.gt_qty) as qty, ROUND(SUM(gt.gt_qty * brmm.brmm_prmt_rate)) as amt
                    FROM grn_master gm
                    INNER JOIN branch_master branch ON(branch.branch_id = gm.gm_branch_id)
                    INNER JOIN grn_trans gt ON(gt.gt_gm_id = gm.gm_id)
                    INNER JOIN barcode_readymade_master brmm ON(brmm.brmm_id = gt.gt_brmm_id)
                    WHERE brmm.brmm_delete_status = 0
                    $subsql
                ";
        // echo "<pre>"; print_r($query); exit();
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($data); exit();
        if(!empty($data)){
            return ['qty' => !empty($data[0]['qty']) ? $data[0]['qty'] : 0, 'amt' => !empty($data[0]['amt']) ? $data[0]['amt'] : 0];
        }
        return ['qty' => 0, 'amt' => 0];
    }

    public function get_outward_data($start_date, $end_date, $open = false){
        $subsql = "";
        if(isset($_GET['_branch_name']) && !empty($_GET['_branch_name'])){
            $subsql .= " AND branch.branch_name = '".$_GET['_branch_name']."'";
            $record['filter']['_branch_name']['text'] = $_GET['_branch_name'];
            $record['filter']['_branch_name']['value'] = $_GET['_branch_name'];
        }else{
            $subsql .= " AND branch.branch_name = '".$_SESSION['user_branch']."'";
            $record['filter']['_branch_name']['text'] = $_SESSION['user_branch'];
            $record['filter']['_branch_name']['value'] = $_SESSION['user_branch'];  
        }
        
        if($open){
            if(!empty($start_date)){
                $subsql .= " AND om.om_entry_date < '".$start_date."'";
            }
        }else{
            if(!empty($start_date)){
                $subsql .= " AND om.om_entry_date >= '".$start_date."'";
            }
            if(!empty($end_date)){
                $subsql .= " AND om.om_entry_date <= '".$end_date."'";
            }
        }
        
        $query  ="
                    SELECT SUM(ot.ot_qty) as qty, ROUND(SUM(ot.ot_qty * brmm.brmm_prmt_rate)) as amt
                    FROM outward_master om
                    INNER JOIN branch_master branch ON(branch.branch_id = om.om_branch_id)
                    INNER JOIN outward_trans ot ON(ot.ot_om_id = om.om_id)
                    INNER JOIN barcode_readymade_master brmm ON(brmm.brmm_id = ot.ot_brmm_id)
                    WHERE brmm.brmm_delete_status = 0
                    $subsql
                ";
        // echo "<pre>"; print_r($query); exit();
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($data); exit();
        if(!empty($data)){
            return ['qty' => !empty($data[0]['qty']) ? $data[0]['qty'] : 0, 'amt' => !empty($data[0]['amt']) ? $data[0]['amt'] : 0];
        }
        return ['qty' => 0, 'amt' => 0];
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
            $subsql .= " AND (branch.branch_name LIKE '".$name."%') ";
        } 
        $query="SELECT branch.branch_name as id, 
                UPPER(branch.branch_name) as name
                FROM branch_master branch
                WHERE 1
                $subsql 
                GROUP BY branch.branch_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>