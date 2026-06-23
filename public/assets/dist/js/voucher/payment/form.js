$(document).ready(function () {
  $("#pm_id")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_pm_id`,
        placeholder: "SELECT",
        param: true,
      })
    )
    .on("change", (event) => get_supplier_from_purchase(event.target.value));
  $("#payment_supplier_id")
    .select2(
      select2_default({
        url: `master/supplier/get_select2/_id`,
        placeholder: "SELECT",
        param: true,
      })
    )
    .on("change", () => get_supplier_data());
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
            const { purchase_data,purchase_readymade_data } = data;
            if (purchase_data && purchase_data.length != 0) {
              purchase_data.forEach((row) => add_purchase_wrapper(row));
            } else {
              $("#btn_adjustment").removeClass("d-none");
            }
            
            if (purchase_readymade_data && purchase_readymade_data.length != 0) {
              purchase_readymade_data.forEach((row) => add_purchase_readymade_wrapper(row));
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

  let purchase_readymade_row = $("#purchase_readymade_wrapper tr").length;
  let purchase_readymade_checked = $(".purchase_readymade_checkboxes:checked").length;
  $("#purchase_readymade_count").html(purchase_readymade_row);
  $("#purchase_readymade_select_count").html(purchase_readymade_checked);
  $(`#purchase_readymade_checkbox`).prop(
    "checked",
    purchase_readymade_row > 0 ? purchase_readymade_row == purchase_readymade_checked : false
  );

};
const set_default = () => {
  $("#payment_purchase_amt").val(0);
  $("#payment_balance_amt_show").val("");
  $("#payment_balance_amt").val(0);
  $("#payment_balance_type").val("");

  $("#purchase_wrapper").html("");
  $("#purchase_count").html(0);
  $("#purchase_select_count").html(0);
  $(`#purchase_checkbox`).prop("checked", false);

  $("#purchase_readymade_wrapper").html("");
  $("#purchase_readymade_count").html(0);
  $("#purchase_readymade_select_count").html(0);
  $(`#purchase_readymade_checkbox`).prop("checked", false);
};
const get_supplier_from_purchase = (id) => {
  if (!id) return false;
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_supplier_from_purchase", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          $("#payment_supplier_id").html(
            `<option value="${data[0]["supplier_id"]}">${data[0]["supplier_name"]}</option>`
          );
          $(`#payment_supplier_id`)
            .val(data[0]["supplier_id"])
            .trigger("change");
          $(`#payment_supplier_id`).select2("close");
        }
      }
    },
    (errmsg) => {}
  );
};

const get_supplier_data = () => {
  set_default();
  const id = $("#payment_supplier_id").val();
  if (!id) return false;
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_supplier_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        const { purchase_data,purchase_readymade_data, balance_data } = data;
        $("#payment_opening_amt").val(balance_data["opening_amt"]);
        $("#payment_purchase_amt").val(balance_data["purchase_amt"]);
        $("#payment_purchase_readymade_amt").val(balance_data["purchase_readymade_amt"]);
        $("#payment_balance_amt").val(balance_data["balance_amt"]);
        $("#payment_balance_type").val(balance_data["type"]);
        $("#payment_balance_amt_show").val(
          `${balance_data["balance_amt"]} ${balance_data["type"]}`
        );

        if (purchase_data && purchase_data.length != 0) {
          purchase_data.forEach((row) => add_purchase_wrapper(row));
        }
        
        if (purchase_readymade_data && purchase_readymade_data.length != 0) {
          purchase_readymade_data.forEach((row) => add_purchase_readymade_wrapper(row));
        }

        set_checkboxes();
        calculate_master();
      }
    },
    (errmsg) => {}
  );
};
const get_data_for_adjustment = () => {
  const id = $("#payment_supplier_id").val();
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
        const { purchase_data ,purchase_readymade_data} = data;

        if (purchase_data && purchase_data.length != 0) {
          purchase_data.forEach((row) => add_purchase_wrapper(row));
        }
        if (purchase_readymade_data && purchase_readymade_data.length != 0) {
            purchase_readymade_data.forEach((row) => add_purchase_readymade_wrapper(row));
        }
        set_checkboxes();
      }
    },
    (errmsg) => {}
  );
};

