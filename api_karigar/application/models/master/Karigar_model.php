<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    require_once APPPATH . 'core/MY_Model.php';
    class karigar_model extends my_model{
        public function __construct(){ parent::__construct(); }
        public function isExist($id){
            return false;
        }
        public function read($search, $args){   
            $where  = '';
            if(isset($search['karigar_id']) && !empty($search['karigar_id']))
                $where .= " AND karigar.karigar_id = '".$search['karigar_id']."'";
            
            if(isset($search['karigar_name']) && !empty($search['karigar_name']))
                $where .= " AND karigar.karigar_name LIKE '%".$search['karigar_name']."%'";

            if(isset($search['karigar_mobile']) && !empty($search['karigar_mobile']))
                $where .= " AND karigar.karigar_mobile LIKE '%".$search['karigar_mobile']."%'";
            
            $query="SELECT 
                    karigar.karigar_id as karigar_id,
                    UPPER(karigar.karigar_name) as karigar_name,
                    karigar.karigar_mobile
                    FROM karigar_master karigar
                    WHERE karigar.karigar_status = 1
                    $where
                    ORDER BY karigar.karigar_name ASC";
            if (isset($args['wantCount']) && $args['wantCount'] == true) 
                return $this->db->query($query)->num_rows();
            if (isset($args['limit']) && !empty($args['limit']))
                $query .= " LIMIT ".(int) $args['limit'];

            if (isset($args['offset']) && !empty($args['offset']))
                $query .= " OFFSET ".(int) $args['offset'];
            return $this->db->query($query)->result_array();
            
        }

        
    }
?>