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
				class="btn btn-md btn-secondary text-uppercase text-warning" 
				href="<?php echo base_url('home/trial?_date_from='.date('Y-m-d').'&_date_to='.date('Y-m-d')); ?>" 
			>trial schedule (<?php echo count($data); ?>)</a>
			<a 
				class="btn btn-md btn-secondary text-uppercase" 
				href="<?php echo base_url('home/delivery?_date_from='.date('Y-m-d').'&_date_to='.date('Y-m-d')); ?>" 
			>delivery schedule</a>
		</div>
		<div class="d-flex align-items-center">
			<button 
				type="button" 
				class="btn btn-md btn-primary btn-filter mx-2"
				data-toggle="tooltip" 
				data-placement="bottom" 
				title="FILTER"
				onclick="toggle_right_panel()"
			>
				<i class="text-dark fa fa-filter"></i>
				<span class="badge badge-dark" id="filter_count"><?php echo isset($data['filter']) ? count($data['filter']) : ''; ?></span>
			</button>
		</div>
	</div>
</section>
<section class="container-fluid">
	<div class="row d-flex justify-content-center">
		<table class="table table-sm">
			<thead class="table-dark">
				<th width="5%" class="<?php echo ENV == DEV ? '' : 'd-none'; ?>">#</th>
				<th width="5%">
					<div class="d-flex">
						<div class="d-flex flex-column">
							<input type="radio" class="d-none" id="memo_no-fa-caret-up" name="sorting" onclick="sorting_by('memo_no', 'asc')">
							<label for="memo_no-fa-caret-up" style="margin:0px;">
								<i class="fa fa-fw fa-caret-up text-danger" id="memo_no_asc"></i>
							</label>

							<span class="text-uppercase">memo no</span>
							<input type="radio" class="d-none" id="memo_no-fa-caret-down" name="sorting" onclick="sorting_by('memo_no', 'desc')">
							<label for="memo_no-fa-caret-down" style="margin:0px;">
								<i class="fa fa-fw fa-caret-down text-danger" id="memo_no_desc"></i>
							</label>
						</div>
					</div>
				</th>
				<th width="5%">
					<div class="d-flex">
						<div class="d-flex flex-column">
							<input type="radio" class="d-none" id="customer_mobile-fa-caret-up" name="sorting" onclick="sorting_by('customer_mobile', 'asc')">
							<label for="customer_mobile-fa-caret-up" style="margin:0px;">
								<i class="fa fa-fw fa-caret-up text-danger" id="customer_mobile_asc"></i>
							</label>

							<span class="text-uppercase">mobile no.</span>
							<input type="radio" class="d-none" id="customer_mobile-fa-caret-down" name="sorting" onclick="sorting_by('customer_mobile', 'desc')">
							<label for="customer_mobile-fa-caret-down" style="margin:0px;">
								<i class="fa fa-fw fa-caret-down text-danger" id="customer_mobile_desc"></i>
							</label>
						</div>
					</div>
				</th>
				<th width="5%">
					<div class="d-flex">
						<div class="d-flex flex-column">
							<input type="radio" class="d-none" id="customer_name-fa-caret-up" name="sorting" onclick="sorting_by('customer_name', 'asc')">
							<label for="customer_name-fa-caret-up" style="margin:0px;">
								<i class="fa fa-fw fa-caret-up text-danger" id="customer_name_asc"></i>
							</label>

							<span class="text-uppercase">customer</span>
							<input type="radio" class="d-none" id="customer_name-fa-caret-down" name="sorting" onclick="sorting_by('customer_name', 'desc')">
							<label for="customer_name-fa-caret-down" style="margin:0px;">
								<i class="fa fa-fw fa-caret-down text-danger" id="customer_name_desc"></i>
							</label>
						</div>
					</div>
				</th>
				<th width="5%">
					<div class="d-flex">
						<div class="d-flex flex-column">
							<input type="radio" class="d-none" id="trial_date-fa-caret-up" name="sorting" onclick="sorting_by('trial_date', 'asc')">
							<label for="trial_date-fa-caret-up" style="margin:0px;">
								<i class="fa fa-fw fa-caret-up text-danger" id="trial_date_asc"></i>
							</label>

							<span class="text-uppercase">trial date</span>
							<input type="radio" class="d-none" id="trial_date-fa-caret-down" name="sorting" onclick="sorting_by('trial_date', 'desc')">
							<label for="trial_date-fa-caret-down" style="margin:0px;">
								<i class="fa fa-fw fa-caret-down text-danger" id="trial_date_desc"></i>
							</label>
						</div>
					</div>
				</th>
			</thead>
			<tbody id="tbody_wrapper" class="font-weight-bold" style="font-size: 0.7rem;">
				<?php 
					if(!empty($data)): 
						foreach ($data as $key => $value):
				?>
							<tr>
								<td width="05%" class="<?php echo ENV == DEV ? '' : 'd-none'; ?>">
									<?php echo $value['cnt']; ?>/<?php echo $value['page']; ?>
								</td>
								<td width="5%"><?php echo $value['memo_no']; ?></td>
								<td width="5%">
									<?php echo $value['entry_date']; ?><br/>
									<?php echo $value['customer_mobile']; ?>
								</td>
								<td width="5%">
									<?php echo $value['customer_name']; ?>
									<button 
										type="button" 
										class="btn btn-xs" 
										onclick='order_status_popup(<?php echo json_encode($value); ?>)'
									><i class="text-info fa fa-eye"></i></button>	
									<?php echo $value['apparel_data'];?>
								</td>
								<td width="5%"><?php echo $value['trial_date']; ?></td>
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
</section>
<?php $this->load->view('templates/footer'); ?>
<script type="text/javascript">sub_link = 'trial';</script>
<script src="<?php echo assets('dist/js/report/report_v2.js?v=1')?>"></script>
<script src="<?php echo assets('dist/js/home/trial_schedule.js?v=1')?>"></script>
</body>
</html>