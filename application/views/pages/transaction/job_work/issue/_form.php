<?php 
    $this->load->view('templates/header');
    $action = (isset($_GET['action'])) ? $_GET['action'] : "";
?>
<script>
    let link 		= "<?php echo $menu; ?>";
    let sub_link 	= "<?php echo $sub_menu; ?>";
</script>
<section class="sticky_top">
    <div class="d-flex justify-content-between">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-uppercase">
                    <a href="<?php echo base_url($menu.'/'.$sub_menu.'?action=issue'); ?>">
                        <?php echo str_replace('_', ' ', $sub_menu_name); ?>
                    </a>
                </li>
            </ol>
        </nav>
        <div class="mt-2">
            <a 
                type="button" 
                class="btn btn-md mx-2 text-uppercase <?php echo $_GET['action'] == 'issue' ? 'btn-secondary' : 'btn-primary'; ?>" 
                href="<?php echo base_url($menu.'/'.$sub_menu.'?action=issue')?>" 
            >job issue</a>
            <a 
                type="button" 
                class="btn btn-md mx-2 text-uppercase <?php echo $_GET['action'] != 'issue' ? 'btn-secondary' : 'btn-primary'; ?>" 
                href="<?php echo base_url($menu.'/'.$sub_menu.'?action=receive')?>" 
            >job receive</a>
        </div>
        <div class="d-flex align-items-center">
            <a 
                type="button" 
                class="btn btn-md btn-primary mx-2" 
                data-toggle="tooltip" 
                data-placement="bottom" 
                title="REFRESH" 
                tabindex="100"
                href="<?php echo base_url($menu.'/'.$sub_menu.'?action=issue')?>" 
            ><i class="text-warning fa fa-undo"></i></a>
        </div>
    </div>
</section>
<section class="container-fluid my-3">
    <form class="form-horizontal" id="_form">
        <?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/issue/_body'); ?>
    </form>
</section>
<?php $this->load->view('templates/footer'); ?>
<?php $this->load->view('pages/'.$menu.'/'.$sub_menu.'/issue/_footer'); ?>
</body>
</html>