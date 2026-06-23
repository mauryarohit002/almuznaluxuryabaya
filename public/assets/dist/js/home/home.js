$(document).ready(function () {
  home();
});
if (!!window.EventSource) {
  let source = new EventSource(`${base_url}/home/get_event_stream/`);
  source.addEventListener(
    "message",
    (e) => {
      console.log(e.data);
      source.close();
    },
    false
  );
}
const home = () => {
  const path = `home/get_data`;
  let form_data = $("#dashboard_form").serialize();
  ajax(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data } = resp;
        if (data) {
          if (data.first_data) {
            first(data.first_data);
          }
          // if(data.second_data){
          //     second(data.second_data)
          // }
          // if(data.third_data){
          //     third(data.third_data)
          // }
          // if(data.fourth_data){
          //     fourth(data.fourth_data)
          // }
          // if(data.fifth_data){
          //     fifth(data.fifth_data)
          // }
        }
      }
    },
    (errmsg) => {}
  );
};
const getRandomColor = () => {
  var letters = "0123456789ABCDEF";
  var color = "#";
  for (var i = 0; i < 6; i++) {
    color += letters[i];
  }
  return color;
};
