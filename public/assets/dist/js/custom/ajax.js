const disable_enable_background = flag =>{
    if(flag) //disable background
    {
        $('#ftco-loader').addClass('show');
        $('body').removeClass('unblur').addClass('blur');
        $('.stage').removeClass('d-none');
        $('.dot-text').addClass('d-none');
    }
    else //enable background
    {
        $('#ftco-loader').removeClass('show');
        $('body').removeClass('blur').addClass('unblur');
        $('.stage').addClass('d-none');
        $('.dot-text').removeClass('d-none');
    }    
    $('.master_block_btn, #sbt_btn').prop('disabled', flag); 
}
const window_reload = () =>{
    $("#table_reload").load(window.location + " #table_tbody");
    $("#count_reload").load(window.location + " #total_rows");
}
const isConnected = () => {
    let len = $('.offline').length;
    if(window.navigator.onLine){
        $('.offline').remove();
        if(len != 0){
            toastr.remove();
            toastr.success('<small>We are back online.</small>', '<span class="online">Internet is online</span>', {closeButton:true, progressBar:true});
        }   
    }else{
        if(len == 0){
            toastr.error('<small>Please check internet connection...</small>', '<span class="offline">Internet is offline</span>', {timeOut: 0, extendedTimeOut: 0, tapToDismiss:false});
            $('toast-error').addClass('offline');
        }
    }
    return window.navigator.onLine;
}
const ajaxCall = (callType,path,form_data,datatype,res_callback,err_callback, async=true) =>{
    if(isConnected()){
        disable_enable_background(true)
        $.ajax({
            type: ''+callType+'',
            url:`${base_url}/${path}`,
            data:form_data,
            dataType:''+datatype+'',
            async:async,
            success: response =>{
                console.log(response)
                window_reload()
                disable_enable_background(false)
                res_callback(response);
            },
            error: error =>{
                console.log(error)
                toastr.error('', 'Something went wrong!!!', {closeButton:true, progressBar:true, preventDuplicates:true});
                disable_enable_background(false)
                err_callback(error);
            }   
        });
    }
}
const ajax = (callType,path,form_data,datatype,res_callback,err_callback, async = true) =>{
    if(isConnected()){
        $.ajax({
            type: ''+callType+'',
            url:`${base_url}/${path}`,
            data:form_data,
            dataType:''+datatype+'',
            async:async,
            success: response =>{
                console.log(response)
                disable_enable_background(false)
                res_callback(response);
                // window_reload()
            },
            error: error =>{
                console.log(error)
                toastr.error('', 'Something went wrong!!!', {closeButton:true, progressBar:true, preventDuplicates:true});
                disable_enable_background(false)
                err_callback(error);
            }   
        });
    }
}
const fileUpAjaxCall = (callType,path,form_data,datatype,res_callback,err_callback, async=true) =>{
    if(isConnected()){
        disable_enable_background(true)
        $.ajax({
            type: ''+callType+'',
            url:`${base_url}/${path}`,
            data:form_data,
            dataType:''+datatype+'',
            contentType:false,
            processData:false,
            async:async,
            success:response=>{
                console.log(response)
                disable_enable_background(false)
                window_reload()
                res_callback(response);
            },
            error: error =>{
                console.log(error)
                toastr.error('', 'Something went wrong!!!', {closeButton:true, progressBar:true, preventDuplicates:true});
                disable_enable_background(false)
                err_callback(error);
            }   
        });
    }
}
const fileUpAjaxCallDesign = (callType,path,form_data,datatype,res_callback,err_callback, async=true) =>{
    if(isConnected()){
        disable_enable_background(true)
        $.ajax({
            type: ''+callType+'',
            url:`${base_url}/${path}`,
            data:form_data,
            dataType:''+datatype+'',
            contentType:false,
            processData:false,
            async:async,
            success:response=>{
                console.log(response)
                disable_enable_background(false)
                window_reload();
                res_callback(response);
            },
            error: error =>{
                console.log(error)
                toastr.error('', 'Something went wrong!!!', {closeButton:true, progressBar:true, preventDuplicates:true});
                disable_enable_background(false)
                err_callback(error);
            }   
        });
    }
}