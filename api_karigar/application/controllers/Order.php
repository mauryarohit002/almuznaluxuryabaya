<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    use Restserver\Libraries\REST_Controller;
    require_once APPPATH . 'core/MY_Controller.php';
    class order extends my_controller{
        public function __construct(){
            parent::__construct([
                'model' => 'Order_model',
                'table' => 'order_master',
                'label' => 'Order',
            ]);
        }
       
        public function scan(){     
            $input = json_decode(file_get_contents("php://input"), true);

            if(!isset($this->post_data['barcode'])) return ['message' => 'barcode not defined.'];
            if(empty($this->post_data['barcode'])) return ['message' => 'barcode is empty.'];

            $km_data = $this->db_operations->get_record('karigar_master',['karigar_id'=>$this->user['id']]); 
            // echo "<pre>"; print_r($km_data);die;
            if(empty($km_data)){ 
                return ['message' => '1. Karigar does not exists!!'];
            }

            // if(empty($input['karigar_id'])) {  
            //     return ['message' => 'Select Karigar first!'];
            // }
             // print_r($input['karigar_id']);die;
            if(empty($input['karigar_id']) && $km_data[0]['karigar_type_id']==1){
                return ['message' => '1. Select Karigar first!'];
            }

            if(!empty($input['karigar_id']) && $km_data[0]['karigar_type_id']==0){  
                return ['message' => 'Only Manager can do production'];
            }

            if($km_data[0]['karigar_type_id']==1){  // FOR MANAGER 

                    $other_km = $this->db_operations->get_record('karigar_master',['karigar_id'=>$input['karigar_id']]);
                    if(empty($other_km)) return ['message' => '2. Karigar not Found!!'];

                    $data = $this->model->get_barcode_data($this->post_data['barcode']); 
                    if(empty($data))                    return ['message' => 'Barcode not found.'];
                    if(empty($data[0]['apparel_id']))   return ['message' => 'Apparel/Shoe not define.'];
                    if($data[0]['obt_delete_status'] == 1) return ['message' => 'Barcode is deleted.'];
                    if($data[0]['obt_delivered'] == 1) return ['message' => '1. Apparel/Shoe is delivered.'];

                    $process_data   = []; 
                    $latest_data    = $this->model->get_latest_issue_data($data[0]['obt_id']);
                    $job_data       = $this->model->get_receive_barcode_data($data[0]['obt_id']);
 
                    if($data[0]['obt_apparel_id']>0){ 
                        $temp = $this->model->get_process_data($input['karigar_id']); 
                        if(empty($temp)) return ['message' => '3. Process not define in karigar master.'];

                        $app=$this->model->is_apparel_exist($input['karigar_id'],$data[0]['apparel_id']); 
                        if(empty($app)) return ['message' => $data[0]['apparel_name'].' Apparel not define in karigar master.'];
                    }else{
                        if($other_km[0]['karigar_type_id'] !=2){
                            return ['message' => 'Shoes Process Not not define for karigar'];
                        }
                    } 
                
                    if(empty($latest_data)) { 
                        if($data[0]['obt_apparel_id']>0){ 
                            foreach ($temp as $key => $value) { 
                                $process_data[$key]['id']      = $value['process_id'];
                                $process_data[$key]['name']    = $value['process_name'];
                                $process_data[$key]['selected']= false;
                            }
                         }else{ 
                            $process_data[0]['id']      = 0;
                            $process_data[0]['name']    = 'START';
                            $process_data[0]['selected']= false;
                        }
                    }else if(!empty($latest_data) && empty($latest_data[0]['jrt_id'])) {    
                        $process_data[0]['id']      = -1;
                        $process_data[0]['name']    = 'FINISH';
                        $process_data[0]['selected']= false;
                    }else if(!empty($job_data) && !empty($job_data[0]['jrt_jit_id'])) {   
                        if($data[0]['obt_apparel_id']>0){ 
                            foreach ($temp as $key => $value) { 
                                $process_data[$key]['id']      = $value['process_id'];
                                $process_data[$key]['name']    = $value['process_name'];
                                $process_data[$key]['selected']= false;
                            }
                         }else{ 
                            $process_data[0]['id']      = 0;
                            $process_data[0]['name']    = 'START';
                            $process_data[0]['selected']= false;
                        }
                    } else{  
                        // echo "<pre>"; print_r($job_data);die; 
                        if($this->user['id'] == $job_data[0]['jim_karigar_id']){  
                        if($job_data[0]['jrt_jit_id'] < 1) {   
                                if($data[0]['obt_apparel_id']>0){ 
                                    $temp = $this->model->get_process_data($input['karigar_id']);
                                    if(empty($temp)) return ['message' => '5. Process not define in karigar master.'];

                                    foreach ($temp as $key => $value) {
                                        $process_data[$key]['id']      = $value['process_id'];
                                        $process_data[$key]['name']    = $value['process_name'];
                                        $process_data[$key]['selected']= false;
                                    }
                                    $process_data[count($temp)]['id']      = -1;
                                    $process_data[count($temp)]['name']    = 'FINISH';
                                    $process_data[count($temp)]['selected']= false;
                                }else{
                                    $process_data[0]['id']      = -1;
                                    $process_data[0]['name']    = 'FINISH';
                                    $process_data[0]['selected']= false;
                                }
                            } else {
                                return ['message' => '1. Job has been compeleted.'];
                            }
                        } else{  
                            if($job_data[0]['jrt_jit_id'] < 1) {
                                $process_data[0]['id']      = $value['process_id'];
                                $process_data[0]['name']    = 'ACCEPT';
                                $process_data[0]['selected']= true;
                            } else {
                                return ['message' => '1. Barcode accepted by other karigar.'];
                            }
                        }
                    }
            }else {
                                             // FOR KARIGAR
                   $data = $this->model->get_barcode_data($this->post_data['barcode']); 
                    if(empty($data))                    return ['message' => 'Barcode not found.'];
                    if(empty($data[0]['apparel_id']))   return ['message' => 'Apparel/Shoe not define.'];
                    if($data[0]['obt_delete_status'] == 1) return ['message' => 'Barcode is deleted.'];
                    if($data[0]['obt_delivered'] == 1) return ['message' => '1. Apparel is delivered.'];

                    $process_data   = []; 
                    $latest_data    = $this->model->get_latest_issue_data($data[0]['obt_id']);
                    $job_data       = $this->model->get_receive_barcode_data($data[0]['obt_id']);

                    if($data[0]['obt_apparel_id']>0){
                        $temp = $this->model->get_process_data($this->user['id']); 
                        if(empty($temp)) return ['message' => '6. Process not define in karigar master.'];

                        $app= $this->model->is_apparel_exist($this->user['id'],$data[0]['apparel_id']); 
                        if(empty($app)) return ['message' => $data[0]['apparel_name'].' Apparel not define in karigar master.'];
                    }else{
                        if($km_data[0]['karigar_type_id'] !=2){
                            return ['message' => '1. Shoes Process Not not define for karigar'];
                        }
                    }

                    if(empty($latest_data)) { 
                        if($data[0]['obt_apparel_id']>0){ 
                            foreach ($temp as $key => $value) { 
                                $process_data[$key]['id']      = $value['process_id'];
                                $process_data[$key]['name']    = $value['process_name'];
                                $process_data[$key]['selected']= false;
                            }
                         }else{ 
                            $process_data[0]['id']      = 0;
                            $process_data[0]['name']    = 'START';
                            $process_data[0]['selected']= false;
                        }
                    }else if(!empty($latest_data) && empty($latest_data[0]['jrt_id'])) {    
                        $process_data[0]['id']      = -1;
                        $process_data[0]['name']    = 'FINISH';
                        $process_data[0]['selected']= false;
                    }else if(!empty($job_data) && !empty($job_data[0]['jrt_jit_id'])) {   
                        if($data[0]['obt_apparel_id']>0){ 
                            foreach ($temp as $key => $value) { 
                                $process_data[$key]['id']      = $value['process_id'];
                                $process_data[$key]['name']    = $value['process_name'];
                                $process_data[$key]['selected']= false;
                            }
                         }else{ 
                            $process_data[0]['id']      = 0;
                            $process_data[0]['name']    = 'START';
                            $process_data[0]['selected']= false;
                        }
                    } else{  
                        // echo "<pre>"; print_r($job_data);die; 
                        if($this->user['id'] == $job_data[0]['jim_karigar_id']){ 
                        if($job_data[0]['jrt_jit_id'] < 1) {  
                                if($data[0]['obt_apparel_id']>0){ 
                                    $temp = $this->model->get_process_data($this->user['id']);
                                    if(empty($temp)) return ['message' => '7. Process not define in karigar master.'];

                                    foreach ($temp as $key => $value) {
                                        $process_data[$key]['id']      = $value['process_id'];
                                        $process_data[$key]['name']    = $value['process_name'];
                                        $process_data[$key]['selected']= false;
                                    }
                                    $process_data[count($temp)]['id']      = -1;
                                    $process_data[count($temp)]['name']    = 'FINISH';
                                    $process_data[count($temp)]['selected']= false;
                                }else{
                                    $process_data[0]['id']      = -1;
                                    $process_data[0]['name']    = 'FINISH';
                                    $process_data[0]['selected']= false;
                                }
                            } else {
                                return ['message' => '1. Job has been compeleted.'];
                            }
                        } else{  
                            if($job_data[0]['jrt_jit_id'] < 1) {
                                $process_data[0]['id']      = $input['process_id'];
                                $process_data[0]['name']    = 'ACCEPT';
                                $process_data[0]['selected']= true;
                            } else {
                                return ['message' => '1. Barcode accepted by other karigar.'];
                            }
                        }
                    }     
            }

            $resp = [];
            $resp['barcode_data'] = $data;
            $resp['process_data'] = $process_data;
            return ['status' => TRUE,'data' => $resp, 'message' => 'Barcode Scanned successfully..'];
        }

        public function store_manager_job(){    
            $input = json_decode(file_get_contents("php://input"), true);

            if(!isset($this->post_data['barcode'])) return ['message' => 'Barcode id not define.'];
            if(empty($this->post_data['barcode'])) return ['message' => 'Barcode id not empty.'];
            if(!isset($input['karigar_id'])) return ['message' => 'Karigar not define.'];
            if(!isset($input['process_id'])) return ['message' => 'Process id not define.'];
            
            if($this->user['type_id']!=1){  
                return ['message' => 'Only Manager Can Do Production '];
            }

            $km_data = $this->db_operations->get_record('karigar_master',['karigar_id'=>$input['karigar_id']]);
            if(empty($km_data)){ 
                return ['message' => '2. Karigar does not exists!!'];
            }
            
            $data = $this->model->get_barcode_data($this->post_data['barcode']); 
            if(empty($data))                    return ['message' => 'Barcode not found.'];
            if(empty($data[0]['apparel_id']))   return ['message' => 'Apparel/Shoe not define.'];
            if($data[0]['obt_delete_status'] == 1)   return ['message' => 'Barcode is deleted.'];
            if($data[0]['obt_delivered'] == 1) return ['message' => '1. Apparel is delivered.'];
            if($input['process_id'] > 0) {
                $process_data = $this->model->is_process_exist($input['karigar_id'], $input['process_id']);
                if(empty($process_data)) return ['message' => '1. Process not define in karigar master.'];
            }

            if($data[0]['obt_apparel_id']>0){
                $app = $this->model->is_apparel_exist($input['karigar_id'],$data[0]['apparel_id']); 
                if(empty($app)) return ['message' => $data[0]['apparel_name'].' Apparel not define in karigar master.'];
            }

            if($input['process_id'] >= 0) { 
                // if($input['process_id']==EMBROIDERY && $data[0]['obt_embroidery']==0){
                //     return ['message' => 'Barcode is Not For Embroidery.'];
                // }

                // if($input['process_id']==MAKKI && $data[0]['obt_makki']==0){
                //     return ['message' => 'Barcode is Not For Makki.'];
                // }

                // if($input['process_id']==M_EMBROIDERY && $data[0]['obt_m_embroidery']==0){
                //     return ['message' => 'Barcode is Not For Machine Embroidery.'];
                // }

                // if($input['process_id']==D_MAKKI && $data[0]['obt_d_makki']==0){
                //     return ['message' => 'Barcode is Not For D - Makki.'];
                // }

                    // ISSUE 
                    $latest_data = $this->model->get_latest_issue_data($data[0]['obt_id']); 
                    // echo "<pre>"; print_r($latest_data);die;
                    if(!empty($latest_data)){      
                        if($latest_data[0]['jrt_id'] == 0){
                            if($input['karigar_id'] == $latest_data[0]['jim_karigar_id']){ 
                                return ['message' => '1. Job already accepted in '.$latest_data[0]['proces_name']];
                            }else{
                                return ['message' => '1. Barcode accepted by other karigar.'];
                            }   
                        }else{
                            if(trim($input['process_id'])==$latest_data[0]['jim_proces_id']){
                                return ['message' => '1. Barcode Already Finished In '. $latest_data[0]['proces_name']];     
                            } 

                        }
                    } 

                    $process_exist = $this->model->get_process_wise_data($data[0]['obt_id'], $input['process_id']);
                    if(!empty($process_exist)) {
                        return ['message' => '1. Barcode already in '.$process_exist[0]['proces_name']];
                    }  

                    $job_data = $this->model->get_issue_barcode_data($data[0]['obt_id']);
                    $master_data['jim_uuid']       = trim($input['karigar_id'].time());
                    $master_data['jim_proces_id']  = trim($input['process_id']);
                    $master_data['jim_karigar_id'] = trim($input['karigar_id']);
                    $master_data['jim_karigar_manager_id'] = $this->user['id'];

                    $master_data['jim_entry_date'] = date('Y-m-d');
                    $master_data['jim_entry_no']    = $this->model->get_max_issue_entry_no($this->user['financial_year']);
                    $master_data['jim_entry_date']  = date('Y-m-d');
                    $master_data['jim_fin_year']    = $this->user['financial_year'];
                    $master_data['jim_branch_id']   = $this->user['branch_id'];
                    $master_data['jim_created_by']  = $this->user['id']; 
                    $master_data['jim_created_at']  = date('Y-m-d H:i:s');
                    $master_data['jim_updated_by']  = $this->user['id'];
                    $master_data['jim_updated_at']  = date('Y-m-d H:i:s');

                    $id = $this->db_operations->data_insert('job_issue_master', $master_data);
                    $message = 'Job issue added successfully.';
                    if($id < 1){ 
                        return ['message' => '1. Job issue not added.'];
                    }

                    $_status=0;
                    $result = $this->add_update_issue_trans($id,$data[0]['obt_id'],$master_data['jim_uuid'],$_status);
                    if(!isset($result['status'])){
                        return $result;
                    } 
                   
                    $message="Job added successfully."; 
               
            }else{       
                // RECEIVE
                    $job_data = $this->model->get_receive_barcode_data($data[0]['obt_id']); 
                    // echo "<pre>"; print_r($job_data);die;
                    if(empty($job_data)){
                        return ['message' => '1. Barcode Not In Issue.']; 
                    } 
                    if($job_data[0]['jrt_jit_id']>0){
                        return ['message' => '1. Barcode Already accepted in '. $job_data[0]['proces_name']];  
                    }  

                    if($input['karigar_id'] == $job_data[0]['jim_karigar_id']){   
                        $master_data =[];
                        $master_data['jrm_uuid']       = trim($input['karigar_id'].time());
                        $master_data['jrm_entry_date'] = date('Y-m-d');
                        $master_data['jrm_entry_no']   = $this->model->get_max_receive_entry_no($this->user['financial_year']);
                        $master_data['jrm_entry_date']  = date('Y-m-d');
                        $master_data['jrm_fin_year']    = $this->user['financial_year'];
                        $master_data['jrm_branch_id']   =$this->user['branch_id'];
                        $master_data['jrm_created_by']  = $this->user['id'];
                        $master_data['jrm_created_at']  = date('Y-m-d H:i:s');
                        $master_data['jrm_updated_by']  = $this->user['id'];
                        $master_data['jrm_updated_at']  = date('Y-m-d H:i:s');
                        $id = $this->db_operations->data_insert('job_receive_master', $master_data);
                        $message = 'Job receive added successfully.';
                        if($id < 1){
                            return ['message' => '1. Job receive not added.'];
                        }
                        $result = $this->add_update_receive_trans($job_data,$id,$master_data['jrm_uuid']);
                        if(!isset($result['status'])){
                            return $result;
                        }
                        $message="Job finished successfully.";
                    }else{  
                       return ['message' => '1. Barcode Already Issueed To '.$job_data[0]['karigar_name']];     
                    } 
            }

            return ['status' => true,'data' => ['job_id' => $id], 'message' => $message, 'code' => REST_Controller::HTTP_OK];
        }

        public function store_job(){ 
                $input = json_decode(file_get_contents("php://input"), true);
            
                if(!isset($this->post_data['barcode'])) return ['message' => 'Barcode id not define.'];
                if(empty($this->post_data['barcode'])) return ['message' => 'Barcode id not empty.'];

                if(!isset($input['process_id'])) return ['message' => 'Process id not define.'];
                $km_data = $this->db_operations->get_record('karigar_master',['karigar_id'=>$this->user['id']]);
                if(empty($km_data)){
                    return ['message' => '1. Karihar does not exists!!'];
                }

                if($this->user['type_id']==1){  
                    return ['message' => 'Other karigar Can Do Production '];
                }

                $data = $this->model->get_barcode_data($this->post_data['barcode']);  
                if(empty($data))                    return ['message' => 'Barcode not found.'];
                if(empty($data[0]['apparel_id']))   return ['message' => 'Apparel/Shoe not define.'];
                if($data[0]['obt_delete_status'] == 1)   return ['message' => 'Barcode is deleted.'];
                if($data[0]['obt_delivered'] == 1) return ['message' => '1. Apparel is delevered.'];
                if($input['process_id'] > 0) {
                    $process_data = $this->model->is_process_exist($this->user['id'], $input['process_id']);
                    if(empty($process_data)) return ['message' => '2. Process not define in karigar master.'];
                }

                if($data[0]['obt_apparel_id']>0){
                    $app = $this->model->is_apparel_exist($this->user['id'],$data[0]['apparel_id']); 
                    if(empty($app)) return ['message' => $data[0]['apparel_name'].' Apparel not define in karigar master.'];
                }

                if($input['process_id'] >= 0) {
                    // if($input['process_id']==EMBROIDERY && $data[0]['obt_embroidery']==0){
                    //     return ['message' => 'Barcode is Not For Embroidery.'];
                    // }

                    // if($input['process_id']==MAKKI && $data[0]['obt_makki']==0){
                    //     return ['message' => 'Barcode is Not For Makki.'];
                    // }

                    // if($input['process_id']==M_EMBROIDERY && $data[0]['obt_m_embroidery']==0){
                    //     return ['message' => 'Barcode is Not For Machine Embroidery.'];
                    // }

                    // if($input['process_id']==D_MAKKI && $data[0]['obt_d_makki']==0){
                    //     return ['message' => 'Barcode is Not For D - Makki.'];
                    // }
      

                    // ISSUE 
                        $latest_data = $this->model->get_latest_issue_data($data[0]['obt_id']); 
                        // echo "<pre>"; print_r($latest_data);die;
                        if(!empty($latest_data)){      
                            if($latest_data[0]['jrt_id'] == 0){ 
                                if($this->user['id'] == $latest_data[0]['jim_karigar_id']){ 
                                    return ['message' => '1. Job already accepted in '.$latest_data[0]['proces_name']];
                                }else{
                                    return ['message' => '1. Barcode accepted by other karigar.'];
                                }   
                            }else{ 
                                if(trim($input['process_id'])==$latest_data[0]['jim_proces_id']){
                                    return ['message' => '1. Barcode Already Finished In '. $latest_data[0]['proces_name']];     
                                }    
                            }
                        }

                        $process_exist = $this->model->get_process_wise_data($data[0]['obt_id'], $input['process_id']);
                        if(!empty($process_exist)) {
                            return ['message' => '1. Barcode already in '.$process_exist[0]['proces_name']];
                        } 
                       
                        $job_data = $this->model->get_issue_barcode_data($data[0]['obt_id']);
                        $master_data['jim_uuid']       = trim($this->user['id'].time());
                        $master_data['jim_proces_id']  = trim($input['process_id']);
                        $master_data['jim_karigar_id'] = trim($this->user['id']);
                        $master_data['jim_entry_date'] = date('Y-m-d');
                        $master_data['jim_entry_no']    = $this->model->get_max_issue_entry_no($this->user['financial_year']);
                        $master_data['jim_entry_date']  = date('Y-m-d');
                        $master_data['jim_fin_year']    = $this->user['financial_year'];
                        $master_data['jim_branch_id']   = $this->user['branch_id'];
                        $master_data['jim_created_by']  = $this->user['id'];
                        $master_data['jim_created_at']  = date('Y-m-d H:i:s');
                        $master_data['jim_updated_by']  = $this->user['id'];
                        $master_data['jim_updated_at']  = date('Y-m-d H:i:s');

                        $id = $this->db_operations->data_insert('job_issue_master', $master_data);
                        $message = 'Job issue added successfully.';
                        if($id < 1){  
                            return ['message' => '1. Job issue not added.'];
                        }
                        $_status=0;
                        $result = $this->add_update_issue_trans($id,$data[0]['obt_id'],$master_data['jim_uuid'],$_status);
                        if(!isset($result['status'])){
                            return $result;
                        } 
                       
                        $message="Job added successfully."; 
                   
                }else{      
                    // RECEIVE
                    $job_data = $this->model->get_receive_barcode_data($data[0]['obt_id']); 
                    // echo "<pre>"; print_r($job_data);die;
                    if(empty($job_data)){
                        $this->db->trans_rollback();
                        return ['message' => '1. Barcode Not In Issue.']; 
                    } 
                    if($job_data[0]['jrt_jit_id']>0){
                        $this->db->trans_rollback();
                        return ['message' => '1. Barcode Already accepted in '. $job_data[0]['proces_name']];  
                    }  

                    if($this->user['id'] == $job_data[0]['jim_karigar_id']){   
                        $master_data =[];
                        $master_data['jrm_uuid']       = trim($this->user['id'].time());
                        $master_data['jrm_entry_date'] = date('Y-m-d');
                        $master_data['jrm_entry_no']   = $this->model->get_max_receive_entry_no($this->user['financial_year']);
                        $master_data['jrm_entry_date']  = date('Y-m-d');
                        $master_data['jrm_fin_year']    = $this->user['financial_year'];
                        $master_data['jrm_branch_id']   =  $this->user['branch_id'];
                        $master_data['jrm_created_by']  = $this->user['id'];
                        $master_data['jrm_created_at']  = date('Y-m-d H:i:s');
                        $master_data['jrm_updated_by']  = $this->user['id'];
                        $master_data['jrm_updated_at']  = date('Y-m-d H:i:s');
                        $id = $this->db_operations->data_insert('job_receive_master', $master_data);
                        $message = 'Job receive added successfully.';
                        if($id < 1){
                            return ['message' => '1. Job receive not added.'];
                        }
                        $result = $this->add_update_receive_trans($job_data,$id,$master_data['jrm_uuid']);
                        if(!isset($result['status'])){
                            return $result;
                        }
                        $message="Job finished successfully.";
                    }else{  
                       return ['message' => '1. Barcode Already Issueed To '.$job_data[0]['karigar_name']];     
                    } 
                }

                return ['status' => true,'data' => ['job_id' => $id], 'message' => $message, 'code' => REST_Controller::HTTP_OK];


        }

        public function add_update_issue_trans($id,$obt_id,$uuid,$_status){
            // foreach ($post_data['trans_data'] as $key => $value){
                $trans_data                     = [];
                $trans_data['jit_jim_id']       = $id;
                $trans_data['jit_jim_uuid']     = $uuid;
                $trans_data['jit_obt_id']       = $obt_id;
                $trans_data['jit_updated_at']   = date('Y-m-d H:i:s');
                if($_status == 0){
                    $trans_data['jit_created_by']   = $this->user['id'];
                    $trans_data['jit_created_at']   = date('Y-m-d H:i:s');
                    if($this->db_operations->data_insert('job_issue_trans', $trans_data) < 1) return ['message' => 'Transaction not added.'];
                }
            // }

            return ['status' => TRUE];
        }

        public function add_update_receive_trans($data,$id,$uuid){

            // foreach ($post_data['trans_data'] as $key => $value){
                $trans_data                     = [];
                $trans_data['jrt_jrm_id']       = $id;
                $trans_data['jrt_jrm_uuid']     = $uuid;
                $trans_data['jrt_obt_id']       = trim($data[0]['obt_id']);
                $trans_data['jrt_jim_id']       = trim($data[0]['jim_id']);
                $trans_data['jrt_jit_id']       = trim($data[0]['jit_id']);
                $trans_data['jrt_updated_by']   = $this->user['id'];
                $trans_data['jrt_updated_at']   = date('Y-m-d H:i:s');
                $trans_data['jrt_created_by']   = $this->user['id'];
                $trans_data['jrt_created_at']   = date('Y-m-d H:i:s');
                if($this->db_operations->data_insert('job_receive_trans', $trans_data) < 1) return ['message' => 'Transaction not added.'];

            // }
            return ['status' => TRUE];
        }



        
        

}?>