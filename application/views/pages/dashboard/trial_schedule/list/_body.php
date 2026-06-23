<tbody class="font-weight-bold" style="font-size: 0.7rem;">
    <form class="form-horizontal" id="trial_form">
        <input type="hidden" id="today_date" value="<?php echo date('Y-m-d'); ?>"/>
    <?php 
        if(!empty($data)): 
            foreach ($data as $key => $value):
    ?>
                <tr>
                    <td width="05%" class="<?php echo ENV == DEV ? '' : 'd-none'; ?>">
                        <?php echo $value['cnt']; ?>/<?php echo $value['page']; ?>
                    </td>
                    <td width="5%"><?php echo $value['memo_no']; ?></td>
                    <td width="5%">
                        <?php echo $value['entry_date']; ?><br/>
                        <?php echo $value['customer_mobile']; ?>
                    </td>
                    <td width="8%">
                        <?php echo $value['customer_name']; ?>
                        <button 
                            type="button" 
                            class="btn btn-xs" 
                            onclick='order_status_popup(<?php echo json_encode($value); ?>)'
                        ><i class="text-info fa fa-eye"></i></button>
                        <?php echo $value['apparel_data'];?>
                    </td>
                    <td width="5%" class="trial_dates" id="trial_date_<?php echo $value['om_id']; ?>"><?php echo $value['trial_date']; ?></td>
                    <td width="15%">
                        <label class="custom-control material-checkbox">
                            <input 
                                type="checkbox" 
                                class="material-control-input trial_reminder_date_checkboxes" 
                                id="trial_reminder_date_<?php echo $value['om_id']; ?>"
                                name="trial_reminder_date[<?php echo $value['om_id']; ?>]"
                                onclick="trial_reminder_date_select_deselect(<?php echo $value['om_id']; ?>)" 
                            />
                            <span class="material-control-indicator"></span>
                            <span class="material-control-description"><?php echo $value['trial_reminder_date']; ?></span>
                        </label>
                    </td>
                    <td width="15%">
                        <label class="custom-control material-checkbox">
                            <input 
                                type="checkbox" 
                                class="material-control-input reschedule_trial_date_checkboxes" 
                                id="reschedule_trial_date_<?php echo $value['om_id']; ?>"
                                name="om_reschedule_trial_date[<?php echo $value['om_id']; ?>]"
                                onclick="reschedule_trial_date_select_deselect(<?php echo $value['om_id']; ?>)" 
                            />
                            <span class="material-control-indicator"></span>
                            <span class="material-control-description"><?php echo $value['reschedule_trial_date']; ?></span>
                        </label>
                    </td>
                    <td width="15%">
                        <label class="custom-control material-checkbox">
                            <input 
                                type="checkbox" 
                                class="material-control-input reschedule_reminder_date_checkboxes" 
                                id="reschedule_reminder_date_<?php echo $value['om_id']; ?>"
                                name="reschedule_reminder_date[<?php echo $value['om_id']; ?>]"
                                onclick="reschedule_reminder_date_select_deselect(<?php echo $value['om_id']; ?>)" 
                            />
                            <span class="material-control-indicator"></span>
                            <span class="material-control-description"><?php echo $value['reschedule_reminder_date']; ?></span>
                        </label>
                    </td>
                </tr>
    <?php 
            endforeach;
        else: 
    ?>
        <tr>
            <td class="text-danger font-weight-bold text-center" colspan="10">NO RECORD FOUND!!!</td>
        </tr>
    <?php endif; ?>
    </form>
</tbody>