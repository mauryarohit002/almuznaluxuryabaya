<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
	class ticket_model extends CI_model{
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
            if(isset($_POST['from_date']) && !empty($_POST['from_date'])){
                $from_date  = date('Y-m-d', strtotime($_POST['from_date']));
                $subsql    .= " AND (ticket.ticket_entry_date >= '".$from_date."')";
            }
            if(isset($_POST['to_date']) && !empty($_POST['to_date'])){
                $to_date  = date('Y-m-d', strtotime($_POST['to_date']));
                $subsql  .= " AND (ticket.ticket_entry_date <= '".$to_date."')";
            }
            $query="SELECT ticket.ticket_id as id,
                    ticket.ticket_entry_no as entry_no,
                    DATE_FORMAT(ticket.ticket_entry_date, '%d-%m-%Y') as entry_date,
                    ticket.ticket_name as name,
                    ticket.ticket_desc as description,
                    ticket.ticket_status as status
                    FROM ticket_master ticket
                    WHERE ticket.ticket_created_by = $user_id
                    AND ticket.ticket_delete_status = 0
                    $subsql
                    GROUP BY ticket.ticket_id 
                    ORDER BY ticket.ticket_id DESC
                    LIMIT $limit
                    OFFSET $offset";
            // echo "<pre>"; print_r($query); exit;
            if($next_offset > 0){
                return ($this->db->query($query)->num_rows() > 0) ? ($ofset + $next_offset) : 0;
            }
            $data = $this->db->query($query)->result_array();
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $status = '';
                    if($value['status'] == 1) $status = 'RESOLVE';
                    if($value['status'] == 2) $status = 'PENDING';
                    if($value['status'] == 3) $status = 'REJECT';
                    if($value['status'] == 4) $status = 'UNDER PROCESS';
                    $data[$key]['status'] = $status;
                }
            }
            return $data;
        }
        public function get_detail(){
            $id     = isset($_POST['id']) ? $_POST['id'] : 0; 
            $user_id= isset($_POST['user_id']) ? $_POST['user_id'] : 0; 
			$query="SELECT ticket.ticket_id as id,
                    ticket.ticket_entry_no as entry_no,
                    DATE_FORMAT(ticket.ticket_entry_date, '%d-%m-%Y') as entry_date,
                    ticket.ticket_name as name,
                    ticket.ticket_desc as description,
                    ticket.ticket_status as status
                    FROM ticket_master ticket
                    WHERE ticket.ticket_created_by = $user_id
                    AND ticket.ticket_delete_status = 0
                    AND ticket.ticket_id = $id
                    GROUP BY ticket.ticket_id";
            // echo "<pre>"; print_r($query); exit;
            $data = $this->db->query($query)->result_array();
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $status = '';
                    if($value['status'] == 1) $status = 'RESOLVE';
                    if($value['status'] == 2) $status = 'PENDING';
                    if($value['status'] == 3) $status = 'REJECT';
                    if($value['status'] == 4) $status = 'UNDER PROCESS';
                    $data[$key]['status'] = $status;
                    $data[$key]['attachment_data'] = $this->get_attachment_data($value['id']);
                }
            }
            return $data;
        }
        public function get_attachment_data($id){
            $query="SELECT tat.tat_id as id,
                    tat.tat_photo as photo
                    FROM ticket_attachment_trans tat
                    WHERE tat.tat_ticket_id = $id";
            // echo "<pre>"; print_r($query); exit;
            return $this->db->query($query)->result_array();
        }
    }
?>