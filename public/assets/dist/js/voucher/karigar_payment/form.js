$(document).ready(function () {
  $("#hm_id")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_hm_id`,
        placeholder: "SELECT",
        param: true,
      })
    ) 
    .on("change", (event) => get_karigar_from_hisab(event.target.value));
  $("#karigar_payment_karigar_id")
    .select2(
      select2_default({
        url: `master/karigar/get_select2/_id`,
        placeholder: "SELECT",
        param: true,
      })
    )
    .on("change", () => get_karigar_data());
});

// core_functions
const get_transaction = () => {
  if (["edit", "read"].includes(get_url_string("action"))) {
    let id = get_url_string("id");
    if (id) {
      $("#btn_adjustment").addClass("d-none");
      const path = `${link}/${sub_link}/handler`;
      const form_data = { func: "get_transaction", id };
      ajaxCall(
        "POST",
        path,
        form_data,
        "JSON",
        (resp) => {
          if (handle_response(resp)) {
            const { data, msg } = resp;
            const { hisab_data } = data;
            if (hisab_data && hisab_data.length != 0) {
              hisab_data.forEach((row) => add_hisab_wrapper(row));
            } else {
              $("#btn_adjustment").removeClass("d-none");
            }
            set_checkboxes();
          }
        },
        (errmsg) => {}
      );
    }
  }
};
const set_checkboxes = () => {
  let purchase_row = $("#purchase_wrapper tr").length;
  let purchase_checked = $(".purchase_checkboxes:checked").length;
  $("#purchase_count").html(purchase_row);
  $("#purchase_select_count").html(purchase_checked);
  $(`#purchase_checkbox`).prop(
    "checked",
    purchase_row > 0 ? purchase_row == purchase_checked : false
  );
};
const set_default = () => {
  $("#karigar_payment_hisab_amt").val(0);
  $("#karigar_payment_balance_amt_show").val("");
  $("#karigar_payment_balance_amt").val(0);
  $("#karigar_payment_balance_type").val("");

  $("#purchase_wrapper").html("");
  $("#purchase_count").html(0);
  $("#purchase_select_count").html(0);
  $(`#purchase_checkbox`).prop("checked", false);
};
const get_karigar_from_hisab = (id) => {
  if (!id) return false;
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_karigar_from_hisab", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          $("#karigar_payment_karigar_id").html(
            `<option value="${data[0]["karigar_id"]}">${data[0]["karigar_name"]}</option>`
          );
          $(`#karigar_payment_karigar_id`)
            .val(data[0]["karigar_id"])
            .trigger("change");
          $(`#karigar_payment_karigar_id`).select2("close");
        }
      }
    },
    (errmsg) => {}
  );
};
const get_karigar_data = () => {
  set_default();
  const id = $("#karigar_payment_karigar_id").val(); 
  if (!id) return false;
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_karigar_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        const { hisab_data, balance_data } = data;
        $("#karigar_payment_opening_amt").val(balance_data["opening_amt"]);
        $("#karigar_payment_hisab_amt").val(balance_data["hisab_amt"]);
        $("#karigar_payment_balance_amt").val(balance_data["balance_amt"]);
        $("#karigar_payment_balance_type").val(balance_data["type"]);
        $("#karigar_payment_balance_amt_show").val(
          `${balance_data["balance_amt"]} ${balance_data["type"]}`
        );

        if (hisab_data && hisab_data.length != 0) {
          hisab_data.forEach((row) => add_hisab_wrapper(row));
        }
        set_checkboxes();
        calculate_master();
      }
    },
    (errmsg) => {}
  );
};
const get_data_for_adjustment = () => {
  const id = $("#karigar_payment_karigar_id").val();
  if (!id) return false;
  const path = `${link}/${sub_link}/handler`;
  let form_data = { func: "get_data_for_adjustment", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        const { hisab_data } = data;

        if (hisab_data && hisab_data.length != 0) {
          hisab_data.forEach((row) => add_hisab_wrapper(row));
        }
        set_checkboxes();
      }
    },
    (errmsg) => {}
  );
};
const calculate_master = () => {
  let payment_amt = $("#karigar_payment_amt").val();
  if (isNaN(payment_amt) || payment_amt == "") payment_amt = 0;

  let balance_amt = $("#karigar_payment_balance_amt").val();
  if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;

  let balance_type = $(`#karigar_payment_balance_type`).val();

  let closing_amt = parseFloat(balance_amt) - parseFloat(payment_amt);
  if (isNaN(closing_amt) || closing_amt == "") closing_amt = 0;

  if (closing_amt < 0) {
    closing_amt = Math.abs(closing_amt);
    if (balance_type == TO_PAY) {
      balance_type = TO_RECEIVE;
    } else {
      balance_type = TO_PAY;
    }
  }
  $("#karigar_payment_balance_amt_show").val(`${closing_amt} ${balance_type}`);

  let purchase_amt = 0;
  let purchase_row = $("#purchase_wrapper tr").length;
  for (let i = 1; i <= purchase_row; i++) {
    let attr = $(`#purchase_wrapper tr:nth-child(${i})`).attr("id");
    let explode = attr.split("_");
    let cnt = explode[1];

    let balance_amt = $(`#pht_balance_amt_${cnt}`).val();
    if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;
    if ($(`#pht_checked_${cnt}`).is(":checked")) {
      let current_amt = parseFloat(payment_amt) - parseFloat(purchase_amt);
      if (isNaN(current_amt) || current_amt == "") current_amt = 0;

      let allocated_amt =
        current_amt > balance_amt
          ? parseFloat(balance_amt)
          : parseFloat(current_amt);
      if (isNaN(allocated_amt) || allocated_amt == "") allocated_amt = 0;
      $(`#pht_adjust_amt_${cnt}`).val(allocated_amt.toFixed(2));

      balance_amt = parseFloat(balance_amt) - parseFloat(allocated_amt);
      if (isNaN(allocated_amt) || allocated_amt == "") allocated_amt = 0;
      $(`#pht_balance_amt_show_${cnt}`).val(balance_amt.toFixed(2));

      purchase_amt = parseFloat(purchase_amt) + parseFloat(allocated_amt);
      if (isNaN(purchase_amt) || purchase_amt == "") purchase_amt = 0;
    } else {
      $(`#pht_adjust_amt_${cnt}`).val(0);
      $(`#pht_balance_amt_show_${cnt}`).val(balance_amt);
    }
  }
  set_checkboxes();
};
const remove_master_notifier = () => {
  notifier("karigar_payment_karigar_id");
  notifier("payment_amt");
};
const add_edit = (id) => {
  remove_master_notifier();
  let check = true;
  if ($("#karigar_payment_karigar_id").val() == null) {
    notifier("karigar_payment_karigar_id", "Required");
    check = false;
  }
  if ($("#karigar_payment_amt").val() <= 0) {
    notifier("karigar_payment_amt", "Required");
    check = false;
  } else {
    if ($("#karigar_payment_amt").val() < 0) {
      notifier("karigar_payment_amt", "Invalid payment amt");
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

// purchase module
const add_hisab_wrapper = (data) => {
  const {
    pht_id,
    pht_checked,
    pht_hm_id,
    pht_entry_no,
    pht_entry_date,
    pht_total_amt,
    pht_adjust_amt,
    balance_amt,
  } = data;
  let tr = `<tr id="rowpurchase_${pht_hm_id}">
                  <td width="2%" >
                      <input 
                          type="hidden" 
                          name="pht_id[${pht_hm_id}]" 
                          id="pht_id_${pht_hm_id}" 
                          value="${pht_id}" 
                      />
                      <label class="custom-control material-checkbox">
                          <input 
                              type="checkbox" 
                              class="material-control-input purchase_checkboxes" 
                              id="pht_checked_${pht_hm_id}" 
                              name="pht_checked[${pht_hm_id}]" 
                              value="${pht_hm_id}"
                              onclick="purchase_select_deselect(${pht_hm_id})" 
                              ${pht_checked == 1 ? "checked" : ""}
                          />
                          <span class="material-control-indicator"></span>
                      </label>
                  </td>
                  <td width="5%">
                      <input 
                          type="hidden" 
                          name="pht_hm_id[${pht_hm_id}]" 
                          id="pht_hm_id_${pht_hm_id}" 
                          value="${pht_hm_id}" 
                      />
                      <input 
                          type="text" 
                          class="border-0 text-center" 
                          name="pht_entry_no[${pht_hm_id}]" 
                          id="pht_entry_no_${pht_hm_id}" 
                          value="${pht_entry_no}" 
                          readonly 
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="text" 
                          class="border-0" 
                          name="pht_entry_date[${pht_hm_id}]" 
                          id="pht_entry_date_${pht_hm_id}" 
                          value="${pht_entry_date}" 
                          readonly 
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          name="pht_total_amt[${pht_hm_id}]" 
                          id="pht_total_amt_${pht_hm_id}" 
                          value="${pht_total_amt}" 
                          readonly 
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          name="pht_adjust_amt[${pht_hm_id}]" 
                          id="pht_adjust_amt_${pht_hm_id}" 
                          value="${pht_adjust_amt}" 
                          readonly
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          id="pht_balance_amt_show_${pht_hm_id}" 
                          value="${balance_amt}" 
                          readonly 
                      />
                      <input 
                          type="hidden" 
                          id="pht_balance_amt_${pht_hm_id}" 
                          value="${pht_id == 0 ? balance_amt : pht_total_amt}" 
                          readonly 
                      />
                  </td>
              </tr>`;
  $("#purchase_wrapper").prepend(tr);
};
const purchase_select_deselect = (count = 0) => {
  let parent_checked = $(`#purchase_checkbox`).is(":checked");
  if (count == 0) $(`.purchase_checkboxes`).prop("checked", parent_checked);
  calculate_master();
};
// purchase module

// payment mode
const get_payment_mode_data = () => {
  let title = `<p>payment mode</p>`;
  let subtitle = `<div class="d-flex justify-content-around">
                      <p class="d-flex flex-column justify-content-around">
                          <span class="pb-1 border-bottom">payment amt</span>
                          <span class="_payment_amt">0</span>
                      </p>
                      </p>
                      <p class="d-flex flex-column justify-content-around">
                          <span class="pb-1 border-bottom">balance amt</span>
                          <span class="_balance_amt">0</span>
                      </p>
                  </div>`;
  let body = ``;
  let footer = `<button 
                    type="button" 
                    id="sbt_btn" 
                    class="btn btn-md btn-secondary btn-block text-uppercase mx-3" 
                    onclick="toggle_karigar_payment_mode_popup()"
                  >close</button>`;
  $(`#karigar_payment_mode_wrapper .right-panel-title`).html(title);
  $(`#karigar_payment_mode_wrapper .right-panel-subtitle`).html(subtitle);
  $(`#karigar_payment_mode_wrapper .right-panel-body`).html(body);
  $(`#karigar_payment_mode_wrapper .right-panel-footer`).html(footer);
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
          $(`#karigar_payment_mode_wrapper .right-panel-body `).html(body);
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
    const { kpmt_id, kpmt_amt, kpmt_payment_mode_id, payment_mode_name } = row;
    advance_amt = parseFloat(advance_amt) + parseFloat(kpmt_amt);
    if (isNaN(advance_amt) || advance_amt == "") advance_amt = 0;

    pay_modes += `<tr id="rowpm_${kpmt_payment_mode_id}">
                          <td width="10%" class="border-0 font-weight-bold"></td>
                          <td width="30%" class="border-0 font-weight-bold">${payment_mode_name} : </td>
                          <td width="50%" class="border-0 floating-label">
                              <input 
                                  type="hidden"
                                  id="kpmt_id_${kpmt_payment_mode_id}" 
                                  name="kpmt_id[${kpmt_payment_mode_id}]" 
                                  value="${kpmt_id}" 
                              />
                              <input 
                                  type="hidden"
                                  id="kpmt_payment_mode_id_${kpmt_payment_mode_id}" 
                                  name="kpmt_payment_mode_id[${kpmt_payment_mode_id}]" 
                                  value="${kpmt_payment_mode_id}" 
                              />
                              <input 
                                  type="number" 
                                  class="form-control floating-input" 
                                  id="kpmt_amt_${kpmt_payment_mode_id}" 
                                  name="kpmt_amt[${kpmt_payment_mode_id}]" 
                                  value="${kpmt_amt}"
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
const toggle_karigar_payment_mode_popup = () => {
  if ($(`#karigar_payment_mode_wrapper .right-panel`).hasClass("active")) {
    $(`#karigar_payment_mode_wrapper .right-panel `).removeClass("active");
  } else {
    $(`#karigar_payment_mode_wrapper .right-panel `).addClass("active");

    let payment_amt = $(`#karigar_payment_amt`).val();
    if (isNaN(payment_amt) || payment_amt == "") payment_amt = 0;
    $(`._payment_amt`).html(payment_amt);

    let debit_note_amt = $("#payment_debit_note_amt").val();
    if (isNaN(debit_note_amt) || debit_note_amt == "") debit_note_amt = 0;
    $(`._debit_note_amt`).html(debit_note_amt);

    let balance_amt = $("#karigar_payment_balance_amt").val();
    if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;

    let closing_amt =
      parseFloat(balance_amt) +
      parseFloat(debit_note_amt) -
      parseFloat(payment_amt);
    if (isNaN(closing_amt) || closing_amt == "") closing_amt = 0;

    $("._balance_amt").html(closing_amt);
  }
};
const calculate_payment_amt = () => {
  let payment_amt = 0;
  for (let i = 1; i <= $("#payment_mode_tbody > tr").length; i++) {
    let cnt = $(`#payment_mode_tbody > tr:nth-child(${i})`).attr("id");
    let explode = cnt.split("_");
    let id = explode[1];

    let amt = $(`#kpmt_amt_${id}`).val();
    if (isNaN(amt) || amt == "") amt = 0;

    payment_amt = parseFloat(payment_amt) + parseFloat(amt);
    if (isNaN(payment_amt) || payment_amt == "") payment_amt = 0;
  }
  $("._payment_amt").html(payment_amt);
  $(`#karigar_payment_amt`).val(payment_amt);

  let debit_note_amt = $("#payment_debit_note_amt").val();
  if (isNaN(debit_note_amt) || debit_note_amt == "") debit_note_amt = 0;

  let balance_amt = $("#karigar_payment_balance_amt").val();
  if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;

  let closing_amt =
    parseFloat(balance_amt) +
    parseFloat(debit_note_amt) -
    parseFloat(payment_amt);
  if (isNaN(closing_amt) || closing_amt == "") closing_amt = 0;

  $("._balance_amt").html(closing_amt);
  if (closing_amt >= 0) {
    // $("#sbt_btn").prop("disabled", false);
    $("._balance_amt").removeClass("text-danger");
  } else {
    // $("#sbt_btn").prop("disabled", true);
    $("._balance_amt").addClass("text-danger");
  }
  calculate_master();
};
// payment mode
