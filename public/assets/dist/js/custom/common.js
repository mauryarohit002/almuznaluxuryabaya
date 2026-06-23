$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip()
    setInterval(()=>{
        isConnected();
    }, RELOAD_TIME)
    $('.hamburger_button').on('click', function () {
        $('.hamburger_icon').toggleClass('open');
        $('.hamburger_button').toggleClass('pressed');
    });
    $('.dropdown').on('click', function () {
        $('.dropdown').removeClass('show')
        $('.dropdown > a').attr('aria-expanded', false)
        $('.dropdown > div').removeClass('show')

        $(`#${this.id}`).addClass('show')
        $(`#${this.id} > a`).attr('aria-expanded', true)
        $(`#${this.id} > div`).addClass('show')
    });
});
const notifier = (id, msg = false) =>{
    if(msg === false){
        $(`#${id}_msg`).html('')
        return
    }
    $("#"+id).addClass('shake');
    $('#'+id).on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e)
    {
      $('#'+id).delay(200).removeClass('shake');
    });
    $('#'+id+'_msg').html(msg)
}
const redirectPage = path =>{
    disable_enable_background(true)
    $('body').animate({opacity: "0.5"},RELOAD_TIME, 'swing', function(){ window.location.href=base_url+'/'+path; });
    disable_enable_background(false)
}
const refreshPage = url => {
	ajax('GET',url,'','JSON',resp=>{
        const {status, flag, data, msg} = resp;
        if(status){
            if(flag == 1){
                if(data != 0){
                    $('.grn_pending').html(data)
                }   
            }else{
                response_error(flag, msg)
            }
        }else{
            session_expired()
        }
    },errmsg =>{});
	history.pushState('', '', url);
}
const set_search_box = () => {
	if($(`#search_status`).is(" :checked")){
		$('#search_box').collapse('hide')
		// $('#search_box').addClass('show')
	}else{
		$('#search_box').collapse('show')
	}
}
const isActive = id => {
    if($(`#multiCollapse_${id}`).hasClass('show')){
        $(`#up_down_${id}`).removeClass('fa-caret-down').addClass('fa-caret-up')
    }else{
        $(`#up_down_${id}`).removeClass('fa-caret-up').addClass('fa-caret-down')
    }
}