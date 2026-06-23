<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
		  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
		  	<title><?php echo $company; ?> | Log in</title>
		  	
		  	<!-- Tell the browser to be responsive to screen width -->
		  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

		  	<!-- Bootstrap 4 -->
		  	<link rel="stylesheet" href="<?php echo assets('plugins/bootstrap/css/bootstrap.min.css')?>">
  
		  	<!-- Font Awesome -->
		  	<link rel="stylesheet" href="<?php echo assets('plugins/font-awesome/css/font-awesome.min.css')?>">

		  	<!--Toastr-->
	  		<link rel="stylesheet" href="<?php echo assets('plugins/toastr/css/toastr.min.css'); ?>" media="screen,projection" />

	  		<!-- SweetAlert2 -->
  			<link rel="stylesheet" href="<?php echo assets('plugins/sweetalert2/css/sweetalert2.min.css')?>">
		
			<!-- Google Font -->
  			<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  			
  			<!-- custom style sheet -->
  			<link rel="stylesheet" href="<?php echo assets('dist/css/bootstrap.css?v=12')?>">
  			<link rel="stylesheet" href="<?php echo assets('dist/css/common.css?v=8')?>">
  			<link rel="stylesheet" href="<?php echo assets('dist/css/login_floating.css?v=1')?>">
  			<link rel="stylesheet" href="<?php echo assets('dist/css/loader.css?v=1')?>">
  			<link rel="stylesheet" href="<?php echo assets('dist/css/login.css?v=1')?>">
  			<style type="text/css">
  				.floating-label{
  					font-family: copperplate !important;
  				}
  			</style>
		</head> 
		<body class="blur">
			<main class="d-flex justify-content-center">
				<section class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-3 align-self-center" style="height: 100vh;">
					<div class="d-flex flex-column align-items-center px-2">
						<div class="d-flex justify-content-center" style="min-height: 129px;">
							<div class="text-center">
    
								<!-- Company Logo -->
								<img src="<?= base_url('public/assets/dist/images/logo.jpg'); ?>" 
									alt="Company Logo" 
									style="max-width:85%;">

								<!-- Company Name -->
								<!-- <p class="font-weight-bold text-white" 
								style="font-size: 3rem; font-family: copperplate !important;">
									<?php echo str_replace(' ', "<br/>", $company); ?>
								</p> -->

							</div>

						</div>
						<div class="d-flex flex-column justify-content-center align-items-center w-100 my-3 py-2">
							<form class="floating-form" id="login_form" onsubmit="login_action()">
								<div class="form-group floating-label">
						            <?php echo form_dropdown('', $year, 0 ,'id="fin_year" name="fin_year" class="form-control floating-select select2" tabindex="1" placeholder=" "'); ?>
						            <label for="fin_year">Financial Year</label>
						            <small class="form-text text-muted helper-text" id="fin_year_msg"></small>          
						          </div>
						          <div class="form-group floating-label">
						            <?php echo form_dropdown('', $branch, 0 ,'id="user_branch_id" name="user_branch_id" class="form-control floating-select" tabindex="2" placeholder=" " onchange="validate_dropdown(this)"'); ?>
						            <label for="user_branch_id">Branch<span class="text-danger"> *</span></label>
						            <small class="form-text text-muted helper-text" id="user_branch_id_msg"></small>          
						          </div>
						          <!-- <input type="hidden" name="user_branch_id" id="user_branch_id" value="1"> -->
						          <div class="form-group floating-label">
						            <input type="text" class="form-control floating-input" id="user_name" name="user_name" placeholder=" " tabindex="2" autocomplete="off" required style="text-transform: none;" onkeyup="validate_textfield(this)">
						            <label for="user_name">Username<span class="text-danger"> *</span></label>
						            <small class="form-text text-muted helper-text" id="user_name_msg"></small>          
						          </div>
						          <div class="form-group floating-label">
						            <input type="password" class="form-control floating-input" id="user_password" name="user_password" placeholder=" " tabindex="3" required style="text-transform: none;" onkeyup="validate_textfield(this)">
						            <label for="user_password">Password<span class="text-danger"> *</span></label>
						            <small class="form-text text-muted helper-text" id="user_password_msg"></small>          
						          </div>
						          <button type="submit" tabindex="4" class="btn btn-secondary btn-sm btn-block master_block_btn" onclick="login_action()">
						          		<div class="stage"><div class="dot-floating"></div></div>
					          			<div class="d-none dot-text text-white">LOGIN</div>
						          </button>

							</form>
						</div>
						<small class="text-white">
			        		<b>Powered by</b> Interlink Consultant
						</small>
						<small class="d-none d-sm-block text-white">
			        		<strong>&copy; <?php echo date('Y')-1?>-<?php echo date('Y')?>.</strong> All rights reserved.
						</small>
					</div>
				</section>
			</main>
			<!-- loader -->
		    <div id="ftco-loader" class="show fullscreen">
		    	<svg class="circular" width="48px" height="48px">
		    		<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="2" stroke-miterlimit="10" stroke="#F96D00"/>
		    	</svg>
		    </div>
		  
	  		<!-- jQuery 3 -->
		  	<script src="<?php echo assets('plugins/jquery/jquery.min.js')?>"></script>
		  	
		  	<!-- Bootstrap -->
		  	<script src="<?php echo assets('plugins/bootstrap/js/bootstrap.min.js')?>"></script>

		  	<!-- Bootstrap -->
		  	<script src="<?php echo assets('plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>

		  	<!-- Toastr -->
			<script src="<?php echo assets('plugins/toastr/js/toastr.min.js')?>"></script>

			<!-- SweetAlert2 -->
			<script src="<?php echo assets('plugins/sweetalert2/js/sweetalert2.min.js')?>"></script>
		  	
		  	<!-- Custom JS files. Note: Keep the sequence of following custom files -->
		  	<script src="<?php echo assets('dist/js/custom/constants.js?v=1')?>"></script>
		  	<script src="<?php echo assets('dist/js/custom/ajax.js?v=1')?>"></script>
		  	<script src="<?php echo assets('dist/js/custom/common.js?v=1')?>"></script>
		  	<script src="<?php echo assets('dist/js/custom/loader.js?v=1')?>"></script>
		  	<script src="<?php echo assets('dist/js/custom/validate.js?v=1')?>"></script>

		  	<!-- Related JS files -->
		  	<script src="<?php echo assets('dist/js/login.js')?>"></script>
		  	<?php if(isset($msg) && !empty($msg)): ?>
			  	<script type="text/javascript">
			  		// toastr.error('',"<?php echo $msg ?>", {timeOut:2000, closeButton:true, progressBar:true, preventDuplicates:true});
			  		Swal.fire({
				      html:'<p class="text-danger">Please wait...</p>',
				      title: `<h2 class="text-danger">Session Expired.</h2>`,
				      icon: 'warning',
				      showCancelButton: false,
				      timer:2000
				    })
			  		setTimeout(()=>{
			  			window.location.href = base_url;
			  		}, 2000)
			  	</script>
			 <?php endif; ?>
		</body>
</html>

