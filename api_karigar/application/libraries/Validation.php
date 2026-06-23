<?php 
    defined('BASEPATH') OR exit('No direct script access allowed');
    require_once APPPATH . 'third_party/php-jwt/JWT.php';
    require_once APPPATH . 'third_party/php-jwt/BeforeValidException.php';
    require_once APPPATH . 'third_party/php-jwt/ExpiredException.php';
    require_once APPPATH . 'third_party/php-jwt/SignatureInvalidException.php';
    use \Firebase\JWT\JWT;
    use Restserver\Libraries\REST_Controller; 
    class validation {
        public $CI;
        protected $headers=null;
        protected $token_key;
        protected $token_algorithm;
        protected $token_header     = 'Auth';
        protected $access_key_header= 'Api-Access-Key';
        protected $app_type         = 'App-Type';
        protected $token_expire_time= 86400; 
        /**
         * Token Expire Time
         * ----------------------
         * ( 1 Day ) : 60 * 60 * 24 = 86400
         * ( 1 Hour ) : 60 * 60     = 3600
         */
        
        public function __construct(){
            $this->CI =& get_instance();
            $this->CI->load->config('jwt');
            $this->token_key        = $this->CI->config->item('jwt_key');
            $this->token_algorithm  = $this->CI->config->item('jwt_algorithm');
            $this->headers          = $this->CI->input->request_headers();
        }
        protected function isKeyExists($key){ 
            if(empty($this->headers)) return ['message' => 'Header not defined.'];
            if (!array_key_exists($key, $this->headers)) return ['message' => $key.' not define in header.'];
            if(empty($this->headers[$key])) return ['message' => $key." value not define."];
            return ['status' => TRUE, 'data' => $key];
        }
        public function generateToken($data){
            try {
                return JWT::encode($data, $this->token_key, $this->token_algorithm);
            }
            catch(Exception $e) {
                return 'Message: ' .$e->getMessage();
            }
        }
        
        public function access_key(){
            $result = $this->isKeyExists($this->access_key_header);
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) return $result;
            if($this->headers[$result['data']] !== API_ACCESS_KEY) return ['message' => 'Invalid Access Key.'];
            return ['status' => TRUE];
        }
       
        public function extract_token(){
            $result = $this->isKeyExists($this->token_header);
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) return $result;
            $key = $result['data'];
            
            $explode= explode(' ', $this->headers[$key]);
            $token = isset($explode[1]) ? $explode[1] : '';
            return ['status' => TRUE, 'data' => $token];
        }
        
        public function jwt_token(){
            $result = $this->extract_token();
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) return $result;
            $token = $result['data'];
            
            if(empty($token)) return ['message' => 'Token is empty.'];
            try{
                try{
                    $token_decode = (array)JWT::decode($token, $this->token_key, array($this->token_algorithm));
                }catch(Exception $e){
                    return ['message' => $e->getMessage()];
                }

                if(empty($token_decode)) return ['message' => 'Forbidden'];
                if(!isset($token_decode['user_id']) ||  (isset($token_decode['user_id']) && empty($token_decode['user_id']))){
                    return ['message' => 'User ID Not Define!'];
                }
                if(!isset($token_decode['created_at']) ||  (isset($token_decode['created_at']) && empty($token_decode['created_at']))){
                    return ['message' => 'Token Time Not Define!'];
                }
                $time_difference = strtotime('now') - $token_decode['created_at'];
                if( $time_difference >= $this->token_expire_time ) return ['session' => FALSE, 'data' => $token_decode, 'message' => 'Token Time Expire.'];
                return ['status' => TRUE, 'message' => '','data' => $token_decode];
            }catch(Exception $e) {
                return ['message' => $e->getMessage()];
            }
        }
    }
?>