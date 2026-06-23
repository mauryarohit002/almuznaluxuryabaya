$(document).ready(function () { 
  $(`#prmm_supplier_id`)
    .select2(
      select2_default({
        url: `master/supplier/get_select2/_id`,
        placeholder: "SELECT",
        param: true,
      })
    )
    .on("change", (event) => get_supplier_data(event.target.value));

    $(`#sku_id`).select2( 
      select2_default({
        url: `${link}/${sub_link}/get_select2/_sku_id`,
        placeholder: "select",
        param: true,
        param1:()=> $("#prmm_supplier_id").val(),
      })
    ).on('change', function () {
        $.post(`${sub_link}/get_data_from_sku`, {
          sku_id: $(this).val()
        }, function (res) {
            $("#mrp").val(res.sku_mrp);
            $("#rate").val(res.sku_cp);

            calculate_transaction();
        }, 'json');
      });

      // size
    $('#size_id').select2(
        select2_default({
            url: `master/size/get_select2/_id`,
            placeholder: "SELECT",
            param: true,
        })
    );

});
// Keyboard shortcut: ALT + S
document.addEventListener('keydown', function(e) {
    if (e.altKey && e.key.toLowerCase() === 's') {
        e.preventDefault();
        openSkuModal();
    }
});
function openSkuModal() {
    loadSkuForm();
    $('#skuModal').modal('show');
}

function loadSkuForm(masterData = {}) {

    let html = `
                <div class="form-group floating-form d-flex flex-wrap mt-4">

                    ${generateSelect("sku_apparel_id", "apparel", true)}
                    ${generateInput("sku_name", "name", "text", true)}
                    ${generateSelect("sku_supplier_id", "supplier", true)}

                    ${generateInput("sku_fabric", "fabric", "number")}
                    ${generateInput("sku_cutting", "cutting", "number")}
                    ${generateInput("sku_silai", "stitch", "number")}
                    ${generateInput("sku_stone", "stone", "number")}
                    ${generateInput("sku_lagwayi", "lagwayi", "number")}
                    ${generateInput("sku_hand_work", "hand work", "number")}
                    ${generateInput("sku_material", "material", "number")}
                    ${generateInput("sku_exp", "exp", "number")}
                    ${generateInput("sku_cp", "cp", "number")}
                    ${generateInput("sku_mrp", "mrp", "number")}
                    ${generateInput("sku_offer_price", "offer price", "number")}
                    ${generateInput("sku_last_price", "last price", "number")}
                    ${generateInput("sku_piece", "no. of pieces", "number", true, 1)}

                    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
                        <textarea
                            class="form-control floating-textarea"
                            id="sku_notes"
                            name="sku_notes"
                            placeholder=" "
                            autocomplete="off"
                        ></textarea>
                        <label class="text-uppercase">notes</label>
                    </div>

                </div>
    `;

    document.getElementById("skuFormContainer").innerHTML = html;

    initSkuSelect2();
}
 function calculate_cp() {

    let fabric      = parseFloat(document.getElementById('sku_fabric')?.value) || 0;
    let cutting     = parseFloat(document.getElementById('sku_cutting')?.value) || 0;
    let silai       = parseFloat(document.getElementById('sku_silai')?.value) || 0;
    let stone       = parseFloat(document.getElementById('sku_stone')?.value) || 0;
    let lagwayi     = parseFloat(document.getElementById('sku_lagwayi')?.value) || 0;
    let hand_work   = parseFloat(document.getElementById('sku_hand_work')?.value) || 0;
    let material    = parseFloat(document.getElementById('sku_material')?.value) || 0;
    let exp         = parseFloat(document.getElementById('sku_exp')?.value) || 0;

    let total = fabric + cutting + silai + stone + lagwayi + hand_work + material + exp;

    document.getElementById('sku_cp').value = total.toFixed(2);
}
function generateInput(id, label, type, required = false, defaultValue = "") {

    return `
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
        <input 
            type="${type}" 
            class="form-control floating-input" 
            id="${id}" 
            name="${id}" 
            value="${defaultValue}" 
            ${type === 'number' ? 'oninput="calculate_cp()"' : ''}
            placeholder=" " 
            autocomplete="off"
        />   
        <label class="text-uppercase">
            ${label} ${required ? '<span class="text-danger">*</span>' : ''}
        </label>
        <small class="form-text text-muted helper-text" id="${id}_msg"></small>
    </div>
    `;
}
function generateSelect(id, label, required = false) {

    return `
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 floating-label">
        <p class="text-uppercase">
            ${label} ${required ? '<span class="text-danger">*</span>' : ''}
        </p>
        <select 
            class="form-control floating-select" 
            id="${id}" 
            name="${id}" 
            placeholder=" "
        ></select>
        <small class="form-text text-muted helper-text" id="${id}_msg"></small>
    </div>
    `;
}
function initSkuSelect2() {

    // Apparel
    $('#sku_apparel_id').select2(
        select2_default({
            url: `master/apparel/get_select2/_id`,
            placeholder: "SELECT",
            param: true,
        })
    );

    // Supplier
    $('#sku_supplier_id').select2(
        select2_default({
            url: `master/supplier/get_select2/_id`,
            placeholder: "SELECT",
            param: true,
        })
    );

}

