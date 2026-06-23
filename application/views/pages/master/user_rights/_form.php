<?php 
    $this->load->view('templates/header');
    $menu   		= 'master';
	$sub_menu   	= 'user_rights';
	$action_data 	= get_action_data($menu, $sub_menu);
?>
<script>
    let link 		= "<?php echo $menu; ?>";
    let sub_link 	= "<?php echo $sub_menu; ?>";
</script>
<section class="d-flex flex-column sticky_top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-uppercase">
                <a href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list'); ?>">
                    <?php echo str_replace('_', ' ', $sub_menu); ?>
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="cancel-page">
                <a 
                    type="button" 
                    class="btn btn-sm btn-primary" 
                    data-toggle="tooltip" 
                    data-placement="bottom" 
                    title="CANCEL" 
                    tabindex="100"
                    href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list')?>" 
                ><i class="text-danger fa fa-close"></i></a>
            </li>
        </ol>
    </nav>
</section>
<section class="container-fluid my-3">
    <form class="form-horizontal" id="_form">
        <div class="row">
           <div class="col-12 col-sm-12 col-md-4 col-lg-3">
                <div class="card">
                    <div class="card-header text-uppercase">menu detail</div>
                    <div class="card-body" style="max-height: 80vh; overflow-x: auto;">
                        <?php if(!empty($master_data) ): ?>
                            <table class="table table-sm">
                                <tbody>
                                    <?php foreach ($master_data as $key => $value): ?>
                                        <tr>
                                            <td class="border-0" width="5%">
                                                <div class="d-flex align-items-end">
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-sm btn-primary mx-2" 
                                                        id="collapse_<?php echo $value['menu_id']; ?>" 
                                                        onclick="show_hide(<?php echo $value['menu_id']; ?>)"
                                                    ><i class="text-success fa fa-plus"></i></button>
                                                    <span><?php echo $key+1; ?></span>
                                                </div>	
                                            </td>
                                            <td class="border-0" width="50%"><?php echo $value['menu_name']; ?> (<?php echo count($value['trans_data']); ?>)</td>
                                        </tr>
                                        <tr class="d-none" id="multiCollapse_<?php echo $value['menu_id']; ?>">
                                            <td class="border-0" colspan="2">
                                                <table class="table-hover" width="100%">
                                                    <tbody>
                                                        <?php foreach ($value['trans_data'] as $k => $v):?>
                                                            <tr 
                                                                class="class_tr"
                                                                id="mt_id_<?php echo $v['mt_id']; ?>" 
                                                                style="cursor:pointer;"
                                                                onclick="get_assign_rights(<?php echo $v['mt_id'] ?>)"
                                                            >
                                                                <td width="20%"></td>
                                                                <td width="80%"><?php echo $v['mt_name'] ?></td>
                                                            </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>	
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else:?>
                            <div class="font-weight-bold text-center text-danger text-uppercase">no record found !!!</div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-5">
                <?php if(in_array('add', $action_data)): ?>
                    <div class="card mb-1">
                        <div class="card-header text-uppercase">user wise rights</div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap form-group floating-form py-2">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                    <select class="form-control floating-select" id="_user_id" name="_user_id"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div id="user_action_wrapper"></div>
            </div>
            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                <?php if(in_array('add', $action_data)): ?>
                    <div class="card mb-1">
                        <div class="card-header text-uppercase">role wise rights</div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap form-group floating-form py-2">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                    <select class="form-control floating-select" id="_role_id" name="_role_id"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div id="role_action_wrapper"></div>
            </div>
        </div>
    </form>
</section>
<?php $this->load->view('templates/footer'); ?>
<script src="<?php echo assets('dist/js/master/user_rights.js?v=1')?>"></script>
</body>
</html>