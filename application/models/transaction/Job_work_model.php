<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Model.php';
class job_work_model extends my_model{
    public function __construct(){ parent::__construct('transaction', 'job_work'); }
    
}
?>