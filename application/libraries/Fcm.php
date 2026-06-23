<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    class fcm {
        public function __construct(){
            $this->CI =& get_instance();
        }
        public function send($args){
            foreach (['title', 'screen'] as $key => $value) {
                if(!isset($args[$value])) return ['status' => false, 'data' => [], 'success' => 0, 'failure' => 1, 'msg' => ucfirst($value).' not define.'];
                if(empty($args[$value])) return ['status' => false, 'data' => [], 'success' => 0, 'failure' => 1, 'msg' => ucfirst($value).' is empty.'];
            }

            $args['ref_id']         = isset($args['ref_id']) ? $args['ref_id'] : '';
            $args['multicast_id']   = '';
            $args['canonical_ids']  = '';
            $args['message_id']     = '';
            $args['error']          = '';
            $args['success']        = 0;
            $args['failure']        = 0;
            if(!isset($args['token']) || (isset($args['token']) && empty($args['token']))){
                $args['error']          = 'Token not define.';
                $args['failure']        = 1;
                return $this->add_update_notification($args);    
            }
            $resp = $this->add_update_notification($args);

            $headers = ['Authorization:key=' .FCM_KEY, 'Content-Type:application/json'];

            $notification['title']              = $args['title'];
            $notification['body']               = $args['body'];
            // $notification['mutable_content']    = isset($args['mutable_content']) ? $args['mutable_content'] : true;
            // $notification['sound']              = isset($args['sound']) ? $args['sound'] : "Tri-tone";

            $data['screen']                     = $args['screen'] == '/today_meeting' ? '/today_meeting' : '/detail';
            $data['id']                         = $resp['id'];

            $fields['to']           = $args['token'];
            $fields['notification'] = $notification;
            $fields['data']         = $data;
            // return ['status' => false, 'data' => $args, 'message' => $headers];
            $curl = curl_init();
            curl_setopt( $curl,CURLOPT_URL, FCM_PATH);
            curl_setopt( $curl,CURLOPT_POST, true );
            curl_setopt( $curl,CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $curl,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $curl,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $curl,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($curl );
            $result = json_decode($result, true);
            curl_close( $curl );

            // echo "<pre>"; print_r($result); exit;

            $args['multicast_id']   = isset($result['multicast_id'])                ? $result['multicast_id']               : '';
            $args['canonical_ids']  = isset($result['canonical_ids'])               ? $result['canonical_ids']              : '';
            $args['message_id']     = isset($result['results'][0]['message_id'])    ? $result['results'][0]['message_id']   : '';
            $args['success']        = isset($result['success'])                     ? $result['success']                    : 0;
            $args['failure']        = isset($result['failure'])                     ? $result['failure']                    : 0;

            if(empty($args['success']) && empty($args['failure'])){
                $args['error'] = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Notification not send';
            }else if(!empty($args['success']) && empty($args['failure'])){
                $args['error'] = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Notification send successfully.';
            }else if(empty($args['success']) && !empty($args['failure'])){
                $args['error'] = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Error message not define.';
            }else{
                $args['error'] = isset($result['results'][0]['error']) ? $result['results'][0]['error'] : 'Not possible.';
            }

            return $this->update_notification($resp['id'], $args);
        }
        public function add_update_notification($args){
            $CI 	=& get_instance();
            // echo "<pre>"; print_r($args); exit;
            $prev_data = $CI->db_operations->get_record('notification_master', ['notification_date' => $args['date'], 'notification_screen' => $args['screen'], 'notification_executive_id' => $args['executive_id'], 'notification_ref_id' => $args['ref_id']]);
            
            $master_data = [];
            $master_data['notification_title']          = $args['title'];
            $master_data['notification_body']           = $args['body'];
            $master_data['notification_token']          = $args['token'];
            $master_data['notification_multicast_id']   = $args['multicast_id'];
            $master_data['notification_canonical_ids']  = $args['canonical_ids'];
            $master_data['notification_message_id']     = $args['message_id'];
            $master_data['notification_success']        = $args['success'];
            $master_data['notification_failure']        = $args['failure'];
            $master_data['notification_error']          = $args['error'];
            $master_data['notification_updated_at']     = date('Y-m-d H:i:s');
            
            if(empty($prev_data)){
                $master_data['notification_date']           = $args['date'];
                $master_data['notification_executive_id']   = $args['executive_id'];
                $master_data['notification_executive_name'] = $args['executive_name'];
                $master_data['notification_screen']         = $args['screen'];
                $master_data['notification_type']           = $args['type'];
                $master_data['notification_ref_id']         = $args['ref_id'];
                $master_data['notification_created_at']     = date('Y-m-d H:i:s');
                $id = $CI->db_operations->data_insert('notification_master', $master_data);
                return ['id' => $id, 'success' => $master_data['notification_success'], 'failure' => $master_data['notification_failure'], 'msg' => $master_data['notification_error']];
            }

            $id = $prev_data[0]['notification_id'];
            $CI->db_operations->data_update('notification_master', $master_data, 'notification_id', $id);
            return ['id' => $id, 'success' => $master_data['notification_success'], 'failure' => $master_data['notification_failure'], 'msg' => $master_data['notification_error']];
        }
        public function update_notification($id, $args){
            $CI 	=& get_instance();
            // echo "<pre>"; print_r($args); exit;
            $prev_data = $CI->db_operations->get_record('notification_master', ['notification_id' => $id]);
            
            $master_data = [];
            $master_data['notification_token']          = $args['token'];
            $master_data['notification_multicast_id']   = $args['multicast_id'];
            $master_data['notification_canonical_ids']  = $args['canonical_ids'];
            $master_data['notification_message_id']     = $args['message_id'];
            $master_data['notification_success']        = $args['success'];
            $master_data['notification_failure']        = $args['failure'];
            $master_data['notification_error']          = $args['error'];
            $master_data['notification_updated_at']     = date('Y-m-d H:i:s');
            
            if(!empty($prev_data)){
                $master_data['notification_id'] = $prev_data[0]['notification_id'];
                $CI->db_operations->data_update('notification_master', $master_data, 'notification_id', $master_data['notification_id']);

                return ['id' => $id, 'success' => $master_data['notification_success'], 'failure' => $master_data['notification_failure'], 'msg' => $master_data['notification_error']];
            }
            return ['id' => $id, 'success' => $master_data['notification_success'], 'failure' => $master_data['notification_failure'], 'msg' => $master_data['notification_error']];
        }
        
    }
?>