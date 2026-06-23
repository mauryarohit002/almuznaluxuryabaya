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
	<div class="row d-flex justify-content-center">
	</div>
</section>
<?php $this->load->view('templates/footer'); ?>
</body>
</html>