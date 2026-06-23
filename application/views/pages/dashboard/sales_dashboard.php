<?php $this->load->view('templates/header'); ?>
<script>
    var link = "home";
    var sub_link = "home";
</script>
<section class="container-fluid sticky_top">
	<div class="d-flex justify-content-between">
		<nav aria-label="breadcrumb">
		  <ol class="breadcrumb">
		    <li class="breadcrumb-item"><a href="<?php echo base_url('home'); ?>">HOME</a></li>
		  </ol>
		</nav>
	</div>
</section>
<section class="container-fluid">
	<div class="row">
		<div class="col-12 col-sm-12 col-md-12 col-lg-6">
			<div class="card my-2 neu_flat_primary" style="position: relative; height: 60vh; width: auto;">
				<div class="card-header">
					<div class="d-flex justify-content-between">
						<h6>DAILY TRANSACTION</h6>
						<h6><i class="cash_time"><?php echo date('d-m-Y h:i:s a'); ?></i></h6>
						<h6>CASH A/C</h6>
					</div>
				</div>
				<div class="card-body d-flex flex-wrap" >
					<table class="table table-hover">
						<tr>
							<th>OPENING BAL.</th>
							<th><span id="cash_open_bal"><?php echo round($cash['open_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>SALES AMT</th>
							<th><span id="cash_sales_amt"><?php echo round($cash['sales_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>SALES RETURN AMT</th>
							<th><span id="cash_return_amt"><?php echo round($cash['return_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>RECEIPT AMT</th>
							<th><span id="cash_receipt_amt"><?php echo round($cash['receipt_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>PAYMENT AMT</th>
							<th><span id="cash_payment_amt"><?php echo round($cash['payment_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>CLOSING BAL</th>
							<th style="font-size: 18px;" ><span id="cash_close_bal"><?php echo round($cash['close_amt'], 2); ?></span></th>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-12 col-lg-6">
			<div class="card my-2 neu_flat_primary" style="position: relative; height: 60vh; width: auto;">
				<div class="card-header">
					<div class="d-flex justify-content-between">
						<h6>DAILY TRANSACTION</h6>
						<h6><i class="cash_time"><?php echo date('d-m-Y h:i:s a'); ?></i></h6>
						<h6>BANK A/C</h6>
					</div>
				</div>
				<div class="card-body d-flex flex-wrap" >
					<table class="table table-hover">
						<tr>
							<th>OPENING BAL.</th>
							<th><span id="bank_open_bal"><?php echo round($bank['open_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>SALES AMT</th>
							<th><span id="bank_sales_amt"><?php echo round($bank['sales_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>SALES RETURN AMT</th>
							<th><span id="bank_return_amt"><?php echo round($bank['return_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>RECEIPT AMT</th>
							<th><span id="bank_receipt_amt"><?php echo round($bank['receipt_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>PAYMENT AMT</th>
							<th><span id="bank_payment_amt"><?php echo round($bank['payment_amt'], 2); ?></span></th>
						</tr>
						<tr>
							<th>CLOSING BAL</th>
							<th style="font-size: 18px;" ><span id="bank_close_bal"><?php echo round($bank['close_amt'], 2); ?></span></th>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->load->view('templates/footer'); ?>
		<script src="<?php echo assets('dist/js/home/daily_transaction.js?v=1')?>"></script>
	</body>
</html>