<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    class fcm {
        protected $path;
        protected $key;
        public function __construct(){
            $this->CI =& get_instance();
            $this->path = FCM_PATH;
            $this->key  = FCM_KEY;
        }
        public function send($data){
            foreach (['title', 'body', 'token'] as $key => $value) {
                if(!isset($data[$value])) return ['status' => false, 'data' => [], 'message' => ucfirst($value).' not define.'];
                if(empty($data[$value])) return ['status' => false, 'data' => [], 'message' => ucfirst($value).' is empty.'];
            }

            $headers = array(
                'Authorization:key=' .FCM_KEY, 
                'Content-Type:application/json');

            $fields['to']               = $data['token'];
            $fields['notification']     = ['title' => $data['title'], 'body' => $data['body']];
            // return ['status' => false, 'data' => $data, 'message' => $headers];
            $curl = curl_init();
            curl_setopt( $curl,CURLOPT_URL, FCM_PATH);
            curl_setopt( $curl,CURLOPT_POST, true );
            curl_setopt( $curl,CURLOPT_HTTPHEADER, $headers);
            curl_setopt( $curl,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $curl,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $curl,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            $result = curl_exec($curl );
            curl_close( $curl );
            return ['status' => true, 'data' => $result, 'message' => 'Notification send successfully'];
        }
    }
?>