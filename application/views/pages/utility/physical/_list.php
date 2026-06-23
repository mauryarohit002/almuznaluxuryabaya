<?php 
	$this->load->view('templates/header'); 
    $menu 				= 'utility';
	$sub_menu 			= 'physical';
	$search_status 		= !isset($_GET['search_status']);
	$action 			= (isset($_GET['action'])) ? $_GET['action'] : "";
    
	$_entry_date_from 	= (isset($_GET['_entry_date_from'])) ? $_GET['_entry_date_from'] : date('Y-m-d');
	$_entry_date_to 	= (isset($_GET['_entry_date_to'])) ? $_GET['_entry_date_to'] : date('Y-m-d');
	$_scan_qty_from	 	= (isset($_GET['_scan_qty_from'])) ? $_GET['_scan_qty_from'] : "";
	$_scan_qty_to		= (isset($_GET['_scan_qty_to'])) ? $_GET['_scan_qty_to'] : "";
	$_unscan_qty_from	= (isset($_GET['_unscan_qty_from'])) ? $_GET['_unscan_qty_from'] : "";
	$_unscan_qty_to 	= (isset($_GET['_unscan_qty_to'])) ? $_GET['_unscan_qty_to'] : "";
	// pre(count($data['data']));
?>
<script>
    let link 	= '<?php echo $menu ?>';
    let sub_link= '<?php echo $sub_menu ?>';
</script>
<section class="container-fluid sticky_top">
	<form class="form-horizontal" id="search_form" action="<?php echo base_url($menu.'/'.$sub_menu)?>" method="get">
		<div class="d-flex justify-content-between">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
                <li class="breadcrumb-item text-uppercase">
                    <a href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list'); ?>"><?php echo $menu; ?></a>
			    </li>
                <li class="breadcrumb-item active text-uppercase" aria-current="page">
			    	<?php echo str_replace('_', ' ', $sub_menu); ?> (<span id="count_reload"><i id="total_rows"><?php echo $total_rows;?></i></span>)
			    </li>
			    <?php if(empty($data['data'])): ?>
				    <li class="breadcrumb-item" aria-current="add-page">
						<a 
							type="button" 
							class="btn btn-sm btn-primary"
							data-toggle="tooltip" 
							data-placement="bottom" 
							title="ADD NEW"
							onclick="initiate_process()" 
						><i class="text-success fa fa-plus"></i></a>
					</li>
				<?php elseif(count($data['data']) == 0): ?>
					<li class="breadcrumb-item" aria-current="add-page">
						<a 
							type="button" 
							class="btn btn-sm btn-primary"
							data-toggle="tooltip" 
							data-placement="bottom" 
							title="ADD NEW"
							onclick="initiate_process()" 
						><i class="text-success fa fa-plus"></i></a>
					</li>
				<?php endif; ?>
			    <li class="breadcrumb-item" aria-current="search-page">
			    	<button 
						type="submit" 
						class="btn btn-sm btn-primary mr-2" 
						id="btn_search" 
						data-toggle="tooltip" 
						data-placement="bottom" 
						title="SEARCH"
					><i class="text-warning fa fa-search"></i></button>
			    	<input type="hidden" name="action" value='<?php echo $action; ?>'>
			    </li>
			    <li class="breadcrumb-item" aria-current="refresh-page">
			    	<a 
			    		type="button" 
			    		class="btn btn-sm btn-primary" 
			    		data-toggle="tooltip" 
			    		data-placement="bottom" 
			    		title="REFRESH"
			    		href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list'); ?>"
		    		><i class="text-info fa fa-undo"></i></a>
			    </li>
			    <li class="breadcrumb-item" aria-current="search-box">
			    	<input 
			    		type="checkbox" 
			    		id="search_status" 
			    		name="search_status" 
			    		data-toggle="toggle" 
			    		data-on="FILTER <i class='fa fa-eye'></i>" 
			    		data-off="FILTER <i class='fa fa-eye-slash'></i>" 
			    		data-onstyle="primary" 
			    		data-offstyle="primary" 
			    		data-width="100" 
			    		data-size="mini" 
			    		data-style="show-hide" 
			    		onchange="set_search_box()" <?php echo empty($search_status) ? 'checked' : ''; ?>
		    		/>
			    </li>
			  </ol>
			</nav>
			<div class="d-none d-sm-block height_60_px">
				<?= $this->pagination->create_links(); ?>
			</div>
		</div>
		<div class="row collapse mt-2 <?php echo empty($search_status) ? '' : 'show'  ?>" id="search_box">
			<div class="d-flex flex-wrap justify-content-center floating-form">
                <div class="col-6 col-sm-6 col-md-3 col-lg-2 floating-label">
					<?php if(isset($data['search']['_entry_no'])): ?><p class="text-uppercase">entry no</p><?php endif; ?>
					<select class="form-control floating-select" id="_entry_no" name="_entry_no">
                    	<?php if(isset($data['search']['_entry_no']) && !empty($data['search']['_entry_no'])): ?>
                        	<option value="<?php echo $data['search']['_entry_no']['value']; ?>" selected>
                            	<?php echo $data['search']['_entry_no']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
                	</select>
				</div>
				<div class="d-flex col-6 col-sm-6 col-md-4 col-lg-3">
					<div class="floating-label">
						<input 
							type="date" 
							class="form-control floating-input" 
							id="_entry_date_from" 
							name="_entry_date_from" 
							value="<?php echo $_entry_date_from ?>" 
							placeholder=" " 
							autocomplete="off"
						/>   
	                    <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
					</div>
					<div class="floating-label">
						<input 
							type="date" 
							class="form-control floating-input" 
							id="_entry_date_to" 
							name="_entry_date_to" 
							value="<?php echo $_entry_date_to ?>" 
							placeholder=" " 
							autocomplete="off"
						/>   
	                    <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
					</div>
				</div>
				<div class="d-flex col-12 col-sm-12 col-md-4 col-lg-3">
					<div class="floating-label">
						<input 
                            type="number" 
                            class="form-control floating-input" 
                            id="_scan_qty_from" 
                            name="_scan_qty_from" 
                            value="<?php echo $_scan_qty_from ?>" 
                            placeholder=" " 
                            autocomplete="off" 
                            onchange="trigger_search()"
                        />   
	                    <label class="text-uppercase">SCAN QTY <small class="font-weight-bold">FROM</small></label>
					</div>
					<div class="floating-label">
						<input 
                            type="number" 
                            class="form-control floating-input" 
                            id="_scan_qty_to" 
                            name="_scan_qty_to" 
                            value="<?php echo $_scan_qty_to ?>" 
                            placeholder=" " 
                            autocomplete="off" 
                            onchange="trigger_search()"
                        />   
	                    <label class="text-uppercase">SCAN QTY <small class="font-weight-bold">TO</small></label>
					</div>
				</div>
				<div class="d-flex col-12 col-sm-12 col-md-4 col-lg-3">
					<div class="floating-label">
						<input 
                            type="number" 
                            class="form-control floating-input" 
                            id="_unscan_qty_from" 
                            name="_unscan_qty_from" 
                            value="<?php echo $_unscan_qty_from ?>" 
                            placeholder=" " 
                            autocomplete="off" 
                            onchange="trigger_search()"
                        />   
	                    <label class="text-uppercase">UNSCAN QTY <small class="font-weight-bold">FROM</small></label>
					</div>
					<div class="floating-label">
						<input 
                            type="number" 
                            class="form-control floating-input" 
                            id="_unscan_qty_to" 
                            name="_unscan_qty_to" 
                            value="<?php echo $_unscan_qty_to ?>" 
                            placeholder=" " 
                            autocomplete="off" 
                            onchange="trigger_search()"
                        />   
	                    <label class="text-uppercase">UNSCAN QTY <small class="font-weight-bold">TO</small></label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
                <table class="table table-sm table-dark text-uppercase">
					<thead>
						<tr>
			                <th width="3%">#</th>
	                        <th width="4%">ENTRY NO</th>
	                        <th width="6%">ENTRY DATE</th>
	                        <th width="5%">SCAN QTY</th>
	                        <th width="5%">UNSCAN QTY</th>
                        	<th width="3%">edit</th> 
							<th width="3%">delete</th>
			            </tr>
					</thead>
				</table>
			</div>
		</div>
	</form>
