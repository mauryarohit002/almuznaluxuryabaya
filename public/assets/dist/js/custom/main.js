$(document).ready(function(){
    $("#"+link).addClass("active");
	$("#"+sub_link).addClass("active");
    if(link){
        let el = document.querySelector(`#${link}`);
        el && el.scrollIntoView(true);
    }
    // $(".select2").select2(select2default)
    $(".select2").select2({width:'100%'});
    $("#_status").select2(select2_default({
        url:`master/common/get_select2_status`,
        placeholder:'STATUS',
    })).on('change', () => trigger_search());
    $("#role").select2(select2_default({
        url:`master/common/get_select2_role`,
        placeholder:'ROLE',
    })).on('change', () => trigger_search());
    $("#drcr").select2(select2_default({
        url:`master/common/get_select2_drcr`,
        placeholder:'DR/CR',
    })).on('change', () => trigger_search());
    $(".datepicker").datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: 'auto bottom',
        startDate: new Date($('#start_year').val()),
        endDate: new Date($('#end_year').val()),
    });
    $(".future-datepicker").datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true,
            startDate: new Date(),
            minDate: 0
    })
    $(".datepicker-top").datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true,
        orientation: 'auto top',
        startDate: new Date($('#start_year').val()),
        endDate: new Date($('#end_year').val()),
    });
    // get_pending_inward();
    // setInterval(()=>{
    //     get_pending_inward();
    // }, 10000)
});
const swalButtonDanger = Swal.mixin({
                                        customClass: {confirmButton: 'btn btn-primary text-danger mx-2',cancelButton: 'btn btn-primary mx-2'},
                                        buttonsStyling: false
                                    });
const swalButtonSuccess = Swal.mixin({
                                        customClass: { confirmButton: 'btn btn-primary text-success mx-2', cancelButton: 'btn btn-primary mx-2'},
                                        buttonsStyling: false
                                    });

