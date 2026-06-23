<?php $action = (isset($_GET['action'])) ? $_GET['action'] : "";?>
<form 
    class="form-horizontal" 
    id="search_form" 
    method="get"
    action="<?php echo base_url('home/trial_schedule')?>" >
    <input type="hidden" name="action" value="<?php echo $action; ?>">	
    <div class="right-panel neu_flat_primary" id="master_right_panel">
        <div class="right-panel-header">
            <div class="d-flex align-items-center">
			    <div class="d-flex flex-column" style="flex-grow: 1;">
			        <div 
			            class="right-panel-title font-weight-bold font-italic text-white text-center text-uppercase" 
			            style="font-size: 1rem;"
			        >Trial schedule</div>	
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
        </div>
        <hr/>
        <div class="right-panel-body" >        
            <div class="row">
			    <div class="d-flex flex-wrap floating-form">
			        <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label mt-3">
			            <p class="text-uppercase">customer</p>
			            <select class="form-control floating-select" id="_customer_name" name="_customer_name">
			                <?php if(isset($filters['_customer_name']) && !empty($filters['_customer_name'])): ?>
			                    <option value="<?php echo $filters['_customer_name']['value']; ?>" selected>
			                        <?php echo $filters['_customer_name']['text']; ?> 
			                    </option>
			                <?php endif; ?>
			            </select>
			        </div>
			        <div class="d-flex col-12 col-sm-12 col-md-12 col-lg-12 mt-3">
			            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
			                <input 
			                    type="date" 
			                    class="form-control floating-input" 
			                    id="_date_from" 
			                    name="_date_from" 
			                    value="<?php echo isset($filters['_date_from']) ? $filters['_date_from'] : date('Y-m-d') ?>" 
			                    placeholder=" " 
			                    autocomplete="off" 
			                />   
			                <label class="text-uppercase">trial date <small class="font-weight-bold">from</small></label>
			            </div>
			            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
			                <input 
			                    type="date" 
			                    class="form-control floating-input" 
			                    id="_date_to" 
			                    name="_date_to" 
			                    value="<?php echo isset($filters['_date_to']) ? $filters['_date_to'] : date('Y-m-d') ?>" 
			                    placeholder=" " 
			                    autocomplete="off" 
			                />   
			                <label class="text-uppercase">trial date <small class="font-weight-bold">to</small></label>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
        <div class="right-panel-footer">
            <button 
			    type="submit" 
			    id="btn_search" 
			    class="btn btn-md btn-secondary btn-block text-uppercase">search</button>
			<a 
			    href="<?php echo base_url('home/trial_schedule')?>" 
			    id="btn_reset" 
			    class="btn btn-md btn-secondary btn-block text-uppercase m-0">reset</a>
        </div>
    </div>
</form>