<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Api_Controller.php';
    class job_issue extends my_api_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'api/job_issue_model',
                'table' => 'job_issue_master',
                'label' => 'job_issue',
            ]);
        }

        public function job_assign(){
            $this->allow_method(['POST']);  
            $search             = isset($this->post_data['search']) ? $this->post_data['search'] : null;
            $limit              = isset($this->post_data['limit']) ? $this->post_data['limit'] : LIMIT;
            $offset             = isset($this->post_data['offset']) ? ($this->post_data['offset'] < 0 ? 0 : $this->post_data['offset']) : OFFSET;
            $data['total']      = $this->model->job_assign($search, ['wantCount' => true]);
            if((ceil($data['total'] / $limit-1)) > $offset) $data['next_offset'] = $offset + 1;
            $data['record']     = $this->model->job_assign($search, ['limit' => $limit, 'offset' => ($limit * $offset)]);
            if (empty($data['record'])) return $this->response(['message' => 'Record not found.']);

            return $this->response(['status' => true, 'data' => $data]);
        }

        public function no_job_karigar(){
            $this->allow_method(['POST']);  
            $search             = isset($this->post_data['search']) ? $this->post_data['search'] : null;
            $limit              = isset($this->post_data['limit']) ? $this->post_data['limit'] : LIMIT;
            $offset             = isset($this->post_data['offset']) ? ($this->post_data['offset'] < 0 ? 0 : $this->post_data['offset']) : OFFSET;
            $data['total']      = $this->model->no_job_karigar($search, ['wantCount' => true]);
            if((ceil($data['total'] / $limit-1)) > $offset) $data['next_offset'] = $offset + 1;
            $data['record']     = $this->model->no_job_karigar($search, ['limit' => $limit, 'offset' => ($limit * $offset)]);
            if (empty($data['record'])) return $this->response(['message' => 'Record not found.']);

            return $this->response(['status' => true, 'data' => $data]);
        }

}?>