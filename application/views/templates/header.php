<?php
  $user_id  	= $_SESSION['user_id'];
  $role     	= $_SESSION['user_role'];
  $name     	= $_SESSION['user_fullname'];
  $uname    	= $_SESSION['user_name'];
  $branch   	= $_SESSION['user_branch'];
  $branch_name  = $_SESSION['user_branch_name'];
  $fin_year 	= $_SESSION['fin_year'];
  $start_year   = $_SESSION['start_year'];
  $end_year 	= $_SESSION['end_year'];
  $title		= $this->config->item('title');
  $company_name	= isset($_SESSION['company_name']) ? $_SESSION['company_name'] : $title[1].' '. $title[2];
  $menu_data 	= get_menu_data();
  $bg_color 	= get_bgcolor();

	$menu_groups = [

		'system_setting' => [
			'label' => 'SYSTEM SETTING',
			'items' => [
				'apparel','branch','city','country','customer','measurement','measurement_setting','general','sku','supplier','size','menu','role','user','user_rights'
			]
		],

		'order' => [
			'label' => 'ORDER',
			'items' => [
				'estimate'
			]
		],

		'stock' => [
			'label' => 'STOCK',
			'items' => [
				'purchase_readymade','purchase_readymade_return'
			]
		],

		'production' => [
			'label' => 'PRODUCTION',
			'items' => [
				'karigar','proces','job_issue','job_receive','job_work','hisab'
			]
		],

		'transfer' => [
			'label' => 'TRANSFER',
			'items' => [
				'outward','grn_pending','grn'
			]
		],
		
		'utility' => [
			'label' => 'UTILITY',
			'items' => [
				'physical'
			]
		]
	];
		
	$all_menu_items = [];

	$voucher_items = [];
	$report_items  = [];

	foreach ($menu_data as $menu) {
		if (!empty($menu['trans_data'])) {
			foreach ($menu['trans_data'] as $item) {

				// attach controller
				$item['controller'] = $menu['menu_js'];

				// âœ… THIS WAS MISSING (CRITICAL)
				$all_menu_items[$item['mt_js']] = $item;

				// voucher menus
				if ($menu['menu_js'] === 'voucher') {
				    
				    // ❌ skip payment
					if ($_SESSION['user_branch_id'] == 4 && $item['mt_js'] === 'payment') {
						continue;
					}
					
					$voucher_items[] = $item;
				}

				// report menus
				if ($menu['menu_js'] === 'report') {
					$report_items[] = $item;
				}
			}
		}
	}
?>

