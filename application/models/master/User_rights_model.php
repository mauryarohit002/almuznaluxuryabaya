<?php defined('BASEPATH') OR exit('No direct script access allowed');
	require_once APPPATH . 'core/MY_Model.php';
	class user_rights_model extends my_model{
		public function __construct(){ parent::__construct('master', 'user_rights'); }
		public function get_menu(){ 
			$record= [];
			$query="SELECT menu.menu_id,
					UPPER(menu.menu_name) as menu_name
					FROM menu_master menu
					WHERE menu.menu_status = 1
					ORDER BY menu_name ASC";
			// echo "<pre>"; print_r($query); exit;
			$record['master_data'] = $this->db->query($query)->result_array();
            if(!empty($record['master_data'])){
                foreach ($record['master_data'] as $key => $value) {
                    $record['master_data'][$key]['trans_data'] = $this->get_sub_menu_data($value);
                }
            }
            // echo "<pre>"; print_r($record); exit;
			return $record;
		}
        public function get_sub_menu_data($val){
			$type = $_SESSION['user_type'];
			// print_r($type);die;
			$subsql = $type == 1 ? '' : " AND mt.mt_type = 2";
			$query="SELECT mt.mt_id,
					UPPER(mt.mt_name) as mt_name
					FROM menu_trans mt
					WHERE mt.mt_status = 1
					AND mt.mt_menu_id = ".$val['menu_id']."
					$subsql
					ORDER BY mt_name ASC";
			// echo "<pre>"; print_r($query); exit;
			return $this->db->query($query)->result_array();
		}
        public function get_assign_rights($mt_id){
			$subsql = $_SESSION['user_type'] == 1 ? "" : " AND mat.mat_type = 2";
            $query="SELECT mat.*
					FROM menu_action_trans mat
					WHERE mat.mat_status = 1
					AND mat.mat_mt_id = $mt_id
					$subsql
					ORDER BY mat.mat_action ASC";
            $data = $this->db->query($query)->result_array();
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $data[$key]['user_trans_data'] = $this->get_menu_action_user($value['mat_id']);
                    $data[$key]['role_trans_data'] = $this->get_menu_action_role($value['mat_id']);
                }
            }
            return $data;
        }
        public function get_menu_action_user($mat_id){
			$type   = $_SESSION['user_type'];
			$subsql = $type == 1 ? '' : " AND user.user_type = 2";
			$query="SELECT maut.*,
					CONCAT(UPPER(user.user_fullname), ' - ', UPPER(branch.branch_name)) as user_fullname
					FROM menu_action_user_trans maut
					INNER JOIN user_master user ON(user.user_id = maut.maut_user_id)
					INNER JOIN branch_master branch ON(branch.branch_id = user.user_branch_id)
					WHERE maut.maut_mat_id = $mat_id
					$subsql";
            return $this->db->query($query)->result_array();
        }
		public function get_menu_action_role($mat_id){
			$type   = $_SESSION['user_type'];
			$subsql = $type == 1 ? '' : " AND user.user_type = 2";
			$query="SELECT mart.*,
					UPPER(role.role_name) as role_name
					FROM menu_action_role_trans mart
					INNER JOIN role_master role ON(role.role_id = mart.mart_role_id)
					INNER JOIN user_master user ON(user.user_role_id = role.role_id)
					WHERE mart.mart_mat_id = $mat_id
					$subsql
					GROUP BY role.role_id";
            return $this->db->query($query)->result_array();
        }
		public function _user_id(){
			$limit  = PER_PAGE;
			$offset = OFFSET;
			$page 	= 1;
			$type   = $_SESSION['user_type']; 
			$subsql = $type == 1 ? '' : " AND user.user_type = 2";
            // echo "<pre>"; print_r($_GET); exit;
			if(isset($_GET['limit']) && !empty($_GET['limit'])){
				$limit = $_GET['limit'];
			}
			if(isset($_GET['page']) && !empty($_GET['page'])){
				$page 	= $_GET['page'];
				$offset = $limit * ($page - 1);
			}
			if(isset($_GET['name']) && !empty($_GET['name'])){
				$name 	= $_GET['name'];
				$subsql .= " AND (user.user_fullname LIKE '%".$name."%' OR branch.branch_name LIKE '%".$name."%') ";
			}
			$query="SELECT user.user_id as id, 
					CONCAT(UPPER(user.user_fullname), ' - ', UPPER(branch.branch_name)) as name
					FROM user_master user
					INNER JOIN branch_master branch ON(branch.branch_id = user.user_branch_id)
					WHERE 1
					$subsql
					GROUP BY user.user_id
					ORDER BY user.user_fullname, branch.branch_name ASC
					LIMIT $limit
					OFFSET $offset";

			// print_r($query);die;		
			return $this->db->query($query)->result_array();
		}
		public function _role_id(){
			$limit  = PER_PAGE;
			$offset = OFFSET;
			$page 	= 1;
			$type   = $_SESSION['user_type'];
			$subsql = $type == 1 ? '' : " AND user.user_type = 2";
			// echo "<pre>"; print_r($_GET); exit;
			if(isset($_GET['limit']) && !empty($_GET['limit'])){
				$limit = $_GET['limit'];
			}
			if(isset($_GET['page']) && !empty($_GET['page'])){
				$page 	= $_GET['page'];
				$offset = $limit * ($page - 1);
			}
			if(isset($_GET['name']) && !empty($_GET['name'])){
				$name 	= $_GET['name'];
				$subsql .= " AND (role.role_name LIKE '%".$name."%') ";
			}
			$query="SELECT role.role_id as id, UPPER(role.role_name) as name
					FROM role_master role
					INNER JOIN user_master user ON(user.user_role_id = role.role_id)
					WHERE 1
					$subsql
					GROUP BY role.role_id
					ORDER BY role.role_name ASC
					LIMIT $limit
					OFFSET $offset";
			// echo $query; exit();
			return $this->db->query($query)->result_array();
		}
	}
?>