const trigger_search = () => $("#btn_search").trigger('click'); 
const refresh_dropdown = (data, field) => $(`#${field.id}`).append(`<option value=${data.id}>${data.name}</option>`).focus().val(data.id)
const remove_preview_image = id => $(`#${id}`).remove();
const zoom = () => $(".pan").pan();    
const set_mobile_length = id =>{
    let mobile_no = $('#'+id).val()
    let length = 10 - parseInt(mobile_no.length)
    if(length >= 0){
        $('#'+id+'_length').html(`(${length})`)
        length == 0 && notifier(id)
    }
    else{
        $('#'+id).val(mobile_no.substring(0,10))  
        let len = parseInt(10 - $('#'+id).val().length)
        $('#'+id+'_length').html(`(${len})`)
    }
}
const session_expired = () =>{
    $("body, html").animate({'scrollTop':0},1000);
    // toastr.error('Please wait...','Session Expired.', {timeOut:2000})
    Swal.fire({
      html:'<p class="text-danger">Please wait...</p>',
      title: `<h2 class="text-danger">Session Expired.</h2>`,
      icon: 'warning',
      showCancelButton: false,
      timer:2000
    })
    setTimeout(()=>{
        window.location.href = base_url;
    },2000);
}
const handle_response = resp => {
    const {session, status, active = true, msg} = resp;
    if(!session){
        session_expired()
        return false;
    }
    if(!active){
        toastr.error('', msg)
        setTimeout(()=>{
            window.location.href = base_url;
        },3000);
        return false;
    }
    if(status == REFRESH){
        toastr.error('',msg)
        setTimeout(() => {
            setTimeout(function(){window.location.reload(); },RELOAD_TIME); 
        }, RELOAD_TIME)
        return false;   
    }else{
        if(!status){
            toastr.error('',msg, {closeButton:true, progressBar:true})
            return false;   
        }
    }
    
    return true;
}
const remove_data = path =>{
    if(confirm("Are you sure? You want to delete item.")){
        ajaxCall('GET',path,'','JSON',resp =>{
            if(handle_response(resp)){
                const {msg} = resp;
                $("body, html").animate({'scrollTop':0},1000);
                toastr.success('',msg, {closeButton:true, progressBar:true});
                setTimeout(() => {
                    lazy_loading('master_loading');
                }, RELOAD_TIME)
            }
        },errmsg=>{});
    }
}
const remove_datav2 = (html, path, className = '') =>{
    swalButtonDanger.fire({
      html,
      title: '<span class="text-danger">Do you want to delete?</span>',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
      if(result.isConfirmed){
        ajaxCall('GET',path,'','JSON',resp =>{
            if(handle_response(resp)){
                const {msg} = resp;
                swalButtonSuccess.fire({
                    title:`<div class="text-success"><p>${msg}</p></div>`,
                    icon:'success',
                    timer:3000,
                    timerProgressBar:true,
                })
            }     
            className != '' && setTimeout(() => {lazy_loading(className)}, 3000);
        },errmsg=>{
        });
      }
    })
}
const custom_confirm = title =>{
    swalButtonDanger.fire({
      title,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, do it!',
    }).then((result) => {
        console.log(result.isConfirmed)
        return result.isConfirmed
    })
}
const refresh_dropdown_select2 = (data, field) => {
    const newOption = new Option(data.name, data.id, true, true)
    $(`#${field.id}`).append(newOption).trigger('change')
    $(`#${field.id}`).select2('open')
}
const drop_down = object => {
    let entries = Object.entries(object);
    return entries.map(entry => `<option value="${entry[0]}">${entry[1]}</option>`)
}
const preview_image = element =>{
    if (validate_img(element)){
        if (typeof (FileReader) != "undefined"){
            let holder = $("#preview");
            holder.empty();
            let reader = new FileReader();
            reader.onload = function (e){
                $(holder).append(`<img class="img-thumbnail pan" width="150px" onclick="zoom()" title="click to zoom in and zoom out" data-big="${e.target.result}" src="${e.target.result}" />`)    
            }
            holder.show();
            reader.readAsDataURL($(element)[0].files[0]);
        }else{
            alert("This browser does not support FileReader.");
        }
    }
}
const set_default_address = field =>{
    let path        = `master/common/get_default`;
    ajaxCall('GET',path,'','JSON',resp=>{
        if(handle_response(resp)){
            const {data} = resp
            if(data){
                if(data.city && data.city.length != 0){
                    $(`#${field}_city_id`).html(`<option value="${data.city[0]['city_id']}">${data.city[0]['city_name']}</option>`);
                }
                if(data.state && data.state.length != 0){
                    $(`#${field}_state_id`).html(`<option value="${data.state[0]['state_id']}">${data.state[0]['state_name']}</option>`);
                }
                if(data.country && data.country.length != 0){
                    $(`#${field}_country_id`).html(`<option value="${data.country[0]['country_id']}">${data.country[0]['country_name']}</option>`);
                }
            }
            $('.master_block_btn, #sbt_btn').prop('disabled', true); 
        }
    },errmsg =>{});
}
const parseValue = value => {
    if(isNaN(value) || value == '') value = 0; 
    return parseFloat( ('0' + value).replace(/[^0-9-\.]/g, ''), 10 );
}
const get_pending_inward = () => {
    let path = "transfer/inward/get_pending_count/";   
    ajax('GET',path,'','JSON',resp=>{
        if(handle_response(resp)){
            const {data, msg} = resp;
            if(data != 0){
                $('.inward_pending').html(data)
            }
        }
    },errmsg =>{}, false);
}
const handle_master_response = (id, field, term, resp, focus='name') => {
    const {status, flag, data, msg} = resp;
    if(status){
        if(flag == 1){
            if(id == 0){
                if(field != undefined){
                    $("#popup_modal_sm").modal('hide');  
                    refresh_dropdown(data, field);
                }else{
                    $(`#${term}_form`)[0].reset();
                    $(`#${term}_${focus}`).focus();
                }
            }else{
                $("#popup_modal_sm").modal('hide');  
            }
            callToastify('success', msg, 'right')
        }else{
            response_error(flag, msg)
        }
    }else{
        session_expired()
    }
}
const handle_master_response_select2 = (id, field, term, resp, focus='name') => {
    const {status, flag, data, msg} = resp;
    if(status){
        if(flag == 1){
            if(id == 0){
                if(field != undefined){
                    $("#popup_modal_sm").modal('hide');  
                    refresh_dropdown_select2(data, field);
                }else{
                    $(`#${term}_form`)[0].reset();
                    $(`#${term}_${focus}`).focus();
                }
            }else{
                $("#popup_modal_sm").modal('hide');  
            }
            callToastify('success', msg, 'right')
        }else{
            response_error(flag, msg)
        }
    }else{
        session_expired()
    }
}
const sync = term =>{
    const path  = `sync/${term}`
    ajaxCall('GET',path,'','JSON',resp=>{
        if(handle_response(resp)){
            const {data ,msg} = resp;
            if(data){
                let html = `<table class="table table-sm table-hover text-uppercase">
                                <tbody>
                                    ${data.num_rows > 0 ? `<tr>
                                        <td class="font-weight-bold" width="70%">no. of record fetched : </td>
                                        <td class="text-left" width="30%">${data.num_rows}</td>
                                    </tr>` : ''}
                                    ${data.add > 0 ? `<tr>
                                        <td class="font-weight-bold" width="70%">record added : </td>
                                        <td class="text-left" width="30%">${data.add}</td>
                                    </tr>`  : ''}
                                    ${data.edit > 0 ? `<tr>
                                        <td class="font-weight-bold" width="70%">record edited : </td>
                                        <td class="text-left" width="30%">${data.edit}</td>
                                    </tr>`  : ''}
                                    ${data.add_fail > 0 ? `<tr>
                                        <td class="font-weight-bold" width="70%">record failed to add : </td>
                                        <td class="text-left" width="30%">${data.add_fail}</td>
                                    </tr>`  : ''}
                                    ${data.edit_fail > 0 ? `<tr>
                                        <td class="font-weight-bold" width="70%">record failed to edit : </td>
                                        <td class="text-left" width="30%">${data.edit_fail}</td>
                                    </tr>`  : ''}
                                </tbody>
                            </table>`;
                
                swalButtonDanger.fire({
                  html,
                  title: `<span class="${data.num_rows == 0 ? 'text-danger' : 'text-success'}">${msg}</span>`,
                  icon: 'info',
                  showCancelButton: false,
                })
            }
        }
    },errmsg =>{});
}
const get_url_string = key =>{
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, props) => searchParams.get(props),
    });
    return params[`${key}`]
}