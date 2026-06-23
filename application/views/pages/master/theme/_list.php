<?php 
	$this->load->view('templates/header'); 
	$menu   		= 'master';
	$sub_menu   	= 'theme';
	$action_data 	= get_action_data($menu, $sub_menu);
	$search_status 	= !isset($_GET['search_status']);
	$action 		= (isset($_GET['action'])) ? $_GET['action'] : "";
?>
<script>
    let link 	 = "<?php echo $menu; ?>";
    let sub_link = "<?php echo $sub_menu; ?>";
</script>
<section class="container-fluid sticky_top">
	<form class="form-horizontal" id="search_form" action="<?php echo base_url($menu.'/'.$sub_menu); ?>" method="get">
		<div class="d-flex justify-content-between">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			  <li class="breadcrumb-item"><a href="<?php echo base_url($menu.'/'.$sub_menu); ?>"><?php echo strtoupper($menu); ?></a></li>
			    <li class="breadcrumb-item active text-uppercase" aria-current="page">
					<?php echo strtoupper($sub_menu); ?> (<span id="count_reload"><i id="total_rows"><?php echo $total_rows;?></i></span>)
			    </li>
				<?php if(in_array('add', $action_data)): ?>
					<li class="breadcrumb-item" aria-current="add-page">
						<a 
							type="button" 
							class="btn btn-sm btn-primary"
							data-toggle="tooltip" 
							data-placement="bottom" 
							title="ADD NEW"
							href="<?php echo base_url($menu.'/'.$sub_menu.'?action=add'); ?>"
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
				<div class="col-6 col-sm-6 col-md-3 col-lg-3 floating-label">
					<?php if(isset($filters['_name'])): ?><p class="text-uppercase">name</p><?php endif; ?>
					<select class="form-control floating-select" id="_name" name="_name">
                    	<?php if(isset($filters['_name']) && !empty($filters['_name'])): ?>
                        	<option value="<?php echo $filters['_name']['value']; ?>" selected>
                            	<?php echo $filters['_name']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
                	</select>
				</div>
				<div class="col-6 col-sm-6 col-md-3 col-lg-2 floating-label">
					<?php if(isset($filters['_status'])): ?><p class="text-uppercase">status</p><?php endif; ?>
					<select class="form-control floating-select" id="_status" name="_status">
                    	<?php if(isset($filters['_status']) && !empty($filters['_status'])): ?>
                        	<option value="<?php echo $filters['_status']['value']; ?>" selected>
                            	<?php echo $filters['_status']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
	                </select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table class="table table-sm table-dark text-uppercase">
					<thead>
						<tr>
			                <th width="5%">#</th>
			                <th width="10%">name</th>
			                <th width="5%">status</th>
							<?php if(in_array('read', $action_data)): ?>
			                	<th width="3%">view</th> 
							<?php endif; ?>
							<?php if(in_array('edit', $action_data)): ?>
			                	<th width="3%">edit</th> 
							<?php endif; ?>
							<?php if(in_array('delete', $action_data)): ?>
			                	<th width="3%">delete</th>
							<?php endif; ?>

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
			<table class="table table-sm table-hover text-uppercase" id="table_reload">
				<tbody id="table_tbody">
					<?php 
						if(!empty($data['data'])): 
							foreach ($data['data'] as $key => $value):
                    			$id = encrypt_decrypt("encrypt", $value['theme_id'], SECRET_KEY);
					?>

								<tr class="<?php echo $value['theme_status'] == 0 ? 'text-danger' : '' ?>">
									<td width="5%"><?php echo $key+1; ?></td>
									<td width="10%"><?php echo $value['theme_name']; ?></td>
									<td width="5%"><?php echo $value['theme_status'] == 1 ? 'active' : 'inactive'; ?></td>
									<?php if(in_array('read', $action_data)): ?>
										<td width="3%">
											<a 
												type="button" 
												class="btn btn-sm btn-primary" 
												href="<?php echo base_url($menu.'/'.$sub_menu.'?action=read&id='.$id); ?>"
											><i class="text-info fa fa-eye"></i></a>										
										</td>
									<?php endif;?>
									<?php if(in_array('edit', $action_data)): ?>
										<td width="3%">
											<a 
												type="button" 
												class="btn btn-sm btn-primary" 
												href="<?php echo base_url($menu.'/'.$sub_menu.'?action=edit&id='.$id); ?>"
											><i class="text-success fa fa-edit"></i></a>										
										</td>
									<?php endif;?>
									<?php if(in_array('delete', $action_data)): ?>
										<td width="3%">
											<a 
												type="button" 
												class="btn btn-sm btn-primary" 
												onclick='theme_remove(<?php echo json_encode($value); ?>);'
											><i class="text-danger fa fa-trash"></i></a>
										</td>
									<?php endif;?>
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
<script src="<?php echo assets('dist/js/master/theme.js?v=1')?>"></script>
</body>
</html>