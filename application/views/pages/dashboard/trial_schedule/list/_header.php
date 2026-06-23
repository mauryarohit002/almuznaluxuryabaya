<thead>
    <th width="5%" class="<?php echo ENV == DEV ? '' : 'd-none'; ?>">#</th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="memo_no-fa-caret-up" name="sorting" onclick="sorting_by('memo_no', 'asc')">
                <label for="memo_no-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="memo_no_asc"></i>
                </label>

                <span class="text-uppercase">order no</span>
                <input type="radio" class="d-none" id="memo_no-fa-caret-down" name="sorting" onclick="sorting_by('memo_no', 'desc')">
                <label for="memo_no-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="memo_no_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_mobile-fa-caret-up" name="sorting" onclick="sorting_by('customer_mobile', 'asc')">
                <label for="customer_mobile-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="customer_mobile_asc"></i>
                </label>

                <span class="text-uppercase">mobile no.</span>
                <input type="radio" class="d-none" id="customer_mobile-fa-caret-down" name="sorting" onclick="sorting_by('customer_mobile', 'desc')">
                <label for="customer_mobile-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_mobile_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="8%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="customer_name-fa-caret-up" name="sorting" onclick="sorting_by('customer_name', 'asc')">
                <label for="customer_name-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="customer_name_asc"></i>
                </label>

                <span class="text-uppercase">customer</span>
                <input type="radio" class="d-none" id="customer_name-fa-caret-down" name="sorting" onclick="sorting_by('customer_name', 'desc')">
                <label for="customer_name-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="customer_name_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="5%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="trial_date-fa-caret-up" name="sorting" onclick="sorting_by('trial_date', 'asc')">
                <label for="trial_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="trial_date_asc"></i>
                </label>

                <span class="text-uppercase">trial date</span>
                <input type="radio" class="d-none" id="trial_date-fa-caret-down" name="sorting" onclick="sorting_by('trial_date', 'desc')">
                <label for="trial_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="trial_date_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="15%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="trial_reminder_date-fa-caret-up" name="sorting" onclick="sorting_by('trial_reminder_date', 'asc')">
                <label for="trial_reminder_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="trial_reminder_date_asc"></i>
                </label>

                <span class="text-uppercase d-flex">
                    <label class="custom-control material-checkbox-secondary mx-2 my-1">
                        <input 
                            type="checkbox" 
                            class="material-control-input-secondary" 
                            id="trial_reminder_date_checkbox" 
                            onclick="trial_reminder_date_select_deselect()" 
                        />
                        <span class="material-control-indicator-secondary"></span>
                        <span class="material-control-description-secondary">
                            <div class="d-flex">
                                <span>trial msg<br/>send at</span>
                                <?php if(in_array('trial_reminder', $action_data)): ?>
                                    <button
                                        type="button"
                                        class="btn btn-xs btn-secondary text-uppercase mx-2"
                                        onclick="send_trial_reminder()"
                                    ><i class="fa fa-send text-info"></i> <span id="trial_reminder_date_count">0</span></button>
                                <?php endif; ?>
                            </div>
                        </span>
                    </label>
                </span>
                <input type="radio" class="d-none" id="trial_reminder_date-fa-caret-down" name="sorting" onclick="sorting_by('trial_reminder_date', 'desc')">
                <label for="trial_reminder_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="trial_reminder_date_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="15%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="reschedule_trial_date-fa-caret-up" name="sorting" onclick="sorting_by('reschedule_trial_date', 'asc')">
                <label for="reschedule_trial_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="reschedule_trial_date_asc"></i>
                </label>

                <span class="text-uppercase d-flex">
                    <label class="custom-control material-checkbox-secondary mx-2 my-1">
                        <input 
                            type="checkbox" 
                            class="material-control-input-secondary" 
                            id="reschedule_trial_date_checkbox" 
                            onclick="reschedule_trial_date_select_deselect()" 
                        />
                        <span class="material-control-indicator-secondary"></span>
                        <span class="material-control-description-secondary">reschedule trial date</span>
                    </label>
                </span>
                <input type="radio" class="d-none" id="reschedule_trial_date-fa-caret-down" name="sorting" onclick="sorting_by('reschedule_trial_date', 'desc')">
                <label for="reschedule_trial_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="reschedule_trial_date_desc"></i>
                </label>
            </div>
        </div>
    </th>
    <th width="15%">
        <div class="d-flex">
            <div class="d-flex flex-column">
                <input type="radio" class="d-none" id="reschedule_reminder_date-fa-caret-up" name="sorting" onclick="sorting_by('reschedule_reminder_date', 'asc')">
                <label for="reschedule_reminder_date-fa-caret-up" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-up text-danger" id="reschedule_reminder_date_asc"></i>
                </label>

                <span class="text-uppercase d-flex">
                    <label class="custom-control material-checkbox-secondary mx-2 my-1">
                        <input 
                            type="checkbox" 
                            class="material-control-input-secondary" 
                            id="reschedule_reminder_date_checkbox" 
                            onclick="reschedule_reminder_date_select_deselect()" 
                        />
                        <span class="material-control-indicator-secondary"></span>
                        <span class="material-control-description-secondary">
                            <div class="d-flex">
                                <span>reschedule msg<br/>send at</span>
                                <?php if(in_array('reschedule_reminder', $action_data)): ?>
                                    <button
                                        type="button"
                                        class="btn btn-xs btn-secondary text-uppercase mx-2"
                                        onclick="send_reschedule_reminder()"
                                    ><i class="fa fa-send text-info"></i> <span id="reschedule_reminder_date_count">0</span></button>
                                <?php endif; ?>
                            </div>
                        </span>
                    </label>
                </span>
                <input type="radio" class="d-none" id="reschedule_reminder_date-fa-caret-down" name="sorting" onclick="sorting_by('reschedule_reminder_date', 'desc')">
                <label for="reschedule_reminder_date-fa-caret-down" style="margin:0px;">
                    <i class="fa fa-fw fa-caret-down text-danger" id="reschedule_reminder_date_desc"></i>
                </label>
            </div>
        </div>
    </th>
</thead>