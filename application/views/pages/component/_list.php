<?php 
	$this->load->view('templates/header'); 
	$search_status 	= !isset($_GET['search_status']);
?>
<script>
    let link 		= "<?php echo $menu; ?>";
    let sub_link 	= "<?php echo $sub_menu; ?>";
</script>
<section class="container-fluid sticky_top">
	<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_navbar'); ?>
</section>
<section class="container-fluid">
	<div class="row">
		<div class="col-12">

			<!-- HEADER TABLE -->
			<table class="table table-dark text-uppercase w-100"
				style="margin-bottom:0rem; table-layout:fixed;">
				<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_header'); ?>
			</table>

			<!-- BODY TABLE -->
			<div class="list_wrapper">
				<table class="table table-sm table-hover text-uppercase w-100"
					id="table_reload"
					style="table-layout:fixed;">
					<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_body', ['data' => $data['data']]); ?>
				</table>
			</div>

		</div>

	</div>
</section>
<div class="pagination_wrapper"><?= $this->pagination->create_links(); ?></div>
<?php $this->load->view('templates/footer'); ?>
<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/_filter', ['filters' => isset($data['filter']) ? $data['filter'] : []]); ?>
<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/list/_footer'); ?>
</body>
</html>