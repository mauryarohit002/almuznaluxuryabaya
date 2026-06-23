<style>
    .premium-toggle-wrapper{
        display:flex;
        align-items:center;
        gap:12px;
        background:linear-gradient(135deg,#ffffff,#f8f9fc);
        border:1px solid rgba(0,0,0,.08);
        border-radius:14px;
        padding:10px 16px;
        box-shadow:
            0 4px 20px rgba(0,0,0,.06),
            inset 0 1px 0 rgba(255,255,255,.8);
        width:fit-content;
        transition:.3s ease;
    }

    .premium-toggle-wrapper:hover{
        transform:translateY(-1px);
        box-shadow:
            0 8px 25px rgba(0,0,0,.08),
            inset 0 1px 0 rgba(255,255,255,.9);
    }

    .premium-toggle{
        position:relative;
        width:62px;
        height:32px;
    }

    .premium-toggle{
    position:relative;
    width:62px;
    height:32px;
    display:inline-block;
}

.premium-toggle input{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    margin:0;
    opacity:0;
    cursor:pointer;
    z-index:5;
}

.premium-toggle-slider{
    position:absolute;
    inset:0;
    border-radius:50px;
    pointer-events:none;
}

    .premium-toggle-slider{
        position:absolute;
        inset:0;
        cursor:pointer;
        background:linear-gradient(135deg,#dee2e6,#ced4da);
        border-radius:50px;
        transition:.35s ease;
        box-shadow:inset 0 2px 6px rgba(0,0,0,.15);
    }

    .premium-toggle-slider::before{
        content:'';
        position:absolute;
        height:24px;
        width:24px;
        left:4px;
        top:4px;
        background:#fff;
        border-radius:50%;
        transition:.35s ease;
        box-shadow:
            0 3px 10px rgba(0,0,0,.25),
            inset 0 1px 2px rgba(255,255,255,.8);
    }

    .premium-toggle input:checked + .premium-toggle-slider{
        background:linear-gradient(135deg,#00c853,#00e676);
        box-shadow:
            inset 0 2px 8px rgba(0,0,0,.18),
            0 0 12px rgba(0,200,83,.35);
    }

    .premium-toggle input:checked + .premium-toggle-slider::before{
        transform:translateX(30px);
    }

    .premium-toggle input:disabled + .premium-toggle-slider{
        opacity:.9;
        cursor:not-allowed;
    }
    .premium-toggle {
        position: relative;
        width: 77px;
        height: 32px;
        display: inline-block;
    }

    .premium-toggle-status{
        display:flex;
        flex-direction:column;
        line-height:1.1;
    }

    .premium-toggle-status .title{
        font-size:11px;
        font-weight:700;
        letter-spacing:1px;
        color:#6c757d;
        text-transform:uppercase;
    }

    .premium-toggle-status .status-text{
        font-size:15px;
        font-weight:700;
        color:<?php echo !empty($report_status) ? '#00a63e' : '#dc3545'; ?>;
        transition:.3s ease;
    }

    .premium-toggle-badge{
        font-size:10px;
        font-weight:700;
        padding:4px 8px;
        border-radius:50px;
        background:<?php echo !empty($report_status) ? 'rgba(0,200,83,.12)' : 'rgba(220,53,69,.12)'; ?>;
        color:<?php echo !empty($report_status) ? '#00a63e' : '#dc3545'; ?>;
        width:fit-content;
        margin-top:3px;
    }
</style>
<?php

$report_status = 0;

if(
    isset($_REQUEST['_entry_date_from']) &&
    !empty($_REQUEST['_entry_date_from']) &&
    isset($_REQUEST['_entry_date_to']) &&
    !empty($_REQUEST['_entry_date_to'])
){

    $request_from = date(
        'Y-m-d',
        strtotime($_REQUEST['_entry_date_from'])
    );

    $request_to = date(
        'Y-m-d',
        strtotime($_REQUEST['_entry_date_to'])
    );


    /* =========================================
       CUSTOMER ID
    ========================================= */

    $customer_id = 0;

    if(isset($_REQUEST['_customer']) && !empty($_REQUEST['_customer'])){

        $customer = $this->db
            ->select('customer_id')
            ->where('customer_name', $_REQUEST['_customer'])
            ->get('customer_master')
            ->row_array();

        $customer_id = !empty($customer)
            ? $customer['customer_id']
            : 0;
    }


    /* =========================================
       BRANCH ID
    ========================================= */

    if($_SESSION['user_branch_id'] == 1){

        $branch_id = 0;

        if(isset($_REQUEST['_branch']) && !empty($_REQUEST['_branch'])){

            $branch = $this->db
                ->select('branch_id')
                ->where('branch_name', $_REQUEST['_branch'])
                ->get('branch_master')
                ->row_array();

            $branch_id = !empty($branch)
                ? $branch['branch_id']
                : 0;
        }

    }else{

        $branch_id = $_SESSION['user_branch_id'];
    }


    /* =========================================
       CHECK DATE RANGE MATCH
       request dates should fall
       INSIDE saved date range
    ========================================= */

    $this->db->where(
        'dtrm_entry_date_from <=',
        $request_from
    );

    $this->db->where(
        'dtrm_entry_date_to >=',
        $request_to
    );

    $this->db->where(
        'dtrm_branch_id',
        $branch_id
    );

    $this->db->where(
        'dtrm_customer_id',
        $customer_id
    );

    $this->db->where(
        'dtrm_status',
        1
    );

    $report = $this->db
        ->get('daily_transaction_report_master')
        ->row_array();

    $report_status = !empty($report) ? 1 : 0;
}
?>
<div class="d-flex justify-content-between">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item text-uppercase">
                <a href="<?php echo base_url($menu.'/'.$sub_menu); ?>">
                    <?php echo str_replace('_', ' ', $menu_name); ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-uppercase" id="sub_menu_name" aria-current="page">
                <?php echo str_replace('_', ' ', $sub_menu_name); ?>
            </li>
           
            <?php if(isset($download)): ?>
                <?php echo $download; ?>
            <?php endif; ?>

            <li>
                <form 
                    class="form-horizontal" 
                    id="search_form" 
                    method="get"
                    action="<?php echo base_url($menu.'/'.$sub_menu)?>" 
                >
                <div class="row"> 
                    <div class="d-flex flex-wrap floating-form">
                        <div class="d-flex col-12 col-sm-12 col-md-6 col-lg-6 mt-3">
                            <div class="floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="_entry_date_from" 
                                    name="_entry_date_from" 
                                    value="<?php echo isset($_REQUEST['_entry_date_from']) ? $_REQUEST['_entry_date_from'] : '' ?>" 
                                    placeholder=" " 
                                    autocomplete="off" 
                                />   
                                <label class="text-uppercase">entry date <small class="font-weight-bold">from</small></label>
                            </div>
                            <div class="floating-label">
                                <input 
                                    type="date" 
                                    class="form-control floating-input" 
                                    id="_entry_date_to" 
                                    name="_entry_date_to" 
                                    value="<?php echo isset($_REQUEST['_entry_date_to']) ? $_REQUEST['_entry_date_to'] : '' ?>" 
                                    placeholder=" " 
                                    autocomplete="off" 
                                />   
                                <label class="text-uppercase">entry date <small class="font-weight-bold">to</small></label>
                            </div>

                            <div class="floating-label col-5">
                                <select class="form-control floating-select" id="_customer" name="_customer">
                                    <?php if(isset($_REQUEST['_customer']) && !empty($_REQUEST['_customer'])): ?>
                                        <option value="<?php echo $_REQUEST['_customer']; ?>" selected>
                                            <?php echo $_REQUEST['_customer']; ?> 
                                        </option>
                                    <?php endif; ?>
                                
                                </select> 
                                <label class="text-uppercase">Customer</label>
                            </div>

                            
		                    <?php if($_SESSION['user_branch_id'] == 1){ ?>
                                <div class="floating-label col-5">
                                    <select class="form-control floating-select" id="_branch" name="_branch">
                                        <?php if(isset($_REQUEST['_branch']) && !empty($_REQUEST['_branch'])): ?>
                                            <option value="<?php echo $_REQUEST['_branch']; ?>" selected>
                                                <?php echo $_REQUEST['_branch']; ?> 
                                            </option>
                                        <?php endif; ?>
                                    
                                    </select> 
                                    <label class="text-uppercase">branch</label>
                                </div>
                            <?php } ?>
                            <button 
                                type="submit" 
                                id="btn_search" 
                                class="btn btn-md btn-secondary btn-block text-uppercase mr-2" 
                                style="height: fit-content;"
                            >search</button>

                            <?php if($_SESSION['user_branch_id'] == 1){ ?>
                                <div class="premium-toggle-wrapper mx-3">

                                    <label class="premium-toggle mb-0">

                                        <input 
                                            type="checkbox" 
                                            id="daily_transaction_status"
                                            <?php echo !empty($report_status) ? 'checked disabled' : ''; ?>
                                        >

                                        <span class="premium-toggle-slider"></span>

                                    </label>

                                    <div class="premium-toggle-status">

                                        <span class="title">Daily Transaction</span>

                                        <span 
                                            class="status-text"
                                            id="daily_transaction_status_text"
                                        >
                                            <?php echo !empty($report_status) ? 'Received' : 'Pending'; ?>
                                        </span>

                                        <span class="premium-toggle-badge">
                                            <?php echo !empty($report_status) ? 'LOCKED' : 'ACTION REQUIRED'; ?>
                                        </span>

                                    </div>

                                </div>
                            <?php } ?>
                            
                        </div> 
                    
                    </div>
                </div>
                </form>
            </li>
        </ol>
    </nav>
    <div class="d-flex align-items-center">
        <?php if(in_array('excel', $action_data)): ?>
            <a 
                type="button" 
                class="btn btn-md btn-primary" 
                id="report_excel_export"
                target="_blank"
                href="<?php echo base_url($menu.'/'.$sub_menu.'/excel?'.$_SERVER['QUERY_STRING']); ?>"
                data-toggle="tooltip" 
                data-placement="bottom" 
                title="EXCEL"
            ><i class="text-warning fa fa-file-excel-o"></i></a>
        <?php endif; ?>
        <?php if(in_array('sync', $action_data)): ?>
            <button 
                type="button" 
                class="btn btn-md btn-primary" 
                target="_blank"
                onclick="sync('<?php echo $sub_menu; ?>')" 
                data-toggle="tooltip" 
                data-placement="bottom" 
                title="SYNC"
            ><i class="text-warning fa fa-retweet"></i></button>
        <?php endif; ?>
        <?php if(in_array('add', $action_data)): ?>
            <a 
                type="button" 
                class="btn btn-md btn-primary mx-2"
                data-toggle="tooltip" 
                data-placement="bottom" 
                title="ADD NEW"
                <?php echo $add; ?> 
            ><i class="text-success fa fa-plus"></i></a>
        <?php endif; ?>
        <a 
            type="button" 
            class="btn btn-md btn-primary mx-2"
            data-toggle="tooltip" 
            data-placement="bottom" 
            title="REFRESH"
            href="<?php echo base_url($menu.'/'.$sub_menu.'?action=list'); ?>"
        ><i class="text-info fa fa-undo"></i></a>
        <!-- <button 
            type="button" 
            class="btn btn-md btn-primary btn-filter mx-2"
            data-toggle="tooltip" 
            data-placement="bottom" 
            title="FILTER"
            onclick="toggle_right_panel()"
        >
            <i class="text-dark fa fa-filter"></i>
            <span class="badge badge-dark" id="filter_count"><?php echo isset($data['filter']) ? count($data['filter']) : ''; ?></span>
        </button>  -->
    </div>
</div>