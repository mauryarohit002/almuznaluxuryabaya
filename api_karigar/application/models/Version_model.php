<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
	class version_model extends CI_model{
        public function get_list($next_offset = 0){
            $subsql = '';
            $limit  = LIMIT;
			$offset = ((LIMIT * OFFSET) + $next_offset);
            $ofset  = OFFSET;
            $user_id= isset($_POST['user_id']) ? $_POST['user_id'] : 0; 
			if(isset($_POST['limit']) && !empty($_POST['limit'])){
                $limit = $_POST['limit'];
            }
            if(isset($_POST['offset'])){
                $offset = $_POST['offset'];
                $ofset  = $offset;
            }
            $offset = $next_offset > 0 ? ($limit * ($offset + $next_offset)) : (($limit * $offset) + $next_offset);
            $query="SELECT version.*
                    FROM customer_version_master version
                    WHERE 1
                    $subsql
                    GROUP BY version.version_id 
                    ORDER BY version.version_id DESC
                    LIMIT $limit
                    OFFSET $offset";
            // echo "<pre>"; print_r($query); exit;
            if($next_offset > 0){
                return ($this->db->query($query)->num_rows() > 0) ? ($ofset + $next_offset) : 0;
            }
            return $this->db->query($query)->result_array();
        } 
        public function get_latest(){
            $query="SELECT version.*
                    FROM customer_version_master version
                    WHERE 1
                    GROUP BY version.version_id 
                    ORDER BY version.version_id DESC
                    LIMIT 1";
            return $this->db->query($query)->result_array();
        }
    }
?>