<!DOCTYPE html> 
<html>
	<head>
		<meta charset="utf-8">
	  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	  	<title><?php echo $company_name; ?></title>
	  	
	  	<!-- Tell the browser to be responsive to screen width -->
	  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	  	<!-- Bootstrap 4 -->
	  	<link rel="stylesheet" href="<?php echo assets('plugins/bootstrap/css/bootstrap.min.css')?>">

	  	<!-- Font Awesome -->
	  	<link rel="stylesheet" href="<?php echo assets('plugins/font-awesome/css/font-awesome.min.css')?>">

	  	<!--Toastr-->
	  	<link rel="stylesheet" href="<?php echo assets('plugins/toastr/css/toastr.min.css'); ?>" />

	  	<!-- Date Picker -->
  		<link rel="stylesheet" href="<?php echo assets('plugins/datepicker/css/bootstrap-datepicker.css')?>">

			<!-- Toggle Switch -->
  		<link rel="stylesheet" href="<?php echo assets('plugins/toggle-switch/css/toggle.min.css')?>">

  		<!-- Select2 -->
  		<link rel="stylesheet" href="<?php echo assets('plugins/select2/css/select2.min.css')?>">
  		
  		<!-- Pan -->
  		<link rel="stylesheet" href="<?php echo assets('plugins/pan/css/pan.min.css')?>">

  		<!-- SweetAlert2 -->
  		<link rel="stylesheet" href="<?php echo assets('plugins/sweetalert2/css/sweetalert2.min.css')?>">

		<!-- Google Font -->
		<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		
		<!-- custom style sheet -->
		<link rel="stylesheet" href="<?php echo assets('dist/css/bootstrap.css?v=16')?>">
		<link rel="stylesheet" href="<?php echo assets('dist/css/common.css?v=8')?>">
		<link rel="stylesheet" href="<?php echo assets('dist/css/floating.css?v=1')?>">
		<link rel="stylesheet" href="<?php echo assets('dist/css/loader.css')?>">
		<link rel="stylesheet" href="<?php echo assets('dist/css/select2.css')?>">

		<style>
			:root { 
					--bg-color-primary : <?php echo $bg_color; ?>; 
					--font-color-secondary : <?php echo $bg_color; ?>; 
				}
				@font-face {
				  font-family: copperplate;
				  src: url(<?php echo assets('dist/css/copperplate/CopperplateCC-Heavy.ttf')?>);
				}

				body{ 
				  font-family: copperplate !important;
				}	
		</style>
	</head>
	<body class="wrapper blur">
		<!-- Modal  -->
		<?php $this->load->view('templates/modal/sm'); ?>
		<?php $this->load->view('templates/modal/lg'); ?>
		<?php $this->load->view('templates/modal/xl'); ?>
		<?php $this->load->view('templates/overlay/xl'); ?>

		<header class="sticky-top">
			<nav class="navbar navbar-expand-lg navbar-dark">
				<button 
					class="navbar-toggler hamburger_button" 
					type="button" 
					data-toggle="collapse" 
					data-target="#navbarSupportedContent" 
					aria-controls="navbarSupportedContent" 
					aria-expanded="false" 
					aria-label="Toggle navigation"
				><div class="hamburger_icon"><span></span><span></span><span></span></div></button>
				<div class="d-flex flex-column">
					<a class="navbar-brand d-flex flex-wrap flex-column" href="<?php echo base_url('/home'); ?>">
		    			<span class="border-bottom text-white font-weight-bold font-italic text-center" style="font-size: 10px;">
				  			<span><?php echo $company_name; ?></span>
			      		</span>
			      		<span class="text-white font-italic text-center" style="font-size: 12px;">
			  					<span class="text-white text-center font-italic" style="font-size: 12px;"><?php echo $fin_year ?></span>
				  				<input type="hidden" id="start_year" value="<?php echo $start_year ?>">
				  				<input type="hidden" id="end_year" value="<?php echo $end_year ?>">
			      		</span>	
		  			</a>
		    	</div>
				<div class="d-block d-sm-block d-md-block d-lg-none">
						<a class="p-2 rounded neu_flat_secondary text-secondary" href="<?php echo base_url('login/logout')?>" data-toggle="tooltip" data-placement="bottom" title="Logout">
						<i class="fa fa-sign-out"></i>
					</a>
					</div>
				</div>
				<div class="collapse navbar-collapse scroll" id="navbarSupportedContent">
					<ul class="navbar-nav">
						<?php foreach ($menu_groups as $key => $group): 
								$valid_items = [];

								foreach ($group['items'] as $js) {
									if (isset($all_menu_items[$js])) {
										$valid_items[$js] = $all_menu_items[$js];
									}
								}

								// ðŸš¨ If no submenu found, skip this group completely
								if (empty($valid_items)) {
									continue;
								}
							?>

    <?php if ($key === 'order'): ?>
        <!-- DIRECT ORDER LINK -->
        <?php 
            $order_js = $group['items'][0]; // estimate
            if (isset($all_menu_items[$order_js])): 
                $order_item = $all_menu_items[$order_js];
        ?>
            <li class="nav-item">
                <a class="nav-link font-weight-bold"
                   id="<?php echo $order_item['mt_js']; ?>"
                   href="<?php echo base_url(
                       $order_item['controller'].'/'.$order_item['mt_url']
                   ); ?>">
                    <?php echo $group['label']; ?>
                </a>
            </li>
        <?php endif; ?>

    <?php else: ?>
        <!-- NORMAL DROPDOWN (OTHER MENUS) -->
        <li class="nav-item dropdown position-static">

            <a class="nav-link dropdown-toggle"
               href="#"
               role="button"
               data-toggle="dropdown">
                <?php echo $group['label']; ?>
            </a>

            <div class="dropdown-menu w-100">
                <div class="d-flex flex-wrap">

                    <?php foreach ($group['items'] as $js): ?>
                        <?php if (isset($all_menu_items[$js])): ?>
                            <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                <a class="dropdown-item my-2"
                                   id="<?php echo $all_menu_items[$js]['mt_js']; ?>"
                                   href="<?php echo base_url(
                                       $all_menu_items[$js]['controller'].'/'.$all_menu_items[$js]['mt_url']
                                   ); ?>">
                                    <?php 
										$mt_name = '';
										if (isset($all_menu_items[$js]['mt_name'])) {
										$mt_name = $all_menu_items[$js]['mt_name'];
										}
										echo strtoupper($mt_name);
									?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
            </div>

        </li>
    <?php endif; ?>

