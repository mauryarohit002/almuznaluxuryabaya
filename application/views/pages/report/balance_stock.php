<?php 
	$this->load->view('templates/header'); 
	$action 			= (isset($_GET['action'])) ? $_GET['action'] : "";
	$search_status 		= !isset($_GET['search_status']);
	$prmt_amt_frm 		= (isset($_GET['prmt_amt_frm'])) ? $_GET['prmt_amt_frm'] : "";
	$prmt_amt_to 			= (isset($_GET['prmt_amt_to'])) ? $_GET['prmt_amt_to'] : "";
	$ot_amt_frm 		= (isset($_GET['ot_amt_frm'])) ? $_GET['ot_amt_frm'] : "";
	$ot_amt_to 			= (isset($_GET['ot_amt_to'])) ? $_GET['ot_amt_to'] : "";
	$sold_amt_frm 		= (isset($_GET['sold_amt_frm'])) ? $_GET['sold_amt_frm'] : "";
	$sold_amt_to 		= (isset($_GET['sold_amt_to'])) ? $_GET['sold_amt_to'] : "";
	$bal_qty_frm 		= (isset($_GET['bal_qty_frm'])) ? $_GET['bal_qty_frm'] : "";
	$bal_qty_to 		= (isset($_GET['bal_qty_to'])) ? $_GET['bal_qty_to'] : "";
	$bal_amt_frm 		= (isset($_GET['bal_amt_frm'])) ? $_GET['bal_amt_frm'] : "";
	$bal_amt_to 		= (isset($_GET['bal_amt_to'])) ? $_GET['bal_amt_to'] : "";
	$url 				= $_SERVER['QUERY_STRING'];
?>
<script>
    let link 	= "report";
    let sub_link= "balance_stock";
</script>
<style>
	.table-stock {
    width: 100%;
    table-layout: fixed;
}

.table-stock th,
.table-stock td {
    overflow: hidden;
    word-wrap: break-word;
}
.table-responsive {
    max-height: calc(100vh - 220px);
    overflow-y: auto;
    overflow-x: auto;
}

.table-responsive table {
    width: 100%;
    table-layout: fixed;
}

.table-responsive thead th {
    position: sticky;
    top: 0;
    z-index: 100;
    background: #343a40;
    color: #fff;
    border-color: #454d55;
}

.table-responsive thead tr:nth-child(2) td {
    position: sticky;
    top: 42px; /* header row height */
    z-index: 99;
    background: #212529;
    color: #fff;
    font-weight: bold;
}

.table-responsive th,
.table-responsive td {
    /* white-space: nowrap; */
    vertical-align: middle;
}

