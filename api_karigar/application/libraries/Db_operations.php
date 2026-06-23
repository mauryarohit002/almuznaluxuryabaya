<?php 
	class Db_operations{

		public $CI="";
		public function __construct(){

			$this->CI =& get_instance();
		}

		function data_insert($table='',$arr=''){

			$this->CI->db->insert($table,$arr);
			return $this->CI->db->insert_id();
		}
		public function get_max_id_custom($table, $field){
		   $query="SELECT $field as max_id 
					FROM $table 
					WHERE 1
					ORDER BY $field DESC
					LIMIT 1";
			$record = $this->CI->db->query($query)->result_array();
			// echo "<pre>"; print_r($query); exit;	
			// echo "<pre>"; print_r($record); exit;	

			if(empty($record[0]['max_id'])) return 1;
			return $record[0]['max_id']+1;
			// echo "<pre>"; print_r($record); exit;	
        }
		function get_recordlist($table='',$field='',$orderby=''){

			if(!empty($orderby)){

				$this->CI->db->order_by($field,$orderby);
			}

			$tdata = $this->CI->db->get($table);
			return $tdata->result_array();
		}

		function get_record($table='', $condition=''){

			return $this->CI->db->get_where($table,$condition)->result_array();
		}

		function data_update($table='',$arr='',$field='',$value=''){

			$this->CI->db->where($field,$value);
			return $this->CI->db->update($table,$arr);
		}


		function delete_record($table='',$arr=''){

			return $this->CI->db->delete($table,$arr);
		}

		function get_max_id($table, $field){

			$this->CI->db->select_max($field, 'max_id');
			return $this->CI->db->get($table)->result_array()[0]['max_id'];
		}

		function get_cnt($table, $arr){

			$this->CI->db->where($arr);
			return $this->CI->db->count_all_results($table);
		}

		function empty_table($table){

			return $this->CI->db->empty_table($table); 
		}

		function image_autorotate_resize($params = NULL, $resize = TRUE)
		{
			if (!is_array($params) || empty($params))
			{
				return 0;
			}
			$filepath = $params['filepath'];
			$exif = @exif_read_data($filepath);
			if (empty($exif['Orientation']))
			{
				return -1;
			}
			else
			{
				$CI =& get_instance();
				$CI->load->library('image_lib');
				$config['image_library'] = 'gd2';
				$config['source_image']	= $filepath;
				if ($resize) 
				{			
					$tmp_filename 			= $filepath;
					list($width, $height) 	= getimagesize($tmp_filename);
					
					if ($width >= $height)
			    	{
			            $config['width'] = 800;
			        }
			        else
			        {
			            $config['height'] = 800;
			        }
					$config['master_dim'] = 'auto';
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = TRUE;
					$config['quality'] = '100%';  
					$config['new_image'] = $filepath;
					$CI->image_lib->initialize($config); 
					$CI->image_lib->resize();
				}

				$oris = array();
					
				switch($exif['Orientation'])
				{
				        case 1: // no need to perform any changes
				        break;
				
				        case 2: // horizontal flip
						$oris[] = 'hor';
				        break;
				                                
				        case 3: // 180 rotate left
				        	$oris[] = '180';
				        break;
				                    
				        case 4: // vertical flip
				        	$oris[] = 'ver';
				        break;
				                
				        case 5: // vertical flip + 90 rotate right
				        	$oris[] = 'ver';
						$oris[] = '270';
				        break;
				                
				        case 6: // 90 rotate right
				        	$oris[] = '270';
				        break;
				                
				        case 7: // horizontal flip + 90 rotate right
				        	$oris[] = 'hor';
						$oris[] = '270';
				        break;
				                
				        case 8: // 90 rotate left
				        	$oris[] = '90';
				        break;
						
					default: break;
				}
			
				foreach ($oris as $ori) 
				{
					$config['rotation_angle'] = $ori;
					$CI->image_lib->initialize($config); 				
					$CI->image_lib->rotate();
				}				
			}
		}

		function get_fin_year_max_id_custom($table,$field,$field2,$f_year)
		{
			$query = "SELECT MAX($field) as max_id FROM $table WHERE $field2 = '$f_year'";			
			$record = $this->CI->db->query($query)->result_array();
			if(empty($record[0]['max_id']))
			{
				return 1;
			}
			else
			{
				return $record[0]['max_id']+1;
			}
		}
		function get_fin_year_f_type_max_id_custom($table,$field,$field2,$fc_type,$field3,$f_year)
		{
			$query = "SELECT MAX($field) as max_id FROM $table WHERE $field2 = $fc_type AND $field3 = '$f_year'";	
			// echo "<pre>"; print_r($query); exit();		
			$record = $this->CI->db->query($query)->result_array();
			if(empty($record[0]['max_id']))
			{
				return 1;
			}
			else
			{
				return $record[0]['max_id']+1;
			}
		}
	}
?>