<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class groupwise_stock_model extends my_model{
    public function __construct(){ parent::__construct('report', 'groupwise_stock'); }
    public function get_record(){
        $record     = [];
        $subsql     = ''; 
        $subsql1     = ''; 
        $having     = '';
        if(isset($_REQUEST['_category_name']) && !empty($_REQUEST['_category_name'])){
            $subsql .=" AND category.category_name = '".$_REQUEST['_category_name']."'";
            $subsql1 .=" AND readymade_category.readymade_category_name = '".$_REQUEST['_category_name']."'";
            $record['filter']['_category_name']['value'] = $_REQUEST['_category_name'];
            $record['filter']['_category_name']['text']  = $_REQUEST['_category_name'];
        } 

       $query1 = "SELECT 
                UPPER(category.category_name) AS category_name,  
                SUM(bm.bm_mrp) AS mrp,
                SUM(bm.bm_pt_mtr) AS pt_mtr,
                SUM(bm.bm_prt_mtr) AS prt_mtr,
                SUM(bm.bm_ot_mtr) AS ot_mtr,
                SUM((bm.bm_pt_mtr - bm.bm_prt_mtr) - bm.bm_ot_mtr) AS bal_mtr,
                SUM((bm.bm_pt_mtr - bm.bm_prt_mtr) * bm.bm_pt_rate) AS bal_amt,
                SUM(bm.bm_mrp * (bm.bm_pt_mtr - bm.bm_ot_mtr)) AS bal_mrp,
                MAX(bm.bm_created_at) AS created_at
            FROM barcode_master bm
            INNER JOIN category_master category ON category.category_id = bm.bm_category_id
            WHERE bm.bm_delete_status = 0
            $subsql
            GROUP BY bm.bm_category_id";

            $query2 = "SELECT 
                IFNULL(UPPER(readymade_category.readymade_category_name), '') AS category_name, 
                SUM(brmm.brmm_mrp) AS mrp,
                SUM(brmm.brmm_prmt_qty) AS pt_mtr,
                SUM(brmm.brmm_prrt_qty) AS prt_mtr,
                SUM(brmm.brmm_ot_qty) AS ot_mtr,
                SUM((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) - brmm.brmm_ot_qty) AS bal_mtr,
                SUM((brmm.brmm_prmt_qty - brmm.brmm_prrt_qty) * brmm.brmm_prmt_rate) AS bal_amt,
                SUM(brmm.brmm_mrp * (brmm.brmm_prmt_qty - brmm.brmm_ot_qty)) AS bal_mrp,
                MAX(brmm.brmm_created_at) AS created_at
            FROM barcode_readymade_master brmm
            INNER JOIN readymade_category_master readymade_category 
                ON readymade_category.readymade_category_id = brmm.brmm_readymade_category_id
            WHERE brmm.brmm_delete_status = 0
            $subsql1
            GROUP BY brmm.brmm_readymade_category_id";

            $query = "SELECT 
                merged.category_name,
                merged.mrp,
                merged.pt_mtr,
                merged.prt_mtr,
                merged.ot_mtr,
                merged.bal_mtr,
                merged.bal_amt,
                merged.bal_mrp,
                merged.created_at
            FROM (
                $query1
                UNION ALL
                $query2
            ) AS merged
            ORDER BY merged.created_at DESC";
                        
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
                                                'category_name'   => $value['category_name'],
                                                'pt_mtr'        => (float)$value['pt_mtr'],
                                                'prt_mtr'        => (float)$value['prt_mtr'],
                                                'ot_mtr'        => (float)$value['ot_mtr'],
                                                'mrp'           => (float)$value['mrp'],
                                                'bal_mtr'       => (float)$value['bal_mtr'],
                                                'bal_amt'       => (float)$value['bal_amt'],
                                                'bal_mrp'       => (float)$value['bal_mrp'],
                                            ]);

                $record['totals']['mrp']        = $record['totals']['mrp']      + $value['mrp'];
                $record['totals']['pt_mtr']     = $record['totals']['pt_mtr']       + $value['pt_mtr'];
                 $record['totals']['prt_mtr']     = $record['totals']['prt_mtr']       + $value['prt_mtr'];
                $record['totals']['ot_mtr']     = $record['totals']['ot_mtr']       + $value['ot_mtr'];
                $record['totals']['bal_mtr']    = $record['totals']['bal_mtr']      + $value['bal_mtr'];
                $record['totals']['bal_amt']    = $record['totals']['bal_amt']      + $value['bal_amt'];
                $record['totals']['bal_mrp']    = $record['totals']['bal_mrp']      + $value['bal_mrp'];

            }
        }
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    public function _category_name(){
        $having = '';
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
            $having.= " AND (name LIKE '%".$name."%') ";
        }

        $query="
            SELECT id, name
                FROM (
                    SELECT category.category_name as id, UPPER(category.category_name) as name
                    FROM barcode_master bm
                    INNER JOIN category_master category ON(category.category_id = bm.bm_category_id)
                    WHERE bm.bm_delete_status = 0
                    UNION ALL
                        SELECT readymade_category.readymade_category_name as id , UPPER(readymade_category.readymade_category_name) as name 
                        FROM barcode_readymade_master brmm
                        INNER JOIN readymade_category_master readymade_category ON(readymade_category.readymade_category_id = brmm.brmm_readymade_category_id)
                        WHERE brmm.brmm_delete_status = 0
                       
                    ) temp
                WHERE 1
                GROUP BY id ASC
                HAVING 1
                $having
                LIMIT $limit
                OFFSET $offset";
         // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
   
   
}
?>