.table-responsive::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}
</style>
<section class="container-fluid sticky_top">
	<form class="form-horizontal" id="search_form" action="<?php echo base_url('report/balance_stock?action=view')?>" method="get">
		<div class="d-flex justify-content-between">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
			    <li class="breadcrumb-item active" aria-current="page">
			    	BALANCE STOCK(<span><i><?php echo $total_rows;?></i></span>)
			    </li>
			    <li class="breadcrumb-item" aria-current="search-page">
			    	<button type="submit" class="btn btn-sm btn-primary mr-2" id="btn_search" data-toggle="tooltip" data-placement="bottom" title="SEARCH">
			    		<i class="text-warning fa fa-search"></i>
			    	</button>
					<input type="hidden" name="action" value='<?php echo $action; ?>'>
			    </li>
			    <li class="breadcrumb-item" aria-current="refresh-page">
			    	<a type="button" class="btn btn-sm btn-primary" onclick="redirectPage('report/balance_stock?action=view')" data-toggle="tooltip" data-placement="bottom" title="REFRESH"><i class="text-info fa fa-undo"></i></a>
			    </li>
			    <li class="breadcrumb-item" aria-current="print-page">
			    	<?php if(!empty($data['data'])): ?>
			    		<a target="_blank" type="button" class="btn btn-sm btn-primary mr-2" data-toggle="tooltip" data-placement="bottom" title="PDF" href="<?php echo base_url("report/balance_stock?submit=PDF&$url"); ?>">
			    			<i class="text-success fa fa-print"></i>
			    		</a>
			    	<?php else: ?>
			    		<button type="button" class="btn btn-sm btn-primary mr-2" data-toggle="tooltip" data-placement="bottom" title="PDF" disabled="disabled">
			    			<i class="text-success fa fa-print"></i>
			    		</button>
			    	<?php endif; ?>
			    </li>
			    <li class="breadcrumb-item" aria-current="search-box">
			    	<input type="checkbox" id="search_status" name="search_status" data-toggle="toggle" data-on="FILTER <i class='fa fa-eye'></i>" data-off="FILTER <i class='fa fa-eye-slash'></i>" data-onstyle="primary" data-offstyle="primary" data-width="100" data-size="mini" data-style="show-hide" onchange="set_search_box()" <?php echo empty($search_status) ? 'checked' : ''; ?>>
			    </li>
			  </ol>
			</nav>
			<div class="d-none d-sm-block height_60_px">
				<?= $this->pagination->create_links(); ?>
			</div>
		</div>
		<div class="row collapse mt-2 <?php echo empty($search_status) ? '' : 'show'  ?>" id="search_box">
			<div class="d-flex flex-wrap justify-content-center floating-form">
				<div class="col-6 col-sm-4 col-md-3 col-lg-2 floating-label">
					<?php if(isset($data['search']['brmm_supplier_id'])): ?><p>SUPPLIER</p><?php endif; ?>
					<select class="form-control floating-select" id="brmm_supplier_id" name="brmm_supplier_id">
                    	<?php if(isset($data['search']['brmm_supplier_id']) && !empty($data['search']['brmm_supplier_id'])): ?>
                        	<option value="<?php echo $data['search']['brmm_supplier_id']['value']; ?>" selected>
                            	<?php echo $data['search']['brmm_supplier_id']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
                	</select>
				</div>
				<div class="col-6 col-sm-4 col-md-3 col-lg-2 floating-label">
					<?php if(isset($data['search']['brmm_sku_id'])): ?><p>SKU</p><?php endif; ?>
					<select class="form-control floating-select" id="brmm_sku_id" name="brmm_sku_id">
                    	<?php if(isset($data['search']['brmm_sku_id']) && !empty($data['search']['brmm_sku_id'])): ?>
                        	<option value="<?php echo $data['search']['brmm_sku_id']['value']; ?>" selected>
                            	<?php echo $data['search']['brmm_sku_id']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
                	</select>
				</div>
				<?php if($_SESSION['user_branch_id'] == 1){ ?>
				<div class="col-6 col-sm-4 col-md-3 col-lg-2 floating-label">
					<?php if(isset($data['search']['brmm_branch_id'])): ?><p>BRANCH</p><?php endif; ?>
					<select class="form-control floating-select" id="brmm_branch_id" name="brmm_branch_id">
                    	<?php if(isset($data['search']['brmm_branch_id']) && !empty($data['search']['brmm_branch_id'])): ?>
                        	<option value="<?php echo $data['search']['brmm_branch_id']['value']; ?>" selected>
                            	<?php echo $data['search']['brmm_branch_id']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
                	</select>
				</div>
				<?php } ?>
				<div class="col-6 col-sm-4 col-md-3 col-lg-2 floating-label">
					<?php if(isset($data['search']['brmm_apparel_id'])): ?><p>APPAREL</p><?php endif; ?>
					<select class="form-control floating-select" id="brmm_apparel_id" name="brmm_apparel_id">
                    	<?php if(isset($data['search']['brmm_apparel_id']) && !empty($data['search']['brmm_apparel_id'])): ?>
                        	<option value="<?php echo $data['search']['brmm_apparel_id']['value']; ?>" selected>
                            	<?php echo $data['search']['brmm_apparel_id']['text']; ?> 
                        	</option>
                    	<?php endif; ?>
                	</select>
				</div>
				
				<div class="d-flex col-6 col-sm-4 col-md-3 col-lg-3">
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="prmt_amt_frm" name="prmt_amt_frm" value="<?php echo $prmt_amt_frm ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">FRM PUR AMT</label>
					</div>
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="prmt_amt_to" name="prmt_amt_to" value="<?php echo $prmt_amt_to ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">TO PUR AMT</label>
					</div>
				</div>
				<div class="d-flex mt-2 col-6 col-sm-4 col-md-3 col-lg-3">
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="ot_amt_frm" name="ot_amt_frm" value="<?php echo $ot_amt_frm ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">FRM SALE AMT</label>
					</div>
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="ot_amt_to" name="ot_amt_to" value="<?php echo $ot_amt_to ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">TO SALE AMT</label>
					</div>
				</div>
				<div class="d-flex mt-2 col-6 col-sm-4 col-md-3 col-lg-3">
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="sold_amt_frm" name="sold_amt_frm" value="<?php echo $sold_amt_frm ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">FRM SOLD AMT</label>
					</div>
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="sold_amt_to" name="sold_amt_to" value="<?php echo $sold_amt_to ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">TO SOLD AMT</label>
					</div>
				</div>
				<div class="d-flex mt-2 col-6 col-sm-4 col-md-3 col-lg-3">
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="bal_qty_frm" name="bal_qty_frm" value="<?php echo $bal_qty_frm ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">FRM BAL QTY</label>
					</div>
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="bal_qty_to" name="bal_qty_to" value="<?php echo $bal_qty_to ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">TO BAL QTY</label>
					</div>
				</div>
				<div class="d-flex mt-2 col-6 col-sm-4 col-md-3 col-lg-3">
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="bal_amt_frm" name="bal_amt_frm" value="<?php echo $bal_amt_frm ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">FRM STOCK AMT</label>
					</div>
					<div class="floating-label">
						<input type="number" class="form-control floating-input" id="bal_amt_to" name="bal_amt_to" value="<?php echo $bal_amt_to ?>" placeholder=" " autocomplete="off"/>   
	                    <label for="inputEmail3">TO STOCK AMT</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<table class="table table-sm table-dark">
					
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</form>
</section>
<section class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="table-responsive">
			<table class="table table-sm table-hover">
				<thead class="thead-dark">
						<tr>
			                <th width="8%">SUPPLIER</th>
							<th width="8%">BRANCH</th>
		                    <th width="8%">SKU</th>
		                    <th width="8%">APPAREL</th>
		                    <th width="10%">BARCODE</th>
		                    <th width="5%">PUR QTY</th>
		                    <th width="5%">PUR RATE</th>
		                    <th width="5%">PUR AMT</th>
							<th width="5%">PUR RET. QTY</th>
							<th width="5%">OUTWARD QTY</th>
		                    <th width="5%">INWARD QTY</th>
		                    <th width="5%">SALE QTY</th>
		                    <th width="5%">SALE RATE</th>
		                    <th width="5%">SALE AMT</th>
		                    <th width="5%">SALE RET QTY</th>
		                    <th width="5%">SOLD QTY <br/> X <br/> PUR RATE</th>
		                    <th width="5%">BALANCE QTY</th>
		                    <th width="5%">STOCK AMT</th>
			            </tr>
						<tr style="font-size: 15px; font-weight: bold;">
			                <td ></td>
		                    <td ></td>
		                    <td ></td>
		                    <td ></td>
		                    <td ></td>
		                    <td ><?php echo $data['totals']['prmt_qty']; ?></td>
		                    <td ></td>
		                    <td ><?php echo round($data['totals']['prmt_amt'], 2); ?></td>
							<td ><?php echo $data['totals']['prrt_qty']; ?></td>
							<td ><?php echo $data['totals']['outward_qty']; ?></td>
				            <td ><?php echo $data['totals']['inward_qty']; ?></td>
		                    
		                    <td ><?php echo $data['totals']['ot_qty']; ?></td>
		                    <td ></td>
		                    <td ><?php echo round($data['totals']['ot_amt'], 2); ?></td>
		                    <td ><?php echo $data['totals']['ort_qty']; ?></td>
		                    <td ><?php echo round($data['totals']['sold_amt'], 2); ?></td>
		                    <td ><?php echo $data['totals']['bal_qty']; ?></td>
		                    <td ><?php echo round($data['totals']['bal_amt'], 2); ?></td>
			            </tr>
					</thead>
				<tbody>
					<?php 
						if(!empty($data['data'])): 
							foreach ($data['data'] as $key => $value):
					?>
								<tr class="<?php echo ($value['bal_qty'] == 0 AND $value['branch_name'] != '') ? 'bg-success text-white' : ''; ?>">
									<td width="8%"><?php echo $value['supplier_name']; ?></td>
									<td width="8%"><?php echo $value['branch_name']; ?></td>
									<td width="8%"><?php echo $value['sku_name']; ?></td>
									<td width="8%"><?php echo $value['apparel_name']; ?></td>
									<td width="10%"><?php echo $value['barcode']; ?></td>
									<td width="5%"><?php echo $value['prmt_qty']; ?></td>
									<td width="5%"><?php echo round($value['prmt_rate'], 2); ?></td>
									<td width="5%"><?php echo round($value['prmt_amt'],2); ?></td>
									<td width="5%"><?php echo $value['prrt_qty']; ?></td>
									<td width="5%"><?php echo $value['outward_qty']; ?></td>
									<td width="5%"><?php echo $value['inward_qty']; ?></td>
									<td width="5%"><?php echo $value['ot_qty']; ?></td>
									<td width="5%"><?php echo round($value['ot_rate'], 2); ?></td>
									<td width="5%"><?php echo round($value['ot_amt'],2); ?></td>
									<td width="5%"><?php echo $value['ort_qty']; ?></td>
									<td width="5%"><?php echo round($value['sold_amt'],2); ?></td>
									<td width="5%"><?php echo $value['bal_qty']; ?></td>
									<td width="5%"><?php echo round($value['bal_amt'],2); ?></td>

								</tr>
					<?php 
							endforeach;
					?>
								<tr style="font-size: 15px; font-weight: bold;">
									<td ></td>
				                    <td ></td>
				                    <td ></td>
				                    <td ></td>
				                    <td >TOTALS</td>
				                    <td ><?php echo $data['totals']['prmt_qty']; ?></td>
				                    <td ></td>
				                    <td ><?php echo round($data['totals']['prmt_amt'], 2); ?></td>
									<td ><?php echo $data['totals']['prrt_qty']; ?></td>
				                    <td ><?php echo $data['totals']['outward_qty']; ?></td>
				                    <td ><?php echo $data['totals']['inward_qty']; ?></td>
				                   
				                    <td ><?php echo $data['totals']['ot_qty']; ?></td>
				                    <td ></td>
				                    <td ><?php echo round($data['totals']['ot_amt'], 2); ?></td>
				                    <td ><?php echo $data['totals']['ort_qty']; ?></td>
				                    <td ><?php echo round($data['totals']['sold_amt'], 2); ?></td>
				                    <td ><?php echo $data['totals']['bal_qty']; ?></td>
				                    <td ><?php echo round($data['totals']['bal_amt'], 2); ?></td>
								</tr>
					<?php
						else: 
					?>
						<tr>
							<td class="text-danger font-weight-bold text-center" colspan="11">NO RECORD FOUND!!!</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
	</div>
</section>
<?= $this->pagination->create_links(); ?>

<?php $this->load->view('templates/footer'); ?>
	<script src="<?php echo assets('dist/js/report/balance_stock.js')?>"></script>
	</body>
</html>