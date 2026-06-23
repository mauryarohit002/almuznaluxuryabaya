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
		<div class="col-12 col-sm-12 col-md-6 col-lg-6 d-flex justify-content-center align-items-center" style="gap: 20px;">
			<a 
				class="btn btn-md btn-secondary text-uppercase" 
				href="<?php echo base_url('home/trial?_date_from='.date('Y-m-d').'&_date_to='.date('Y-m-d')); ?>" 
			>trial schedule</a>
			<a 
				class="btn btn-md btn-secondary text-uppercase text-warning" 
				href="<?php echo base_url('home/delivery?_date_from='.date('Y-m-d').'&_date_to='.date('Y-m-d')); ?>" 
			>delivery schedule</a>
		</div>
		<div class="d-flex align-items-center"></div>
	</div>
</section>
<section class="container-fluid">
	<div class="row d-flex justify-content-center">
	</div>
</section>
<?php $this->load->view('templates/footer'); ?>
</body>
</html>