</section>
<section class="container-fluid">
	<div class="row">
		<div class="col-12">
			<table class="table table-sm table-reponsive table-hover" id="table_reload">
				<tbody id="table_tbody">
					<?php 
						if(!empty($data['data'])): 
							foreach ($data['data'] as $key => $value):
                    			$id = encrypt_decrypt("encrypt", $value['psm_id'], SECRET_KEY);
					?>

								<tr>
									<td width="3%"><?php echo $key+1; ?></td>
									<td width="4%"><?php echo $value['psm_entry_no']; ?></td>
									<td width="6%"><?php echo date('d-m-Y', strtotime($value['psm_entry_date'])); ?></td>
									<td width="5%"><?php echo $value['psm_scan_qty']; ?></td>
									<td width="5%"><?php echo $value['psm_unscan_qty']; ?></td>
									<td width="3%">
										<a 
											type="button" 
											class="btn btn-sm btn-primary" 
											href="<?php echo base_url($menu.'/'.$sub_menu.'?action=edit&id='.$id); ?>"
										><i class="text-success fa fa-edit"></i></a>										
									</td>
									<td width="3%">
										<?php if($value['isExist']): ?>
											<button type="button" class="btn btn-sm btn-primary"><i class="text-danger fa fa-ban"></i></button>
										<?php else: ?>
											<a 
												type="button" 
												class="btn btn-sm btn-primary" 
												onclick='remove_record(<?php echo json_encode($value); ?>);'
											><i class="text-danger fa fa-trash"></i></a>
										<?php endif; ?>                         
									</td>
								</tr>
					<?php 
							endforeach;
						else: 
					?>
						<tr>
							<td class="text-danger font-weight-bold text-center" colspan="10">NO RECORD FOUND!!!</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</section>
<?= $this->pagination->create_links(); ?>
<?php $this->load->view('templates/footer'); ?>
<script src="<?php echo assets('dist/js/utility/physical.js')?>"></script>
</body>
</html>