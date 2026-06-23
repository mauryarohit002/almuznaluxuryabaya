<?php 
	$this->load->view('templates/header'); 
	$start_date2 	= date('01-m-Y', strtotime('-6 MONTHS'));
	$end_date2 		= date('d-m-Y');			
	$start_date3 	= date('01-m-Y', strtotime('-6 MONTHS'));
	$end_date3 		= date('d-m-Y');
	$start_date4 	= date('01-m-Y', strtotime('-6 MONTHS'));
	$end_date4 		= date('d-m-Y');
	$start_date5 	= date('01-m-Y', strtotime('-6 MONTHS'));
	$end_date5 		= date('d-m-Y');
?>
<script>
    var link = "home";
    var sub_link = "home";
</script>
<link rel="stylesheet" href=<?php echo assets('plugins/chart/css/chart.min.css'); ?>>
<link rel="stylesheet" href=<?php echo assets('dist/css/home/first.css'); ?>>
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
	<form class="form-horizontal" id="dashboard_form">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card my-2 neu_flat_primary">
					<div class="card-header" id="balance_stock_tabs">
						<h5 class="mb-0">
	              			<a type="button" class="btn btn-sm btn-secondary" data-toggle="collapse" data-target="#balance_stock_tab" aria-expanded="true" aria-controls="balance_stock_tab">
	                			BALANCE STOCK
	              			</a>
	          			</h5>
					</div>
					<div id="balance_stock_tab" class="collapse show" aria-labelledby="balance_stock_tabs" data-parent="#accordion">
						<div class="d-flex flex-wrap justify-content-center">
							<div class="col-12 col-sm-12 col-md-10 col-lg-10 d-flex flex-wrap">
								<div class="col-6 col-sm-6 col-md-3 col-lg-2 note-display">
									<div class="circle">
								      <svg class="circle__svg">
								        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--path"></circle>
								        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--fill"></circle>
								      </svg>
								      <div class="percent">
								        <span class="percent__int" id="pur_qty"><?php echo $first['pur_qty']; ?></span>
								        <!-- <span class="percent__dec">00</span> -->
								      </div>
								    </div>
								    <span class="label">Purchase Qty</span>
								</div>
								<div class="col-6 col-sm-6 col-md-3 col-lg-2 note-display">
									<div class="circle">
								      <svg class="circle__svg">
								        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--path"></circle>
								        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--fill"></circle>
								      </svg>
								      <div class="percent">
								        <span class="percent__int" id="pret_qty"><?php echo $first['pret_qty']; ?></span>
								        <!-- <span class="percent__dec">00</span> -->
								      </div>
								    </div>
								    <span class="label">Purchase Return Qty</span>
								</div>
								<div class="col-6 col-sm-6 col-md-3 col-lg-2 note-display">
									<div class="circle">
								      <svg class="circle__svg">
								        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--path"></circle>
								        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--fill"></circle>
								      </svg>

								      <div class="percent">
								        <span class="percent__int" id="sale_qty"><?php echo $first['sale_qty']; ?></span>
								        <!-- <span class="percent__dec">00</span> -->
								      </div>
								    </div>
								    <span class="label">Sale Qty</span>
								</div>
								<div class="col-6 col-sm-6 col-md-3 col-lg-2 note-display">
									<div class="circle">
								      <svg class="circle__svg">
								        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--path"></circle>
								        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--fill"></circle>
								      </svg>

								      <div class="percent">
								        <span class="percent__int" id="sret_qty"><?php echo $first['sret_qty']; ?></span>
								        <!-- <span class="percent__dec">00</span> -->
								      </div>
								    </div>
								    <span class="label">Sales Return Qty</span>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-2 col-lg-2 note-display">
								<div class="circle">
							      <svg class="circle__svg">
							        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--path"></circle>
							        <circle cx="50%" cy="50%" r="50%" class="circle__progress circle__progress--fill"></circle>
							      </svg>

							      <div class="percent">
							        <span class="percent__int" id="bal_qty"><?php echo $first['bal_qty']; ?></span>
							        <!-- <span class="percent__dec">00</span> -->
							      </div>
							    </div>
							    <span class="label">Balance Qty</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="col-12 col-sm-12 col-md-12 col-lg-6">
				<div class="card my-2 neu_flat_primary" style="position: relative;">
					<div class="card-header" id="monthly_profit_tabs">
						<h5 class="d-block d-sm-block d-md-block d-lg-none mb-0">
	              			<a type="button" class="btn btn-sm btn-secondary" data-toggle="collapse" data-target="#monthly_profit_tab" aria-expanded="true" aria-controls="monthly_profit_tab">
	                			<small class="font-weight-bold">MONTHLY PROFIT</small>
	              			</a>
	          			</h5>
	          			<h6 class="d-none d-sm-none d-md-none d-lg-block mb-0">
	                		MONTHLY PROFIT
	          			</h6>
					</div>
					<div id="monthly_profit_tab" class="collapse show" aria-labelledby="monthly_profit_tabs" data-parent="#accordion">
						<div class="card-body d-flex flex-wrap chart-container" >
							<canvas id="second-chart" aria-label="chart" role="img"></canvas>
						</div>
						<div class="dropdown-divider"></div>
						<div class="form-group floating-form d-flex flex-wrap justify-content-around">
							<div class="col-sm-6 col-md-6 col-lg-3 mb-5 floating-label">
								<input type="text" class="form-control floating-input datepicker-top" id="start_date2" name="start_date2" value="<?php echo $start_date2; ?>" placeholder=" " readonly="readonly" onchange="home()"/>   
			        	<label for="inputEmail3">START DATE</label>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-3 floating-label">
								<input type="text" class="form-control floating-input datepicker-top" id="end_date2" name="end_date2" value="<?php echo $end_date2 ?>" placeholder=" " readonly="readonly" onchange="home()"/>   
			          <label for="inputEmail3">END DATE</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-6">
				<div class="card my-2 neu_flat_primary" style="position: relative;">
					<div class="card-header" id="mode_wise_sale_tabs">
						<h5 class="d-block d-sm-block d-md-block d-lg-none mb-0">
	              			<a type="button" class="btn btn-sm btn-secondary" data-toggle="collapse" data-target="#mode_wise_sale_tab" aria-expanded="true" aria-controls="mode_wise_sale_tab">
	                			<small class="font-weight-bold">PAYMENT MODE WISE SALE</small>
	              			</a>
	          			</h5>
			          	<h6 class="d-none d-sm-none d-md-none d-lg-block mb-0">
			                PAYMENT MODE WISE SALE
			          	</h6>
					</div>
					<div id="mode_wise_sale_tab" class="collapse show" aria-labelledby="mode_wise_sale_tabs" data-parent="#accordion">
						<div class="card-body d-flex flex-wrap chart-container" >
							<canvas id="third-chart" aria-label="chart" role="img"></canvas>
						</div>
						<div class="dropdown-divider"></div>
						<div class="form-group floating-form d-flex flex-wrap justify-content-around">
							<div class="col-sm-6 col-md-6 col-lg-3 mb-5 floating-label">
								<input type="text" class="form-control floating-input datepicker-top" id="start_date3" name="start_date3" value="<?php echo $start_date3; ?>" placeholder=" " readonly="readonly" onchange="home()"/>   
			        	<label for="inputEmail3">START DATE</label>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-3 floating-label">
								<input type="text" class="form-control floating-input datepicker-top" id="end_date3" name="end_date3" value="<?php echo $end_date3 ?>" placeholder=" " readonly="readonly" onchange="home()"/>   
			          <label for="inputEmail3">END DATE</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-6">
				<div class="card my-2 neu_flat_primary" style="position: relative;">
					<div class="card-header" id="gender_wise_sale_tabs">
						<h5 class="d-block d-sm-block d-md-block d-lg-none mb-0">
			              	<a type="button" class="btn btn-sm btn-secondary" data-toggle="collapse" data-target="#gender_wise_sale_tab" aria-expanded="true" aria-controls="gender_wise_sale_tab">
				                <small class="font-weight-bold">MOST SOLD ITEM (GENDER WISE)</small>
			              	</a>
			          	</h5>
			          	<h6 class="d-none d-sm-none d-md-none d-lg-block mb-0">
				        	MOST SOLD ITEM (GENDER WISE)
			          	</h6>
					</div>
					<div id="gender_wise_sale_tab" class="collapse show" aria-labelledby="gender_wise_sale_tabs" data-parent="#accordion">
						<div class="card-body d-flex flex-wrap chart-container" >
							<canvas id="fourth-chart" aria-label="chart" role="img"></canvas>
						</div>
						<div class="dropdown-divider"></div>
						<div class="form-group floating-form d-flex flex-wrap justify-content-around">
							<div class="col-sm-6 col-md-6 col-lg-3 mb-5 floating-label">
								<input type="text" class="form-control floating-input datepicker-top" id="start_date4" name="start_date4" value="<?php echo $start_date4; ?>" placeholder=" " readonly="readonly" onchange="home()"/>   
			        			<label for="inputEmail3">START DATE</label>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-3 floating-label">
								<input type="text" class="form-control floating-input datepicker-top" id="end_date4" name="end_date4" value="<?php echo $end_date4 ?>" placeholder=" " readonly="readonly" onchange="home()"/>   
			          			<label for="inputEmail3">END DATE</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-6">
				<div class="card my-2 neu_flat_primary" style="position: relative;">
					<div class="card-header" id="category_wise_sale_tabs">
						<h5 class="d-block d-sm-block d-md-block d-lg-none mb-0">
			              	<a type="button" class="btn btn-sm btn-secondary" data-toggle="collapse" data-target="#category_wise_sale_tab" aria-expanded="true" aria-controls="category_wise_sale_tab">
			                	<small class="font-weight-bold">MOST SOLD ITEM (CATEGORY WISE - TOP FIVE)</small>
			              	</a>
				          </h5>
			          	<h6 class="d-none d-sm-none d-md-none d-lg-block mb-0">
				                MOST SOLD ITEM (CATEGORY WISE - TOP FIVE)
				        </h6>
					</div>
					<div id="category_wise_sale_tab" class="collapse show" aria-labelledby="category_wise_sale_tabs" data-parent="#accordion">
						<div class="card-body d-flex flex-wrap chart-container" >
							<canvas id="fifth-chart" aria-label="chart" role="img"></canvas>
						</div>
						<div class="dropdown-divider"></div>
						<div class="form-group floating-form d-flex flex-wrap justify-content-around">
							<div class="col-sm-6 col-md-6 col-lg-3 mb-5 floating-label">
								<input type="text" class="form-control floating-input datepicker-top" id="start_date5" name="start_date5" value="<?php echo $start_date5; ?>" placeholder=" " readonly="readonly" onchange="home()"/>   
			        			<label for="inputEmail3">START DATE</label>
							</div>
							<div class="col-sm-6 col-md-6 col-lg-3 floating-label">
								<input type="text" class="form-control floating-input datepicker-top" id="end_date5" name="end_date5" value="<?php echo $end_date5 ?>" placeholder=" " readonly="readonly" onchange="home()"/>   
			          			<label for="inputEmail3">END DATE</label>
							</div>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</form>
</section>
<?php $this->load->view('templates/footer'); ?>
		<script src="<?php echo assets('plugins/chart/js/chart.min.js')?>"></script>
		<script src="<?php echo assets('plugins/chart/js/bundle.min.js')?>"></script>
		<script src="<?php echo assets('dist/js/home/home.js')?>"></script>
		<script src="<?php echo assets('dist/js/home/first.js')?>"></script>
		<!-- <script src="<?php echo assets('dist/js/home/second.js')?>"></script> -->
		<!-- <script src="<?php echo assets('dist/js/home/third.js')?>"></script> -->
		<!-- <script src="<?php echo assets('dist/js/home/fourth.js')?>"></script> -->
		<!-- <script src="<?php echo assets('dist/js/home/fifth.js')?>"></script> -->
	</body>
</html>