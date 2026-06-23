<?php
	if (!function_exists('pre'))
	{
		function pre(...$data)
		{
			foreach ($data as $all_data) {
				echo "<pre>";
				print_r($all_data);
				echo "</pre>";
			}
			exit();	
		}
	}
	if (!function_exists('assets')){
		function assets($path = ''){
			return base_url()."public/assets/".$path;
		}
	}
	if (!function_exists('uploads')){
		function uploads($path = ''){
			return base_url()."public/uploads/".$path;
		}
	}
	if (!function_exists('sessionExist')){
		function sessionExist(){
			$CI =& get_instance();
			return ($CI->session->userdata('user_id')) ? true : false;
    	}
	}
	if (!function_exists('isLoggedIn')){
		function isLoggedIn($table = 'user_master', $field = 'user_id', $status = 'user_status'){
			$CI =& get_instance();
			$id = $CI->session->userdata($field) ? $CI->session->userdata($field) : 0;
			
			if(empty($id)) return ['session' => FALSE, 'status' => FALSE, 'active' => FALSE, 'data' => [], 'msg' => 'Session expired. Please wait...'];
			
			$data = $CI->db->get_where($table,[$field => $id])->result_array();
			if(empty($data)) return ['session' => FALSE, 'status' => FALSE, 'active' => FALSE, 'data' => [], 'msg' => 'User not found.'];

			if($data[0][$status] != 1){
				return ['session' => TRUE, 'status' => FALSE, 'active' => FALSE, 'data' => [], 'msg' => 'Account has been deactivated.'];
			}

			return ['session' => TRUE, 'status' => TRUE, 'active' => TRUE, 'data' => $data, 'msg' => ''];				
    	}
	}
	if (!function_exists('encrypt_decrypt')) {
		function encrypt_decrypt($action, $data, $secret_key) {
		    $output         = false;
		    $encrypt_method = "AES-256-CBC";
		    $secret_iv      = $secret_key;
		    $key            = hash('sha256', $secret_key);
		    $iv             = substr(hash('sha256', $secret_iv), 0, 16);

		    if ($action == 'encrypt') {
		        $output = openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
		        $output = base64_encode($output);
		    }else if ($action == 'decrypt') {
		        $output = openssl_decrypt(base64_decode($data), $encrypt_method, $key, 0, $iv);
		    }
		    return $output;
		}
	}
	if (!function_exists('number_to_word')){
		function number_to_word( $number = '' ){
		   $no = ($number);
		   $point = round($no - $number, 2) * 100;
		   $hundred = null;
		   $digits_1 = strlen($no);
		   $i = 0;
		   $str = array();
		   $words = array('0' => '', '1' => 'One', '2' => 'Two',
			'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
			'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
			'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
			'13' => 'Thirteen', '14' => 'Fourteen',
			'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
			'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
			'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
			'60' => 'Sixty', '70' => 'Seventy',
			'80' => 'Eighty', '90' => 'Ninety');
		   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
		   while ($i < $digits_1) {
			 $divider = ($i == 2) ? 10 : 100;
			 $number = floor($no % $divider);
			 $no = floor($no / $divider);
			 $i += ($divider == 10) ? 1 : 2;
			 if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? 'And ' : null;
				$str [] = ($number < 21) ? $words[$number] .
					" " . $digits[$counter] . $plural . " " . $hundred
					:
					$words[floor($number / 10) * 10]
					. " " . $words[$number % 10] . " "
					. $digits[$counter] . $plural . " " . $hundred;
			 } else $str[] = null;
		  }
		  $str = array_reverse($str);
		  $result = implode('', $str);
		  $result = rtrim($result);
		  $points = ($point) ?
			"." . $words[$point / 10] . " " .
				  $words[$point = $point % 10] : '';
		  return $result . " Rupees" . $points . " "."Only";
		}
	}
	if (!function_exists('text_value')){
		function text_value($table, $condition, $value, $text){
			$CI =& get_instance();
			$data = $CI->db->get_where($table,$condition)->result_array();
			if(empty($data)) return ['value' => '', 'text' => ''];
			$value 	= $data[0][$value];
			$text 	= $data[0][$text];
			return ['value' => $value, 'text' => $text];
    	}
	}
