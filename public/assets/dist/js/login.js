$(document).ready(function(){
  if ($('#user_branch_id').length > 0) {
      if($('#user_branch_id').val() == 0){
        $('#user_branch_id').focus();
      }else{
        $('#user_name').focus();
      }
  }
});
const remove_login_notifier = () => {
  notifier('user_branch_id')
  notifier('user_name')
  notifier('user_password')

}
const login_action = () =>{
  event.preventDefault()
  remove_login_notifier()
  let check = true;
  if($("#user_branch_id").val() == 0){
      notifier('user_branch_id', 'Required')
      check = false
  }
  if($("#user_name").val() == ''){
      notifier('user_name', 'Required')
      check = false
  }

  if($("#user_password").val() == ''){
      notifier('user_password', 'Required')
      check = false
  }
  if(check){
    let form = $("#login_form").serialize();
    let path = `login/login_action`;
    ajaxCall('POST',path,form,'JSON',resp=>{
      const {status, msg} = resp
      if(status == REFRESH){
          toastr.error('',msg);
          setTimeout(() => {
              window.location.reload();
          }, RELOAD_TIME)
      }else{
          if(status){
            toastr.success('', msg);
            setTimeout(() => {
                redirectPage('home');
            }, RELOAD_TIME)
          }else{
            notifier('user_branch_id', msg)
            notifier('user_name', msg)
            notifier('user_password', msg)
          }
      }
    },err=>{})
  }
}