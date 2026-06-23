<?php $this->load->view('templates/header'); ?>
<script>
    var link = "home";
    var sub_link = "home";
</script>
<style type="text/css">
	.table th {
    padding: .25rem;
}
tr:first-child th{
    border-top: 0;
}
tr{
	font-size: smaller;
}
</style>

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
		<div class="col-12 mt-2">
			<div class="d-flex justify-content-between">
			    <nav aria-label="breadcrumb">
			        <ol class="breadcrumb">
			            <li class="breadcrumb-item active text-uppercase" id="sub_menu_name" aria-current="page">
			                <a href="<?php echo base_url('home'); ?>">Delivery schedule </a>
			            </li>
			            <li class="breadcrumb-item active text-uppercase" aria-current="record-count">
			                count : <span id="count_reload"><i id="total_rows"><?php echo $total_rows;?></i></span>
			            </li>
			        </ol>
			    </nav>

				<?php if($_SESSION['user_branch_id'] == 1){ ?>
				<form id="search_form" method="GET">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-10 col-lg-10 floating-label mt-3">
								<p class="text-uppercase">Branch</p>
								<select class="form-control floating-select" id="_branch_name" name="_branch_name">
									<?php if(isset($_REQUEST['_branch_name']) && !empty($_REQUEST['_branch_name'])): ?>
										<option value="<?php echo $_REQUEST['_branch_name']; ?>" selected>
											<?php echo $_REQUEST['_branch_name']; ?>
										</option>
									<?php endif; ?>
								</select>
							</div>

							<div class="col-12 col-sm-12 col-md-2 col-lg-2 mt-3 d-flex mt-3" style="height: max-content; margin-left: -24px;">
								<button type="submit" class="btn btn-primary px-3">
									<i class="fa fa-search"></i>
								</button>
							</div>

						</div>
					</form>	
				<?php } ?>

			    <div class="col-12 col-sm-12 col-md-6 col-lg-4 d-flex flex-wrap floating-form">   
			            <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label mt-3">
			                <input 
			                    type="date" 
			                    class="form-control floating-input reschedule_delivery_date_checkboxes" 
			                    id="reschedule_delivery_date" 
			                    value="" 
			                    placeholder=" " 
			                    autocomplete="off" 
			                />   
			                <label class="text-uppercase">reschedule delivery date</label>
			            </div>
			            <div class="col-12 col-sm-12 col-md-4 col-lg-2">
			                <button
			                    type="button"
			                    class="btn btn-md btn-secondary text-uppercase mt-3"
			                    onclick="add_reschedule_delivery_date()"
			                >add new delivery date (<span id="reschedule_delivery_date_count">0</span>)</button>
			            </div>
			       
			    </div>
			    <div class="d-flex align-items-center">
			       <!--  <a 
			            type="button" 
			            class="btn btn-md btn-primary mx-2"
			            data-toggle="tooltip" 
			            data-placement="bottom" 
			            title="TRIAL"
			            href="<?php echo base_url('home/trial_schedule'); ?>"
			        ><i class="text-success fa fa-list"></i></a> -->
			        <a 
			            type="button" 
			            class="btn btn-md btn-primary mx-2"
			            data-toggle="tooltip" 
			            data-placement="bottom" 
			            title="REFRESH"
			            href="<?php echo base_url('home')?>" 
			        ><i class="text-info fa fa-undo"></i></a>
			        <button 
			            type="button" 
			            class="btn btn-md btn-primary btn-filter mx-2"
			            data-toggle="tooltip" 
			            data-placement="bottom" 
			            title="FILTER"
			            onclick="toggle_right_panel()">
			            <i class="text-dark fa fa-filter"></i>
			            <span class="badge badge-dark" id="filter_count"><?php echo isset($data['filter']) ? count($data['filter']) : ''; ?></span>
			        </button>
			    </div>
			</div>
		</div>
		<div class="col-12">
			<table class="table table-sm table-dark text-uppercase">
				<thead>
				    <th width="5%" class="<?php echo ENV == DEV ? '' : 'd-none'; ?>">#</th>
				    <th width="5%">
				        <div class="d-flex">
				            <div class="d-flex flex-column">
				                <input type="radio" class="d-none" id="entry_no-fa-caret-up" name="sorting" onclick="sorting_by('entry_no', 'asc')">
				                <label for="entry_no-fa-caret-up" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-up text-danger" id="entry_no_asc"></i>
				                </label>
				                <span class="text-uppercase">entry no</span>
				                <input type="radio" class="d-none" id="entry_no-fa-caret-down" name="sorting" onclick="sorting_by('entry_no', 'desc')">
				                <label for="entry_no-fa-caret-down" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-down text-danger" id="entry_no_desc"></i>
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
				    <th width="8%">
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
				                <input type="radio" class="d-none" id="branch_name-fa-caret-up" name="sorting" onclick="sorting_by('branch_name', 'asc')">
				                <label for="branch_name-fa-caret-up" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-up text-danger" id="branch_name_asc"></i>
				                </label>
				                <span class="text-uppercase">branch</span>
				                <input type="radio" class="d-none" id="branch_name-fa-caret-down" name="sorting" onclick="sorting_by('branch_name', 'desc')">
				                <label for="branch_name-fa-caret-down" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-down text-danger" id="branch_name_desc"></i>
				                </label>
				            </div>
				        </div>
				    </th>
				    <th width="5%">
				        <div class="d-flex">
				            <div class="d-flex flex-column">
				                <input type="radio" class="d-none" id="delivery_date-fa-caret-up" name="sorting" onclick="sorting_by('delivery_date', 'asc')">
				                <label for="delivery_date-fa-caret-up" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-up text-danger" id="delivery_date_asc"></i>
				                </label>

				                <span class="text-uppercase">delivery date</span>
				                <input type="radio" class="d-none" id="delivery_date-fa-caret-down" name="sorting" onclick="sorting_by('delivery_date', 'desc')">
				                <label for="delivery_date-fa-caret-down" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-down text-danger" id="delivery_date_desc"></i>
				                </label>
				            </div>
				        </div>
				    </th>
				    <th width="15%">
				        <div class="d-flex">
				            <div class="d-flex flex-column">
				                <input type="radio" class="d-none" id="delivery_reminder_date-fa-caret-up" name="sorting" onclick="sorting_by('delivery_reminder_date', 'asc')">
				                <label for="delivery_reminder_date-fa-caret-up" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-up text-danger" id="delivery_reminder_date_asc"></i>
				                </label>

				                <span class="text-uppercase d-flex">
				                    <label class="custom-control material-checkbox-secondary mx-2 my-1">
				                        <input 
				                            type="checkbox" 
				                            class="material-control-input-secondary" 
				                            id="delivery_reminder_date_checkbox" 
				                            onclick="delivery_reminder_date_select_deselect()" 
				                        />
				                        <span class="material-control-indicator-secondary"></span>
				                        <span class="material-control-description-secondary">
				                            <div class="d-flex">
				                                <span>delivery msg<br/>send at</span>
				                                    <button
				                                        type="button"
				                                        class="btn btn-xs btn-secondary text-uppercase mx-2"
				                                        onclick="send_delivery_reminder()"
				                                    ><i class="fa fa-send text-info"></i> <span id="delivery_reminder_date_count">0</span></button>
				                               
				                            </div>
				                        </span>
				                    </label>
				                </span>
				                <input type="radio" class="d-none" id="delivery_reminder_date-fa-caret-down" name="sorting" onclick="sorting_by('delivery_reminder_date', 'desc')">
				                <label for="delivery_reminder_date-fa-caret-down" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-down text-danger" id="delivery_reminder_date_desc"></i>
				                </label>
				            </div>
				        </div>
				    </th>
				    <th width="15%">
				        <div class="d-flex">
				            <div class="d-flex flex-column">
				                <input type="radio" class="d-none" id="reschedule_delivery_date-fa-caret-up" name="sorting" onclick="sorting_by('reschedule_delivery_date', 'asc')">
				                <label for="reschedule_delivery_date-fa-caret-up" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-up text-danger" id="reschedule_delivery_date_asc"></i>
				                </label>

				                <span class="text-uppercase d-flex">
				                    <label class="custom-control material-checkbox-secondary mx-2 my-1">
				                        <input 
				                            type="checkbox" 
				                            class="material-control-input-secondary" 
				                            id="reschedule_delivery_date_checkbox" 
				                            onclick="reschedule_delivery_date_select_deselect()" 
				                        />
				                        <span class="material-control-indicator-secondary"></span>
				                        <span class="material-control-description-secondary">reschedule delivery date</span>
				                    </label>
				                </span>
				                <input type="radio" class="d-none" id="reschedule_delivery_date-fa-caret-down" name="sorting" onclick="sorting_by('reschedule_delivery_date', 'desc')">
				                <label for="reschedule_delivery_date-fa-caret-down" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-down text-danger" id="reschedule_delivery_date_desc"></i>
				                </label>
				            </div>
				        </div>
				    </th>
				    <th width="15%">
				        <div class="d-flex">
				            <div class="d-flex flex-column">
				                <input type="radio" class="d-none" id="reschedule_reminder_date-fa-caret-up" name="sorting" onclick="sorting_by('reschedule_reminder_date', 'asc')">
				                <label for="reschedule_reminder_date-fa-caret-up" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-up text-danger" id="reschedule_reminder_date_asc"></i>
				                </label>

				                <span class="text-uppercase d-flex">
				                    <label class="custom-control material-checkbox-secondary mx-2 my-1">
				                        <input 
				                            type="checkbox" 
				                            class="material-control-input-secondary" 
				                            id="reschedule_reminder_date_checkbox" 
				                            onclick="reschedule_reminder_date_select_deselect()" 
				                        />
				                        <span class="material-control-indicator-secondary"></span>
				                        <span class="material-control-description-secondary">
				                            <div class="d-flex">
				                                <span>reschedule msg<br/>send at</span>
				                                    <button
				                                        type="button"
				                                        class="btn btn-xs btn-secondary text-uppercase mx-2"
				                                        onclick="send_reschedule_reminder()"
				                                    ><i class="fa fa-send text-info"></i> <span id="reschedule_reminder_date_count">0</span></button>
				                            </div>
				                        </span>
				                    </label>
				                </span>
				                <input type="radio" class="d-none" id="reschedule_reminder_date-fa-caret-down" name="sorting" onclick="sorting_by('reschedule_reminder_date', 'desc')">
				                <label for="reschedule_reminder_date-fa-caret-down" style="margin:0px;">
				                    <i class="fa fa-fw fa-caret-down text-danger" id="reschedule_reminder_date_desc"></i>
				                </label>
				            </div>
				        </div>
				    </th>
				</thead>
			</table>
			<div class="list_wrapper">
				<table class="table table-sm table-hover text-uppercase" id="table_reload11">
					<tbody class="font-weight-bold" style="font-size: 0.7rem;">
					    <form class="form-horizontal" id="delivery_form">
					        <input type="hidden" id="today_date" value="<?php echo date('Y-m-d'); ?>"/>
					    <?php  
					        if(!empty($data['data'])): 
					            foreach ($data['data'] as $key => $value):
					    ?>
					                <tr>
					                    <td width="05%" class="<?php echo ENV == DEV ? '' : 'd-none'; ?>">
					                        <?php echo $value['cnt']; ?>/<?php echo $value['page']; ?>
					                    </td>
					                    <td width="5%"><?php echo $value['entry_no']; ?></td>
					                    <td width="5%">
					                        <?php echo $value['entry_date']; ?><br/>
					                        <?php echo $value['customer_mobile']; ?>
					                    </td>
					                    <td width="8%">
					                        <?php echo $value['customer_name']; ?>
					                         <button 
					                            type="button" 
					                            class="btn btn-xs" 
					                            onclick='order_status_popup(<?php echo json_encode($value); ?>)'
					                        ><i class="text-info fa fa-eye"></i></button>	
					                        <?php echo $value['apparel_data'];?>
					                    </td>
										<td width="5%"><?php echo $value['branch_name']; ?></td>
					                    <td width="5%" class="delivery_dates" id="delivery_date_<?php echo $value['om_id']; ?>"><?php echo $value['delivery_date']; ?></td>
					                    <td width="15%">
					                        <label class="custom-control material-checkbox">
					                            <input 
					                                type="checkbox" 
					                                class="material-control-input delivery_reminder_date_checkboxes" 
					                                id="delivery_reminder_date_<?php echo $value['om_id']; ?>"
					                                name="delivery_reminder_date[<?php echo $value['om_id']; ?>]"
					                                onclick="delivery_reminder_date_select_deselect(<?php echo $value['om_id']; ?>)" 
					                            />
					                            <span class="material-control-indicator"></span>
					                            <span class="material-control-description"><?php echo $value['delivery_reminder_date']; ?></span>
					                        </label>
					                    </td>
					                    <td width="15%">
					                        <label class="custom-control material-checkbox">
					                            <input 
					                                type="checkbox" 
					                                class="material-control-input reschedule_delivery_date_checkboxes" 
					                                id="reschedule_delivery_date_<?php echo $value['om_id']; ?>"
					                                name="om_reschedule_delivery_date[<?php echo $value['om_id']; ?>]"
					                                onclick="reschedule_delivery_date_select_deselect(<?php echo $value['om_id']; ?>)" 
					                            />
					                            <span class="material-control-indicator"></span>
					                            <span class="material-control-description"><?php echo $value['reschedule_delivery_date']; ?></span>
					                        </label>
					                    </td>
					                    <td width="15%">
					                        <label class="custom-control material-checkbox">
					                            <input 
					                                type="checkbox" 
					                                class="material-control-input reschedule_reminder_date_checkboxes" 
					                                id="reschedule_reminder_date_<?php echo $value['om_id']; ?>"
					                                name="reschedule_reminder_date[<?php echo $value['om_id']; ?>]"
					                                onclick="reschedule_reminder_date_select_deselect(<?php echo $value['om_id']; ?>)" 
					                            />
					                            <span class="material-control-indicator"></span>
					                            <span class="material-control-description"><?php echo $value['reschedule_reminder_date']; ?></span>
					                        </label>
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
					    </form>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section> 
<?php $this->load->view('templates/footer'); ?> 
<?php $this->load->view('pages/home/_delivery_filter', ['filters' => isset($data['filter']) ? $data['filter'] : []]); ?>
<script src="<?php echo assets('dist/js/report/report_v2.js?v=1')?>"></script>
<script src="<?php echo assets('dist/js/dashboard/delivery_schedule.js?v=1')?>"></script>

	</body>
</html>