const calculate_master = () => {
  let payment_amt = $("#payment_amt").val();
  if (isNaN(payment_amt) || payment_amt == "") payment_amt = 0;

  let balance_amt = $("#payment_balance_amt").val();
  if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;

  let balance_type = $(`#payment_balance_type`).val();

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

  $("#payment_balance_amt_show").val(`${closing_amt} ${balance_type}`);
  let purchase_amt = 0;
  let purchase_row = $("#purchase_wrapper tr").length;
  for (let i = 1; i <= purchase_row; i++) {
    let attr = $(`#purchase_wrapper tr:nth-child(${i})`).attr("id");
    let explode = attr.split("_");
    let cnt = explode[1];

    let balance_amt = $(`#ppt_balance_amt_${cnt}`).val();
    if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;
    if ($(`#ppt_checked_${cnt}`).is(":checked")) {
      let current_amt = parseFloat(payment_amt) - parseFloat(purchase_amt);
      if (isNaN(current_amt) || current_amt == "") current_amt = 0;

      let allocated_amt =
        current_amt > balance_amt
          ? parseFloat(balance_amt)
          : parseFloat(current_amt);
      if (isNaN(allocated_amt) || allocated_amt == "") allocated_amt = 0;
      $(`#ppt_adjust_amt_${cnt}`).val(allocated_amt.toFixed(2));

      balance_amt = parseFloat(balance_amt) - parseFloat(allocated_amt);
      if (isNaN(allocated_amt) || allocated_amt == "") allocated_amt = 0;
      $(`#ppt_balance_amt_show_${cnt}`).val(balance_amt.toFixed(2));

      purchase_amt = parseFloat(purchase_amt) + parseFloat(allocated_amt);
      if (isNaN(purchase_amt) || purchase_amt == "") purchase_amt = 0;
    } else {
      $(`#ppt_adjust_amt_${cnt}`).val(0);
      $(`#ppt_balance_amt_show_${cnt}`).val(balance_amt);
    }
  }

  let purchase_readymade_amt = purchase_amt;
  let purchase_readymade_row = $("#purchase_readymade_wrapper tr").length;

  for (let i = 1; i <= purchase_readymade_row; i++) {
    let attr = $(`#purchase_readymade_wrapper tr:nth-child(${i})`).attr("id");
    let explode = attr.split("_");
    // alert(explode)
    let cnt = explode[1];
    let balance_amt = $(`#pprt_balance_amt_${cnt}`).val();
    if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;

    if ($(`#pprt_checked_${cnt}`).is(":checked")) {
      let current_amt = parseFloat(payment_amt) - parseFloat(purchase_readymade_amt);
      if (isNaN(current_amt) || current_amt == "") current_amt = 0;
      let allocated_amt =
        current_amt > balance_amt
          ? parseFloat(balance_amt)
          : parseFloat(current_amt);
      if (isNaN(allocated_amt) || allocated_amt == "") allocated_amt = 0;
      $(`#pprt_adjust_amt_${cnt}`).val(allocated_amt.toFixed(2));

      balance_amt = parseFloat(balance_amt) - parseFloat(allocated_amt);
      if (isNaN(allocated_amt) || allocated_amt == "") allocated_amt = 0;
      $(`#pprt_balance_amt_show_${cnt}`).val(balance_amt.toFixed(2));

      purchase_readymade_amt = parseFloat(purchase_readymade_amt) + parseFloat(allocated_amt);
      if (isNaN(purchase_readymade_amt) || purchase_readymade_amt == "") purchase_readymade_amt = 0;
    } else {
      $(`#pprt_adjust_amt_${cnt}`).val(0);
      $(`#pprt_balance_amt_show_${cnt}`).val(balance_amt);
    }
  }

  set_checkboxes();
};
const remove_master_notifier = () => {
  notifier("payment_supplier_id");
  notifier("payment_amt");
};
const add_edit = (id) => {
  remove_master_notifier();
  let check = true;
  if ($("#payment_supplier_id").val() == null) {
    notifier("payment_supplier_id", "Required");
    check = false;
  }

  if ($("#payment_amt").val() <= 0) {
    notifier("payment_amt", "Required");
    check = false;
  } else {
    if ($("#payment_amt").val() < 0) {
      notifier("payment_amt", "Invalid payment amt");
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
const add_purchase_wrapper = (data) => {
  const {
    ppt_id,
    ppt_checked,
    ppt_pm_id,
    ppt_entry_no,
    ppt_entry_date,
    ppt_bill_no,
    ppt_bill_date,
    ppt_total_amt,
    ppt_adjust_amt,
    balance_amt,
  } = data;
  let tr = `<tr id="rowpurchase_${ppt_pm_id}">
                  <td width="2%" >
                      <input 
                          type="hidden" 
                          name="ppt_id[${ppt_pm_id}]" 
                          id="ppt_id_${ppt_pm_id}" 
                          value="${ppt_id}" 
                      />
                      <label class="custom-control material-checkbox">
                          <input 
                              type="checkbox" 
                              class="material-control-input purchase_checkboxes" 
                              id="ppt_checked_${ppt_pm_id}" 
                              name="ppt_checked[${ppt_pm_id}]" 
                              value="${ppt_pm_id}"
                              onclick="purchase_select_deselect(${ppt_pm_id})" 
                              ${ppt_checked == 1 ? "checked" : ""}
                          />
                          <span class="material-control-indicator"></span>
                      </label>
                  </td>
                  <td width="5%">
                      <input 
                          type="hidden" 
                          name="ppt_pm_id[${ppt_pm_id}]" 
                          id="ppt_pm_id_${ppt_pm_id}" 
                          value="${ppt_pm_id}" 
                      />
                      <input 
                          type="text" 
                          class="border-0 text-center" 
                          name="ppt_entry_no[${ppt_pm_id}]" 
                          id="ppt_entry_no_${ppt_pm_id}" 
                          value="${ppt_entry_no}" 
                          readonly 
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="text" 
                          class="border-0" 
                          name="ppt_entry_date[${ppt_pm_id}]" 
                          id="ppt_entry_date_${ppt_pm_id}" 
                          value="${ppt_entry_date}" 
                          readonly 
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="text" 
                          class="border-0 text-center" 
                          id="ppt_bill_no_${ppt_pm_id}" 
                          value="${ppt_bill_no}" 
                          readonly 
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="text" 
                          class="border-0" 
                          name="ppt_bill_date[${ppt_pm_id}]" 
                          id="ppt_bill_date_${ppt_pm_id}" 
                          value="${ppt_bill_date}" 
                          readonly 
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          name="ppt_total_amt[${ppt_pm_id}]" 
                          id="ppt_total_amt_${ppt_pm_id}" 
                          value="${ppt_total_amt}" 
                          readonly 
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          name="ppt_adjust_amt[${ppt_pm_id}]" 
                          id="ppt_adjust_amt_${ppt_pm_id}" 
                          value="${ppt_adjust_amt}" 
                          readonly
                      />
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          id="ppt_balance_amt_show_${ppt_pm_id}" 
                          value="${balance_amt}" 
                          readonly 
                      />
                      <input 
                          type="hidden" 
                          id="ppt_balance_amt_${ppt_pm_id}" 
                          value="${ppt_id == 0 ? balance_amt : ppt_total_amt}" 
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

const add_purchase_readymade_wrapper = (data) => {
  const {
    pprt_id,
    pprt_checked,
    pprt_prmm_id,
    pprt_entry_no,
    pprt_entry_date,
    pprt_bill_no,
    pprt_bill_date,
    pprt_total_amt,
    pprt_adjust_amt,
    balance_amt,
  } = data;
  let tr = `<tr id="rowreadymade_${pprt_prmm_id}">
                  <td width="2%" >
                      <input 
                          type="hidden" 
                          name="pprt_id[${pprt_prmm_id}]" 
                          id="pprt_id_${pprt_prmm_id}" 
                          value="${pprt_id}"/>
                      <label class="custom-control material-checkbox">
                          <input 
                              type="checkbox" 
                              class="material-control-input purchase_readymade_checkboxes" 
                              id="pprt_checked_${pprt_prmm_id}" 
                              name="pprt_checked[${pprt_prmm_id}]" 
                              value="${pprt_prmm_id}"
                              onclick="purchase_readymade_select_deselect(${pprt_prmm_id})" 
                              ${pprt_checked == 1 ? "checked" : ""}/>
                          <span class="material-control-indicator"></span>
                      </label>
                  </td>
                  <td width="5%">
                      <input 
                          type="hidden" 
                          name="pprt_prmm_id[${pprt_prmm_id}]" 
                          id="pprt_prmm_id_${pprt_prmm_id}" 
                          value="${pprt_prmm_id}"/>
                      <input 
                          type="text" 
                          class="border-0 text-center" 
                          name="pprt_entry_no[${pprt_prmm_id}]" 
                          id="pprt_entry_no_${pprt_prmm_id}" 
                          value="${pprt_entry_no}" 
                          readonly />
                  </td>
                  <td width="5%">
                      <input 
                          type="text" 
                          class="border-0" 
                          name="pprt_entry_date[${pprt_prmm_id}]" 
                          id="pprt_entry_date_${pprt_prmm_id}" 
                          value="${pprt_entry_date}" 
                          readonly/>
                  </td>
                  <td width="5%">
                      <input 
                          type="text" 
                          class="border-0 text-center" 
                          name="pprt_bill_no[${pprt_prmm_id}]" 
                          id="pprt_bill_no_${pprt_prmm_id}" 
                          value="${pprt_bill_no}" 
                          readonly />
                  </td>
                  <td width="5%">
                      <input 
                          type="text" 
                          class="border-0" 
                          name="pprt_bill_date[${pprt_prmm_id}]" 
                          id="pprt_bill_date_${pprt_prmm_id}" 
                          value="${pprt_bill_date}" 
                          readonly/>
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          name="pprt_total_amt[${pprt_prmm_id}]" 
                          id="pprt_total_amt_${pprt_prmm_id}" 
                          value="${pprt_total_amt}" 
                          readonly />
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          name="pprt_adjust_amt[${pprt_prmm_id}]" 
                          id="pprt_adjust_amt_${pprt_prmm_id}" 
                          value="${pprt_adjust_amt}" 
                          readonly/>
                  </td>
                  <td width="5%">
                      <input 
                          type="number" 
                          class="border-0" 
                          id="pprt_balance_amt_show_${pprt_prmm_id}" 
                          value="${balance_amt}" 
                          readonly 
                      />
                      <input 
                          type="hidden" 
                          id="pprt_balance_amt_${pprt_prmm_id}" 
                          value="${pprt_id == 0 ? balance_amt : pprt_total_amt}" 
                          readonly 
                      />
                  </td>
              </tr>`;
  $("#purchase_readymade_wrapper").prepend(tr);
};

const purchase_readymade_select_deselect = (count = 0) => {
  let parent_checked = $(`#purchase_readymade_checkbox`).is(":checked");
  if (count == 0) $(`.purchase_readymade_checkboxes`).prop("checked", parent_checked);
  calculate_master();
};

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
        const { payment_mode_data } = data;
        if (payment_mode_data && payment_mode_data.length != 0) {
          const { pay_modes } = get_pay_modes(payment_mode_data);
          let body = `<div class="row pt-2">
                        <div class="col-12">
                          <div style="max-height: 65vh; overflow-x: auto;">
                            <table class="table table-sm w-100">
                              <tbody>
                                <tr>
                                  <td width="04%" class="border-0 font-weight-bold"></td>
                                  <td width="96%" class="border-0 font-weight-bold" colspan="4">
                                   
                                  </td>
                                </tr>
                              </tbody>
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
    const {
      ppmt_id,
      ppmt_amt,
      ppmt_payment_mode_id,
      payment_mode_name,
    } = row;
    advance_amt = parseFloat(advance_amt) + parseFloat(ppmt_amt);
    if (isNaN(advance_amt) || advance_amt == "") advance_amt = 0;

    pay_modes += `<tr id="rowpm_${ppmt_payment_mode_id}">
                    <td width="04%" class="border-0 font-weight-bold"></td>
                    <td width="20%" class="border-0 font-weight-bold text-right">${payment_mode_name}</td>
                    <td width="03%" class="border-0 font-weight-bold">:</td>
                    <td width="50%" class="border-0 floating-label">
                      <input 
                        type="hidden"
                        id="ppmt_id_${ppmt_payment_mode_id}" 
                        name="ppmt_id[${ppmt_payment_mode_id}]" 
                        value="${ppmt_id}" 
                      />
                      <input 
                        type="hidden"
                        id="ppmt_payment_mode_id_${ppmt_payment_mode_id}" 
                        name="ppmt_payment_mode_id[${ppmt_payment_mode_id}]" 
                        value="${ppmt_payment_mode_id}" 
                      />
                      <input 
                        type="number" 
                        class="form-control floating-input" 
                        id="ppmt_amt_${ppmt_payment_mode_id}" 
                        name="ppmt_amt[${ppmt_payment_mode_id}]" 
                        value="${ppmt_amt}"
                        onkeyup="calculate_payment_amt()"
                        placeholder=" " 
                        autocomplete="off" 
                      />
                    </td>
                   
                  </tr>`;
  });
  return { advance_amt, pay_modes };
};
const toggle_payment_mode_popup = () => {
  if ($(`#payment_mode_wrapper .right-panel`).hasClass("active")) {
    $(`#payment_mode_wrapper .right-panel `).removeClass("active");
  } else {
    $(`#payment_mode_wrapper .right-panel `).addClass("active");

    let payment_amt = $(`#payment_amt`).val();
    if (isNaN(payment_amt) || payment_amt == "") payment_amt = 0;
    $(`._payment_amt`).html(payment_amt);

    let debit_note_amt = $("#payment_debit_note_amt").val();
    if (isNaN(debit_note_amt) || debit_note_amt == "") debit_note_amt = 0;
    $(`._debit_note_amt`).html(debit_note_amt);

    let balance_amt = $("#payment_balance_amt").val();
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
    let amt = $(`#ppmt_amt_${id}`).val();
    if (isNaN(amt) || amt == "") amt = 0;
    payment_amt = parseFloat(payment_amt) + parseFloat(amt);
    if (isNaN(payment_amt) || payment_amt == "") payment_amt = 0;
  }
  $("._payment_amt").html(payment_amt);
  $(`#payment_amt`).val(payment_amt);

  let debit_note_amt = $("#payment_debit_note_amt").val();
  if (isNaN(debit_note_amt) || debit_note_amt == "") debit_note_amt = 0;

  let balance_amt = $("#payment_balance_amt").val();
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
