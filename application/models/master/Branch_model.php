<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class branch_model extends my_model{
	public function __construct(){ parent::__construct('master', 'branch'); }
	public function isExist($id){
		// $data = $this->db->query("SELECT pt_id FROM purchase_trans WHERE pt_branch_id = $id LIMIT 1")->result_array();
		// if(!empty($data)) return true;

		return false;
	}
	public function get_list($wantCount, $per_page = 20, $offset = 0){
        $record 	= [];
        $subsql 	= '';
        $limit  	= '';
        $ofset  	= '';
        
        if(!$wantCount){
            $limit .= " LIMIT $per_page";
            $ofset .= " OFFSET $offset";
        }
        
        if(isset($_GET['_branch_name']) && !empty($_GET['_branch_name'])){
            $subsql .=" AND branch.branch_name LIKE '%".$_GET['_branch_name']."%'";
            $record['filter']['_branch_name']['value'] = $_GET['_branch_name'];
            $record['filter']['_branch_name']['text'] = $_GET['_branch_name'];
        }
        if(isset($_GET['_status'])){
            $status = $_GET['_status'] == 2 ? 0 : $_GET['_status'];
            $subsql .=" AND branch_status = ".$status;
            $record['filter']['_status'] = $this->Commonmdl->get_status($_GET['_status']);
        }
        $query="SELECT branch.branch_id, 
                UPPER(branch.branch_name) as branch_name, 
                IF(branch.branch_mobile_no = 0, '', branch.branch_mobile_no) AS branch_mobile_no,
                branch.branch_address,
                branch.branch_status,
                branch.branch_default as isExist
                FROM branch_master branch
                WHERE 1
                $subsql
                ORDER BY branch.branch_id DESC
                $limit
                $ofset";
        // echo "<pre>"; print_r($query); exit;
        if($wantCount){
            return $this->db->query($query)->num_rows();
        }
        $record['data'] = $this->db->query($query)->result_array();
        if(!empty($record['data'])){
            foreach ($record['data'] as $key => $value) {
                $record['data'][$key]['isExist']    = $value['isExist'] ?  $value['isExist'] : $this->isExist($value['branch_id']);
            }
        }
        return $record;
    }
	public function get_data($id){
        $query="SELECT branch.*,
				IF(branch.branch_mobile_no = 0, '', branch.branch_mobile_no) AS branch_mobile_no
                FROM branch_master branch
                WHERE branch.branch_id = $id";
        $data['master_data']= $this->db->query($query)->result_array();     
       return $data;          
       
    }

      public function _branch_name(){
        $subsql = "";
        $limit  = PER_PAGE;
        $offset = OFFSET;
        $page   = 1;
        if(isset($_GET['limit']) && !empty($_GET['limit'])){
            $limit = $_GET['limit'];
        }
        if(isset($_GET['page']) && !empty($_GET['page'])){
            $page   = $_GET['page'];
            $offset = $limit * ($page - 1);
        }
        if(isset($_GET['name']) && !empty($_GET['name'])){
            $name   = $_GET['name'];
            $subsql .= " AND (branch.branch_name LIKE '".$name."%') ";
        }
        if(isset($_GET['param']) && !empty($_GET['param'])){
            $param  = $_GET['param'];
            $subsql .= " AND (branch.branch_status = $param) ";
        }
        $query="SELECT 
                branch.branch_name as id, 
                UPPER(branch.branch_name) as name
                FROM branch_master branch
                WHERE 1
                $subsql
                GROUP BY branch.branch_name ASC
                LIMIT $limit
                OFFSET $offset";
        // echo $query; exit();
        return $this->db->query($query)->result_array();
    }
}
?>
