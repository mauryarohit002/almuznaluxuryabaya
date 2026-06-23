<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class order_of_the_day_model extends my_model{ 
    public function __construct(){ parent::__construct('report', 'order_of_the_day'); }
    public function get_record(){ 
        $record     = [];
        $subsql 	= '';
        $subsql1    = '';
        $having 	= ''; 
     
        if(isset($_REQUEST['_entry_date_from'])){  
            if($_REQUEST['_entry_date_from'] != ''){
                $having .=" AND entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }else{
            $having .=" AND entry_date >= '".date('Y-m-d')."'";
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $having .=" AND entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }else{
            $having .=" AND entry_date <= '".date('Y-m-d')."'";
        }
    
        if(isset($_REQUEST['_trans_type'])){
            if($_REQUEST['_trans_type'] != ''){ 
                $subsql .=" AND ot.ot_trans_type = '".$_REQUEST['_trans_type']."'";
                $subsql1 .=" AND et.et_trans_type = '".$_REQUEST['_trans_type']."'";
                $record['filter']['_trans_type'] = $_REQUEST['_trans_type'];
            }
        }   

        $query ="SELECT * FROM (
                        -- FABRIC
                        SELECT 
                            ot.ot_id AS id,    
                            'ORDER' AS module_name, 
                            ot.ot_trans_type AS type, 
                            IF(om.om_status = 0, om.om_em_entry_date, om.om_entry_date) AS entry_date,
                            IFNULL(UPPER(fabric.fabric_name), '') AS apparel_name,
                            '' AS item_code,
                            SUM(ot.ot_qty) AS qty,
                            SUM(ot.ot_rate) AS rate,
                            SUM(ot.ot_amt) AS amt,
                            SUM(ot.ot_disc_amt) AS disc_amt, 
                            SUM(ot.ot_taxable_amt) AS taxable_amt, 
                            SUM(ot.ot_sgst_amt + ot.ot_cgst_amt + ot.ot_igst_amt) AS gst_amt,
                            SUM(ot.ot_total_amt) AS total_amt
                        FROM order_trans ot  
                        INNER JOIN order_master om ON ot.ot_om_id = om.om_id
                        INNER JOIN barcode_master bm ON bm.bm_id = ot.ot_bm_id
                        INNER JOIN fabric_master fabric ON fabric.fabric_id = bm.bm_fabric_id
                        WHERE om.om_delete_status = 0
                          AND ot.ot_delete_status = 0
                          AND ot.ot_bm_id > 0
                          AND ot.ot_ot_id = 0 
                          AND ot.ot_trans_type = 'FABRIC'
                          $subsql
                        GROUP BY om.om_em_entry_date,om.om_entry_date

                        UNION ALL

                        -- STITCHING
                        SELECT 
                            ot.ot_id AS id,    
                            'ORDER' AS module_name, 
                            ot.ot_trans_type AS type, 
                            IF(om.om_status = 0, om.om_em_entry_date, om.om_entry_date) AS entry_date,
                            IFNULL(UPPER(apparel.apparel_name), '') AS apparel_name,
                            '' AS item_code,
                            SUM(ot.ot_qty) AS qty,
                            SUM(ot.ot_rate) AS rate,
                            SUM(ot.ot_amt) AS amt,
                            SUM(ot.ot_disc_amt) AS disc_amt, 
                            SUM(ot.ot_taxable_amt) AS taxable_amt, 
                            SUM(ot.ot_sgst_amt + ot.ot_cgst_amt + ot.ot_igst_amt) AS gst_amt,
                            SUM(ot.ot_total_amt) AS total_amt
                        FROM order_trans ot  
                        INNER JOIN order_master om ON ot.ot_om_id = om.om_id
                        INNER JOIN apparel_master apparel ON apparel.apparel_id = ot.ot_apparel_id
                        WHERE om.om_delete_status = 0
                          AND ot.ot_delete_status = 0
                          AND ot.ot_apparel_id > 0 
                          AND ot.ot_ot_id = 0
                          AND ot.ot_trans_type = 'STITCHING'
                          $subsql
                        GROUP BY om.om_em_entry_date,om.om_entry_date

                        UNION ALL

                        -- PACKAGE
                        SELECT 
                            ot.ot_id AS id,    
                            'ORDER' AS module_name, 
                            ot.ot_trans_type AS type, 
                            IF(om.om_status = 0, om.om_em_entry_date, om.om_entry_date) AS entry_date,
                            IFNULL(UPPER(apparel.apparel_name), '') AS apparel_name,
                            '' AS item_code,
                            SUM(ot.ot_qty) AS qty,
                            SUM(ot.ot_rate) AS rate,
                            SUM(ot.ot_amt) AS amt,
                            SUM(ot.ot_disc_amt) AS disc_amt, 
                            SUM(ot.ot_taxable_amt) AS taxable_amt, 
                            SUM(ot.ot_sgst_amt + ot.ot_cgst_amt + ot.ot_igst_amt) AS gst_amt,
                            SUM(ot.ot_total_amt) AS total_amt
                        FROM order_trans ot  
                        INNER JOIN order_master om ON ot.ot_om_id = om.om_id
                        INNER JOIN barcode_master bm ON bm.bm_id = ot.ot_bm_id
                        INNER JOIN fabric_master fabric ON fabric.fabric_id = bm.bm_fabric_id
                        INNER JOIN apparel_master apparel ON apparel.apparel_id = ot.ot_apparel_id
                        WHERE om.om_delete_status = 0
                          AND ot.ot_delete_status = 0
                          AND ot.ot_bm_id > 0 
                          AND ot.ot_ot_id = 0
                          AND ot.ot_trans_type = 'PACKAGE'
                          $subsql
                        GROUP BY om.om_em_entry_date,om.om_entry_date

                        UNION ALL

                        -- OTHER
                        SELECT 
                            ot.ot_id AS id,    
                            'ORDER' AS module_name, 
                            ot.ot_trans_type AS type, 
                            IF(om.om_status = 0, om.om_em_entry_date, om.om_entry_date) AS entry_date,
                            IFNULL(UPPER(product.product_name), '') AS apparel_name,
                            '' AS item_code,
                            SUM(ot.ot_qty) AS qty,
                            SUM(ot.ot_rate) AS rate,
                            SUM(ot.ot_amt) AS amt,
                            SUM(ot.ot_disc_amt) AS disc_amt, 
                            SUM(ot.ot_taxable_amt) AS taxable_amt, 
                            SUM(ot.ot_sgst_amt + ot.ot_cgst_amt + ot.ot_igst_amt) AS gst_amt,
                            SUM(ot.ot_total_amt) AS total_amt
                        FROM order_trans ot  
                        INNER JOIN order_master om ON ot.ot_om_id = om.om_id
                        INNER JOIN barcode_readymade_master brmm ON brmm.brmm_id = ot.ot_brmm_id
                        INNER JOIN product_master product ON product.product_id = brmm.brmm_product_id
                        LEFT JOIN apparel_master apparel ON apparel.apparel_id = ot.ot_apparel_id
                        WHERE om.om_delete_status = 0
                          AND ot.ot_delete_status = 0
                          AND ot.ot_brmm_id > 0 
                          AND ot.ot_ot_id = 0
                          AND ot.ot_trans_type = 'OTHER'
                          $subsql
                        GROUP BY om.om_em_entry_date,om.om_entry_date
                    ) AS result
                    HAVING 1 $having";
     
        $data = $this->db->query($query)->result_array();
        // echo "<pre>"; print_r($query); exit();
        // echo "<pre>"; print_r($data); exit();
        
        $record['totals']['rows']           = count($data);
        $record['totals']['qty']            = 0;
        $record['totals']['amt']            = 0;
        $record['totals']['rate']           = 0;
        $record['totals']['amt']            = 0;
        $record['totals']['disc_amt']       = 0;
        $record['totals']['taxable_amt']    = 0;
        $record['totals']['gst_amt']        = 0;
        $record['totals']['total_amt']      = 0;
        $record['data']                     = [];
        if(!empty($data)){
            foreach ($data as $key => $value) { 
                array_push($record['data'], [
                                                'module_name'   => $value['module_name'],
                                                'type'          => $value['type'],
                                                'apparel_name'  => $value['apparel_name'],
                                                'qty'           => round($value['qty'],2),
                                                'rate' 		    => (float)$value['rate'],
                                                'amt' 		    => (float)$value['amt'],
                                                'disc_amt' 		=> (float)$value['disc_amt'],
                                                'taxable_amt' 	=> (float)$value['taxable_amt'],
                                                'gst_amt' 	    => (float)$value['gst_amt'],
                                                'total_amt'     => (float)$value['total_amt'],
                                               
                                            ]);

                $record['totals']['qty'] 	    = $record['totals']['qty'] 		+ $value['qty'];
                $record['totals']['rate'] 	        = $record['totals']['rate'] 	+ $value['rate'];
                 $record['totals']['amt']           = $record['totals']['amt']      + $value['amt'];
                $record['totals']['disc_amt'] 	    = $record['totals']['disc_amt'] + $value['disc_amt'];
                $record['totals']['taxable_amt'] 	= $record['totals']['taxable_amt'] 		+ $value['taxable_amt'];
                $record['totals']['gst_amt'] 	    = $record['totals']['gst_amt'] 		+ $value['gst_amt'];
                $record['totals']['total_amt'] 	= $record['totals']['total_amt'] 		+ $value['total_amt'];
            }
        }
        
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }

    public function _module_name(){
        return [0 => ['id' => 'ESTIMATE', 'name' => 'ESTIMATE'], 1 => ['id' => 'ORDER', 'name' => 'ORDER']];
    } 

}
?>