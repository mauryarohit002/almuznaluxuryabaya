<?php 
	$this->load->view('templates/header'); 
	$action = (isset($_GET['action'])) ? $_GET['action'] : "";
	$entry_date 	= (isset($_GET['entry_date'])) ? $_GET['entry_date'] : "";
	$from_qty 		= (isset($_GET['from_qty'])) ? $_GET['from_qty'] : "";
	$to_qty 		= (isset($_GET['to_qty'])) ? $_GET['to_qty'] : "";
	$from_bill_amt 	= (isset($_GET['from_bill_amt'])) ? $_GET['from_bill_amt'] : "";
	$to_bill_amt 	= (isset($_GET['to_bill_amt'])) ? $_GET['to_bill_amt'] : "";
?>
<script>
    let link = "grn_pending";
    let sub_link = "grn_pending";
</script>
<section class="container-fluid sticky_top">
	<form class="form-horizontal" id="search_form" action="<?php echo base_url('transfer/grn/pending?action=view')?>" method="get">
		<div class="d-flex justify-content-between">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
	    	    <li class="breadcrumb-item active" aria-current="page">
			    	GRN PENDING(<span id="count_reload"><i id="total_rows"><?php echo $total_rows;?></i></span>)
			    </li>
			    <li class="breadcrumb-item" aria-current="search-page">
			    	<button type="submit" class="btn btn-sm btn-primary mr-2" id="btn_search" data-toggle="tooltip" data-placement="bottom" title="SEARCH">
			    		<i class="text-warning fa fa-search"></i>
			    	</button>
					<input type="hidden" name="action" value='<?php echo $action; ?>'>
			    </li>
			    <li class="breadcrumb-item" aria-current="reload-page">
			    	<a type="button" class="btn btn-sm btn-primary" onclick="redirectPage('transfer/grn/pending?action=view')" data-toggle="tooltip" data-placement="bottom" title="REFRESH"><i class="text-info fa fa-undo"></i></a>
			    </li>
			  </ol>
			</nav>
			<div class="d-none d-sm-block height_60_px">
				<?= $this->pagination->create_links(); ?>
			</div>
		</div>
		<!-- <div class="row">
			<div class="d-flex flex-wrap floating-form">
				<div class="col-6 col-sm-3 col-md-2 col-lg-1 floating-label">
					<?php if(isset($data['search']['entry_no'])): ?><p>ENTRY NO</p><?php endif; ?>
					<select class="form-control floating-select" id="entry_no" name="entry_no">
                    	<?php if(isset($data['search']['entry_no']) && !empty($data['search']['entry_no'])): ?>
                        	<option value="<?php echo $data['search']['entry_no']['value']; ?>" selected>
                            	<?php echo $data['search']['entry_no']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
                	</select>
				</div>
				<div class="col-6 col-sm-3 col-md-2 col-lg-1 floating-label">
					<input type="text" class="form-control floating-input datepicker" id="entry_date" name="entry_date" value="<?php echo $entry_date ?>" placeholder=" " autocomplete="off"/>   
                    <label for="inputEmail3">ENTRY DATE</label>
				</div>
				<div class="col-6 col-sm-4 col-md-3 col-lg-2 floating-label">
					<?php if(isset($data['search']['branch_id'])): ?><p>CUSTOMER</p><?php endif; ?>
					<select class="form-control floating-select" id="branch_id" name="branch_id">
                    	<?php if(isset($data['search']['branch_id']) && !empty($data['search']['branch_id'])): ?>
                        	<option value="<?php echo $data['search']['branch_id']['value']; ?>" selected>
                            	<?php echo $data['search']['branch_id']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
                	</select>
				</div>
				<div class="col-6 col-sm-3 col-md-2 col-lg-2 floating-label">
					<input type="number" class="form-control floating-input" id="from_qty" name="from_qty" value="<?php echo $from_qty ?>" placeholder=" " autocomplete="off"/>   
                    <label for="inputEmail3">FROM OUTWARD QTY</label>
				</div>
				<div class="col-6 col-sm-3 col-md-2 col-lg-2 floating-label">
					<input type="number" class="form-control floating-input" id="to_qty" name="to_qty" value="<?php echo $to_qty ?>" placeholder=" " autocomplete="off"/>   
                    <label for="inputEmail3">TO OUTWARD QTY</label>
				</div>
				<div class="col-6 col-sm-4 col-md-3 col-lg-2 floating-label">
					<input type="number" class="form-control floating-input" id="from_bill_amt" name="from_bill_amt" value="<?php echo $from_bill_amt ?>" placeholder=" " autocomplete="off"/>   
                    <label for="inputEmail3">FROM OUTWARD AMT</label>
				</div>
				<div class="col-6 col-sm-4 col-md-3 col-lg-2 floating-label">
					<input type="number" class="form-control floating-input" id="to_bill_amt" name="to_bill_amt" value="<?php echo $to_bill_amt ?>" placeholder=" " autocomplete="off"/>   
                    <label for="inputEmail3">TO OUTWARD AMT</label>
				</div>
			</div>
		</div> -->
		<div class="row">
			<div class="col-12">
				<table class="table table-sm table-dark">
					<thead>
						<tr>
			                <th width="3%">#</th>
	                        <th width="7%">ENTRY NO</th>
	                        <th width="10%">ENTRY DATE</th>
	                        <th width="10%">BRANCH</th>
	                        <th width="10%">OUTWARD QTY</th>
	                        <th width="10%">OUTWARD AMT</th>
	                        <th width="10%">GRN QTY</th>
	                        <th width="10%">GRN AMT</th>
	                        <th width="10%">PENDING QTY</th>
	                        <th width="10%">PENDING AMT</th>
	                        <th width="3%" align="center">RECEIVE</th> 
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
                    			$id = encrypt_decrypt("encrypt", $value['om_id'], SECRET_KEY);
					?>

								<tr>
									<td width="3%"><?php echo $key+1; ?></td>
									<td width="7%"><?php echo $value['om_entry_no']; ?></td>
									<td width="10%"><?php echo date('d-m-Y', strtotime($value['om_entry_date'])); ?></td>
									<td width="10%"><?php echo strtoupper($value['branch_name']); ?></td>
									<td width="10%"><?php echo $value['om_total_qty']; ?></td>
									<td width="10%"><?php echo round($value['om_final_amt'], 2); ?></td>
									<td width="10%"><?php echo $value['om_gm_total_qty']; ?></td>
									<td width="10%"><?php echo round($value['om_gm_final_amt'], 2); ?></td>
									<td width="10%"><?php echo $value['mis_qty']; ?></td>
									<td width="10%"><?php echo round($value['mis_amt'], 2); ?></td>
									<td width="3%">
										<?php if($value['isAnyReceived'] < 1): ?>
											<a type="button" class="btn btn-sm btn-primary" onclick="redirectPage('<?php echo 'transfer/grn/pending?action=add&id='.$value['om_id'] ?>')">
												<i class="text-success fa fa-eye"></i>
											</a>										
										<?php else: ?>
											<a type="button" class="btn btn-sm btn-primary" onclick="redirectPage('<?php echo 'grn?action=edit&id='.$value['isAnyReceived'] ?>')">
												<i class="text-success fa fa-eye"></i>
											</a>										
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
		<script src="<?php echo assets('dist/js/transfer/grn.js?v=2')?>"></script>
	</body>
</html>