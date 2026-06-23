<?php $action = (isset($_GET['action'])) ? $_GET['action'] : "";?>
<form 
    class="form-horizontal" 
    id="search_form" 
    method="get"
    action="<?php echo base_url($menu.'/'.$sub_menu)?>" 
>
    <input type="hidden" name="action" value="<?php echo $action; ?>">	
    <div class="right-panel neu_flat_primary" id="master_right_panel">
        <div class="right-panel-header">
            <?php $this->load->view('pages/'.$header.'/filter/_header'); ?>
        </div>
        <hr/>
        <div class="right-panel-body" >        
            <?php $this->load->view('pages/'.$body.'/filter/_body'); ?>
        </div>
        <div class="right-panel-footer">
            <?php $this->load->view('pages/'.$footer.'/filter/_footer'); ?>
        </div>
    </div>
</form>