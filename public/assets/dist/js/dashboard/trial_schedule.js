$(document).ready(function () {
    $("#_customer_name").select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_customer_name`,
        placeholder: "select",
      })
    );
    get_sort_by();
  });
  const filters_arr = [
    "_customer_name",
    "_date_from",
    "_date_to",
  ];
  const render = (data) => {}; 
  const order_status_popup = ({ om_id, memo_no, customer_name }) => {
    const url = `transaction/order/handler`;
    const form_data = { func: "get_order_status", id: om_id };
    ajaxCall(
      "POST",
      url,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          const title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12 d-flex flex-wrap justify-content-between">
                              <p class="text-uppercase text-center font-weight-bold">Memo no. : ${memo_no}</p>
                              <p class="text-uppercase text-center font-weight-bold">${customer_name}</p>
                          </div>`;
          const body = `<div class="row"><div class="col-12 col-sm-12 col-md-12 col-lg-12">${data}</div></div>`;
          const footer = `<button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">CANCEL</button>`;
  
          $(".modal-title-sm").html(title);
          $(".modal-body-sm").html(body);
          $(".modal-footer-sm").html(footer);
          $("#popup_modal_sm").modal("show");
        }
      },
      (errmsg) => {}
    );
  };

// trial_reminder
const trial_reminder_date_select_deselect = (count = 0) => {
  const parent_checked = $(`#trial_reminder_date_checkbox`).is(":checked");
  if (count == 0) $(`.trial_reminder_date_checkboxes`).prop("checked", parent_checked);

  const selected_count = $(".trial_reminder_date_checkboxes:checked").length;
  const total_count = parseInt($('#total_rows').html());
  $(`#trial_reminder_date_checkbox`).prop("checked", total_count == selected_count);
  $('#trial_reminder_date_count').html(selected_count);
};
const send_trial_reminder = () => {
  const selected_count = $(".trial_reminder_date_checkboxes:checked").length;
  if(selected_count <= 0){
      toastr.error("Order not select to send reminder", "", {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
        return false
  }

  const url = `${link}/${sub_link}/handler`;
  let form_data = $("#trial_form").serialize();
  form_data += `&func=send_trial_reminder`;
  ajaxCall(
    "POST",
    url,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        toastr.success("", msg, { closeButton: true, progressBar: true });
          $("body, html").animate({ scrollTop: 0 }, 1000);
          setTimeout(function () {
              window.location.reload();
          }, RELOAD_TIME);
      }
    },
    (errmsg) => {}
  );
}
// trial_reminder

// reschedule_reminder
const reschedule_reminder_date_select_deselect = (count = 0) => {
  const parent_checked = $(`#reschedule_reminder_date_checkbox`).is(":checked");
  if (count == 0) $(`.reschedule_reminder_date_checkboxes`).prop("checked", parent_checked);

  const selected_count = $(".reschedule_reminder_date_checkboxes:checked").length;
  const total_count = parseInt($('#total_rows').html());
  $(`#reschedule_reminder_date_checkbox`).prop("checked", total_count == selected_count);
  $('#reschedule_reminder_date_count').html(selected_count);
};
const send_reschedule_reminder = () => {
  const selected_count = $(".reschedule_reminder_date_checkboxes:checked").length;
  if(selected_count <= 0){
      toastr.error("Memo not select to send reminder", "", {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
        return false
  }

  const url = `${link}/${sub_link}/handler`;
  let form_data = $("#trial_form").serialize();
  form_data += `&func=send_reschedule_reminder`;
  ajaxCall(
    "POST",
    url,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        toastr.success("", msg, { closeButton: true, progressBar: true });
          $("body, html").animate({ scrollTop: 0 }, 1000);
          setTimeout(function () {
              window.location.reload();
          }, RELOAD_TIME);
      }
    },
    (errmsg) => {}
  );
}
// reschedule_reminder

// reschedule_date
const reschedule_trial_date_select_deselect = (count = 0) => {
  const parent_checked = $(`#reschedule_trial_date_checkbox`).is(":checked");
  if (count == 0) $(`.reschedule_trial_date_checkboxes`).prop("checked", parent_checked);

  const selected_count = $(".reschedule_trial_date_checkboxes:checked").length;
  const total_count = parseInt($('#total_rows').html());
  $(`#reschedule_trial_date_checkbox`).prop("checked", total_count == selected_count);
  $('#reschedule_trial_date_count').html(selected_count)
};
const add_reschedule_trial_date = () => {
  $('.trial_dates').removeClass('text-danger')
  const reschedule_trial_date = $('#reschedule_trial_date').val();
  const selected_count = $(".reschedule_trial_date_checkboxes:checked").length;
  if(reschedule_trial_date == ''){
      toastr.error("Reschedule trial date not define", "", {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
        return false
  }else{
      const today_date = $('#today_date').val();
      if(reschedule_trial_date < today_date){
          toastr.error("Reschedule trial date should not be less than today date.", "", {
              closeButton: true,
              progressBar: true,
              preventDuplicates: true,
            });
            return false
      }
  }

  if(selected_count <= 0){
      toastr.error("Memo not select to reschedule", "", {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
        return false
  }
  const url = `${link}/${sub_link}/handler`;
  let form_data = $("#trial_form").serialize();
  form_data += `&reschedule_trial_date=${reschedule_trial_date}`;
  form_data += `&func=add_reschedule_trial_date`;
  ajaxCall(
    "POST",
    url,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if(data && data.length != 0){
          data.forEach((value, index) => {
              toastr.error("Reschedule date should not be before order date.", "", {
                  closeButton: true,
                  progressBar: true,
                  preventDuplicates: true,
                });
              $(`#reschedule_trial_date_${value}`).addClass('text-danger')
          });
        }else{
          toastr.success("", msg, { closeButton: true, progressBar: true });
          $("body, html").animate({ scrollTop: 0 }, 1000);
          setTimeout(function () {
              window.location.reload();
          }, RELOAD_TIME);
        }
      } 
    },
    (errmsg) => {}
  );
};
// reschedule_date