function saveSku() {

    // Get selected supplier from SKU modal BEFORE AJAX
    let selectedSupplier = $('#sku_supplier_id').val();
    let selectedSupplierText = $('#sku_supplier_id option:selected').text();

    $.ajax({
        url: base_url + "/transaction/purchase_readymade/save_sku",
        type: "POST",
        data: $("#skuForm").serialize(),
        success: function(response) {

            let res = JSON.parse(response);

            if(res.status == 1) {

                $('#skuModal').modal('hide');

                // ✅ Add new SKU to dropdown
                let newOption = new Option(res.name, res.id, true, true);
                $('#sku_id').append(newOption).trigger('change');

                // ✅ Change prmm_supplier_id to selected supplier
                if(selectedSupplier) {
                    let supplierOption = new Option(
                        selectedSupplierText,
                        selectedSupplier,
                        true,
                        true
                    );

                    $('#prmm_supplier_id')
                        .append(supplierOption)
                        .trigger('change');
                }

                toastr.success("SKU Added Successfully");

            } else {
                toastr.error(res.message);
            }
        }
    });
}

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
const calculate_transaction = () => {
  let qty = $("#qty").val();
  if (isNaN(qty) || qty == "") qty = 0;

  let rate = $("#rate").val();
  if (isNaN(rate) || rate == "") rate = 0;

  let amt = parseFloat(qty) * parseFloat(rate);
  if (isNaN(amt) || amt == "") amt = 0;
  $("#amt").val(amt.toFixed(2));

  let taxable_amt = parseFloat(amt);
  if (isNaN(taxable_amt) || taxable_amt == "") taxable_amt = 0;
  $("#taxable_amt").val(taxable_amt.toFixed(2));

  let extra_amt = $("#extra_amt").val();
  if (isNaN(extra_amt) || extra_amt == "") extra_amt = 0;

  let actual_taxable_amt = parseFloat(taxable_amt) + parseFloat(extra_amt);
  if (isNaN(actual_taxable_amt) || actual_taxable_amt == "") actual_taxable_amt = 0;
  $("#actual_taxable_amt").val(actual_taxable_amt.toFixed(2));

  let total_amt =
    parseFloat(actual_taxable_amt);
  if (isNaN(total_amt) || total_amt == "") total_amt = 0;
  $("#total_amt").val(total_amt.toFixed(2));
  set_cost_char();
};
const calculate_master = () => {
  let total_qty = 0;
  let total_sub_amt = 0;
  let total_taxable_amt = 0;
  let total_extra_amt = 0;
  let total_total_amt = 0;

  trans_data.forEach((value, index) => {
    const {
      prmt_qty,
      prmt_amt,
      prmt_taxable_amt,
      prmt_extra_amt,
      prmt_total_amt,
    } = value;

    total_qty = parseInt(total_qty) + parseInt(prmt_qty);
    if (isNaN(total_qty) || total_qty == "") total_qty = 0;

    total_sub_amt = parseFloat(total_sub_amt) + parseFloat(prmt_amt);
    if (isNaN(total_sub_amt) || total_sub_amt == "") total_sub_amt = 0;

    total_extra_amt = parseFloat(total_extra_amt) + parseFloat(prmt_extra_amt);
    if (isNaN(total_extra_amt) || total_extra_amt == "") total_extra_amt = 0;

    total_taxable_amt =
      parseFloat(total_taxable_amt) + parseFloat(prmt_extra_amt) + parseFloat(prmt_taxable_amt);
    if (isNaN(total_taxable_amt) || total_taxable_amt == "")
      total_taxable_amt = 0;

    total_total_amt = parseFloat(total_total_amt) + parseFloat(prmt_total_amt);
    if (isNaN(total_total_amt) || total_total_amt == "") total_total_amt = 0;
  });
  $("#prmm_total_qty").val(total_qty);
  $("#prmm_sub_amt").val(total_sub_amt.toFixed(2));
  $("#prmm_taxable_amt").val(total_taxable_amt.toFixed(2));
  $("#prmm_extra_amt").val(total_extra_amt.toFixed(2));

  let total_amt = parseFloat(total_total_amt);
  if (isNaN(total_amt) || total_amt == "") total_amt = 0;

  let after_decimal = parseFloat("0." + total_amt.toString().split(".")[1]);
  after_decimal = after_decimal.toFixed(2);
  after_decimal = after_decimal == 1 ? 0 : after_decimal;
  $("#prmm_round_off").val(after_decimal);

  $("#prmm_total_amt").val(Math.round(total_amt));

  if (total_amt > 0) {
    $(".master_block_btn").prop("disabled", false);
    notifier("prmm_total_amt");
  } else {
    $(".master_block_btn").prop("disabled", true);
    notifier("prmm_total_amt", "Required");
  }
};
const check_transaction = () => { 
  let prmt_id = 0;
  let brmm_id = 0;
  let flag = true;
  if (trans_data.length > 0) {
    trans_data.forEach((value) => {
      const { prmt_qty, prmt_rate, prmt_amt } =
        value;
     
      if (prmt_qty == 0 || prmt_qty == "") {
        prmt_id = id;
        flag = false;
      } else if (prmt_qty < 0) {
        prmt_id = id;
        flag = false;
      } else {
      }

      if (prmt_rate == 0 || prmt_rate == "") {
        prmt_id = id;
        flag = false;
      } else if (prmt_rate < 0) {
        prmt_id = id;
        flag = false;
      } else {
      }

      if (prmt_amt == 0 || prmt_amt == "") {
        prmt_id = id;
        flag = false;
      } else if (prmt_amt < 0) {
        prmt_id = id;
        flag = false;
      } else {
      }
    });
  }
  if (!flag) {
    if (prmt_id != 0 && brmm_id != 0) {
      //   $(`#brmm_roll_no_${brmm_id}`).focus();
      //   if ($(`#brmm_roll_no_${brmm_id}`).length) {
      //     $(window).scrollTop(
      //       $(`#brmm_roll_no_${brmm_id}`).offset().top - $(window).height() / 2
      //     );
      //   }
    }
  }
  return flag;
};
const add_transaction = () => {
  remove_master_notifier();
  let check = true;

  if ($("#prmm_supplier_id").val() == null) {
    notifier("prmm_supplier_id", "Required");
    check = false;
  }
  if ($("#sku_id").val() == null) {
    notifier("sku_id", "Required");
    check = false;
  }
  
  if ($("#qty").val() == "" || $("#qty").val() == 0) {
    notifier("qty", "Required");
    check = false;
  } else {
    if ($("#qty").val() < 0) {
      notifier("qty", "Invalid qty");
      check = false;
    }
  }
  if ($("#rate").val() == "" || $("#rate").val() == 0) {
    notifier("rate", "Required");
    check = false;
  } else {
    if ($("#rate").val() < 0) {
      notifier("rate", "Invalid rate");
      check = false;
    }
  }

  if ($("#amt").val() == "" || $("#amt").val() == 0) {
    notifier("amt", "Required");
    check = false;
  } else {
    if ($("#amt").val() < 0) {
      notifier("amt", "Invalid amt");
      check = false;
    }
  }
  if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
  } else {
    let prmt_id = $("#prmt_id").val();
    const path = `${link}/${sub_link}/handler`;
    let form_data = $(`#_form`).serialize();
    form_data += `&func=add_transaction`;
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          if (data && data.length != 0) {
            if (prmt_id == 0) {
              trans_data.unshift(data);
              add_wrapper_data(data);
              toastr.success(
                `${$("#sku_id :selected").text()}`,
                "ITEM ADDED TO LIST.",
                { closeButton: true, progressBar: true }
              );
            } else {
              let index = trans_data.findIndex((value) => value.prmt_id == prmt_id);
              if (index > -1) {
                trans_data[index].prmt_sku_id = data["prmt_sku_id"];
                trans_data[index].sku_name = data["sku_name"];
                $(`#sku_name_${prmt_id}`).html(data["sku_name"]);

                trans_data[index].prmt_size_id = data["prmt_size_id"];
                trans_data[index].size_name = data["size_name"];
                $(`#size_name_${prmt_id}`).html(data["size_name"]);

                trans_data[index].prmt_cost_char = data["prmt_cost_char"];
                trans_data[index].prmt_mrp = data["prmt_mrp"];
                trans_data[index].prmt_qty = data["prmt_qty"];
                trans_data[index].prmt_rate = data["prmt_rate"];
                trans_data[index].prmt_amt = data["prmt_amt"];
                trans_data[index].prmt_taxable_amt = data["prmt_taxable_amt"];

                trans_data[index].prmt_extra_amt = data["prmt_extra_amt"];
                trans_data[index].prmt_actual_taxable_amt = data["prmt_actual_taxable_amt"];

                trans_data[index].prmt_total_amt = data["prmt_total_amt"];
                trans_data[index].prmt_description = data["prmt_description"];


                $(`#mrp_${prmt_id}`).html(data["prmt_mrp"]);
                $(`#qty_${prmt_id}`).html(data["prmt_qty"]);
                $(`#rate_${prmt_id}`).html(data["prmt_rate"]);
                $(`#amt_${prmt_id}`).html(data["prmt_amt"]);
                $(`#taxable_amt_${prmt_id}`).html(data["prmt_taxable_amt"]);

                $(`#extra_amt_${prmt_id}`).html(data["prmt_extra_amt"]);

                $(`#total_amt_${prmt_id}`).html(data["prmt_total_amt"]);
                $(`#description_${prmt_id}`).html(data["prmt_description"]);

                toastr.success(
                  `${$("#sku_id :selected").text()}`,
                  "ITEM UPDATED TO LIST.",
                  { closeButton: true, progressBar: true }
                );
              }
            }
            $("#amt").val(0);
            $("#prmt_id").val(0);
            calculate_transaction(true);
            calculate_master(true);
            $("#transaction_count").html(trans_data.length);
          }
        }
      },
      (errmsg) => {}
    );
  }
};
const add_wrapper_data = (data, append = false) => {
  let prmm_id = $("#id").val();
  const {
    encrypt_prmt_id,
    prmt_id,
    sku_name,
    size_name,
    prmt_mrp,
    prmt_qty,
    prmt_rate,
    prmt_amt,
    prmt_taxable_amt,
    prmt_extra_amt,
    prmt_total_amt,
    prmt_description,
    isExist,
  } = data;
  let tr = `<tr id="row_${prmt_id}">
                <td id="sku_name_${prmt_id}">${sku_name}</td>
                <td id="size_name_${prmt_id}">${size_name}</td>
                <td id="mrp_${prmt_id}">${prmt_mrp}</td>
                <td id="qty_${prmt_id}">${prmt_qty}</td>
                <td id="rate_${prmt_id}">${prmt_rate}</td>
                <td id="amt_${prmt_id}">${prmt_amt}</td>
                <td class="d-none" id="taxable_amt_${prmt_id}">${prmt_taxable_amt}</td>
                 <td id="extra_amt_${prmt_id}">${prmt_extra_amt}</td>
                <td id="total_amt_${prmt_id}">${prmt_total_amt}</td>
                <td id="description_${prmt_id}">${prmt_description}</td>
                <td>
                    <div class="navigationn_wrapper">
                        <div class="navigationn">
                            <div class="menuToggle" id="menu_toggle_${prmt_id}" onclick="toggle_menuu(this)"></div>
                            <div class="menuu">
                                <ul>
                                    ${
                                      prmm_id != 0 
                                        ? `<li>
                                                <a 
                                                    type="button" 
                                                    class="btn btn-sm" 
                                                    target="_blank" 
                                                    href="${base_url}/${link}/${sub_link}?action=barcode&clause=brmm.brmm_prmt_id&id=${encrypt_prmt_id}"
                                                    ><i class="text-info fa fa-barcode"></i></a>
                                            </li>`
                                        : ``
                                    }
                                    ${ !isExist 
                                        ? `<li>
                                                <a 
                                                    type="button" 
                                                    class="btn btn-md" 
                                                    onclick="edit_transaction(${prmt_id})"
                                                    ><i class="text-success fa fa-edit"></i></a>
                                            </li>`
                                        :``
                                    }
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
                                                onclick="remove_transaction(${prmt_id})"
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
  if ($(`#prmm_supplier_id`).val() == null) {
    notifier(`prmm_supplier_id`, "Required");
    check = false;
  }
  if ($(`#prmm_total_amt`).val() <= 0 || $(`#prmm_total_amt`).val() == "") {
    notifier(`prmm_total_amt`, "Required");
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
    let form_id = document.getElementById("_form");
    let form_data = new FormData(form_id);
    form_data.append("func", "add_edit");
    form_data.append("trans_data", JSON.stringify(trans_data));
    fileUpAjaxCall(
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
          Swal.fire({
            title:
              '<span class="text-info">Do you want to print barcode?</span>',
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Yes",
          }).then((result) => {
            if (result.isConfirmed) {
              window.open(
                `${base_url}/${link}/${sub_link}?action=barcode&clause=brmm.brmm_prmm_id&id=${data.id}`,
                "_blank",
                "width=1024, height=768"
              );
            }
            window.location.reload();
          });
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
  notifier(`prmm_supplier_id`);
  notifier(`prmm_total_amt`);
};
const remove_master_notifier = () => {
  notifier("sku_id");
  notifier("qty");
  notifier("rate");
  notifier("amt");
};
const remove_transaction = (prmt_id) => {
  trans_data = trans_data.filter((value) => value.prmt_id != prmt_id);
  let sku_name = $(`#sku_name_${prmt_id}`).html();
  toastr.success(`${sku_name}`, "ITEM REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#row_${prmt_id}`).detach();
  $("#transaction_count").html(trans_data.length);
  calculate_master();
};
const edit_transaction = (prmt_id) => {
  const find = trans_data.find((value) => value["prmt_id"] == prmt_id);
  const {
    prmt_sku_id,
    sku_name,
    prmt_size_id,
    size_name,
    prmt_mrp,
    prmt_qty,
    prmt_rate,
    prmt_amt,
    prmt_taxable_amt,
    prmt_extra_amt,
    prmt_total_amt,
    prmt_description,
    prmt_cost_char,
  } = find;
  $("#prmt_id").val(prmt_id);
  $("#cost_char").val(prmt_cost_char);
 
  $("#sku_id").html(
    `<option value="${prmt_sku_id}">${sku_name}</option>`
  );

  $("#size_id").html(
    `<option value="${prmt_size_id}">${size_name}</option>`
  );
  
  $("#mrp").val(prmt_mrp);
  $("#qty").val(prmt_qty);
  $("#rate").val(prmt_rate);
  $("#amt").val(prmt_amt);
  $("#taxable_amt").val(prmt_taxable_amt);
  $("#extra_amt").val(prmt_extra_amt);
  $("#total_amt").val(prmt_total_amt);
  $("#description").val(prmt_description);
  toggle_menuu({ id: prmt_id });
};

const record_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.prmm_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">entry no : </td>
                          <td width="70%">${data.prmm_entry_no}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">entry date : </td>
                          <td width="70%">${data.prmm_entry_date}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">supplier : </td>
                          <td width="70%">${data.supplier_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">total amt : </td>
                          <td width="70%">${data.prmm_total_amt}</td>
                      </tr>
                  </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};
// core_functions

// additional_functions
const get_supplier_data = (id) => {
  if (id) {
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
          calculate_master(false);
        }
      },
      (errmsg) => {}
    );
  }
};

const get_readymade_category_data_for_trans = (prmt_id, id) => {
  calculate_master();
  if (id) {
    const path = `master/readymade_category/handler`;
    const form_data = { func: "get_data", id };
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          if (data && data.length != 0) {
            calculate_master();
          }
        }
      },
      (errmsg) => {}
    );
  }
}; 
const set_cost_char = () => {
  let cost_char = "";
  let rate = $(`#rate`).val();
  if (isNaN(rate) || rate == "") rate = 0;
  if (rate.length != 0) {
    for (let pos = 0; pos < rate.length; pos++) {
      let char = rate.charAt(pos);
      
      cost_char += char == "." ? "." : $(`#cost_char_${char}`).val();
    }
  }
  $(`#cost_char`).val(cost_char);
};
// additional_functions