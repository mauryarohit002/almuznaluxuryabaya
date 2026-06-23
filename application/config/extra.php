<?php
	$config['title'][1] = 'C&C'; 
	$config['title'][2] = '';

	$config['status'][1]= 'ACTIVE';
	$config['status'][2]= 'INACTIVE';

	$config['impact_on']['NORMAL']   = 'NORMAL';
	$config['impact_on']['PACK LIST']= 'PACK LIST';

	$config['executive_type']['SALES EXECUTIVE']   = 'SALES EXECUTIVE';
	$config['executive_type']['GODOWN EXECUTIVE']  = 'GODOWN EXECUTIVE';

	$config['launch_type']['WHOLESALE'] = 'WHOLESALE';
	$config['launch_type']['GARMENT']  	= 'GARMENT';
	$config['launch_type']['DIVISION']  = 'DIVISION';
	$config['launch_type']['CUTTING']  	= 'CUTTING';
	$config['launch_type']['CUT-PACK']  = 'CUT-PACK';
	$config['launch_type']['FLOOR']  	= 'FLOOR';
	
	$config['remark_type']['ON TIME PAYMENT']  	= 'ON TIME PAYMENT';
	$config['remark_type']['PAYMENT DELAY']  	= 'PAYMENT DELAY';

	$config['order_status'][1]  	= 'PENDING';
	$config['order_status'][2]  	= 'COMPLETED';
	$config['order_status'][3]  	= 'PENDING FOR STAFF APPROVAL';
	$config['order_status'][4]  	= 'PENDING FOR ADMIN APPROVAL';
	$config['order_status'][5]  	= 'APPROVED BY STAFF';
	$config['order_status'][6]  	= 'APPROVED BY ADMIN';
	$config['order_status'][7]  	= 'REJECT BY STAFF';
	$config['order_status'][8]  	= 'REJECT BY ADMIN';
	$config['order_status'][9]  	= 'DISPATCHING';

	$config['role']['ADMIN'] 		= 'ADMIN';
	$config['role']['PURCHASE'] 	= 'PURCHASE';
	$config['role']['SALES'] 		= 'SALES';
	$config['role']['DISPATCH']		= 'DISPATCH';
	$config['role']['SOURCING']		= 'SOURCING';

	$config['group']['CUSTOMER']= 'CUSTOMER';
	// $config['group']['GENERAL'] = 'GENERAL';
	$config['group']['SUPPLIER']= 'SUPPLIER';

	$config['supplier_constant'] = ['BLOCK_GOODS', 'SALES_RETURN'];
	$config['user_constant'] 	 = ['BLOCK_GOODS'];

	$config['pagination']['query_string_segment'] 	= 'offset';
	$config['pagination']['page_query_string'] 		= true;
	$config['pagination']['total_rows'] 			= TOTAL_ROWS;
	$config['pagination']['per_page'] 				= PER_PAGE;

	$config['pagination']['full_tag_open'] 			= '
														<nav aria-label="Page navigation example">
															<ul class="pagination justify-content-center">
													  ';

	$config['pagination']['prev_tag_open']			= '
																<li class="page-item">
													  ';
	
	$config['pagination']['prev_link']				= '
																		<span aria-hidden="true"><i class="fa fa-backward"></i></span>
																		<span class="sr-only">PREVIOUS</span>
													  ';

	$config['pagination']['prev_tag_close'] 		= '
																</li>
													  ';
	

	$config['pagination']['first_tag_open']			= '
																<li class="page-item">
													  ';
	
	$config['pagination']['first_link']				= '
																		<span aria-hidden="true"><i class="fa fa-step-backward"></i></span>
																		<span class="sr-only">FIRST</span>
													  ';

	$config['pagination']['first_tag_close'] 		= '
																</li>
													  ';													  						
	

	$config['pagination']['last_tag_open']			= '
																<li class="page-item">
													  ';
	
	$config['pagination']['last_link']				= '
																		<span aria-hidden="true"><i class="fa fa-step-forward"></i></span>
																		<span class="sr-only">LAST</span>
													  ';

	$config['pagination']['last_tag_close'] 		= '
																</li>
													  ';													  						

	
	$config['pagination']['next_tag_open']			= '
																<li class="page-item">
													  ';
	
	$config['pagination']['next_link']				= '
																		<span aria-hidden="true"><i class="fa fa-forward"></i></span>
																		<span class="sr-only">NEXT</span>
													  ';

	$config['pagination']['next_tag_close'] 		= '
																</li>
													  ';													  					

	$config['pagination']['num_tag_open']			= '
																<li class="page-item">
													  ';
	
	$config['pagination']['num_tag_close']			= '
																</li>
													  ';												  							

	$config['pagination']['cur_tag_open']			= '
																<li class="page-item active">
																	<span class="page-link">
													  ';
	
	$config['pagination']['cur_tag_close']			= '
																	</span>
																</li>
													  ';															  							  

	$config['pagination']['full_tag_close'] 		= '
															</ul>
														</nav>
													  ';
?>