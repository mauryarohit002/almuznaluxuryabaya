$(document).ready(function () {
  $("#payment_general_general_id").select2(
    select2_default({
      url: `master/general/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
});

// core_functions
const remove_master_notifier = () => {
  notifier("payment_general_general_id");
  notifier("payment_general_amt");
};
const add_edit = (id) => {
  remove_master_notifier();
  let check = true;
  if ($("#payment_general_general_id").val() == null) {
    notifier("payment_general_general_id", "Required");
    check = false;
  }
  if ($("#payment_general_amt").val() <= 0) {
    notifier("payment_general_amt", "Required");
    check = false;
  } else {
    if ($("#payment_general_amt").val() < 0) {
      notifier("payment_general_amt", "Invalid payment amt");
      check = false;
    }
  }

  if (!check) {
    toastr.error("You forgot to enter some information", "Oh snap !!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    const path = `${link}/${sub_link}/handler`;
    let form_data = $("#_form").serialize();
    form_data += `&func=add_edit`;
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          let { msg } = resp;
          if (id == 0) {
          } else {
          }
          remove_master_notifier();
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
};

// core_functions

// payment mode
const get_payment_mode_data = () => {
  let title = `<p>payment mode</p>`;
  let subtitle = `<div class="d-flex justify-content-around">
                      <p class="d-flex flex-column justify-content-around">
                          <span class="pb-1 border-bottom">payment amt</span>
                          <span class="_payment_general_amt">0</span>
                      </p>
                      </p>
                  </div>`;
  let body = ``;
  let footer = `<button 
                    type="button" 
                    id="sbt_btn" 
                    class="btn btn-md btn-secondary btn-block text-uppercase mx-3" 
                    onclick="toggle_payment_mode_popup()"
                  >close</button>`;
  $(`#payment_mode_wrapper .right-panel-title`).html(title);
  $(`#payment_mode_wrapper .right-panel-subtitle`).html(subtitle);
  $(`#payment_mode_wrapper .right-panel-body`).html(body);
  $(`#payment_mode_wrapper .right-panel-footer`).html(footer);
  const id = $("#id").val();
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_payment_mode_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          const { pay_modes } = get_pay_modes(data);
          let body = `<div class="row pt-2">
                          <div class="col-12">
                              <div style="max-height: 50vh; overflow-x: auto;">
                                  <table class="table table-sm w-100">
                                      <tbody id="payment_mode_tbody">
                                          ${pay_modes}
                                      </tbody>
                                  </table>
                              </div>              
                          </div>              
                      </div> `;
          $(`#payment_mode_wrapper .right-panel-body `).html(body);
        }
      }
    },
    (errmsg) => {
      console.log(errmsg);
    }
  );
};
const get_pay_modes = (data) => {
  let pay_modes = ``;
  let advance_amt = 0;
  data.forEach((row) => {
    const { pgpmt_id, pgpmt_amt, pgpmt_payment_mode_id, payment_mode_name } =
      row;
    pay_modes += `<tr id="rowpm_${pgpmt_payment_mode_id}">
                          <td width="10%" class="border-0 font-weight-bold"></td>
                          <td width="30%" class="border-0 font-weight-bold">${payment_mode_name} : </td>
                          <td width="50%" class="border-0 floating-label">
                              <input 
                                  type="hidden"
                                  id="pgpmt_id_${pgpmt_payment_mode_id}" 
                                  name="pgpmt_id[${pgpmt_payment_mode_id}]" 
                                  value="${pgpmt_id}" 
                              />
                              <input 
                                  type="hidden"
                                  id="pgpmt_payment_mode_id_${pgpmt_payment_mode_id}" 
                                  name="pgpmt_payment_mode_id[${pgpmt_payment_mode_id}]" 
                                  value="${pgpmt_payment_mode_id}" 
                              />
                              <input 
                                  type="number" 
                                  class="form-control floating-input" 
                                  id="pgpmt_amt_${pgpmt_payment_mode_id}" 
                                  name="pgpmt_amt[${pgpmt_payment_mode_id}]" 
                                  value="${pgpmt_amt}"
                                  onkeyup="calculate_payment_amt()"
                                  placeholder=" " 
                                  autocomplete="off" 
                              />
                          </td>
                          <td width="10%" class="border-0 font-weight-bold"></td>
                      </tr>`;
  });
  return { advance_amt, pay_modes };
};
const toggle_payment_mode_popup = () => {
  if ($(`#payment_mode_wrapper .right-panel`).hasClass("active")) {
    $(`#payment_mode_wrapper .right-panel `).removeClass("active");
  } else {
    $(`#payment_mode_wrapper .right-panel `).addClass("active");

    let payment_amt = $(`#payment_general_amt`).val();
    if (isNaN(payment_amt) || payment_amt == "") payment_amt = 0;
    $(`._payment_general_amt`).html(payment_amt);
  }
};
const calculate_payment_amt = () => {
  let payment_amt = 0;
  for (let i = 1; i <= $("#payment_mode_tbody > tr").length; i++) {
    let cnt = $(`#payment_mode_tbody > tr:nth-child(${i})`).attr("id");
    let explode = cnt.split("_");
    let id = explode[1];

    let amt = $(`#pgpmt_amt_${id}`).val();
    if (isNaN(amt) || amt == "") amt = 0;

    payment_amt = parseFloat(payment_amt) + parseFloat(amt);
    if (isNaN(payment_amt) || payment_amt == "") payment_amt = 0;
  }
  $("._payment_general_amt").html(payment_amt);
  $(`#payment_general_amt`).val(payment_amt);
};
// payment mode
