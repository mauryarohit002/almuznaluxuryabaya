$(document).ready(()=>{
    $("#brmm_id").select2(select2_default({
        url:`master/barcode/get_barcode_select2`,
        placeholder:'SCAN BARCODE',
        maximumInputLength:12,
        minimumInputLength:4,
        param:'GRN',
        param1:$('#gm_om_id').val(),
        barcode:'brmm_id',
    })).on('change', ()=> get_barcode_data());
    $("#entry_no").select2(select2_default({
        url:`grn/get_select2_entry_no`,
        placeholder:'ENTRY NO',
    })).on('change', () => trigger_search());
    $("#branch_id").select2(select2_default({
        url:`grn/get_select2_branch_id`,
        placeholder:'BRANCH',
    })).on('change', () => trigger_search());        
    $("#from_entry_date, #to_entry_date").on('change', () => trigger_search());
});
var outward_cnt = 1;

const bulk_receive_data = () =>{
    let check   = false;
    let total_tr= $('#outward_material_wrapper tr').length;
    if(total_tr > 0){
        for (let i = 1; i <= total_tr; i++){
            let id = $('#outward_material_wrapper tr:nth-child('+i+')').attr('id');
            let lastunderscore = id.lastIndexOf("_");
            let cnt = id.substring(lastunderscore+1);
           
            if(!$(`#received_status_${cnt}`).is(':checked')){
                $(`#received_status_${cnt}`).bootstrapToggle('on');
            }
        }
        calculate_master_total()
        show_product()

    }
    
}

