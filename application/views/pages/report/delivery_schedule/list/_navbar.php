<div class="d-flex justify-content-between">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-uppercase">
                <a href="<?php echo base_url($menu.'/'.$sub_menu); ?>">
                    <?php echo str_replace('_', ' ', $menu_name); ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-uppercase" id="sub_menu_name" aria-current="page">
                <?php echo str_replace('_', ' ', $sub_menu_name); ?>
            </li>
            <li class="breadcrumb-item active text-uppercase" aria-current="record-count">
                count : <span id="count_reload"><i id="total_rows"><?php echo $total_rows;?></i></span>
            </li>
        </ol>
    </nav>
    <div class="d-flex align-items-center">
        <a 
            type="button" 
            class="btn btn-md btn-primary mx-2"
            id="<?php echo $sub_menu; ?>_pdf_btn"
            data-toggle="tooltip" 
            data-placement="bottom" 
            title="PRINT"
            target="_blank"
            href="<?php echo base_url($menu.'/'.$sub_menu.'/pdf?'.$_SERVER['QUERY_STRING']); ?>"
        ><i class="text-success fa fa-print"></i></a>
        <a 
            type="button" 
            class="btn btn-md btn-primary mx-2"
            data-toggle="tooltip" 
            data-placement="bottom" 
            title="REFRESH"
            href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list'); ?>"
        ><i class="text-info fa fa-undo"></i></a>
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