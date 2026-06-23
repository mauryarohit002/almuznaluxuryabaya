<div class="d-flex justify-content-between">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active text-uppercase" id="sub_menu_name" aria-current="page">
                <a href="<?php echo base_url($menu.'/'.$sub_menu); ?>">
                    <?php echo str_replace('_', ' ', $sub_menu_name); ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-uppercase" aria-current="record-count">
                count : <span id="count_reload"><i id="total_rows"><?php echo $total_rows;?></i></span>
            </li>
        </ol>
    </nav>
    
    <div class="col-12 col-sm-12 col-md-6 col-lg-4 d-flex flex-wrap floating-form">
        <?php if(in_array('reschedule', $action_data)): ?>
            <div class="col-12 col-sm-12 col-md-4 col-lg-6 floating-label mt-3">
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
        <?php endif; ?>
    </div>
    <div class="d-flex align-items-center">
        <a 
            type="button" 
            class="btn btn-md btn-primary mx-2"
            data-toggle="tooltip" 
            data-placement="bottom" 
            title="TRIAL"
            href="<?php echo base_url($menu.'/trial_schedule'); ?>"
        ><i class="text-success fa fa-list"></i></a>
        <a 
            type="button" 
            class="btn btn-md btn-primary mx-2"
            data-toggle="tooltip" 
            data-placement="bottom" 
            title="REFRESH"
            href="<?php echo base_url($menu.'/'.$url)?>" 
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