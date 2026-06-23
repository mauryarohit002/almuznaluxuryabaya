<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class order_model extends my_model{
    public function __construct(){ parent::__construct(); }

        public function get_max_issue_entry_no($fin_year){  
            $query="SELECT jim_entry_no as max_no
                    FROM job_issue_master
                    WHERE jim_delete_status = 0
                    AND jim_fin_year ='".$fin_year."'
                    ORDER BY jim_entry_no DESC
                    LIMIT 1";     
            $data = $this->db->query($query)->result_array();
            return empty($data) ? 1 : $data[0]['max_no']+1;
        } 

        public function get_max_receive_entry_no($fin_year){ 
            $query="SELECT jrm_entry_no as max_no
                    FROM job_receive_master
                    WHERE jrm_delete_status = 0
                    AND jrm_fin_year ='".$fin_year."'
                    ORDER BY jrm_entry_no DESC
                    LIMIT 1";
            $data = $this->db->query($query)->result_array();
            return empty($data) ? 1 : $data[0]['max_no']+1;
        }

        public function get_barcode_data($barcode){  
            $subsql = '';
            $subsql .= " AND obt.obt_item_code = '".$barcode."'";
            $query="SELECT 
                obt.*,
                om.om_em_entry_no as entry_no,
                obt.obt_apparel_id  as apparel_id,
                UPPER(apparel.apparel_name)  as apparel_name
                FROM order_barcode_trans obt
                INNER JOIN order_master om ON(om.om_id = obt.obt_om_id)
                INNER JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)
                INNER JOIN order_trans ot ON(ot.ot_id = obt.obt_ot_id) 
                LEFT JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                WHERE obt.obt_delete_status=0 AND (obt.obt_apparel_id > 0)  $subsql";
            // echo "<pre>"; print_r($query); exit;
            return $this->db->query($query)->result_array();
        }

        public function get_issue_barcode_data($id){        
            $query="SELECT 0 as jrt_id,
                    obt.*,
                    jim.*,
                    jit.jit_id,
                    IFNULL(jrt.jrt_jit_id, 0) as jrt_jit_id, 
                    UPPER(apparel.apparel_name)  as apparel_name,
                    UPPER(customer.customer_name) as customer_name,
                    om.om_em_entry_no as entry_no,
                    DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as entry_date,
                    UPPER(proces.proces_name) as proces_name,
                    UPPER(karigar.karigar_name) as karigar_name
                    FROM job_issue_master jim
                    INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                    LEFT JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                    LEFT JOIN order_master om ON(om.om_id = obt.obt_om_id)
                    LEFT JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)  
                    LEFT JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                    LEFT JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                    LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                    LEFT JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                    WHERE jim.jim_delete_status = 0
                    AND jit.jit_delete_status = 0
                    AND IFNULL(jrt.jrt_jit_id, 0)=0
                    AND obt.obt_id = $id
                    ORDER BY jit.jit_id DESC LIMIT 1
                    ";
            return $this->db->query($query)->result_array();
        }

        public function get_latest_issue_data($id){    
            $query="SELECT jim.*,
                    IFNULL(jrt.jrt_id, 0) as jrt_id,
                    IFNULL(jit.jit_id, 0) as jit_id,
                    jim.jim_proces_id as proces_id,
                    UPPER(proces.proces_name) as proces_name
                    FROM job_issue_master jim 
                    INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                    LEFT JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                    LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                    WHERE jim.jim_delete_status = 0
                    AND jit.jit_delete_status = 0
                    AND jit.jit_obt_id = $id
                    ORDER BY jit.jit_id DESC
                    LIMIT 1";
            return $this->db->query($query)->result_array();
        }
        public function get_process_wise_data($id, $process_id){
            $query="SELECT jim.*,
                    IFNULL(jrt.jrt_id, 0) as jrt_id,
                    jim.jim_proces_id as proces_id,
                    UPPER(proces.proces_name) as proces_name
                    FROM job_issue_master jim
                    INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                    INNER JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                    LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                    WHERE jim.jim_delete_status = 0
                    AND jit.jit_delete_status = 0
                    AND jit.jit_obt_id = $id
                    AND jim.jim_proces_id = $process_id
                    ORDER BY jit.jit_id DESC
                    LIMIT 1";
            return $this->db->query($query)->result_array();
        }
        public function get_receive_barcode_data($id){          
            $query="SELECT 0 as jrt_id,
                    obt.*,
                    jim.*,
                    jit.jit_id,
                    IFNULL(jrt.jrt_jit_id, 0) as jrt_jit_id, 
                    UPPER(apparel.apparel_name)  as apparel_name,
                    UPPER(customer.customer_name) as customer_name,
                    om.om_em_entry_no as entry_no,
                    DATE_FORMAT(om.om_em_entry_date, '%d-%m-%Y') as entry_date,
                    UPPER(proces.proces_name) as proces_name,
                    UPPER(karigar.karigar_name) as karigar_name
                    FROM job_issue_master jim
                    INNER JOIN job_issue_trans jit ON(jit.jit_jim_id = jim.jim_id)
                    LEFT JOIN order_barcode_trans obt ON(obt.obt_id = jit.jit_obt_id)
                    LEFT JOIN order_master om ON(om.om_id = obt.obt_om_id)
                    LEFT JOIN customer_master customer ON(customer.customer_id = om.om_customer_id)  
                    LEFT JOIN proces_master proces ON(proces.proces_id = jim.jim_proces_id)
                    LEFT JOIN karigar_master karigar ON(karigar.karigar_id = jim.jim_karigar_id)
                    LEFT JOIN job_receive_trans jrt ON(jrt.jrt_jit_id = jit.jit_id)
                    LEFT JOIN apparel_master apparel ON(apparel.apparel_id = obt.obt_apparel_id)
                    WHERE jim.jim_delete_status = 0
                    AND jit.jit_delete_status = 0
                    AND obt.obt_id = $id
                    ORDER BY jit.jit_id DESC LIMIT 1
                    ";
            // echo "<pre>"; print_r($query); exit;
            return $this->db->query($query)->result_array();
        }

        public function get_process_data($karigar_id){    
            $query="SELECT proces.proces_id as process_id,
                    UPPER(proces.proces_name) as process_name
                    FROM karigar_proces_trans kpt
                    INNER JOIN proces_master proces ON(proces.proces_id = kpt.kpt_proces_id)
                    WHERE kpt.kpt_karigar_id = $karigar_id
                    ORDER BY proces.proces_id ASC";
            // echo "<pre>"; print_r($query); exit;
            return $this->db->query($query)->result_array();
        }

        public function is_process_exist($karigar_id, $process_id){ 
            $query="SELECT proces.proces_id as process_id,
                    UPPER(proces.proces_name) as process_name
                    FROM karigar_proces_trans kpt
                    INNER JOIN proces_master proces ON(proces.proces_id = kpt.kpt_proces_id)
                    WHERE kpt.kpt_karigar_id = $karigar_id
                    AND kpt.kpt_proces_id = $process_id
                    ORDER BY proces.proces_id ASC";
            // echo "<pre>"; print_r($query); exit;
            return $this->db->query($query)->result_array();
        }
        public function is_apparel_exist($karigar_id, $apparel_id){  
            $query="SELECT apparel.apparel_id as apparel_id,
                    UPPER(apparel.apparel_name) as apparel_name
                    FROM karigar_apparel_trans kapt
                    INNER JOIN apparel_master apparel ON(apparel.apparel_id = kapt.kapt_apparel_id)
                    WHERE kapt.kapt_karigar_id = $karigar_id
                    AND kapt.kapt_apparel_id = $apparel_id
                    ORDER BY apparel.apparel_id ASC";
            // echo "<pre>"; print_r($query); exit;
            return $this->db->query($query)->result_array();
        }
       

   

}
?>
