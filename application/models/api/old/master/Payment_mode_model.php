<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    require_once APPPATH . 'core/MY_Api_Model.php';
    class payment_mode_model extends my_api_model{
        public function __construct(){ parent::__construct(); }
        public function isExist($id){
            return false;
        }
        public function read($search, $args){   
            $where  = '';
            if(isset($search['id']) && !empty($search['id']))
                $where .= " AND payment_mode.payment_mode_id = '".$search['id']."'";
            
            if(isset($search['name']) && !empty($search['name']))
                $where .= " AND payment_mode.payment_mode_name LIKE '%".$search['name']."%'";
            
            $query="SELECT 
                    payment_mode.payment_mode_id as id,
                    payment_mode.payment_mode_name as name
                    FROM payment_mode_master payment_mode
                    WHERE payment_mode.payment_mode_status = 1
                    $where
                    ORDER BY payment_mode.payment_mode_name ASC";
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