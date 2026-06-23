<?php

$summary = isset($data['summary'])
    ? $data['summary']
    : [];

?>

<div class="profit-navbar">

    <div class="navbar-top">

        <!-- LEFT -->

        <div>

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">

                        <a href="<?php echo base_url($menu.'/'.$sub_menu); ?>">

                            <?php echo strtoupper($menu_name); ?>

                        </a>

                    </li>

                    <li class="breadcrumb-item active">

                        <?php echo strtoupper($sub_menu_name); ?>

                    </li>

                </ol>

            </nav>

        </div>

        <!-- RIGHT -->

        <div class="right-tools">

            <!-- FILTER -->

            <form
                method="GET"
                action="<?php echo base_url($menu.'/'.$sub_menu); ?>"
                class="mini-filter-box"
            >

                <div class="floating-label">
                    <label class="text-uppercase" style="color: #000000;">entry date <small class="font-weight-bold">from</small></label>
                    <input 
                        type="date" 
                        class="form-control floating-input mini-input" 
                        id="_entry_date_from" 
                        name="_entry_date_from" 
                        value="<?php echo isset($_GET['_entry_date_from']) ? $_GET['_entry_date_from'] : date('Y-m-d'); ?>" 
                        placeholder=" " 
                        autocomplete="off" 
                    />   
                    
                </div>

                <div class="floating-label">
                    <label class="text-uppercase" style="color: #000000;">entry date <small class="font-weight-bold">from</small></label>
                    <input 
                        type="date" 
                        class="form-control floating-input mini-input" 
                        id="_entry_date_to" 
                        name="_entry_date_to" 
                        value="<?php echo isset($_GET['_entry_date_to']) ? $_GET['_entry_date_to'] : date('Y-m-d'); ?>" 
                        placeholder=" " 
                        autocomplete="off" 
                    />   
                    
                </div>

                <!-- <select
                    class="mini-select"
                    name="_order_type"
                >

                    <option value="">
                        ALL TYPE
                    </option>

                    <option
                        value="CUSTOM"
                        <?php echo (isset($_GET['_order_type']) && $_GET['_order_type'] == 'CUSTOM') ? 'selected' : ''; ?>
                    >
                        CUSTOM
                    </option>

                    <option
                        value="READYMADE"
                        <?php echo (isset($_GET['_order_type']) && $_GET['_order_type'] == 'READYMADE') ? 'selected' : ''; ?>
                    >
                        READYMADE
                    </option>

                </select> -->

                 <div class="floating-label">
                    <label class="text-uppercase" style="color: #000000;">Customer</label>
                    <select class="form-control floating-select mini-select" id="_customer" name="_customer">
                        <?php if(isset($_REQUEST['_customer']) && !empty($_REQUEST['_customer'])): ?>
                            <option value="<?php echo $_REQUEST['_customer']; ?>" selected>
                                <?php echo $_REQUEST['_customer']; ?> 
                            </option>
                        <?php endif; ?>
                    
                    </select> 
                    
                </div>

                <?php if($_SESSION['user_branch_id'] == 1){ ?>

                <div class="floating-label branch-filter">

                    <label class="text-uppercase" style="color: #000000;">Branch</label>

                    <select
                        class="form-control floating-select mini-select"
                        id="_branch"
                        name="_branch"
                        style="width:180px;"
                    >

                        <?php if(isset($_REQUEST['_branch']) && !empty($_REQUEST['_branch'])): ?>

                            <option
                                value="<?php echo $_REQUEST['_branch']; ?>"
                                selected
                            >
                                <?php echo $_REQUEST['_branch']; ?>
                            </option>

                        <?php endif; ?>

                    </select>

                </div>

                <?php } ?>

                <button
                    type="submit"
                    class="btn-mini-search"
                >
                    <i class="fa fa-search"></i>
                </button>

            </form>

            <!-- EXPORT -->

            <?php if(isset($action_data) && in_array('excel',$action_data)): ?>

                <a
                    href="<?php echo base_url($menu.'/'.$sub_menu.'/excel?'.$_SERVER['QUERY_STRING']); ?>"
                    target="_blank"
                    class="btn-premium btn-export"
                >
                    <i class="fa fa-file-excel-o"></i>
                    Excel
                </a>

            <?php endif; ?>

            <!-- REFRESH -->

            <a
                href="<?php echo base_url($menu.'/'.$sub_menu); ?>"
                class="btn-premium btn-refresh"
            >
                <i class="fa fa-refresh"></i>
                Refresh
            </a>

        </div>

    </div>

    <!-- SUMMARY -->

    <!-- <div class="quick-summary">

        <div class="summary-card summary-blue">

            <div class="summary-title">
                Total Sales
            </div>

            <div class="summary-value">
                ₹ <?= number_format($summary['total_sales'],2) ?>
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-title">
                Gross Profit
            </div>

            <div class="summary-value">
                ₹ <?= number_format($summary['gross_profit'],2) ?>
            </div>

        </div>

        <div class="summary-card summary-green">

            <div class="summary-title">
                Custom Profit
            </div>

            <div class="summary-value">
                ₹ <?= number_format($summary['custom_profit'],2) ?>
            </div>

        </div>

        <div class="summary-card summary-red">

            <div class="summary-title">
                Readymade Profit
            </div>

            <div class="summary-value">
                ₹ <?= number_format($summary['readymade_profit'],2) ?>
            </div>

        </div>

        <div class="summary-card">

            <div class="summary-title">
                Net Profit
            </div>

            <div class="summary-value">
                ₹ <?= number_format($summary['net_profit'],2) ?>
            </div>

        </div>

    </div> -->

</div>