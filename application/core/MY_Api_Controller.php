<?php
    use Restserver\Libraries\REST_Controller;
    require APPPATH . '/libraries/REST_Controller.php';
    class my_api_controller extends CI_Controller {
        protected $version;
        protected $app_type;
        protected $user;
        protected $categories;
        protected $post_data;
        protected $table;
        protected $label;
        protected $request_id = NULL;
        protected $_request_start_time = NULL;
        public $validation;
        // public $parent_model;
        public function __construct($args = []) {
            parent::__construct();
            $this->version  = 1;
            $this->table    = isset($args['table']) ? $args['table'] : '';
            $this->label    = isset($args['label']) ? $args['label'] : 'Record';
            $this->app_type = null;
            $this->user     = null;
            $this->post_data= null;
            $this->_request_start_time = microtime(true);
            
            $this->load->library('validation');
            // $this->load->model('Parent_model', 'parent_model');
            // echo "<pre>"; print_r($args); exit; // Debugging line, remove in production
            $this->bootstrap();
            if(isset($args['model'])) {
                $this->load->model($args['model'], 'model');
                $this->model->set_user($this->user);
            }

        }

        protected function bootstrap() {  
            $result = $this->validation->access_key();
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) return $this->response($result);
                
            $result = $this->set_post_data();
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) return $this->response($result);
            
            if($this->is_route_public()) return;
            $result = $this->validation->jwt_token();
            
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) return $this->response(['message' => $result['message']]);
            $jwt_token = $result['data'];
            $result = $this->get_user($jwt_token);
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) return $this->response($result);
           
        }
        protected function log_request() {
            $ip         = $this->input->ip_address();
            $uri        = uri_string();
            $controller = $this->router->class;
            $method     = $this->router->method;
            $http_method= $this->input->method(TRUE); // GET/POST/PUT/DELETE
            $user_agent = $this->input->user_agent();
            $request    = file_get_contents('php://input');
            $user_id    = $this->get_user_id();
            
            $log_data = [
                'ip_address'    => $ip,
                'user_id'       => $user_id,
                'uri'           => $uri,
                'controller'    => $controller,
                'method'        => $method,
                'http_method'   => $http_method,
                'user_agent'    => $user_agent,
                'request'       => $request,
                'created_at'    => date('Y-m-d H:i:s'),
            ];

            $this->request_id = $this->db_operations->data_insert('request_logs', $log_data);
        }
        protected function check_rate_limit ($limit = 10, $interval_seconds = 60) {
            $user_id    = $this->get_user_id();
            $ip         = $this->input->ip_address();
            $controller = $this->router->class;
            $method     = $this->router->method;
            $where      = ['controller' => $controller,'method' => $method];
            if ($user_id) { 
                $where['user_id'] = $user_id;
            } else {
                $where['ip_address'] = $ip;
            }

            // Time window
            $cutoff_time = date('Y-m-d H:i:s', time() - $interval_seconds);
            $this->db->from('request_logs');
            $this->db->where($where);
            $this->db->where('created_at >=', $cutoff_time);

            $count = $this->db->count_all_results();

            if ($count >= $limit) 
                $this->response(['status' => false, 'message' => 'Rate limit exceeded. Please try again later.','code' => REST_Controller::HTTP_TOO_MANY_REQUESTS]);
        }
        protected function is_route_public() {
            $controller     = $this->router->fetch_class();
            $method         = $this->router->fetch_method();
            $current_route  = strtolower($controller . '/' . $method);
            $public_routes  = [
                                'user/login', 
                                'user/refresh_token', 
                                'branch/read',
                            ];
            // pre($current_route, $public_routes);exit;
            if (in_array($current_route, $public_routes)) return true;
            return false;
        }
      
        protected function bypass_subscription() {
            $controller     = $this->router->fetch_class();
            $method         = $this->router->fetch_method();
            $current_route  = strtolower($controller . '/' . $method);
            $public_routes  = ['subscription/payment_link', 'payment/get_status'];
            if (in_array($current_route, $public_routes)) return true;
            return false;
        }
        protected function set_post_data() {
            $request_method = $this->input->method(TRUE);
            if(in_array($request_method, ['GET', 'DELETE'])) return ['status' => TRUE];
            $post_data  = json_decode(file_get_contents('php://input'), true);
            if(empty($post_data)) return ['message' => 'Form data is empty.'];
            $this->post_data = $this->security->xss_clean($post_data);
            return ['status' => TRUE];
        }
        protected function response($args) {
            $response['status']  = isset($args['status'])  ? $args['status']  : false;
            $response['message'] = isset($args['message']) ? $args['message'] : '';
            $response['data']    = isset($args['data'])    ? $args['data']    : [];
            $response['error']   = isset($args['error'])   ? $args['error']   : [];

            $code = isset($args['code'])
                ? $args['code']
                : ($response['status'] ? REST_Controller::HTTP_OK : REST_Controller::HTTP_BAD_REQUEST);
            // $this->response($response, $code);
            // exit; // ðŸ”´ Important: stops further code execution

            // Send response using native CI output
            if($this->request_id) {
                $update = [
                    'response_status'    => $response['status'],
                    'response_message'   => $response['message'],
                    'response_data'      => empty($response['data']) ? NULL : json_encode($response['data']),
                    'response_error'     => empty($response['error']) ? NULL : json_encode($response['error']),
                    'response_code'      => $code,
                    'updated_at'         => date('Y-m-d H:i:s'),
                    'process_time'       => microtime(true) - $this->_request_start_time,
                ];
                $this->db_operations->data_update('request_logs', $update, 'id', $this->request_id);
                $this->request_id = NULL; // Reset request_id after logging
            }
            $this->output
                ->set_status_header($code)
                ->set_content_type('application/json')
                ->set_output(json_encode($response))
                ->_display(); // force output

            exit; 
        }

        protected function allow_method($methods = []){
            $request_method = $this->input->method(TRUE);
            if(!in_array($request_method, array_map('strtoupper', (array) $methods))) 
                $this->response(['message' => 'Method not allowed.', 'code' => REST_Controller::HTTP_METHOD_NOT_ALLOWED]);
            return ['status' => TRUE];
        }
        public function read(){
            $this->allow_method(['POST']);  
            $search             = isset($this->post_data['search']) ? $this->post_data['search'] : null;
            $limit              = isset($this->post_data['limit']) ? $this->post_data['limit'] : LIMIT;
            $offset             = isset($this->post_data['offset']) ? ($this->post_data['offset'] < 0 ? 0 : $this->post_data['offset']) : OFFSET;
            $data['total']      = $this->model->read($search, ['wantCount' => true]);
            if((ceil($data['total'] / $limit-1)) > $offset) $data['next_offset'] = $offset + 1;
            $data['record']     = $this->model->read($search, ['limit' => $limit, 'offset' => ($limit * $offset)]);
            if (empty($data['record'])) return $this->response(['message' => 'Record not found.']);

            return $this->response(['status' => true, 'data' => $data]);
        }
       
        public function store($id = 0){
            $this->allow_method(['POST']);
            $this->db->trans_begin();
            $result = $this->store_master($id);
            if(!isset($result['status']) || (isset($result['status']) && $result['status'] === FALSE)) {
                $this->db->trans_rollback();
                return $this->response($result);
            }

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return $this->response(['message' => '1. Transaction Rollback.']);
            }
            $this->db->trans_commit();
            return $this->response($result);
        }

        public function remove($id){ 
            $this->allow_method(['DELETE']);
            
            $this->db->trans_begin();

            $result = $this->remove_master($id); 
            if(!isset($result['status'])) {
                $this->db->trans_rollback();
                return $this->response($result);
            }

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return $this->response(['message' => '1. Transaction Rollback.']);
            }
            $this->db->trans_commit();

            return $this->response($result);
        }
        protected function get_ids($arr, $id){
            $record = [];
            foreach ($arr as $key => $value) array_push($record, $value[$id]);
            return $record;
        }
        protected function get_user($token){
            if(!isset($token['user_id']) || (isset($token['user_id']) && empty($token['user_id']))) return ['message' => 'User ID not define in token!'];
            $user = $this->db_operations->get_record('user_master', ['user_id' => $token['user_id']]);
            if(empty($user)) return ['message' => 'User not found.'];
            if($user[0]['user_status'] == 0) return ['message' => 'User account has been deactivated.', 'code' => REST_Controller::HTTP_UNAUTHORIZED];
         
            $this->user = [
                'id'            => $user[0]['user_id'],
                'user_name' => $user[0]['user_name'],
                'financial_year'=> $token['financial_year']
            ];
            return ['status' => TRUE, 'data' => $token];
        }
       
        protected function get_user_id() {
            $result = $this->validation->jwt_token();
            if(isset($result['data']) && !empty($result['data'])) {
                return isset($result['data']['user_id']) ? $result['data']['user_id'] : NULL;
            }
            return NULL;
        }
      
    }
?>