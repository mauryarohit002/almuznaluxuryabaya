</main>
<footer class="d-flex justify-content-around bg-primary text-dark py-2">
	<span>
		<b>Powered by</b> Interlink Consultant
	</span>
	<span>
		<i><?php echo $this->benchmark->elapsed_time(); ?></i>
	</span>
	<span class="d-none d-sm-none d-md-block">
		<strong>Copyright &copy; <?php echo date('Y')-1?>-<?php echo date('Y')?>.</strong> All rights reserved.
	</span>
</footer>
<!-- loader -->
<div id="ftco-loader" class="show fullscreen">
	<svg class="circular" width="48px" height="48px">
		<!-- <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/> -->
		<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="2" stroke-miterlimit="10" stroke="#F96D00"/>
	</svg>
</div>

<!-- jQuery 3 -->
<script src="<?php echo assets('plugins/jquery/jquery.min.js')?>"></script>

<!-- Bootstrap -->
<!-- <script src="<?php echo assets('plugins/bootstrap/js/bootstrap.min.js')?>"></script> -->

<!-- Bootstrap -->
<script src="<?php echo assets('plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>

<!-- Date Picker -->
<script src="<?php echo assets('plugins/datepicker/js/bootstrap-datepicker.js')?>"></script>

<!-- Toastr -->
<script src="<?php echo assets('plugins/toastr/js/toastr.min.js')?>"></script>

<!-- Toggle Switch -->
<script src="<?php echo assets('plugins/toggle-switch/js/toggle.min.js')?>"></script>

<!-- Select2 -->
<script src="<?php echo assets('plugins/select2/js/select2.min.js')?>"></script>

<!-- Pan -->
<script src="<?php echo assets('plugins/pan/js/pan.js')?>"></script>

<!-- SweetAlert2 -->
<script src="<?php echo assets('plugins/sweetalert2/js/sweetalert2.min.js')?>"></script>

<!-- Brower-Image-Compressor -->
<script src="<?php echo assets('plugins/compressor/js/compressor.js')?>"></script>


<!-- Custom JS files. Note: Keep the sequence of following custom files -->
<script type="text/javascript">
	const loc 			= window.location;
	const base_url   	= "<?php echo base_url(); ?>";
	const TO_PAY 		= "<?php echo TO_PAY; ?>";
	const TO_RECEIVE 	= "<?php echo TO_RECEIVE; ?>";
	const NOIMAGE 		= "<?php echo assets(NOIMAGE); ?>";
	const USERIMAGE 	= "<?php echo assets(USERIMAGE); ?>";
	const LAZYLOADING 	= "<?php echo assets(LAZYLOADING); ?>";
	const WITHIN 		= "<?php echo WITHIN; ?>";
	const OUTSIDE 		= "<?php echo OUTSIDE; ?>";
	const REFRESH 		= "<?php echo REFRESH; ?>";
	const PER_PAGE 		= "<?php echo PER_PAGE; ?>";
	const BARCODE_LENGTH= "<?php echo BARCODE_LENGTH; ?>";
	const RELOAD_TIME 	= 800;
</script>
<script src="<?php echo assets('dist/js/custom/ajax.js?v=1')?>"></script>
<script src="<?php echo assets('dist/js/custom/loader.js?v=1')?>"></script>
<script src="<?php echo assets('dist/js/custom/validate.js?v=1')?>"></script>
<script src="<?php echo assets('dist/js/custom/script.js?v=4')?>"></script>
<script src="<?php echo assets('dist/js/custom/select2.js?v=1')?>"></script>
<script src="<?php echo assets('dist/js/custom/lazy_loading.js?v=1')?>"></script>

<!-- Related JS files -->