<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    require_once APPPATH . 'core/MY_Model.php';
    class user_model extends my_model{
        public function __construct(){ parent::__construct(); }
        public function isExist($id){
             $query="SELECT user_role_id
                    FROM user_master WHERE user_id = $id LIMIT 1";
            $data = $this->db->query($query)->result_array();   
            if(!empty($data)) return true;
            return false;
        }
        public function read($search, $args){   
            $where  = '';
            if(isset($search['user_id']) && !empty($search['user_id']))
                $where .= " AND user.user_id = '".$search['user_id']."'";
            
            if(isset($search['user_name']) && !empty($search['user_name']))
                $where .= " AND user.user_name LIKE '%".$search['user_name']."%'";

            if(isset($search['user_mobile']) && !empty($search['user_mobile']))
                $where .= " AND user.user_mobile LIKE '%".$search['user_mobile']."%'";
            
            $query="SELECT 
                    user.user_id as user_id,
                    user.user_full_name,
                    user.user_name,
                    user.user_mobile,
                    user.user_email,
                    user.user_address,
                    user.user_status
                    FROM user_master user
                    WHERE user.user_status = 1
                    $where
                    ORDER BY user.user_name ASC";
            if (isset($args['wantCount']) && $args['wantCount'] == true) 
                return $this->db->query($query)->num_rows();
            if (isset($args['limit']) && !empty($args['limit']))
                $query .= " LIMIT ".(int) $args['limit'];

            if (isset($args['offset']) && !empty($args['offset']))
                $query .= " OFFSET ".(int) $args['offset'];
            return $this->db->query($query)->result_array();
        }

        public function check_duplicate($id, $name){   
             $query="SELECT user_id
                    FROM user_master
                    WHERE user_name = '$name'
                    AND user_id != $id
                    LIMIT 1";
            return $this->db->query($query)->result_array();        
        }       
    }
?>