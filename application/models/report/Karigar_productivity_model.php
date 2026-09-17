<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class karigar_productivity_model extends my_model{ 
    public function __construct(){ parent::__construct('report', 'karigar_productivity'); }
    public function get_record(){
        $record     = [];
        $subsql 	= '';
        $subsql1 	= '';
        $having 	= ''; 
        
        if(isset($_REQUEST['_karigar_name']) && !empty($_REQUEST['_karigar_name'])){
            $subsql .=" AND karigar.karigar_name = '".$_REQUEST['_karigar_name']."'";
            $record['filter']['_karigar_name']['value'] = $_REQUEST['_karigar_name'];
            $record['filter']['_karigar_name']['text']  = $_REQUEST['_karigar_name'];
        }
       
        if(isset($_REQUEST['_entry_date_from'])){
            if($_REQUEST['_entry_date_from'] != ''){
                $subsql .=" AND jrm.jrm_entry_date >= '".$_REQUEST['_entry_date_from']."'";
                $record['filter']['_entry_date_from'] = $_REQUEST['_entry_date_from'];
            }
        }
        if(isset($_REQUEST['_entry_date_to'])){
            if($_REQUEST['_entry_date_to'] != ''){
                $subsql .=" AND jrm.jrm_entry_date <= '".$_REQUEST['_entry_date_to']."'";
                $record['filter']['_entry_date_to'] = $_REQUEST['_entry_date_to'];
            }
        }
       
        $query="SELECT 
            UPPER(karigar.karigar_name) as karigar_name,
                UPPER(apparel.apparel_name) as apparel_name,
                jrm.jrm_entry_date,
                count(jrt.jrt_id) as completed_qty
                FROM job_receive_master jrm
                INNER JOIN job_receive_trans jrt ON(jrt.jrt_jrm_id=jrm.jrm_id)
                INNER JOIN job_issue_master jim ON(jim.jim_id = jrt.jrt_jim_id)
                INNER JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                INNER JOIN order_barcode_trans obt ON(obt.obt_id = jrt.jrt_obt_id)
                INNER JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                WHERE jrm.jrm_delete_status = 0 
                AND jrt.jrt_delete_status = 0
                $subsql
                GROUP BY karigar.karigar_name, apparel.apparel_name
                ORDER BY karigar_name, completed_qty DESC
                 ";
        $results = $this->db->query($query)->result_array();

        $record['data'] = $this->formatProductivityData($results);
        // echo "<pre>"; print_r($record);die;
        // echo "<pre>"; print_r($record); exit();
        return $record;
    }
    
    private function formatProductivityData($results) {
        $formattedData = [];
        foreach ($results as $row) {
            $karigarName = $row['karigar_name'];
            $apparelName = $row['apparel_name'];
            $quantity = (int)$row['completed_qty'];
            if (!isset($formattedData[$karigarName])) {
                $formattedData[$karigarName] = [
                    'karigar_name' => $karigarName,
                    'apparel_data' => [],
                    'total_quantity' => 0,  // Add total field
                    'apparel_count' => 0,    // Count of different apparels
                    'average_per_apparel' => 0 // Average quantity per apparel
                ];
            }
            $formattedData[$karigarName]['apparel_data'][$apparelName] = $quantity;
        }
        foreach ($formattedData as &$karigar) {
            $total = array_sum($karigar['apparel_data']);
            $karigar['total_quantity'] = $total;
            $karigar['apparel_count'] = count($karigar['apparel_data']);
            $karigar['average_per_apparel'] = $karigar['apparel_count'] > 0 
                ? round($total / $karigar['apparel_count'], 2) 
                : 0;
            arsort($karigar['apparel_data']);
        }
        usort($formattedData, function($a, $b) {
            return $b['total_quantity'] - $a['total_quantity'];
        });
        
        return array_values($formattedData);
    }

    public function _entry_no(){
        $subsql = '';
        $subsql1= '';
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
            $subsql .= " AND (om.om_entry_no LIKE '%".$name."%') ";
            $subsql1.= " AND (em.em_entry_no LIKE '%".$name."%') ";
        }
        $query="SELECT id, name
                FROM (
                        SELECT om.om_entry_no as id , UPPER(om.om_entry_no) as name 
                        FROM order_master om 
                        WHERE om.om_delete_status = 0
                        $subsql
                        UNION
                        SELECT em.em_entry_no as id , UPPER(em.em_entry_no) as name 
                        FROM estimate_master em 
                        WHERE em.em_delete_status = 0
                        $subsql1
                    ) temp
                WHERE 1
                GROUP BY id ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    public function _karigar_name(){
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
            $subsql .= " AND (karigar.karigar_name LIKE '%".$name."%') ";
        }
        $query="SELECT karigar.karigar_name as id, karigar.karigar_name as name
                FROM karigar_master karigar
                WHERE 1
                $subsql
                GROUP BY karigar.karigar_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
    
}
?>