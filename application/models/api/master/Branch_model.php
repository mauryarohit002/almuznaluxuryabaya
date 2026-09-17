<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    require_once APPPATH . 'core/MY_Api_Model.php';
    class Branch_model extends my_api_model{
        public function __construct(){ parent::__construct(); }
        public function isExist($id){
            return false;
        }
        public function read($search, $args){   
            $where  = '';
            if(isset($search['id']) && !empty($search['id']))
                $where .= " AND branch.branch_id = '".$search['id']."'";
            
            if(isset($search['name']) && !empty($search['name']))
                $where .= " AND branch.branch_name LIKE '%".$search['name']."%'";
            
            $query="SELECT 
                    branch.branch_id as id,
                    branch.branch_name as name
                    FROM branch_master branch
                    WHERE branch.branch_status = 1
                    $where
                    ORDER BY branch.branch_name ASC ";
                    
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