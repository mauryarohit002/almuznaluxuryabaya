<?php 
    $isTestEmail 	= in_array('test_email', $action_data) ? 1 : 0;
	$auth 			= ['isTestEmail' => $isTestEmail];
    $this->load->view('pages/component/_list', ['add' => 'onclick=company_popup('.json_encode([$auth]).')']); 
?>