const get_barcode_data = () =>{
    notifier('brmm_id')
    let brmm_id   = $('#brmm_id').val();
    let check   = false;
    let total_tr= $('#outward_material_wrapper tr').length;
    if(brmm_id != null){
        if(total_tr > 0){
            for (let i = 1; i <= total_tr; i++){
                let id = $('#outward_material_wrapper tr:nth-child('+i+')').attr('id');
                let lastunderscore = id.lastIndexOf("_");
                let cnt = id.substring(lastunderscore+1);
                let old_brmm_id   = $('#gt_brmm_id_'+cnt).val();
                if(brmm_id == old_brmm_id){
                    if(!$(`#received_status_${cnt}`).is(':checked')){
                        $(`#received_status_${cnt}`).bootstrapToggle('on');
                    }else{
                        check = true
                    }
                }
            }
        }
        if(check){
            notifier('brmm_id', 'Duplicate Barcode')
            toastr.error(`Barcode already received.`, "", {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
        });
            $("body, html").animate({'scrollTop':0},1000);
        }
        setTimeout(() =>{
            $('#brmm_id').val(null).trigger('change');
            $('#brmm_id').select2('open');
        },RELOAD_TIME);
    }
}
const set_gt_status = (cnt) => {
    let sku_name = $(`#sku_name_${cnt}`).val()
    let apparel_name = $(`#apparel_name_${cnt}`).val()
    if($(`#received_status_${cnt}`).is(':checked')){
        $(`#gt_status_${cnt}`).val(1);
        toastr.success(
            `${apparel_name} - ${sku_name} RECEIVED`,
            "",
            { closeButton: true, progressBar: true }
        );
    }else{
        $(`#gt_status_${cnt}`).val(0);
        toastr.error(`${apparel_name} - ${sku_name} NOT RECEIVED`, "", {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
        });
    }
    calculate_master_total()
    show_product()
}
const show_product = () =>{
    let total_tr = $('#outward_material_wrapper tr').length;
    for (let i = 1; i <= total_tr; i++) {
        let id = $('#outward_material_wrapper tr:nth-child('+i+')').attr('id');
        let lastunderscore = id.lastIndexOf("_");
        let cnt = id.substring(lastunderscore+1);
        if($('#show').is(':checked')){
            if(!$(`#received_status_${cnt}`).is(':checked')){
                $(`#rowid_${cnt}`).addClass('show_pending')
            }else{
                $(`#rowid_${cnt}`).removeClass('show_pending')
            }
        }else{
            if($(`#received_status_${cnt}`).is(':checked')){
                $(`#rowid_${cnt}`).addClass('show_pending')
            }else{
                $(`#rowid_${cnt}`).removeClass('show_pending')
            }
        }
    }
}
const calculate_master_total = () =>{
    let total_tr            = $('#outward_material_wrapper tr').length;
    let total_qty           = 0;
    let total_sub_amt       = 0;
    let total_final_amt     = 0;
    for (let i = 1; i <= total_tr; i++) {
        let id = $('#outward_material_wrapper tr:nth-child('+i+')').attr('id');
        let lastunderscore = id.lastIndexOf("_");
        let cnt = id.substring(lastunderscore+1);
        if($(`#gt_status_${cnt}`).val() == 1){
            let qty = $("#gt_qty_"+cnt).val() 
            if(isNaN(qty) || qty == '') qty = 0;

            let rate = $("#gt_rate_"+cnt).val() 
            if(isNaN(rate) || rate == '') rate = 0;

            let sub_total = parseFloat(qty) * parseFloat(rate);
            if(isNaN(sub_total) || sub_total == '') sub_total = 0; 
            $("#gt_sub_total_"+cnt).val(sub_total.toFixed(2));

            total_qty    = parseInt(total_qty) + parseInt(qty);
            total_sub_amt= parseFloat(total_sub_amt) + parseFloat(sub_total);
        }
    }
    $("#gm_total_qty").val(total_qty);        
    $("#gm_sub_total").val(total_sub_amt.toFixed(2));

    let after_decimal = parseFloat('0.'+total_sub_amt.toString().split(".")[1]);
    $("#gm_round_off").val(after_decimal.toFixed(2))
    
    total_final_amt = parseFloat(total_sub_amt); 
    $("#gm_final_amt").val(Math.round(total_final_amt));        
    
    if(total_final_amt > 0)
    {
        $('.master_block_btn').prop('disabled', false)
        // $('.master_block_btn').removeClass('btn-default').addClass('btn-success')
    }
    else
    {
        $('.master_block_btn').prop('disabled', true)   
        // $('.master_block_btn').removeClass('btn-success').addClass('btn-default')            
    }
    both_equal()
}
const both_equal = () =>{
    if(parseInt($("#gm_total_qty").val()) == parseInt($("#om_total_qty").val())){
       $("#gm_total_qty").removeClass('text-danger').addClass('text-success') 
       $("#gm_sub_total").removeClass('text-danger').addClass('text-success') 
       $("#gm_final_amt").removeClass('text-danger').addClass('text-success') 
    }else{
       $("#gm_total_qty").removeClass('text-success').addClass('text-danger');
       $("#gm_sub_total").removeClass('text-success').addClass('text-danger');
       $("#gm_final_amt").removeClass('text-success').addClass('text-danger');
    }
}
const remove_grn_master_notifier = () =>{
    notifier('gm_branch')
    notifier('gm_total_qty')
    notifier('gm_sub_total')
    notifier('gm_final_amt')
}
const add_update_grn = (id) =>{
    remove_grn_master_notifier()
    let check   = true;
    let total_tr= $('#outward_material_wrapper tr').length;
    if($("#gm_branch").val() == 0){
        notifier('gm_branch', 'Required')
        check = false;
    }
    if($("#gm_total_qty").val() <= 0){
        notifier('gm_total_qty', 'Required')
        check = false;
    }

    if($("#gm_sub_total").val() <= 0){
        notifier('gm_sub_total', 'Required')
        check = false;
    }

    if($("#gm_final_amt").val() <= 0){
        notifier('gm_final_amt', 'Required')
        check = false;
    }
    if(!check){
        toastr.error("You forgot to enter some information.", "Oh snap!!!", {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
        });
        $("body, html").animate({'scrollTop':0},1000);
    }else{
        let path        = "transfer/grn/add_update/"+id;   
        var form_data   = $("#grn_form").serialize();

        ajaxCall('POST',path,form_data,'JSON',resp =>{
            let {status, flag, msg} = resp;
            console.log(resp);
            if(status){
                if(flag == 1){
                    redirectPage('transfer/grn/pending?action=view');
                    remove_grn_master_notifier()
                    toastr.success(
                        msg,
                        "",
                        { closeButton: true, progressBar: true }
                    );
                    $("body, html").animate({'scrollTop':0},1000);
                    // setTimeout(function(){window.location.reload(); },RELOAD_TIME); 
                }else{
                    response_error(flag, msg)
                }
            }else{
                session_expired()               
            }                
        },errmsg => {
        });
    }
}
function remove_master(path) {

    if(!confirm("Are you sure you want to delete this record?")){
        return;
    }

    ajaxCall('GET', path, '', 'JSON', resp => {
        let {status, flag, msg} = resp;

        if(status){
            if(flag == 1){
                toastr.success(msg);

                setTimeout(() => {
                    window.location.reload();
                }, 800);

            }else{
                toastr.error(msg);
            }
        }else{
            session_expired();
        }
    });
}