$(document).ready(function(){
  loader();
});

// loader
const loader = () => {
    setTimeout(() => {
    if ($('#ftco-loader').length > 0) {
      // $('#ftco-loader').removeClass('show');
      disable_enable_background(false)
    }
  }, RELOAD_TIME);
};