<?php endforeach; ?>


						<!-- VOUCHER (AS IS) -->
						<?php if (!empty($voucher_items)): ?>
						<li class="nav-item dropdown position-static">

							<a class="nav-link dropdown-toggle font-weight-bold"
							href="#"
							role="button"
							data-toggle="dropdown">
								VOUCHER
							</a>

							<div class="dropdown-menu w-100">
								<div class="d-flex flex-wrap">

									<?php foreach ($voucher_items as $v): ?>
										<div class="col-12 col-sm-6 col-md-3 col-lg-3">
											<a class="dropdown-item my-2"
											id="<?php echo $v['mt_js']; ?>"
											href="<?php echo base_url($v['controller'].'/'.$v['mt_url']); ?>">
												<?php echo strtoupper($v['mt_name']); ?>
											</a>
										</div>
									<?php endforeach; ?>

								</div>
							</div>

						</li>
						<?php endif; ?>

						<!-- REPORT (AS IS) -->
						<?php if (!empty($report_items)): ?>
						<li class="nav-item dropdown position-static">

							<a class="nav-link dropdown-toggle font-weight-bold"
							href="#"
							role="button"
							data-toggle="dropdown">
								REPORT
							</a>

							<div class="dropdown-menu w-100">
								<div class="d-flex flex-wrap">

									<?php foreach ($report_items as $r): ?>
										<div class="col-12 col-sm-6 col-md-3 col-lg-3">
											<a class="dropdown-item my-2"
											id="<?php echo $r['mt_js']; ?>"
											href="<?php echo base_url($r['controller'].'/'.$r['mt_url']); ?>">
												<?php echo strtoupper($r['mt_name']); ?>
											</a>
										</div>
									<?php endforeach; ?>

								</div>
							</div>

						</li>
					<?php endif; ?>

				</ul>

				</div>
				<div class="d-none d-sm-none d-md-none d-lg-block mx-2">
					<div class="d-flex">
						<input 
							type="color"
							class="m-2 neu_flat_secondary"
							id="user_bgcolor"
							value="<?php echo $bg_color; ?>"
							style="width: 2rem;"
							onchange="set_user_bg_color()"
						/>
						<form class="form-inline my-2 my-lg-0 search_wrapper">
							<select name="search_sub_menu" class="search_sub_menu"></select>
						</form>
					</div>
				</div>
				<div class="d-none d-sm-none d-md-none d-lg-block">
					<div class="d-flex">
						<div class="d-flex flex-column ml-3 mr-2">
							<span class=" text-white font-weight-bold font-italic text-center border-bottom" style="font-size: 12px;">
								<?php echo strtoupper($uname); ?>
							</span>
							<span class=" text-white font-italic text-center" style="font-size: 10px;">
								<?php echo strtoupper($branch_name); ?>
							</span>	
						</div>
						<a class="mx-2 p-2 rounded neu_flat_secondary text-secondary" href="<?php echo base_url('login/logout')?>" data-toggle="tooltip" data-placement="bottom" title="Logout">
							<i class="fa fa-sign-out"></i>
						</a>
					</div>
				</div>
			</nav>
		</header>
		<main>