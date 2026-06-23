$(document).ready(function () {
  $(`#brmm_id`)
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_brmm_id`,
        placeholder: "scan",
        barcode: "brmm_id",
      })
    )
    .on("change", (event) => get_barcode_data(event.target.value));
});
// core_functions
let trans_data = [];
const get_transaction = () => {
  if (["edit", "read"].includes(get_url_string("action"))) {
    let id = get_url_string("id");
    if (id) {
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
            if (data && data.length != 0) {
              trans_data = data;
              let result = paginate(trans_data, page);
              if (result && result.length != 0) {
                result.forEach((value) => add_wrapper_data(value, true));
              }
            }
            calculate_master();
            $("#transaction_count").html(trans_data.length);
          }
        },
        (errmsg) => {}
      );
    }
  }
};
const calculate_master = () => {
  let total_qty = 0;
  let total_sub_amt = 0;
  let total_taxable_amt = 0;
  let total_total_amt = 0;

  trans_data.forEach((value, index) => { 
    const {
      prrt_qty,
      prrt_amt,
      prrt_taxable_amt,
      prrt_total_amt,
    } = value;

    total_qty = parseInt(total_qty) + parseInt(prrt_qty);
    if (isNaN(total_qty) || total_qty == "") total_qty = 0;

    total_sub_amt = parseFloat(total_sub_amt) + parseFloat(prrt_amt);
    if (isNaN(total_sub_amt) || total_sub_amt == "") total_sub_amt = 0;

    total_taxable_amt =
      parseFloat(total_taxable_amt) + parseFloat(prrt_taxable_amt);
    if (isNaN(total_taxable_amt) || total_taxable_amt == "")
      total_taxable_amt = 0;

    total_total_amt = parseFloat(total_total_amt) + parseFloat(prrt_total_amt);
    if (isNaN(total_total_amt) || total_total_amt == "") total_total_amt = 0;
  });
  $("#prrm_total_qty").val(total_qty);
  $("#prrm_sub_amt").val(total_sub_amt.toFixed(2));
  $("#prrm_taxable_amt").val(total_taxable_amt.toFixed(2));

  let total_amt = parseFloat(total_total_amt);
  if (isNaN(total_amt) || total_amt == "") total_amt = 0;

  let after_decimal = parseFloat("0." + total_amt.toString().split(".")[1]);
  after_decimal = after_decimal.toFixed(2);
  after_decimal = after_decimal == 1 ? 0 : after_decimal;
  $("#prrm_round_off").val(after_decimal);

  $("#prrm_total_amt").val(Math.round(total_amt));

  if (total_amt > 0) {
    $(".master_block_btn").prop("disabled", false);
    notifier("prrm_total_amt");
  } else {
    $(".master_block_btn").prop("disabled", true);
    notifier("prrm_total_amt", "Required");
  }
};
const check_transaction = () => {
  let flag = true;
  if (trans_data.length > 0) {
    trans_data.forEach((value) => {
      const { prrt_qty , prrt_amt } = value;
      if (prrt_qty == 0 || prrt_qty == "") {
        prrt_id = id;
        flag = false;
      } else if (prrt_qty < 0) {
        prrt_id = id;
        flag = false;
      } else {
      }

      if (prrt_amt == 0 || prrt_amt == "") {
        prrt_id = id;
        flag = false;
      } else if (prrt_amt < 0) {
        prrt_id = id;
        flag = false;
      } else {
      }
    });
  }
  return flag;
};
const add_wrapper_data = (data, append = false) => {
  const {
    prrt_brmm_id,
    bill_no,
    bill_date,
    qrcode,
    sku_name,
    prrt_qty,
    prrt_rate,
    prrt_amt,
    prrt_taxable_amt,
    prrt_total_amt,
    isExist,
  } = data;
  let tr = `<tr id="row_${prrt_brmm_id}">
                <td id="qrcode_${prrt_brmm_id}">${qrcode}</td>
                <td id="bill_no_${prrt_brmm_id}">${bill_no}</td>
                <td id="bill_date_${prrt_brmm_id}">${bill_date}</td>
                <td id="sku_name_${prrt_brmm_id}">${sku_name}</td>
                <td id="qty_${prrt_brmm_id}">${prrt_qty}</td>
                <td id="rate_${prrt_brmm_id}">${prrt_rate}</td>
                <td id="amt_${prrt_brmm_id}">${prrt_amt}</td>
                <td class="d-none" id="taxable_amt_${prrt_brmm_id}">${prrt_taxable_amt}</td>
                <td id="total_amt_${prrt_brmm_id}">${prrt_total_amt}</td>
                <td>
                    <div class="navigationn_wrapper">
                        <div class="navigationn">
                            <div class="menuToggle" id="menu_toggle_${prrt_brmm_id}" onclick="toggle_menuu(this)"></div>
                            <div class="menuu">
                                <ul>
                                    <li>
                                        <a 
                                            type="button" 
                                            class="btn btn-md" 
                                            onclick="edit_transaction(${prrt_brmm_id})"
                                            ><i class="text-success fa fa-edit"></i></a>
                                    </li>
                                    <li>
                                        ${
                                          isExist
                                            ? `<button 
                                                type="button" 
                                                class="btn btn-md"
                                                ><i class="text-danger fa fa-ban"></i></button>`
                                            : `<a 
                                                type="button" 
                                                class="btn btn-md" 
                                                onclick="remove_transaction(${prrt_brmm_id})"
                                                ><i class="text-danger fa fa-trash"></i></a>`
                                        }
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>`;
  if (append) {
    $("#transaction_wrapper").append(tr);
  } else {
    $("#transaction_wrapper").prepend(tr);
  }
};
const add_edit = () => {
  event.preventDefault();
  remove_transaction_notifier();
  let check = true;
  let required_row = true;
  if (!check_transaction()) {
    required_row = false;
  }
  if ($(`#prrm_supplier_id`).val() == null) {
    notifier(`prrm_supplier_id`, "Required");
    check = false;
  }
  if ($(`#prrm_total_qty`).val() <= 0 || $(`#prrm_total_qty`).val() == "") {
    notifier(`prrm_total_qty`, "Required");
    check = false;
  }
  if ($(`#prrm_total_amt`).val() <= 0 || $(`#prrm_total_amt`).val() == "") {
    notifier(`prrm_total_amt`, "Required");
    check = false;
  }
  if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else if (!required_row) {
    toastr.error("You forgot to enter some item information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
  } else {
    const path = `${link}/${sub_link}/handler`;
    let form_data = $(`#_form`).serialize();
    form_data += `&func=add_edit`;
    form_data += `&trans_data=${JSON.stringify(trans_data)}`;
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        const { data, msg } = resp;
        if (handle_response(resp)) {
          if (id == 0) {
          } else {
          }
          window.location.reload();
          remove_transaction_notifier();
          toastr.success("", msg, {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
          });
          $("body, html").animate({ scrollTop: 0 }, 1000);
        }
      },
      (errmsg) => {}
    );
  }
};
const remove_transaction_notifier = () => {
  notifier(`prrm_supplier_id`);
  notifier(`prrm_total_qty`);
  notifier(`prrm_total_amt`);
};
const remove_master_notifier = () => {
  notifier("sku_id");
  notifier("qty");
  notifier("total_qty");
  notifier("rate");
  notifier("amt");
};
const remove_transaction = (prrt_brmm_id) => {
  trans_data = trans_data.filter((value) => value.prrt_brmm_id != prrt_brmm_id);
  let qrcode = $(`#qrcode_${prrt_brmm_id}`).html();
  toastr.success(`${qrcode}`, "ITEM REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#row_${prrt_brmm_id}`).detach();
  $("#transaction_count").html(trans_data.length);
  calculate_master();
};
const edit_transaction = (prrt_brmm_id) => {
  const find = trans_data.find((value) => value["prrt_brmm_id"] == prrt_brmm_id);
  const { bal_qty, prrt_qty } = find;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">change qty</p>
                </div>`;
  let data = `<div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="bal_qty" 
                          value="${bal_qty}"
                          readonly
                        />   
                        <label class="text-uppercase">balance qty</label>
                        <small class="form-text text-muted helper-text" id="bal_qty_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="return_qty" 
                          name="return_qty" 
                          value="${prrt_qty}"
                          onkeyup="check_bal_qty()"
                          placeholder=" " 
                          autocomplete="off"
                        />   
                        <label class="text-uppercase">return_qty</label>
                        <small class="form-text text-muted helper-text" id="return_qty_msg"></small>
                      </div>
                  </div>              
                </div>`;
  let btn = `<button 
                type="button" 
                class="btn btn-sm btn-primary" 
                id="sbt_btn" 
                onclick="update_qty(${prrt_brmm_id})" 
                style="width:15%;"
              >
                <div class="stage d-none"><div class="dot-flashing"></div></div>
                <div class="dot-text text-primary text-uppercase">update</div>
              </button>
              <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">CANCEL</button>`;
  $(".modal-title-sm").html(title);
  $(".modal-body-sm").html(data);
  $(".modal-footer-sm").html(btn);
  $("#popup_modal_sm").modal("show");
  setTimeout(() => {
    $("#return_qty").focus();
  }, RELOAD_TIME);
  toggle_menuu({ id: prrt_brmm_id });
};
const check_bal_qty = () => {
  let bal_qty = $("#bal_qty").val();
  if (isNaN(bal_qty) || bal_qty == "") bal_qty = 0;

  let return_qty = parseValue($("#return_qty").val());
  if (isNaN(return_qty) || return_qty == "") return_qty = 0;

  notifier("return_qty");
  $("#sbt_btn").prop("disabled", false);
  if (return_qty <= 0) {
    notifier("return_qty", "Invalid return qty");
    $("#sbt_btn").prop("disabled", true);
    return false;
  }
  if (return_qty > bal_qty) {
    notifier("return_qty", "Return qty should be less than balance qty.");
    $("#sbt_btn").prop("disabled", true);
    return false;
  }
  return true;
};
const update_qty = (prrt_brmm_id) => {
  if (!check_bal_qty()) return false;
  let return_qty = parseValue($("#return_qty").val());
  if (isNaN(return_qty) || return_qty == "") return_qty = 0;
  let index = trans_data.findIndex((value) => value.prrt_brmm_id == prrt_brmm_id);
  if (index < 0) return false;
  trans_data[index].prrt_qty = return_qty;
  trans_data[index].prrt_total_qty = return_qty;
  $(`#qty_${prrt_brmm_id}`).html(return_qty.toFixed(2));
  $(`#total_qty_${prrt_brmm_id}`).html(return_qty.toFixed(2));
  calculate_transaction(prrt_brmm_id);
  calculate_master(true);
  toastr.success(
    `${trans_data[index]["qrcode"]}`,
    "RETURN QTY UPDATED SUCCESSFULLY.",
    {
      closeButton: true,
      progressBar: true,
    }
  );
  $("#popup_modal_sm").modal("hide");
};
const calculate_transaction = (prrt_brmm_id) => {
  let index = trans_data.findIndex((value) => value.prrt_brmm_id == prrt_brmm_id);
  if (index < 0) return false;
  const {
    prrt_qty,
    prrt_rate,
  } = trans_data[index];

  let amt = parseFloat(prrt_rate) * parseFloat(prrt_qty);
  if (isNaN(amt) || amt == "") amt = 0;
  trans_data[index].prrt_amt = amt;
  $(`#amt_${prrt_brmm_id}`).html(amt.toFixed(2));

  let taxable_amt = parseFloat(amt);
  if (isNaN(taxable_amt) || taxable_amt == "") taxable_amt = 0;
  trans_data[index].prrt_taxable_amt = taxable_amt;
  $(`#taxable_amt_${prrt_brmm_id}`).html(taxable_amt.toFixed(2));

  let total_amt =
    parseFloat(taxable_amt);
  if (isNaN(total_amt) || total_amt == "") total_amt = 0;
  trans_data[index].prrt_total_amt = total_amt;
  $(`#total_amt_${prrt_brmm_id}`).html(total_amt.toFixed(2));
};
const record_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.prrm_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">entry no : </td>
                          <td width="70%">${data.prrm_entry_no}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">entry date : </td>
                          <td width="70%">${data.prrm_entry_date}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">supplier : </td>
                          <td width="70%">${data.supplier_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">total qty : </td>
                          <td width="70%">${data.prrm_total_qty}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">total amt : </td>
                          <td width="70%">${data.prrm_total_amt}</td>
                      </tr>
                  </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};
// core_functions

// additional_functions
const get_barcode_data = (id) => {
  if (id == null || id == "" || id == 0) return false;
  let find = trans_data.find((value) => value.prrt_brmm_id == id);
  if (find != undefined) {
    toastr.error("Barcode already added.", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    setTimeout(() => {
      $("#brmm_id").val(null).trigger("change");
      $("#brmm_id").select2("open");
    }, RELOAD_TIME);
    return false;
  }
  const supplier_id = $("#prrm_supplier_id").val();
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_barcode_data", id, supplier_id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          data.forEach((value) => {
            trans_data.push(value);
            add_wrapper_data(value);
            if (supplier_id == 0) {
              $("#prrm_supplier_id").val(value["supplier_id"]);
              $("#supplier_name").val(value["supplier_name"]);
            }
          });
          $("#transaction_count").html(trans_data.length);
          calculate_transaction(id);
          calculate_master(false);
          console.log({ trans_data });
        }

        toastr.success(data[0]["qrcode"], msg, {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
        setTimeout(() => {
          $("#brmm_id").val(null).trigger("change");
          $("#brmm_id").select2("open");
        }, RELOAD_TIME);
      } else {
        setTimeout(() => {
          $("#brmm_id").val(null).trigger("change");
          $("#brmm_id").select2("open");
        }, RELOAD_TIME);
      }
    },
    (errmsg) => {
      setTimeout(() => {
        $("#brmm_id").val(null).trigger("change");
        $("#brmm_id").select2("open");
      }, RELOAD_TIME);
    }
  );
};
// additional_functions
