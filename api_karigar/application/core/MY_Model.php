<?php 
    class my_model extends CI_model{
        protected $user = null;
        public function __construct() {parent::__construct();}
        public function set_user($user) {
            $this->user = $user;
        }
       
        protected function get_ids($arr){
            $ids = '';
            foreach ($arr as $key => $value) $ids = empty($ids) ? $value['id'] : $ids.','.$value['id'];
            return $ids;
        }

	}
?>