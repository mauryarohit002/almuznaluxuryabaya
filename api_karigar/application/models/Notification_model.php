<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
	class notification_model extends CI_model{
        public function get_list($next_offset = 0){
            // echo "<pre>"; print_r($_POST); exit;
            $subsql = '';
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
                $subsql    .= " AND (notification.notification_date >= '".$from_date."')";
            }
            if(isset($_POST['to_date']) && !empty($_POST['to_date'])){
                $to_date  = date('Y-m-d', strtotime($_POST['to_date']));
                $subsql  .= " AND (notification.notification_date <= '".$to_date."')";
            }
            if(isset($_POST['type']) && !empty($_POST['type'])){
                $type    = $_POST['type'];
                $subsql .= " AND (notification.notification_type = '".$type."')";
            }
            $query="SELECT notification.notification_id as id,
                    notification.notification_title as title,
                    notification.notification_body as body,
                    notification.notification_is_read as is_read
                    FROM notification_master notification
                    WHERE notification.notification_executive_id = $user_id
                    $subsql
                    GROUP BY notification.notification_id
                    ORDER BY notification.notification_date DESC, notification.notification_title ASC
                    LIMIT $limit
                    OFFSET $offset";
            // echo "<pre>"; print_r($query); exit;
            if($next_offset > 0){
                return ($this->db->query($query)->num_rows() > 0) ? ($ofset + $next_offset) : 0;
            }
            return $this->db->query($query)->result_array();
        }
        public function get_detail(){
            // echo "<pre>"; print_r($_POST); exit;
            $user_id        = isset($_POST['user_id']) ? $_POST['user_id'] : 0; 
            $id             = (isset($_POST['id']) && !empty($_POST['id'])) ? $_POST['id'] : 0;
            $data           = $this->db_operations->get_record('notification_master', ['notification_id' => $id]);
            if(empty($data)) return $data;
            $func           = strtolower($data[0]['notification_type']);
            $result         = $this->$func($user_id, $data);
            $master_data    = [];
            if(!empty($data)){
               $master_data['notification_title']   = date('d-m-Y', strtotime($data[0]['notification_date'])).': '.strtoupper(str_replace('_', ' ', $data[0]['notification_type'])).' ('.count($result).')';
               $master_data['notification_is_read'] = 1;
            }
            $this->db_operations->data_update('notification_master', $master_data, 'notification_id', $id);
            return $result;
        }
        public function get_type($next_offset = 0){
            // echo "<pre>"; print_r($_POST); exit;
            $subsql = '';
            $subsql = '';
            $limit  = LIMIT;
			$offset = ((LIMIT * OFFSET) + $next_offset);
            $ofset  = OFFSET;
			if(isset($_POST['limit']) && !empty($_POST['limit'])){
                $limit = $_POST['limit'];
            }
            if(isset($_POST['offset'])){
                $offset = $_POST['offset'];
                $ofset  = $offset;
            }
            $offset = $next_offset > 0 ? ($limit * ($offset + $next_offset)) : (($limit * $offset) + $next_offset);
            if(isset($_POST['name']) && !empty($_POST['name'])){
                $name    = str_replace(' ', '_', $_POST['name']);
                $subsql .= " AND (notification.notification_type LIKE '%".$name."%')";
            }
            $query="SELECT notification.notification_type as id,
                    REPLACE(notification.notification_type, '_', ' ') as name
                    FROM notification_master notification
                    WHERE 1
                    $subsql
                    GROUP BY notification.notification_type
                    ORDER BY notification.notification_type ASC
                    LIMIT $limit
                    OFFSET $offset";
            // echo "<pre>"; print_r($query); exit;
            if($next_offset > 0){
                return ($this->db->query($query)->num_rows() > 0) ? ($ofset + $next_offset) : 0;
            }
            return $this->db->query($query)->result_array();
        }
        public function get_count(){
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0; 
            $query="SELECT COUNT(notification.notification_id) as total_notification,
                    SUM(notification.notification_is_read) as total_read
                    FROM notification_master notification
                    WHERE notification.notification_executive_id = $user_id
                    GROUP BY notification.notification_executive_id";
            return $this->db->query($query)->result_array();
        }
        public function payment_reminder($executive_id, $data){
            $day = (isset($_GET['day']) && !empty($_GET['day'])) ? $_GET['day'] : 60;
            $query="SELECT im.im_entry_no as title,
                    DATE_FORMAT(im.im_entry_date, '%d-%m-%Y') as date,
                    im.im_total_amt as total_amt,
                    DATEDIFF(CURDATE(), im.im_entry_date) as day_pass,
                    $day as frequency
                    FROM invoice_master im
                    WHERE im.im_delete_status = 0
                    AND im.im_customer_id = $executive_id
                    AND im.im_allocated_amt <= 0
                    HAVING (day_pass > frequency)
                    ORDER BY im.im_entry_no, im.im_entry_date ASC";
            $data = $this->db->query($query)->result_array();
            // echo "<pre>"; print_r($query); exit;
            // echo "<pre>"; print_r($data); exit;
            $record     = [];
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    $body = "Invoice date : ".date('d-m-Y', strtotime($value['date']));
                    $body .= "\nInvoice amt : ".$value['total_amt'];
                    array_push($record, ['title' => 'Invoice no. : '.$value['title'], 'body' => $body]);
                }
            }
            
            return $record;
        }
    }
?>

