<?php
    class my_sync_controller extends CI_Controller {
        protected $add;
		protected $edit;
		protected $add_fail;
		protected $edit_fail;
		protected $i;
		protected $offset;
		protected $per_page;
		protected $no_loops;
		protected $table;
		protected $id;
        public function __construct($table, $id) {
           parent::__construct();

            // $this->load->model('Syncmdl', 'model');
            $this->add 		    = 0;
            $this->edit 		= 0;
            $this->add_fail 	= 0;
            $this->edit_fail 	= 0;
            $this->i 			= 0;
            $this->offset 	    = 0;
            $this->per_page  	= 10;
            $this->no_loops  	= 10;
            $this->table  	    = $table;
            $this->id  	        = $id;
            $this->config->load('extra');
            $this->db2 = $this->load->database('second', TRUE);

            ini_set('sqlsrv.ClientBufferMaxKBSize','524288');  
            ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288');
            ini_set('memory_limit','-1');
        }

        public function index(){
            // $result     = $this->db2->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE'")->result_array();
            // echo "<pre>"; print_r($result); exit;

            // $result     = $this->db2->query("SELECT * from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='".$this->table."'")->result_array();
            // echo "<pre>"; print_r($result); exit;

            $rows       = $this->db2->query("SELECT COUNT(*) as cnt FROM $this->table")->result_array();
            $num_rows   = empty($rows) ? 0 : $rows[0]['cnt'];
            if(!empty($num_rows)){
                $no_loops  	= true ? ceil($num_rows / $this->per_page) : $this->no_loops;
                // echo "<pre>";print_r($no_loops); exit();
                do {
                    $query = "SELECT * FROM $this->table ORDER BY $this->id OFFSET ".$this->offset." ROWS FETCH FIRST ".$this->per_page." ROWS ONLY;";
                    $record= $this->db2->query($query)->result_array();
                    // echo "<pre>";print_r($record); exit;
                    if(!empty($record)){
                        foreach ($record as $key => $value) {
                            $this->sub_func($value);
                        }
                    }
                    $this->offset = $this->offset + $this->per_page; 
                    $this->i++;
                } while ($this->i < $no_loops);
            }
            $response['num_rows'] 	= $num_rows;
            $response['add'] 		= $this->add;
            $response['edit'] 		= $this->edit;
            $response['add_fail'] 	= $this->add_fail;
            $response['edit_fail'] 	= $this->edit_fail;
            $msg 					= empty($num_rows) ? "NO RECORD FOUND" : "RECORD FETCHED SUCCESSFULLY";
            echo json_encode(['session' => TRUE, 'status' => TRUE, 'data' => $response,  'msg' => $msg]);
            
        }
        public function get_city_id($name){
            if($name == NULL) return 0;
            if($name == '') return 0;
            if($name == 0) return 0;
    
            $query="SELECT city_id as id 
                    FROM city_master
                    WHERE city_name = '".$name."'";
            $data = $this->db->query($query)->result_array();
            return empty($data) ? 0 : $data[0]['id'];
        }
    }
?>