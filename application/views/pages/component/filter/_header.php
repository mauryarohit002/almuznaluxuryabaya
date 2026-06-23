<div class="d-flex align-items-center">
    <div class="d-flex flex-column" style="flex-grow: 1;">
        <div 
            class="right-panel-title font-weight-bold font-italic text-white text-center text-uppercase" 
            style="font-size: 1rem;"
        ><?php echo $sub_menu_name; ?></div>	
        <div 
            class="right-panel-subtitle font-weight-bold font-italic text-white text-center text-uppercase pt-2" 
            style="font-size: 0.8rem;"
        >filter</div>
    </div>
    <button 
        type="button" 
        class="btn btn-md btn-secondary mx-2" 
        id="btn_close" 
        onclick="toggle_right_panel()"
    ><i class="text-warning fa fa-close"></i></button>
</div>