// 	if (!function_exists('isRowEmpty')){
// 		function isRowEmpty($cellIterator){
// 			foreach ($cellIterator as $cell){
// 	    	if (!empty($cell->getValue())){
// 		            return true;
// 		        }
// 		    }
// 		    return false;
//     	}
// 	}
	if (!function_exists('validate_mobile')){
		function validate_mobile($mobile){
			return preg_match('/^[0-9]{10}+$/', $mobile);
    	}
	}
	if (!function_exists('remove_report_pdf')){
		function remove_report_pdf(){
			$path 	= 'public/uploads/reports';
			$dir 	= scandir($path);
			$files 	= array_diff($dir, array('.', '..'));
			if(!empty($files)){
				foreach ($files as $key => $value) {
					$explode 	= explode('.', $value);
					$time 		= $explode[0];
					$date 		= date('Y-m-d');
					if(strtotime($date) > $time){
						$filename = $path.'/'.$value;
						if(file_exists($filename)){
							unlink($filename);
						}
					}
				}
			}
    	}
	}
	if (!function_exists('get_menu_data')){
		function get_menu_data(){
			$CI 	=& get_instance();
			$type 	= $CI->session->userdata('user_type');
			$user_id= $CI->session->userdata('user_id') ? $CI->session->userdata('user_id') : 0;
			$role_id= $CI->session->userdata('user_role_id') ? $CI->session->userdata('user_role_id') : 0;
			$subsql = $type == 1 ? '' : " AND mt.mt_type = 2";
			$query 	="SELECT menu.*
						FROM menu_master menu
						INNER JOIN menu_action_trans mat ON(mat.mat_menu_id = menu.menu_id) 
						LEFT JOIN menu_action_user_trans maut ON(maut.maut_mat_id = mat.mat_id)
						LEFT JOIN menu_action_role_trans mart ON(mart.mart_mat_id = mat.mat_id)
						WHERE menu.menu_status = 1
						AND mat.mat_status = 1
						AND (maut.maut_user_id = $user_id OR mart.mart_role_id = $role_id)
						GROUP BY menu.menu_id
						ORDER BY menu.menu_id ASC";
			// echo "<pre>"; print_r($query); exit;
			$record =  $CI->db->query($query)->result_array();
			// echo "<pre>"; print_r($record); exit;

			if(!empty($record)){
				foreach ($record as $key => $value) {
					$query="SELECT mt.*
							FROM menu_trans mt
							INNER JOIN menu_action_trans mat ON(mat.mat_mt_id = mt.mt_id) 
							LEFT JOIN menu_action_user_trans maut ON(maut.maut_mat_id = mat.mat_id)
							LEFT JOIN menu_action_role_trans mart ON(mart.mart_mat_id = mat.mat_id)
							WHERE mat.mat_status = 1
							AND mt.mt_status = 1
							AND (maut.maut_user_id = $user_id OR mart.mart_role_id = $role_id)
							AND mt.mt_menu_id = ".$value['menu_id']."
							$subsql
							GROUP BY mt.mt_id
							ORDER BY mt.mt_name ASC";
					// echo "<pre>"; print_r($query); exit;
					$record[$key]['trans_data'] =  $CI->db->query($query)->result_array();
					// echo "<pre>"; print_r($record); exit;
				}
			}
			// echo "<pre>"; print_r($record); exit;
			return $record;
    	}
	}
	if (!function_exists('isMenuAssigned')){
		function isMenuAssigned($menu, $sub_menu, $action = ''){
			$CI 	=& get_instance();
			$user_id= $CI->session->userdata('user_id') ? $CI->session->userdata('user_id') : 0;
			$role_id= $CI->session->userdata('user_role_id') ? $CI->session->userdata('user_role_id') : 0;
			$join 	= empty($sub_menu) ? '' : " INNER JOIN menu_trans mt ON(mt.mt_id = mat.mat_mt_id)";
			$subsql = empty($sub_menu) ? '' : " AND mt.mt_status = 1 AND mt.mt_js = '".$sub_menu."'";
			$subsql.= empty($action)   ? '' : " AND mat.mat_action = '".$action."'";
			
			if(empty($user_id)) return ['session' => FALSE, 'status' => FALSE, 'active' => FALSE, 'msg' => 'Session expired. Please wait...'];
			$query="SELECT menu.menu_id
					FROM menu_master menu
					INNER JOIN menu_action_trans mat ON(mat.mat_menu_id = menu.menu_id)
					LEFT JOIN menu_action_user_trans maut ON(maut.maut_mat_id = mat.mat_id)
					LEFT JOIN menu_action_role_trans mart ON(mart.mart_mat_id = mat.mat_id)
					$join
					WHERE menu.menu_status = 1
					AND mat.mat_status = 1
					AND (maut.maut_user_id = $user_id OR mart.mart_role_id = $role_id)
					AND menu.menu_js = '".$menu."'
					$subsql
					GROUP BY menu.menu_id";
			// echo"<pre>"; print_r($query);exit;
			$data = $CI->db->query($query)->result_array();
			// echo"<pre>"; print_r($data);exit;
			
			if(empty($data)){
				return ['session' => TRUE, 'status' => FALSE, 'active' => TRUE, 'msg' => 'Unauthorized request.'];
			}
			return ['session' => TRUE, 'status' => TRUE, 'active' => TRUE, 'msg' => ''];
    	}
	}
	if (!function_exists('get_action_data')){
		function get_action_data($menu, $sub_menu){
			$CI 	=& get_instance();
			$user_id= $CI->session->userdata('user_id') ? $CI->session->userdata('user_id') : 0;
			$role_id= $CI->session->userdata('user_role_id') ? $CI->session->userdata('user_role_id') : 0;
			$join 	= empty($sub_menu) ? '' : " INNER JOIN menu_trans mt ON(mt.mt_id = mat.mat_mt_id)";
			$subsql = empty($sub_menu) ? '' : " AND mt.mt_status = 1 AND mt.mt_js = '".$sub_menu."'";
			
			if(empty($user_id)) return ['session' => FALSE, 'status' => FALSE, 'active' => FALSE, 'data' => [], 'msg' => 'Session expired. Please wait...'];
			$query="SELECT mat.mat_action
					FROM menu_master menu
					INNER JOIN menu_action_trans mat ON(mat.mat_menu_id = menu.menu_id)
					LEFT JOIN menu_action_user_trans maut ON(maut.maut_mat_id = mat.mat_id)
					LEFT JOIN menu_action_role_trans mart ON(mart.mart_mat_id = mat.mat_id)
					$join
					WHERE menu.menu_status = 1
					AND mat.mat_status = 1
					AND (maut.maut_user_id = $user_id OR mart.mart_role_id = $role_id)
					AND menu.menu_js = '".$menu."'
					$subsql
					GROUP BY maut.maut_id, mart.mart_id";
			$data = $CI->db->query($query)->result_array();
			// echo"<pre>"; print_r($data);exit;
			$record = [];
			if(!empty($data)){
				foreach ($data as $key => $value) {
					array_push($record, $value['mat_action']);
				}
			}

			// echo"<pre>"; print_r($record); exit;
			return $record; 
    	}
	}
	if (!function_exists('get_submenu_data')){
		function get_submenu_data($menu, $sub_menu){
			$CI 	=& get_instance();
			$user_id= $CI->session->userdata('user_id') ? $CI->session->userdata('user_id') : 0;
			$role_id= $CI->session->userdata('user_role_id') ? $CI->session->userdata('user_role_id') : 0;
			
			if(empty($user_id)) return ['session' => FALSE, 'status' => FALSE, 'active' => FALSE, 'data' => [], 'msg' => 'Session expired. Please wait...'];
			$query="SELECT menu.menu_name as menu_name,
					mt.mt_name
					FROM menu_master menu
					INNER JOIN menu_trans mt ON(mt.mt_menu_id = menu.menu_id)
					WHERE menu.menu_status = 1
					AND mt.mt_status = 1
					AND menu.menu_js = '".$menu."'
					AND mt.mt_js = '".$sub_menu."'";
			$data = $CI->db->query($query)->result_array();
			return empty($data) ? ['menu_name' => '', 'sub_menu_name' => ''] : ['menu_name' => $data[0]['menu_name'], 'sub_menu_name' => $data[0]['mt_name']];
    	}
	}
	if (!function_exists('get_constant_user')){
		function get_constant_user($constants){
			$CI 	=& get_instance();
			$implode= implode("', '", $constants);
			$subsql = empty($constants) ? "" : " AND user.user_constant IN ('$implode')";
			$query="SELECT user.user_id as _id, 
					user.user_constant as _constant 
					FROM user_master user
					WHERE 1
					$subsql";
			$data = $CI->db->query($query)->result_array();
			// echo"<pre>"; print_r($query);exit;
			// echo"<pre>"; print_r($data); exit;
			$record = [];
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$record[$value['_constant']] = $value['_id'];
				}
			}

			// echo"<pre>"; print_r($record); exit;
			return $record; 
    	}
	}
	if (!function_exists('get_constant_supplier')){
		function get_constant_supplier($constants){
			$CI 	=& get_instance();
			$implode= implode("', '", $constants);
			$subsql = empty($constants) ? "" : " AND supplier.supplier_constant IN ('$implode')";
			$query="SELECT supplier.supplier_id as _id, 
					supplier.supplier_constant as _constant 
					FROM supplier_master supplier
					WHERE 1
					$subsql";
			$data = $CI->db->query($query)->result_array();
			// echo"<pre>"; print_r($query);exit;
			// echo"<pre>"; print_r($data); exit;
			$record = [];
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$record[$value['_constant']] = $value['_id'];
				}
			}

			// echo"<pre>"; print_r($record); exit;
			return $record; 
    	}
	}
	if (!function_exists('get_constant_company')){
		function get_constant_company($constants){
			$CI 	=& get_instance();
			$implode= implode("', '", $constants);
			$subsql = empty($constants) ? "" : " AND company.company_constant IN ('$implode')";
			$query="SELECT company.company_ccm_id as _id, 
					company.company_constant as _constant 
					FROM company_master company
					WHERE 1
					$subsql";
			$data = $CI->db->query($query)->result_array();
			// echo"<pre>"; print_r($query);exit;
			// echo"<pre>"; print_r($data); exit;
			$record = [];
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$record[$value['_constant']] = $value['_id'];
				}
			}

			// echo"<pre>"; print_r($record); exit;
			return $record; 
    	}
	}
	if (!function_exists('get_bgcolor')){
		function get_bgcolor(){
			$CI 	=& get_instance();
			$query="SELECT IF(user.user_bgcolor = '', '#cacfd1', user.user_bgcolor) as bgcolor
					FROM user_master user
					WHERE user.user_id = ".$_SESSION['user_id'];
			$data = $CI->db->query($query)->result_array();
			return empty($data) ? '#cacfd1' : $data[0]['bgcolor'];
    	}
	}
?>