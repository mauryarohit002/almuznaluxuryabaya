<?php
    $isConstant 	= in_array('constant', $action_data) ? 1 : 0;
	$auth 			= ['isConstant' => $isConstant];
    $this->load->view('pages/component/_list', ['add' => 'href='.base_url($menu.'/'.$sub_menu.'?action=add')]); 
?>
