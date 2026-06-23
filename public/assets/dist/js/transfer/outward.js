$(document).ready(()=>{
    $("#brmm_id").select2(select2_default({
        url:`transfer/outward/get_barcode_select2`,
        placeholder:'SCAN BARCODE',
        maximumInputLength:12,
        minimumInputLength:4,
        param:'OUTWARD',
        param1:$('#om_id').val(),
        param2:$('#om_branch').val(),
        barcode:'brmm_id',
    })).on('change', ()=> get_barcode_data());
    $("#entry_no").select2(select2_default({
        url:`outward/get_select2_entry_no`,
        placeholder:'ENTRY NO',
    })).on('change', () => trigger_search());
    $("#branch_id").select2(select2_default({
        url:`outward/get_select2_branch_id`,
        placeholder:'BRANCH',
    })).on('change', () => trigger_search());    
    $("#from_entry_date, #to_entry_date").on('change', () => trigger_search());
});
var outward_cnt = 1;
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
                let old_brmm_id   = $('#ot_brmm_id_'+cnt).val();
                if(brmm_id == old_brmm_id){
                    check = true;
                }
            }
        }
        if(check){
            toastr.error("Duplicate item found!", "", {
                closeButton: true,
                progressBar: true,
                preventDuplicates: true,
            });
            notifier('brmm_id', 'Barcode already added')
            $("body, html").animate({'scrollTop':0},1000);

            setTimeout(() =>{
                $('#brmm_id').val(null).trigger('change');
                $('#brmm_id').select2('open');
            },RELOAD_TIME);
        }else{
            let path = `transfer/outward/get_barcode_data/${brmm_id}`;
            ajaxCall('GET',path,'','JSON',resp=>{
                let {status, flag, data, msg} = resp
                if(status){
                    if(flag == 1){
                        if(data.length != 0){
                            let tr = `
                                <tr id="rowid_${outward_cnt}" class="floating-form">
                                    <td class="floating-label">
                                        <input type="hidden" name="ot_id[]" id="ot_id_${outward_cnt}" value="0" />
                                        <input type="hidden" name="ot_brmm_id[]" id="ot_brmm_id_${outward_cnt}" value="${data[0]['brmm_id']}" />
                                        <input type="number" class="form-control floating-input" id="brmm_item_code_${outward_cnt}" value="${data[0]['brmm_item_code']}" readonly />
                                    </td>
                                    
                                        <input type="hidden" class="form-control floating-input" name="ot_bill_no[]" id="ot_bill_no_${outward_cnt}" value="${data[0]['prmm_bill_no']}" readonly />
                                        <input type="hidden" name="ot_prmm_id[]" id="ot_prmm_id_${outward_cnt}" value="${data[0]['prmm_id']}" />
                                        
                                        <input type="hidden" class="form-control floating-input" name="ot_bill_date[]" id="ot_bill_date_${outward_cnt}" value="${data[0]['prmm_bill_date']}" readonly />
                                  
                                    <td class="floating-label">
                                        <input type="text" class="form-control floating-input" id="sku_name_${outward_cnt}" value="${data[0]['sku_name']}" readonly />
                                        <input type="hidden" name="ot_sku_id[]" id="ot_sku_id_${outward_cnt}" value="${data[0]['sku_id']}" />
                                    </td>
                                    <td class="floating-label">
                                        <input type="text" class="form-control floating-input" id="apparel_name_${outward_cnt}" value="${data[0]['apparel_name']}" readonly />
                                        <input type="hidden" name="ot_apparel_id[]" id="ot_apparel_id_${outward_cnt}" value="${data[0]['apparel_id']}" />
                                    </td>
                                  
                                    <td class="floating-label">
                                        <input type="number" class="form-control floating-input" name="ot_qty[]" id="ot_qty_${outward_cnt}" value="${data[0]['brmm_prmt_qty']}" readonly />
                                    </td>
                                    <td class="floating-label">
                                        <input type="number" class="form-control floating-input" name="ot_rate[]" id="ot_rate_${outward_cnt}" value="${data[0]['brmm_prmt_rate']}" readonly />
                                    </td>
                                    <td class="floating-label">
                                        <input type="number" class="form-control floating-input" name="ot_sub_total[]" id="ot_sub_total_${outward_cnt}" value="${data[0]['brmm_prmt_rate']}" readonly />
                                    </td>
                                    <td class="floating-label">
                                        <button type="button" class="btn btn-primary" href="javascript:void(0)" onclick="remove_row(${outward_cnt})"> 
                                            <i class="text-danger fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;

                            $('#outward_material_wrapper').prepend(tr);

                            toastr.success(
                                `${data[0]['apparel_name']} - ${data[0]['sku_name']} ADDED`,
                                "",
                                { closeButton: true, progressBar: true }
                            );
                            
                            outward_cnt++;

                            $('#brmm_id').val(null).trigger('change');
                            $('#brmm_id').select2('open');
                            calculate_master_total()                            

                        }
                    }else{
                        response_error(flag, msg)
                    }
                }else{
                    session_expired();
                }
            }, errmsg=>{})
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
        
        let qty = $("#ot_qty_"+cnt).val() 
        if(isNaN(qty) || qty == '') qty = 0;

        let rate = $("#ot_rate_"+cnt).val() 
        if(isNaN(rate) || rate == '') rate = 0;

        let sub_total = parseFloat(qty) * parseFloat(rate);
        if(isNaN(sub_total) || sub_total == '') sub_total = 0; 
        $("#ot_sub_total_"+cnt).val(sub_total.toFixed(2));

        total_qty    = parseInt(total_qty) + parseInt(qty);
        total_sub_amt= parseFloat(total_sub_amt) + parseFloat(sub_total);
    }
    $("#om_total_qty").val(total_qty);        
    $("#om_sub_total").val(total_sub_amt.toFixed(2));

    let after_decimal = parseFloat('0.'+total_sub_amt.toString().split(".")[1]);
    $("#om_round_off").val(after_decimal.toFixed(2))
    
    total_final_amt = parseFloat(total_sub_amt); 
    $("#om_final_amt").val(Math.round(total_final_amt));        
    
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
}
const remove_row = cnt =>{
    let apparel_name = $(`#apparel_name_${cnt}`).val()
    let sku_name = $(`#sku_name_${cnt}`).val()
    toastr.success(
        `${apparel_name} - ${sku_name} REMOVED`,
        "",
        { closeButton: true, progressBar: true }
    );
    $("#rowid_"+cnt).detach();  
    calculate_master_total() 
}
const remove_out_master_notifier = () =>{
    notifier('om_branch')
    notifier('om_total_qty')
    notifier('om_sub_total')
    notifier('om_final_amt')
}
const add_update_outward = (id) =>{
    remove_out_master_notifier()
    let check   = true;
    let total_tr= $('#outward_material_wrapper tr').length;
    if($("#om_branch").val() == 0){
        notifier('om_branch', 'Required')
        check = false;
    }
    if($("#om_total_qty").val() <= 0){
        notifier('om_total_qty', 'Required')
        check = false;
    }

    if($("#om_sub_total").val() <= 0){
        notifier('om_sub_total', 'Required')
        check = false;
    }

    if($("#om_final_amt").val() <= 0){
        notifier('om_final_amt', 'Required')
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
        let path        = "transfer/outward/add_update/"+id;   
        let form_data   = $("#outward_form").serialize();
        ajaxCall('POST',path,form_data,'JSON',resp =>{
            let {status, flag, msg} = resp;
            if(status){
                if(flag == 1){
                    if(id == 0) {
                    } else {
                    }
                    remove_out_master_notifier()
                    toastr.success(
                        msg,
                        "",
                        { closeButton: true, progressBar: true }
                    );
                    $("body, html").animate({'scrollTop':0},1000);
                    setTimeout(function(){window.location.reload(); },RELOAD_TIME); 
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

function open_barcode_modal() {
    let path = "transfer/outward/get_all_purchase_barcodes";

    ajaxCall('GET', path, '', 'JSON', resp => {
        let {status, flag, data} = resp;

        if(status && flag == 1){
            let html = '';
            let count = data.length;

            data.forEach(row => {
                html += `
                    <div>
                        <input type="checkbox" class="barcode-checkbox" value="${row.brmm_id}">
                        ${row.brmm_item_code} - ${row.sku_name}
                    </div>
                `;
            });

            $('#barcode_list').html(html);

            $('#barcode_count').text(count);

            // reset select all
            $('#select_all_barcodes').prop('checked', false);

            $('#barcodeModal').modal('show');
        }
    });
}
// Select All toggle
$(document).on('change', '#select_all_barcodes', function () {
    $('.barcode-checkbox').prop('checked', $(this).prop('checked'));
});

// If user manually unchecks one → uncheck select all
$(document).on('change', '.barcode-checkbox', function () {
    if(!$(this).prop('checked')){
        $('#select_all_barcodes').prop('checked', false);
    } else {
        let total = $('.barcode-checkbox').length;
        let checked = $('.barcode-checkbox:checked').length;

        if(total === checked){
            $('#select_all_barcodes').prop('checked', true);
        }
    }
});
$(document).on('keyup', '#barcode_search', function () {
    let val = $(this).val().toLowerCase();

    $('#barcode_list div').filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
    });
});
async function add_selected_barcodes() {

    let selected = [];

    $('.barcode-checkbox:checked').each(function(){
        selected.push($(this).val());
    });

    if(selected.length === 0){
        toastr.error("Select at least one barcode");
        return;
    }

    // ✅ SHOW LOADER
    $('#barcodeLoader').show();

    // small delay to allow UI render
    await new Promise(r => setTimeout(r, 100));

    try {

        let results = await Promise.all(
            selected.map(id => add_barcode_by_id(id))
        );

        let added = results.filter(r => r === 'added').length;
        let duplicate = results.filter(r => r === 'duplicate').length;

        // ✅ HIDE MODAL AFTER PROCESS
        $('#barcodeModal').modal('hide');

        // ✅ SUCCESS MESSAGE (guaranteed)
        setTimeout(() => {
            toastr.success(
                `${added} barcode(s) added successfully` +
                (duplicate ? `, ${duplicate} skipped (duplicate)` : ''),
                "",
                { closeButton: true, progressBar: true }
            );
        }, 200);

    } catch (e) {
        toastr.error("Error while adding barcodes");
        console.error(e);
    }

    // ✅ HIDE LOADER
    $('#barcodeLoader').hide();
}

function add_barcode_by_id(brmm_id) {

    return new Promise((resolve) => {

        let check = false;
        let total_tr= $('#outward_material_wrapper tr').length;

        for (let i = 1; i <= total_tr; i++){
            let id = $('#outward_material_wrapper tr:nth-child('+i+')').attr('id');
            let cnt = id.split("_")[1];
            let old_brmm_id = $('#ot_brmm_id_'+cnt).val();

            if(brmm_id == old_brmm_id){
                check = true;
                break;
            }
        }

        if(check){
            resolve('duplicate');
            return;
        }

        let path = `transfer/outward/get_barcode_data/${brmm_id}`;

        ajaxCall('GET', path, '', 'JSON', resp => {

            let {status, flag, data} = resp;

            if(status && flag == 1 && data.length){

                let d = data[0];

                let tr = `
                    <tr id="rowid_${outward_cnt}" class="floating-form">
                        <td>
                            <input type="hidden" name="ot_id[]" id="ot_id_${outward_cnt}" value="0" />
                            <input type="hidden" name="ot_brmm_id[]" id="ot_brmm_id_${outward_cnt}" value="${d.brmm_id}" />
                            <input type="text" class="form-control" value="${d.brmm_item_code}" readonly />
                        </td>

                        <input type="hidden" name="ot_bill_no[]" id="ot_bill_no_${outward_cnt}" value="${d.prmm_bill_no}" />
                        <input type="hidden" name="ot_prmm_id[]" id="ot_prmm_id_${outward_cnt}" value="${d.prmm_id}" />
                        <input type="hidden" name="ot_bill_date[]" id="ot_bill_date_${outward_cnt}" value="${d.prmm_bill_date}" />

                        <td>
                            <input type="text" class="form-control" value="${d.sku_name}" readonly />
                            <input type="hidden" name="ot_sku_id[]" id="ot_sku_id_${outward_cnt}" value="${d.sku_id}" />
                        </td>

                        <td>
                            <input type="text" class="form-control" value="${d.apparel_name}" readonly />
                            <input type="hidden" name="ot_apparel_id[]" id="ot_apparel_id_${outward_cnt}" value="${d.apparel_id}" />
                        </td>

                        <td>
                            <input type="number" class="form-control" name="ot_qty[]" id="ot_qty_${outward_cnt}" value="${d.brmm_prmt_qty}" readonly />
                        </td>

                        <td>
                            <input type="number" class="form-control" name="ot_rate[]" id="ot_rate_${outward_cnt}" value="${d.brmm_prmt_rate}" readonly />
                        </td>

                        <td>
                            <input type="number" class="form-control" name="ot_sub_total[]" id="ot_sub_total_${outward_cnt}" value="${d.brmm_prmt_rate}" readonly />
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger" onclick="remove_row(${outward_cnt})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#outward_material_wrapper').prepend(tr);

                outward_cnt++;

                calculate_master_total();

                resolve('added');

            } else {
                resolve('error');
            }

        }, () => {
            resolve('error');
        });

    });
}