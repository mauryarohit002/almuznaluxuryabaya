const set_mobile_no = (element, validate = false) =>{
    const {id} = element
    let mobile_no = $('#'+id).val()
    let length = 10 - parseInt(mobile_no.length)
    if(length >= 0){
        $('#'+id+'_length').html(`(${length})`)
        length == 0 && notifier(id)
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', (length > 0));
    }
    else{
        $('#'+id).val(mobile_no.substring(0,10))  
        let len = parseInt(10 - $('#'+id).val().length)
        $('#'+id+'_length').html(`(${len})`)
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', (len > 0));
    }
}
const validate_mobile_no = (element, validate = false) =>{
    const {value, id} = element
    if(value.length > 0){
        if(value.length !== 10){
            validate && $('.master_block_btn, #sbt_btn').prop('disabled', true);
            notifier(id, 'Invalid Mobile No')
        }else{
            validate && $('.master_block_btn, #sbt_btn').prop('disabled', false);
            notifier(id)
        }
    }else{
        notifier(id)
    }
}
const validate_email = (element, validate = false) =>{
    const {value, id} = element
    if(value.length > 0){
        if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(value)){
            notifier(`${id}`)
            validate && $('.master_block_btn, #sbt_btn').prop('disabled', false);
            return true
        }
        notifier(`${id}`, 'Invalid Email')
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', true);
    }else{
        notifier(`${id}`)
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', false);
    }
}
const validate_length = (element, msg, check, len = 6) =>{
    if(check == 0){
        const {value, id} = element
        if(value.length != 0){
            if(value.length <= len){
                notifier(id, msg)
            }else{
                notifier(id)
            }
        }else{
            notifier(id, 'Required')
        }    
    }
}
const validate_password = (element, temp, check) =>{
    if(check == 0){
        const {value, id}  = element
        const password = $(`#${temp}`).val()
        if(value.length != 0){
            if(value != password){
                notifier(id, 'Password mismatch.')
            }else{
                notifier(id)
            }
        }else{
            notifier(id, 'Required')
        }
    }
}
const validate_dropdown = (element, validate = false) => {
    const {id, value} = element
    if(value == 0){
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', true);
        notifier(id, 'Required')
    }else{
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', false);
        notifier(id)
    }
}
const validate_textfield = (element, validate = false) => {
    const {id} = element;
    let name = $(`#${id}`).val();
    if(name.length > 0){
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', false);
        notifier(id)
    }else{
        if(validate){
            $('.master_block_btn, #sbt_btn').prop('disabled', true);
            notifier(id, 'Required')
        }
    }
}
const validate_number = (element, validate = false) => {
    const {value}  = element;
    if(isNaN(value) || value == '') value = 0;
    if(value > 0){
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', false);
        notifier(id)
    }else{
        validate && $('.master_block_btn, #sbt_btn').prop('disabled', true);
        notifier(id, 'Required')
    }
}
const validate_multiple_img = element =>{
    let id              = `#${element.id}`;
    let len             = $(id).get(0).files.length;
    let validImageTypes = ["image/gif", "image/jpeg", "image/png"];
    let check           = true;
    
    // if(len > 5)
    if(0){
        toastr.error('Maximum images selection is five only.', 'NUMBER OF IMAGE EXCEED.', {closeButton:true, progressBar:true, preventDuplicates: true});
        check = false;
    }else{
        for(i = 0; i < len; i++){
            let fileType = $(id).get(0).files[i]['type'];
           if($.inArray(fileType, validImageTypes) < 0){
                check = false;                    
           }
        }
        if(!check){
            toastr.error('Please Select Images only. Select Again', 'INVALID IMAGE FORMAT.', {closeButton:true, progressBar:true, preventDuplicates: true});
        }
    }
    if(!check){
        $(id).val('');                
    }
    return check
}
const validate_multiple_document = (element, validTypes) =>{
    let id              = `#${element.id}`;
    let len             = $(id).get(0).files.length;
    let check           = true;
    // if(len > 5)
    if(0){
        toastr.error('Maximum document selection is five only.', 'NUMBER OF DOCUMENT EXCEED.', {closeButton:true, progressBar:true, preventDuplicates: true});
        check = false;
    }else{
        for(i = 0; i < len; i++){
            let fileType = $(id).get(0).files[i]['type'];
           if($.inArray(fileType, validTypes) < 0){
                check = false;                    
           }
        }
        if(!check){
            toastr.error('Please Select mention format only. Select Again', 'INVALID DOCUMENT FORMAT.', {closeButton:true, progressBar:true, preventDuplicates: true});
        }
    }
    if(!check){
        $(id).val('');                
    }
    return check
}
const validate_img = element =>{
    let id          = `#${element.id}`;
    let imgPath     = $(id)[0].value;
    let extn        = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    let check       = true;
    if(extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg"){
        check = true;
    }else{
        toastr.error('Choose only image format file.', 'Invalid Image', {closeButton:true, progressBar:true});
        $(id).val('');
        check = false;
    }
    return check;
}
const validate_excel = element =>{
    let id          = `#${element.id}`;
    let path        = $(id)[0].value;
    let extn        = path.substring(path.lastIndexOf('.') + 1).toLowerCase();
    let check       = true;
    if(extn == "xlsx" || extn == "xls"){
        check = true;
    }else{
        toastr.error('Choose only excel format file.', 'Invalid Excel', {closeButton:true, progressBar:true});
        $(id).val('');
        check = false;
    }
    return check;
}
const validate_email_value = (email) =>{
    if(email.length > 0){
        if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)){
            return true
        }
    }
    return false;
}
const validate_mobile_length = id =>{
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