$(document).ready(function () {
  $("#om_id")
    .select2(
      select2_default({
        url: `voucher/receipt/get_select2/_om_id`,
        placeholder: "SELECT",
        param: true,
      })
    )
    .on("change", (event) => get_customer_from_order(event.target.value));
  $("#receipt_customer_id")
    .select2(
      select2_default({
        url: `master/customer/get_select2/_id`,
        placeholder: "SELECT",
        param: true,
      })
    )
    .on("change", () => get_customer_data());
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
            const { order_data } = data;
            if (order_data && order_data.length != 0) {
              order_data.forEach((row) => add_order_wrapper(row));
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
  let order_row = $("#order_wrapper tr").length;
  let order_checked = $(".order_checkboxes:checked").length;
  $("#order_count").html(order_row);
  $("#order_select_count").html(order_checked);
  $(`#order_checkbox`).prop(
    "checked",
    order_row > 0 ? order_row == order_checked : false
  );
};
const set_default = () => {
  $("#receipt_order_amt").val(0);
  $("#receipt_balance_amt_show").val("");
  $("#receipt_balance_amt").val(0);
  $("#receipt_balance_type").val("");

  $("#order_wrapper").html("");
  $("#order_count").html(0);
  $("#order_select_count").html(0);
  $(`#order_checkbox`).prop("checked", false);
};
const get_customer_from_order = (id) => {
  if (!id) return false;
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_customer_from_order", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          $("#receipt_customer_id").html(
            `<option value="${data[0]["customer_id"]}">${data[0]["customer_name"]}</option>`
          );
          $(`#receipt_customer_id`)
            .val(data[0]["customer_id"])
            .trigger("change");
          $(`#receipt_customer_id`).select2("close");
        }
      }
    },
    (errmsg) => {}
  );
};
const get_customer_data = () => {
  set_default();
  const id = $("#receipt_customer_id").val();
  const om_id = $("#om_id").val();
  if (!id) return false;
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_customer_data", id, om_id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        const { order_data, balance_data } = data;
        $("#receipt_opening_amt").val(balance_data["opening_amt"]);
        $("#receipt_order_amt").val(balance_data["order_amt"]);
        $("#receipt_balance_amt").val(balance_data["balance_amt"]);
        $("#receipt_balance_type").val(balance_data["type"]);
        $("#receipt_balance_amt_show").val(
          `${balance_data["balance_amt"]} ${balance_data["type"]}`
        );

        if (order_data && order_data.length != 0) {
          order_data.forEach((row) => add_order_wrapper(row));
        }
        set_checkboxes();
        calculate_master();
      }
    },
    (errmsg) => {}
  );
};
const get_data_for_adjustment = () => {
  const id = $("#receipt_customer_id").val();
  if (!id) return false;
  const om_id = $("#om_id").val();
  const path = `${link}/${sub_link}/handler`;
  let form_data = { func: "get_data_for_adjustment", id, om_id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        const { order_data } = data;

        if (order_data && order_data.length != 0) {
          order_data.forEach((row) => add_order_wrapper(row));
        }
        set_checkboxes();
      }
    },
    (errmsg) => {}
  );
};
const calculate_master = () => {
  let receipt_amt = $("#receipt_amt").val();
  if (isNaN(receipt_amt) || receipt_amt == "") receipt_amt = 0;

  let balance_amt = $("#receipt_balance_amt").val();
  if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;

  let balance_type = $(`#receipt_balance_type`).val();

  let closing_amt = parseFloat(balance_amt) - parseFloat(receipt_amt);
  if (isNaN(closing_amt) || closing_amt == "") closing_amt = 0;

  if (closing_amt < 0) {
    closing_amt = Math.abs(closing_amt);
    if (balance_type == TO_RECEIVE) {
      balance_type = TO_PAY;
    } else {
      balance_type = TO_RECEIVE;
    }
  }
  $("#receipt_balance_amt_show").val(`${closing_amt} ${balance_type}`);

  let order_amt = 0;
  let order_row = $("#order_wrapper tr").length;
  for (let i = 1; i <= order_row; i++) {
    let attr = $(`#order_wrapper tr:nth-child(${i})`).attr("id");
    let explode = attr.split("_");
    let cnt = explode[1];

    let balance_amt = $(`#rot_balance_amt_${cnt}`).val();
    if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;
    if ($(`#rot_checked_${cnt}`).is(":checked")) {
      let current_amt = parseFloat(receipt_amt) - parseFloat(order_amt);
      if (isNaN(current_amt) || current_amt == "") current_amt = 0;

      let allocated_amt =
        current_amt > balance_amt
          ? parseFloat(balance_amt)
          : parseFloat(current_amt);
      if (isNaN(allocated_amt) || allocated_amt == "") allocated_amt = 0;
      $(`#rot_adjust_amt_${cnt}`).val(allocated_amt.toFixed(2));

      balance_amt = parseFloat(balance_amt) - parseFloat(allocated_amt);
      if (isNaN(allocated_amt) || allocated_amt == "") allocated_amt = 0;
      $(`#rot_balance_amt_show_${cnt}`).val(balance_amt.toFixed(2));

      order_amt = parseFloat(order_amt) + parseFloat(allocated_amt);
      if (isNaN(order_amt) || order_amt == "") order_amt = 0;
    } else {
      $(`#rot_adjust_amt_${cnt}`).val(0);
      $(`#rot_balance_amt_show_${cnt}`).val(balance_amt);
    }
  }
  set_checkboxes();
};
const remove_master_notifier = () => {
  notifier("receipt_customer_id");
  notifier("receipt_amt");
};
const add_edit = (id) => {
  remove_master_notifier();
  let check = true;
  if ($("#receipt_customer_id").val() == null) {
    notifier("receipt_customer_id", "Required");
    check = false;
  }
  if ($("#receipt_amt").val() <= 0) {
    notifier("receipt_amt", "Required");
    check = false;
  } else {
    if ($("#receipt_amt").val() < 0) {
      notifier("receipt_amt", "Invalid receipt amt");
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
const remove_record = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.receipt_id };
  let html = `<table class="table table-sm table-hover" style="font-size:0.8rem;">
                <tbody>
                    <tr>
                        <td class="font-weight-bold text-uppercase" width="30%" align="right">entry no : </td>
                        <td width="70%">${data.receipt_entry_no}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-uppercase" width="30%" align="right">entry date : </td>
                        <td width="70%">${data.entry_date}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-uppercase" width="30%" align="right">customer : </td>
                        <td width="70%">${data.customer_name}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold text-uppercase" width="30%" align="right">receipt amt : </td>
                        <td class="font-weight-bold text-uppercase" width="70%">${data.receipt_amt}</td>
                    </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
// core_functions

// order module
const add_order_wrapper = (data) => {
  const {
    rot_id,
    rot_checked,
    rot_om_id,
    rot_entry_no,
    rot_type,
    rot_entry_date,
    rot_total_amt,
    rot_allocated_amt,
    rot_adjust_amt,
    balance_amt,
  } = data;
  let tr = `<tr id="roworder_${rot_om_id}">
				<td width="2%" >
					<input 
						type="hidden" 
						name="rot_id[${rot_om_id}]" 
						id="rot_id_${rot_om_id}" 
						value="${rot_id}" 
					/>
					<label class="custom-control material-checkbox">
						<input 
							type="checkbox" 
							class="material-control-input order_checkboxes" 
							id="rot_checked_${rot_om_id}" 
							name="rot_checked[${rot_om_id}]" 
							value="${rot_om_id}"
							onclick="order_select_deselect(${rot_om_id})" 
							${rot_checked == 1 ? "checked" : ""}
						/>
						<span class="material-control-indicator"></span>
					</label>
				</td>
				<td width="5%">
					<input 
						type="hidden" 
						name="rot_om_id[${rot_om_id}]" 
						id="rot_om_id_${rot_om_id}" 
						value="${rot_om_id}"/>
					<input 
						type="text" 
						class="form-control bg-light text-dark border-0 text-center" 
						name="rot_entry_no[${rot_om_id}]" 
						id="rot_entry_no_${rot_om_id}" 
						value="${rot_entry_no}" 
						readonly 
					/>
				</td>
        <td width="5%">
          <input 
            type="text" 
            class="form-control bg-light text-dark border-0 text-center" 
            name="rot_type[${rot_om_id}]" 
            id="rot_type_${rot_om_id}" 
            value="${rot_type}" 
            readonly 
          />
        </td>
				<td width="5%">
					<input 
						type="text" 
						class="form-control bg-light text-dark border-0" 
						name="rot_entry_date[${rot_om_id}]" 
						id="rot_entry_date_${rot_om_id}" 
						value="${rot_entry_date}" 
						readonly 
					/>
				</td>
				<td width="5%">
					<input 
						type="number" 
						class="form-control bg-light text-dark border-0" 
						name="rot_total_amt[${rot_om_id}]" 
						id="rot_total_amt_${rot_om_id}" 
						value="${rot_total_amt}" 
						readonly 
					/>
				</td>
        <td width="5%">
					<input 
						type="number" 
						class="form-control bg-light text-dark border-0" 
						name="rot_allocated_amt[${rot_om_id}]" 
						id="rot_allocated_amt_${rot_om_id}" 
						value="${rot_allocated_amt}" 
						readonly 
					/>
				</td>
				<td width="5%">
					<input 
						type="number" 
						class="form-control bg-light text-dark border-0" 
						name="rot_adjust_amt[${rot_om_id}]" 
						id="rot_adjust_amt_${rot_om_id}" 
						value="${rot_adjust_amt}" 
						readonly
					/>
				</td>
				<td width="5%">
					<input 
						type="number" 
						class="form-control bg-light text-dark border-0" 
						id="rot_balance_amt_show_${rot_om_id}" 
						value="${balance_amt}" 
						readonly 
					/>
					<input 
						type="hidden" 
						id="rot_balance_amt_${rot_om_id}" 
						value="${rot_id == 0 ? balance_amt : rot_total_amt}" 
						readonly 
					/>
				</td>
			</tr>`;
  $("#order_wrapper").prepend(tr);
};
const order_select_deselect = (count = 0) => {
  let parent_checked = $(`#order_checkbox`).is(":checked");
  if (count == 0) $(`.order_checkboxes`).prop("checked", parent_checked);
  calculate_master();
};
// order module

// payment mode
const get_payment_mode_data = () => {
  let title = `<p>payment mode</p>`;
  let subtitle = `<div class="d-flex justify-content-around">
                    <p class="d-flex flex-column justify-content-around">
                        <span class="pb-1 border-bottom">receipt amt</span>
                        <span class="_receipt_amt">0</span>
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
    const { rpmt_id, rpmt_amt, rpmt_payment_mode_id, payment_mode_name } = row;
    advance_amt = parseFloat(advance_amt) + parseFloat(rpmt_amt);
    if (isNaN(advance_amt) || advance_amt == "") advance_amt = 0;

    pay_modes += `<tr id="rowpm_${rpmt_payment_mode_id}">
						<td width="10%" class="border-0 font-weight-bold"></td>
						<td width="30%" class="border-0 font-weight-bold">${payment_mode_name} : </td>
						<td width="50%" class="border-0 floating-label">
							<input 
								type="hidden"
								id="rpmt_id_${rpmt_payment_mode_id}" 
								name="rpmt_id[${rpmt_payment_mode_id}]" 
								value="${rpmt_id}" 
							/>
							<input 
								type="hidden"
								id="rpmt_payment_mode_id_${rpmt_payment_mode_id}" 
								name="rpmt_payment_mode_id[${rpmt_payment_mode_id}]" 
								value="${rpmt_payment_mode_id}" 
							/>
							<input 
								type="number" 
								class="form-control floating-input" 
								id="rpmt_amt_${rpmt_payment_mode_id}" 
								name="rpmt_amt[${rpmt_payment_mode_id}]" 
								value="${rpmt_amt}"
								onkeyup="calculate_receipt_amt()"
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

    let receipt_amt = $(`#receipt_amt`).val();
    if (isNaN(receipt_amt) || receipt_amt == "") receipt_amt = 0;
    $(`._receipt_amt`).html(receipt_amt);

    let credit_note_amt = $("#receipt_credit_note_amt").val();
    if (isNaN(credit_note_amt) || credit_note_amt == "") credit_note_amt = 0;
    $(`._credit_note_amt`).html(credit_note_amt);

    let balance_amt = $("#receipt_balance_amt").val();
    if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;

    let closing_amt =
      parseFloat(balance_amt) +
      parseFloat(credit_note_amt) -
      parseFloat(receipt_amt);
    if (isNaN(closing_amt) || closing_amt == "") closing_amt = 0;

    $("._balance_amt").html(closing_amt);
  }
};
const calculate_receipt_amt = () => {
  let receipt_amt = 0;
  for (let i = 1; i <= $("#payment_mode_tbody > tr").length; i++) {
    let cnt = $(`#payment_mode_tbody > tr:nth-child(${i})`).attr("id");
    let explode = cnt.split("_");
    let id = explode[1];

    let amt = $(`#rpmt_amt_${id}`).val();
    if (isNaN(amt) || amt == "") amt = 0;

    receipt_amt = parseFloat(receipt_amt) + parseFloat(amt);
    if (isNaN(receipt_amt) || receipt_amt == "") receipt_amt = 0;
  }
  $("._receipt_amt").html(receipt_amt);
  $(`#receipt_amt`).val(receipt_amt);

  let credit_note_amt = $("#receipt_credit_note_amt").val();
  if (isNaN(credit_note_amt) || credit_note_amt == "") credit_note_amt = 0;

  let balance_amt = $("#receipt_balance_amt").val();
  if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;

  let closing_amt =
    parseFloat(balance_amt) +
    parseFloat(credit_note_amt) -
    parseFloat(receipt_amt);
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
