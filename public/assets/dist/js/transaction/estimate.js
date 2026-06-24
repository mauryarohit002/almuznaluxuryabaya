$(document).ready(function () {
  $(`#om_billing_id`)
    .select2(
      select2_default({
        url: `master/customer/get_select2/_id`,
        placeholder: "select",
        param: true,
      })
    ) 
    .on("change", (event) => get_customer_data(event.target.value)); 

  $(`#om_customer_id`).select2( 
    select2_default({
      url: `master/customer/get_select2/_id`,
      placeholder: "select",
      param: true,
    })
  );
  
  $(`#om_salesman_id`).select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_user_id`,
      placeholder: "select",
      param: true,
      param1:()=>'salesman'
    })
  );

  $(`#bm_id`)
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_bm_id`,
        placeholder: "scan",
        barcode: "bm_id",
      })
    )
    .on("change", (event) => get_barcode_data(event.target.value));

  $(`#brmm_id`)
  .select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_brmm_id`,
      placeholder: "scan",
      barcode: "brmm_id",
      param: () => $("#trans_type").val(),
    })
  )
  .on("change", function (event) {

    let brmm_id = event.target.value;
    if (!brmm_id) return;

    $.ajax({
      url: `${sub_link}/check_brmm_exists`,
      type: "POST",
      data: { brmm_id: brmm_id },
      dataType: "json",
      success: function (res) {

        if (res.exists && res.branch_match) {
            get_readymade_barcode_data(brmm_id);
        } else {
            alert(res.message || "Invalid barcode");

            $('#brmm_id').val(null).trigger('change.select2');
            return false;
        }

      }
    });

  });

  $(`#sku_id`).select2( 
      select2_default({
        url: `${link}/${sub_link}/get_select2/_sku_id`,
        placeholder: "select",
        param: true,
      })
    ).on('change', function () {
        $.post(`${sub_link}/get_apparel_from_sku`, {
          sku_id: $(this).val()
        }, function (res) {

          if (res.apparel_id) {

            $('#apparel_id').empty(); // clear old

            const option = new Option(
              res.apparel_name,
              res.apparel_id,
              true,
              true
            );

            $('#apparel_id')
              .append(option)
              .trigger('change');

            $("#rate").val(res.sku_mrp);
            // $("#cp").val(res.sku_cp);

            calculate_transaction();
          }
        }, 'json');
      });

  $(`#apparel_id`)
    .select2(
      select2_default({
        url: `master/apparel/get_select2/_id`,
        placeholder: "select",
        param: true,
      })
    )
    .on("change", (event) => get_apparel_data(event.target.value));

    // size
    $('#size_id').select2(
        select2_default({
            url: `master/size/get_select2/_id`,
            placeholder: "SELECT",
            param: true,
        })
    );

    set_area();
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
const calculate_transaction = (fromDiscPer = false) => {
  let qty = $("#qty").val();
  if (isNaN(qty) || qty == "") qty = 0;

  let rate = $("#rate").val();
  if (isNaN(rate) || rate == "") rate = 0;

  let amt = parseFloat(rate);
  if ($("#trans_type").val() == "READYMADE" || $("#trans_type").val() == "CUSTOM")
    amt = parseFloat(qty) * parseFloat(rate);
  if (isNaN(amt) || amt == "") amt = 0;
  $("#amt").val(amt.toFixed(2));

  let disc_per = $("#disc_per").val();
  if (isNaN(disc_per) || disc_per == "") disc_per = 0;

  let disc_amt = $("#disc_amt").val();
  if (isNaN(disc_amt) || disc_amt == "") disc_amt = 0;

  if (fromDiscPer) {
    disc_amt = (parseFloat(amt) * parseFloat(disc_per)) / 100;
    if (isNaN(disc_amt) || disc_amt == "") disc_amt = 0;
    $("#disc_amt").val(disc_amt.toFixed(0));
  } else {
    disc_per = (parseFloat(disc_amt) * 100) / parseFloat(amt);
    if (isNaN(disc_per) || disc_per == "") disc_per = 0;
    $("#disc_per").val(disc_per.toFixed(2));
  }

  let taxable_amt = parseFloat(amt) - parseFloat(disc_amt);
  if (isNaN(taxable_amt) || taxable_amt == "") taxable_amt = 0;

  $("#taxable_amt").val(taxable_amt.toFixed(2));

  let total_amt =
    parseFloat(taxable_amt);
  if (isNaN(total_amt) || total_amt == "") total_amt = 0;
  $("#total_amt").val(total_amt.toFixed(2));
};
const calculate_master = (fromDiscPer = false) => {
  let total_qty = 0;
  let total_sub_amt = 0;
  let total_disc_amt = 0;
  let total_taxable_amt = 0;
  let total_total_amt = 0;
  trans_data.forEach((value, index) => {
    const {
      ot_id,
      ot_qty,
      ot_amt,
      ot_disc_amt,
    } = value;

    let taxable_amt = parseFloat(ot_amt) - parseFloat(ot_disc_amt);
    if (isNaN(taxable_amt) || taxable_amt == "") taxable_amt = 0;

    let total_amt =
      parseFloat(taxable_amt);
    if (isNaN(total_amt) || total_amt == "") total_amt = 0;

    taxable_amt = taxable_amt > 0 ? taxable_amt.toFixed(2) : 0.0;
    total_amt = total_amt > 0 ? total_amt.toFixed(2) : 0.0;

    trans_data[index].ot_taxable_amt = taxable_amt;
    trans_data[index].ot_total_amt = total_amt;

    $(`#taxable_amt_${ot_id}`).html(taxable_amt);
    $(`#total_amt_${ot_id}`).html(total_amt);

    total_qty = parseFloat(total_qty) + parseFloat(ot_qty);
    if (isNaN(total_qty) || total_qty == "") total_qty = 0;

    total_sub_amt = parseFloat(total_sub_amt) + parseFloat(ot_amt);
    if (isNaN(total_sub_amt) || total_sub_amt == "") total_sub_amt = 0;

    total_disc_amt = parseFloat(total_disc_amt) + parseFloat(ot_disc_amt);
    if (isNaN(total_disc_amt) || total_disc_amt == "") total_disc_amt = 0;

    total_taxable_amt = parseFloat(total_taxable_amt) + parseFloat(taxable_amt);
    if (isNaN(total_taxable_amt) || total_taxable_amt == "")
      total_taxable_amt = 0;

    total_total_amt = parseFloat(total_total_amt) + parseFloat(total_amt);
    if (isNaN(total_total_amt) || total_total_amt == "") total_total_amt = 0;
  });

  $("#om_total_qty").val(total_qty);
  $("#om_sub_amt").val(total_sub_amt.toFixed(2));
  $("#om_disc_amt").val(total_disc_amt.toFixed(2));
  $("#om_taxable_amt").val(total_taxable_amt.toFixed(2));
  $("#om_total_amt").val(total_total_amt.toFixed(2));

  let bill_disc_per = $(`#om_bill_disc_per`).val();
  if (isNaN(bill_disc_per) || bill_disc_per == "") bill_disc_per = 0;

  let bill_disc_amt = $(`#om_bill_disc_amt`).val();
  if (isNaN(bill_disc_amt) || bill_disc_amt == "") bill_disc_amt = 0;

  if (fromDiscPer) {
    bill_disc_amt =
      (parseFloat(total_total_amt) * parseFloat(bill_disc_per)) / 100;
    if (isNaN(bill_disc_amt) || bill_disc_amt == "") bill_disc_amt = 0;
    $(`#om_bill_disc_amt`).val(bill_disc_amt.toFixed(2));
  } else {
    bill_disc_per =
      (parseFloat(bill_disc_amt) * 100) / parseFloat(total_total_amt);
    if (isNaN(bill_disc_per) || bill_disc_per == "") bill_disc_per = 0;
    $(`#om_bill_disc_per`).val(bill_disc_per.toFixed(2));
  }

  let round_off = $(`#om_round_off`).val();
  if (isNaN(round_off) || round_off == "") round_off = 0;

  let total_amt =
    parseFloat(total_total_amt) -
    (parseFloat(round_off) + parseFloat(bill_disc_amt));
  if (isNaN(total_amt) || total_amt == "") total_amt = 0;
  $("#om_total_amt").val(total_amt.toFixed(2));

  let advance_amt = parseFloat($(`#om_advance_amt`).val());
  if (isNaN(advance_amt) || advance_amt == "") advance_amt = 0;

  let balance_amt = parseFloat(total_amt) - parseFloat(advance_amt);
  if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;
  $("#om_balance_amt").val(balance_amt.toFixed(2));

  if (total_amt > 0) {
    $(".master_block_btn").prop("disabled", false);
    notifier("om_total_amt");
  } else {
    $(".master_block_btn").prop("disabled", true);
    notifier("om_total_amt", "Required");
  }
};
const check_transaction = () => {
  let ot_id = 0;
  let bm_id = 0;
  let flag = true;
  if (trans_data.length > 0) {
    trans_data.forEach((value) => {
      const { ot_trans_type, ot_qty, ot_rate, ot_amt } =
        value;
      if (ot_trans_type == "READYMADE" || ot_trans_type == "CUSTOM") {
        if (ot_qty == 0 || ot_qty == "") {
          ot_id = id;
          flag = false;
        } else if (ot_qty < 0) {
          ot_id = id;
          flag = false;
        } else {
        }
      }

      if (ot_rate == 0 || ot_rate == "") {
        ot_id = id;
        flag = false;
      } else if (ot_rate < 0) {
        ot_id = id;
        flag = false;
      } else {
      }

      if (ot_amt == 0 || ot_amt == "") {
        ot_id = id;
        flag = false;
      } else if (ot_amt < 0) {
        ot_id = id;
        flag = false;
      } else {
      }
    });
  }
  if (!flag) {
    if (ot_id != 0 && bm_id != 0) {
      // $(`#bm_roll_no_${bm_id}`).focus();
      // if ($(`#bm_roll_no_${bm_id}`).length) {
      //   $(window).scrollTop(
      //     $(`#bm_roll_no_${bm_id}`).offset().top - $(window).height() / 2
      //   );
      // }
    }
  }
  return flag;
};
const add_transaction = () => { 
  remove_master_notifier();
  let check = true;
  if ($("#om_billing_id").val() == null) {
    notifier("om_billing_id", "Required");
    check = false;
    $("body, html").animate({ scrollTop: 0 }, 1000);
  }
  if ($("#om_customer_id").val() == null) {
    notifier("om_customer_id", "Required");
    check = false;
    $("body, html").animate({ scrollTop: 0 }, 1000);
  }
  if ($("#trans_type").val() == "READYMADE" || $("#trans_type").val() == "CUSTOM") {
    if ($("#qty").val() == "" || $("#qty").val() == 0) {
      notifier("qty", "Required");
      check = false;
    } else {
      if ($("#qty").val() < 0) {
        notifier("qty", "Invalid qty");
        check = false;
      }else{
        if($("#trans_type").val() == "READYMADE" || $("#trans_type").val() == "CUSTOM"){
          if (
            $("#brmm_id").val() != null &&
            $("#brmm_id").val() != 0 &&
            $("#brmm_id").val() != ""
          ) {
            if (
              parseFloat($("#qty").val()) >
              parseFloat($("#available_qty").val())
            ) {
              toastr.error("Qty should less than available Qty.", "", {
                closeButton: true,
                progressBar: true,
                preventDuplicates: true,
              });
              return false;
            }
          }
        }
      }
    }
    if($("#trans_type").val() == "CUSTOM"){
      if ($("#sku_id").val() == null ||
            $("#sku_id").val() == 0 ||
            $("#sku_id").val() == "") {
        notifier("sku_id", "Required");
        check = false;
      }
      if ($("#apparel_id").val() == null ||
            $("#apparel_id").val() == 0 ||
            $("#apparel_id").val() == "") {
        notifier("apparel_id", "Required");
        check = false;
      }
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
    let ot_id = $("#ot_id").val();
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
            if (ot_id == 0) {
              trans_data.unshift(data);
              add_wrapper_data(data);
              toastr.success(
                `${$("#trans_type :selected").text()}`,
                "ITEM ADDED TO LIST.",
                { closeButton: true, progressBar: true }
              );
              
              if (data["apparel_data"]?.length) { 
                get_sku_measurement_data(data["ot_id"]);
              } else {
                if (data["ot_apparel_id"] > 0) get_measurement_data(data["ot_id"]);
              }

            } else {
              let index = trans_data.findIndex((value) => value.ot_id == ot_id);
              if (index < 0) {
                toastr.success(`Transaction not found`, "", {
                  closeButton: true,
                  progressBar: true,
                });
              }
              trans_data[index].ot_apparel_id = data["ot_apparel_id"];
              trans_data[index].apparel_name = data["apparel_name"];
              trans_data[index].ot_size_id = data["ot_size_id"];
              trans_data[index].size_name = data["size_name"];
              trans_data[index].ot_bm_id = data["ot_bm_id"];
              trans_data[index].ot_brmm_id = data["ot_brmm_id"];
              trans_data[index].item_code = data["item_code"];
              trans_data[index].ot_trans_type = data["ot_trans_type"];
              trans_data[index].ot_qty = data["ot_qty"];
              trans_data[index].ot_rate = data["ot_rate"];
              trans_data[index].ot_amt = data["ot_amt"];
              trans_data[index].ot_disc_per = data["ot_disc_per"];
              trans_data[index].ot_disc_amt = data["ot_disc_amt"];
              trans_data[index].ot_taxable_amt = data["ot_taxable_amt"];
              trans_data[index].ot_total_amt = data["ot_total_amt"];
              trans_data[index].ot_description = data["ot_description"];

              $(`#apparel_name_${ot_id}`).html(data["apparel_name"]);
              // $(`#size_name_${ot_id}`).html(data["size_name"]);
              $(`#item_code_${ot_id}`).html(data["item_code"]);
              $(`#trans_type_${ot_id}`).html(data["ot_trans_type"]);
              $(`#qty_${ot_id}`).html(data["ot_qty"]);
              $(`#rate_${ot_id}`).html(data["ot_rate"]);
              $(`#amt_${ot_id}`).html(data["ot_amt"]);
              $(`#disc_per_${ot_id}`).html(data["ot_disc_per"]);
              $(`#disc_amt_${ot_id}`).html(data["ot_disc_amt"]);
              $(`#taxable_amt_${ot_id}`).html(data["ot_taxable_amt"]);
              $(`#total_amt_${ot_id}`).html(data["ot_total_amt"]);
              $(`#description_${ot_id}`).html(data["ot_description"]);
              toastr.success(
                `${$("#fabric_id :selected").text()}`,
                "ITEM UPDATED TO LIST.",
                { closeButton: true, progressBar: true }
              );
            }
            $("#bm_id").val(null).trigger("change");
            $("#bm_id").select2("close");
            $("#sku_id").val(null).trigger("change");
            $("#sku_id").select2("close");
            // $("#size_id").val(null).trigger("change");
            // $("#size_id").select2("close");
            $("#apparel_id").val(null).trigger("change");
            $("#apparel_id").select2("close");
            $("#brmm_id").val(null).trigger("change");
            $("#brmm_id").select2("close");

            if ($("#trans_type").val() == "READYMADE" || $("#trans_type").val() == "CUSTOM") {
              $("#qty").val(0);
              $("#qty").attr("onkeyup", "calculate_transaction()");
              $("#qty").removeAttr("readonly");

              $("#amt_area")
                .removeClass("col-md-6")
                .removeClass("col-lg-6")
                .addClass("col-md-12")
                .addClass("col-lg-12");
            } else {
              $("#qty").val(1);
              $("#qty").attr("readonly", "readonly");
              $("#qty").removeAttr("onkeyup");

              $("#amt_area")
                .removeClass("col-md-12")
                .removeClass("col-lg-12")
                .addClass("col-md-6")
                .addClass("col-lg-6");
            }
            $("#rate").val(0);
            $("#ot_id").val(0);
            
            // Enable trans_type again for new entry
            $("#trans_type").next(".select2-container").removeClass("select2-lock");
            $("#brmm_id").next(".select2-container").removeClass("select2-lock");
            
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
  const {
    ot_id,
    ot_om_id,
    ot_trans_type,
    ot_sku_id,
    sku_name,
    ot_apparel_id,
    apparel_name,
    item_code,
    ot_size_id,
    size_name,
    ot_qty,
    ot_rate,
    ot_amt,
    ot_disc_per,
    ot_disc_amt,
    ot_taxable_amt,
    ot_total_amt,
    ot_description,
    apparel_data,
    isExist,
  } = data;
  let tr = `<tr id="row_${ot_id}">
                <td id="serial_no_${ot_id}"></td>
                <td id="trans_type_${ot_id}">${ot_trans_type}</td>
                <td id="sku_name_${ot_id}">${sku_name}</td>
                <td id="apparel_name_${ot_id}">${apparel_name}</td>
                <td id="item_code_${ot_id}">${item_code}</td>
                <!-- <td id="size_name_${ot_id}">${size_name}</td> -->
                <td id="qty_${ot_id}">${ot_qty}</td>
                <td id="rate_${ot_id}">${ot_rate}</td>
                <td id="amt_${ot_id}">${ot_amt}</td>
                <td id="disc_per_${ot_id}">${ot_disc_per}</td>
                <td id="disc_amt_${ot_id}">${ot_disc_amt}</td>
                <td class="d-none" id="taxable_amt_${ot_id}">${ot_taxable_amt}</td>
                <td id="total_amt_${ot_id}">${ot_total_amt}</td>
                <td id="description_${ot_id}">${ot_description}</td>
                <td>
                  <div class="navigationn_wrapper">
                    <div class="navigationn">
                      <div class="menuToggle" id="menu_toggle_${ot_id}" onclick="toggle_menuu(this)"></div>
                      <div class="menuu">
                        <ul>
                          <li>
                            <a 
                              type="button" 
                              class="btn btn-md" 
                              target="_blank"
                              href="${base_url}/${link}/${sub_link}/measurement_print/${ot_om_id}/${ot_id}"
                              ><i class="text-info fa fa-print"></i></a>
                          </li>
                         
                         ${apparel_data?.length
                            ?
                            `<li>
                                  <a 
                                    type="button" 
                                    class="btn btn-md" 
                                    onclick="get_sku_measurement_data(${ot_id})"
                                    ><i class="text-info fa fa-eye"></i></a>
                                </li>`
                            :
                            ot_apparel_id > 0
                              ? `<li>
                                    <a 
                                      type="button" 
                                      class="btn btn-md" 
                                      onclick="get_measurement_data(${ot_id})"
                                      ><i class="text-info fa fa-eye"></i></a>
                                  </li>`
                              : ``
                          }

                          ${
                            isExist
                              ? ``
                              : `<li>
                                    <a 
                                        type="button" 
                                        class="btn btn-md" 
                                        onclick="edit_transaction(${ot_id})"
                                        ><i class="text-success fa fa-edit"></i></a>
                                </li>`
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
                                    onclick="remove_transaction(${ot_id})"
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
  if ($(`#om_billing_id`).val() == null) {
    notifier(`om_billing_id`, "Required");
    check = false;
  }
  if ($(`#om_customer_id`).val() == null) {
    notifier(`om_customer_id`, "Required");
    check = false;
  }
  if ($(`#om_total_amt`).val() <= 0 || $(`#om_total_amt`).val() == "") {
    notifier(`om_total_amt`, "Required");
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
    toastr.error("You forgot to enter some item information.", "Oh snap!!!!", {
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
        const { status, data, msg } = resp;
        if (handle_response(resp)) {
          if (id == 0) {
          } else {
          }
          Swal.fire({
            title: '<span class="text-info">Do you want to print bill?</span>',
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Yes",
          }).then((result) => {
            if (result.isConfirmed) {
              window.open(
                `${base_url}/${link}/${sub_link}?action=print&id=${data.id}`,
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
  notifier(`om_billing_id`);
  notifier(`om_customer_id`);
  notifier(`om_total_amt`);
};
const remove_master_notifier = () => {
  notifier("fabric_id");
  notifier("design_id");
  notifier("color_id");
  notifier("qty");
  notifier("rate");
  notifier("amt");
};
const remove_transaction = (ot_id) => {
  trans_data = trans_data.filter((value) => value.ot_id != ot_id);
  let trans_type = $(`#trans_type_${ot_id}`).html();
  toastr.success(`${trans_type}`, "ITEM REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#row_${ot_id}`).detach();
  $("#transaction_count").html(trans_data.length);
  calculate_master();
};
const edit_transaction = (ot_id) => {
  const find = trans_data.find((value) => value["ot_id"] == ot_id);
  const {
    ot_om_id,
    ot_trans_type,
    ot_apparel_id,
    apparel_name,
    ot_sku_id,
    sku_name,
    ot_size_id,
    size_name,
    ot_bm_id,
    ot_brmm_id,
    item_code,
    ot_qty,
    ot_rate,
    ot_amt,
    ot_disc_per,
    ot_disc_amt,
    ot_taxable_amt,
    ot_total_amt,
    ot_description,
  } = find;
  
  // Disable trans_type again for new entry
  $("#trans_type").next(".select2-container").addClass("select2-lock");
  $("#brmm_id").next(".select2-container").addClass("select2-lock");

  $("#ot_id").val(ot_id);
  $("#trans_type").val(ot_trans_type).trigger("change");
  set_area();
  $("#sku_id").html(
    `<option value="${ot_sku_id}">${sku_name}</option>`
  );
  $("#apparel_id").html(
    `<option value="${ot_apparel_id}">${apparel_name}</option>`
  );
  $("#bm_id").html(`<option value="${ot_bm_id}">${item_code}</option>`);
  $("#brmm_id").html(`<option value="${ot_brmm_id}">${item_code}</option>`);

  //  $("#size_id").html(
  //   `<option value="${ot_size_id}">${size_name}</option>`
  // );
  $("#qty").val(ot_qty);
  if ($("#trans_type").val() == "READYMADE" || $("#trans_type").val() == "CUSTOM") {
    $("#qty").attr("onkeyup", "calculate_transaction()");
    $("#qty").removeAttr("readonly");
  } else {
    $("#qty").attr("readonly", "readonly");
    $("#qty").removeAttr("onkeyup");
  }
  $("#rate").val(ot_rate);
  $("#available_qty").val(0);
  $("#amt").val(ot_amt);
  $("#disc_per").val(ot_disc_per);
  $("#disc_amt").val(ot_disc_amt);
  $("#taxable_amt").val(ot_taxable_amt);
  $("#total_amt").val(ot_total_amt);
  $("#description").val(ot_description);
  toggle_menuu({ id: ot_id });
  if (ot_bm_id > 0) {
    const path = `${link}/${sub_link}/handler`;
    const form_data = { func: "get_barcode_data", id: ot_bm_id };
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
        }
      },
      (errmsg) => {}
    );
  }
  if (ot_brmm_id > 0) {
    let qty = 0;
    if (ot_om_id == 0) {
      trans_data.forEach((value) => {
        if (value["ot_id"] != ot_id && value["ot_brmm_id"] == ot_brmm_id) {
          qty =
            parseFloat(qty) +
            parseFloat(-1) * parseFloat(value["ot_qty"]);
        }
      });
    } else {
      trans_data.forEach((value) => {
        if (
          value["ot_om_id"] != 0 &&
          value["ot_id"] == ot_id &&
          value["ot_brmm_id"] == ot_brmm_id
        ) {
          qty = parseFloat(qty) + parseFloat(value["ot_qty"]);
        }
        if (
          value["ot_om_id"] == 0 &&
          value["ot_id"] != ot_id &&
          value["ot_brmm_id"] == ot_brmm_id
        ) {
          qty = parseFloat(qty) - parseFloat(value["ot_qty"]);
        }
      });
    }
    const path = `${link}/${sub_link}/handler`;
    const form_data = { func: "get_readymade_barcode_data", id: ot_brmm_id, qty };
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          if (data && data.length != 0) {
            $("#available_qty").val(data[0]["bal_qty"]);
          }
        }
      },
      (errmsg) => {}
    ); 
  }
};
const record_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.om_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">estimate no : </td>
                    <td width="70%">${data.om_em_entry_no}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">estimate date : </td>
                    <td width="70%">${data.om_em_entry_date}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">customer : </td>
                    <td width="70%">${data.billing_name}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">total qty : </td>
                    <td width="70%">${data.om_total_qty}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">total amt : </td>
                    <td width="70%">${data.om_total_amt}</td>
                  </tr>
              </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
// core_functions

// additional_functions
const get_customer_data = (id) => {
  if (id) {
    const path = `${link}/${sub_link}/handler`;
    const form_data = { func: "get_customer_data", id };
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          if (data && data.length != 0) {
            if($(`#om_customer_id`).val()==null){
              let cname = $(`#om_billing_id :selected`).text();
              $(`#om_customer_id`).html(`<option value="${id}">${cname}</option>`);
            }
          }
        }
      },
      (errmsg) => {}
    );
  }
};
const get_barcode_data = (id) => {
  $("#rate").val(0);
  if (id) {
    const path = `${link}/${sub_link}/handler`;
    const form_data = { func: "get_barcode_data", id };
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          if (data && data.length != 0) {
            if(data[0]["bm_shirt_mrp"]>0){
                $("#rate").val(data[0]["bm_shirt_mrp"]);
            }else if(data[0]["bm_trouser_mrp"]>0){
                $("#rate").val(data[0]["bm_trouser_mrp"]);
            }else if(data[0]["bm_2pc_suit_mrp"]>0){ 
                $("#rate").val(data[0]["bm_2pc_suit_mrp"]); 
            }else if(data[0]["bm_3pc_suit_mrp"]>0){ 
                $("#rate").val(data[0]["bm_3pc_suit_mrp"]);         
            }else{
              $("#rate").val(data[0]["bm_mrp"]);
            }
            calculate_transaction();
          }
          toastr.success(data[0]["bm_item_code"], msg, {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
          });
          setTimeout(() => {
            // $("#bm_id").val(null).trigger("change");
            // $("#bm_id").select2("open");
          }, RELOAD_TIME);
        } else {
          setTimeout(() => {
            $("#bm_id").val(null).trigger("change");
            $("#bm_id").select2("open");
          }, RELOAD_TIME);
        }
      },
      (errmsg) => {
        setTimeout(() => {
          $("#bm_id").val(null).trigger("change");
          $("#bm_id").select2("open");
        }, RELOAD_TIME);
      }
    );
  }
};

const get_readymade_barcode_data = (id)=>{
    $("#rate").val(0);
    $("#available_qty").val(0);
    if (id) {
      let qty = trans_data.reduce((n, { ot_brmm_id, ot_qty }) => {
        return ot_brmm_id == id ? parseFloat(n) + parseFloat(ot_qty) : 0;
      }, 0);
      const path = `${link}/${sub_link}/handler`;
      const form_data = { func: "get_readymade_barcode_data", id, qty};
      ajaxCall(
        "POST",
        path,
        form_data,
        "JSON",
        (resp) => {
          if (handle_response(resp)) {
            const { data, msg } = resp;
            if (data && data.length != 0) {
              $("#rate").val(data[0]["brmm_mrp"]);
              $("#available_qty").val(data[0]["bal_qty"]);
              $("#qty").val(1);

              let id = data[0]["brmm_size_id"];
              let text = data[0]["size_name"]; // make sure you're returning this

              // let option = new Option(text, id, true, true);
              // $("#size_id").append(option).trigger("change");

              $("#add_row_btn").focus();
              calculate_transaction();
            }
            toastr.success(data[0]["brmm_item_code"], msg, {
              closeButton: true,
              progressBar: true,
              preventDuplicates: true,
            });
            setTimeout(() => {
              // $("#bm_id").val(null).trigger("change");
              // $("#bm_id").select2("open");
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
    }
}
const get_apparel_data = (id) => {
  if ($("#trans_type").val() == "CUSTOM") {
    $("#rate").val(0);
    if (id) {
      const path = `${link}/${sub_link}/handler`;
      const form_data = { func: "get_apparel_data", id };
      ajaxCall(
        "POST",
        path,
        form_data,
        "JSON",
        (resp) => {
          if (handle_response(resp)) {
            const { data, msg } = resp;
            if (data && data.length != 0) {
              // $("#rate").val(data[0]["charges"]);
              calculate_transaction();
            }
            toastr.success(data[0]["apparel_name"], msg, {
              closeButton: true,
              progressBar: true,
              preventDuplicates: true,
            });
            setTimeout(() => {
              // $("#bm_id").val(null).trigger("change");
              // $("#bm_id").select2("open");
            }, RELOAD_TIME);
          } else {
            setTimeout(() => {
              $("#bm_id").val(null).trigger("change");
              $("#bm_id").select2("open");
            }, RELOAD_TIME);
          }
        },
        (errmsg) => {
          setTimeout(() => {
            $("#bm_id").val(null).trigger("change");
            $("#bm_id").select2("open");
          }, RELOAD_TIME);
        }
      );
    }
  }
};

const get_measurement_data = (ot_id) => { 
  toggle_menuu({ id: ot_id });
  const customer_id = $("#om_customer_id").val();
  notifier("om_customer_id");
  if (customer_id == null) {
    notifier("om_customer_id", "Required");
    toastr.error("Select customer first.", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    return;
  }
  
  const find = trans_data.find((value) => value.ot_id == ot_id);
  const om_id = $("#id").val();
  const ids = [];
  ids.push({
    ot_id: find['ot_id'],
    apparel_id: parseInt(find['ot_apparel_id']),
    apparel_name: find['apparel_name']
  });
  if (find['ot_apparel_id']) {
    const path = `${link}/${sub_link}/handler`;
    const form_data = {
      func: "get_sku_measurement_data",
      om_id,
      customer_id,
      ids: JSON.stringify(ids),
    };
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          if (data && data.length) {
            let title = `<div class="px-2 mx-2" style="font-size: 0.8rem;">${get_sku_tab(data)}</div>`;
            let body = `<div class="d-flex flex-column font-weight-bold" style="font-size: 0.8rem;">${get_sku_tab_content(data)}</div>`;
            let footer = `<button 
                            type="button" 
                            id="sbt_btn" 
                            class="btn btn-sm btn-secondary btn-block text-uppercase mx-3" 
                            onclick="add_edit_measurement(${ot_id})"
                          >add</button>
                          <button 
                            type="button" 
                            id="cnl_btn" 
                            class="btn btn-sm btn-secondary btn-block text-uppercase mx-3 mt-0" 
                            onclick="toggle_measurement_popup()"
                        >close</button>`;
            $(`#measurement_wrapper .top-panel-title`).html(title);
            $(`#measurement_wrapper .top-panel-body`).html(body);
            $(`#measurement_wrapper .top-panel-footer`).html(footer);
            toggle_measurement_popup();
            lazy_loading(`form_loading`);
          }
        } else {
          setTimeout(() => {
            $("#apparel_id").val(null).trigger("change");
            $("#apparel_id").select2("open");
          }, RELOAD_TIME);
        }
      },
      (errmsg) => {
        setTimeout(() => {
          $("#apparel_id").val(null).trigger("change");
          $("#apparel_id").select2("open");
        }, RELOAD_TIME);
      }
    );
  }
};

const get_sku_measurement_data = (ot_id) => {
  toggle_menuu({ id: ot_id });
  const customer_id = $("#om_customer_id").val();
  notifier("om_customer_id");
  if (customer_id == null) {
    notifier("om_customer_id", "Required");
    toastr.error("Select customer first.", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    return;
  }
  let temp = trans_data.find(value => value['ot_id'] == ot_id);
  if (temp['apparel_data'].length <= 0) {
    toastr.error("Apparel not defined in sku.", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    return;
  }
  const om_id = $("#id").val();
  const ids = [];
  temp['apparel_data'].forEach(value => {
    ids.push({ ot_id: value['ot_id'], apparel_id: parseInt(value['ot_apparel_id']), apparel_name: value['apparel_name'] });
  });
  const path = `${link}/${sub_link}/handler`;
  const form_data = {
    func: "get_sku_measurement_data",
    om_id,
    customer_id,
    ids: JSON.stringify(ids),
  };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length) {
          let title = `<div class="px-2 mx-2" style="font-size: 0.8rem;">${get_sku_tab(data)}</div>`;
          let body = `<div class="d-flex flex-column font-weight-bold" style="font-size: 0.8rem;">${get_sku_tab_content(data)}</div>`;
          let footer = `<button 
                          type="button" 
                          id="sbt_btn" 
                          class="btn btn-sm btn-secondary btn-block text-uppercase mx-3" 
                          onclick="add_edit_measurement(${ot_id})"
                        >add</button>
                        <button 
                          type="button" 
                          id="cnl_btn" 
                          class="btn btn-sm btn-secondary btn-block text-uppercase mx-3 mt-0" 
                          onclick="toggle_measurement_popup()"
                      >close</button>`;
          $(`#measurement_wrapper .top-panel-title`).html(title);
          $(`#measurement_wrapper .top-panel-body`).html(body);
          $(`#measurement_wrapper .top-panel-footer`).html(footer);
          toggle_measurement_popup();
          lazy_loading(`form_loading`);
        }
      }
    },
    (errmsg) => { }
  );
};

const set_area = () => {
  $("#sku_id").val(null).trigger("change");
  $("#apparel_id").val(null).trigger("change");
  // $("#size_id").val(null).trigger("change");
  $("#bm_id").val(null).trigger("change");
  $("#brmm_id").val(null).trigger("change");
  $("._stitching_area").addClass("d-none");
  $("._readymade_fabric_area").addClass("d-none");
  $("._display_qty").addClass("d-none");
  
  if ($("#trans_type").val() == "READYMADE") {
    $("._readymade_fabric_area").removeClass("d-none");
    $("._sku_area").addClass("d-none");
    $("._display_qty").removeClass("d-none");
    $("#qty").val(0);
    $("#qty").attr("onkeyup", "calculate_transaction()");
    $("#qty").removeAttr("readonly");

    $("#amt_area")
      .removeClass("col-md-6")
      .removeClass("col-lg-6")
      .addClass("col-md-12")
      .addClass("col-lg-12");
  }else if ($("#trans_type").val() == "CUSTOM") {
    $("._stitching_area").removeClass("d-none");
    $("._sku_area").removeClass("d-none");

    $("#qty").val(1);
    $("#qty").attr("readonly", "readonly");
    $("#qty").removeAttr("onkeyup");

    $("#amt_area")
      .removeClass("col-md-12")
      .removeClass("col-lg-12")
      .addClass("col-md-6")
      .addClass("col-lg-6");
  }else {
    $("._stitching_area").removeClass("d-none");
    $("._sku_area").addClass("d-none");

    $("#qty").val(1);
    $("#qty").attr("readonly", "readonly");
    $("#qty").removeAttr("onkeyup");

    $("#amt_area")
      .removeClass("col-md-12")
      .removeClass("col-lg-12")
      .addClass("col-md-6")
      .addClass("col-lg-6");
  }
};
// additional_functions

// payment_mode
const get_payment_mode_data = () => {
  let title = `<p>payment mode</p>`;
  let subtitle = `<div class="d-flex justify-content-around">
                    <p class="d-flex flex-column justify-content-around">
                      <span class="pb-1 border-bottom">total amt</span>
                      <span class="_total_amt">0</span>
                    </p>
                    <p class="d-flex flex-column justify-content-around">
                      <span class="pb-1 border-bottom">advance amt</span>
                      <span class="_advance_amt">0</span>
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
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_payment_mode_data", id: $("#id").val() };
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
    const { opmt_id, opmt_amt, opmt_payment_mode_id, payment_mode_name } = row;
    advance_amt = parseFloat(advance_amt) + parseFloat(opmt_amt);
    if (isNaN(advance_amt) || advance_amt == "") advance_amt = 0;

    pay_modes += `<tr id="rowpm_${opmt_payment_mode_id}">
                      <td width="10%" class="border-0 font-weight-bold"></td>
                      <td width="30%" class="border-0 font-weight-bold">${payment_mode_name} : </td>
                      <td width="50%" class="border-0 floating-label">
                        <input 
                          type="hidden"
                          id="opmt_id_${opmt_payment_mode_id}" 
                          name="opmt_id[${opmt_payment_mode_id}]" 
                          value="${opmt_id}" 
                        />
                        <input 
                          type="hidden"
                          id="opmt_payment_mode_id_${opmt_payment_mode_id}" 
                          name="opmt_payment_mode_id[${opmt_payment_mode_id}]" 
                          value="${opmt_payment_mode_id}" 
                        />
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="opmt_amt_${opmt_payment_mode_id}" 
                          name="opmt_amt[${opmt_payment_mode_id}]" 
                          value="${opmt_amt}"
                          onkeyup="calculate_advance_amt()"
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
    $(`#payment_mode_wrapper,  #payment_mode_wrapper .right-panel`).removeClass(
      "active"
    );
  } else {
    $(`#payment_mode_wrapper, #payment_mode_wrapper .right-panel`).addClass(
      "active"
    );

    let total_amt = $(`#om_total_amt`).val();
    if (isNaN(total_amt) || total_amt == "") total_amt = 0;
    $(`._total_amt`).html(total_amt);

    let advance_amt = $(`#om_advance_amt`).val();
    if (isNaN(advance_amt) || advance_amt == "") advance_amt = 0;
    $(`._advance_amt`).html(advance_amt);

    let balance_amt = parseFloat(total_amt) - parseFloat(advance_amt);
    if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;
    $(`._balance_amt`).html(balance_amt);
  }
};
const calculate_advance_amt = () => {
  let advance_amt = 0;
  for (let i = 1; i <= $("#payment_mode_tbody > tr").length; i++) {
    let cnt = $(`#payment_mode_tbody > tr:nth-child(${i})`).attr("id");
    let explode = cnt.split("_");
    let id = explode[1];

    let amt = $(`#opmt_amt_${id}`).val();
    if (isNaN(amt) || amt == "") amt = 0;

    advance_amt = parseFloat(advance_amt) + parseFloat(amt);
    if (isNaN(advance_amt) || advance_amt == "") advance_amt = 0;
  }
  $("._advance_amt").html(advance_amt);
  $(`#om_advance_amt`).val(advance_amt);

  let total_amt = $(`#om_total_amt`).val();
  if (isNaN(total_amt) || total_amt == "") total_amt = 0;

  let balance_amt = parseFloat(total_amt) - parseFloat(advance_amt);
  if (isNaN(balance_amt) || balance_amt == "") balance_amt = 0;
  $("._balance_amt").html(balance_amt);
  $(`#om_balance_amt`).val(balance_amt);

  if (balance_amt >= 0) {
    // $("#sbt_btn").prop("disabled", false);
    $("._balance_amt").removeClass("text-danger");
  } else {
    // $("#sbt_btn").prop("disabled", true);
    $("._balance_amt").addClass("text-danger");
  }
  calculate_master();
};
// payment_mode

// measurement
const toggle_measurement_popup = () => {
  if ($(`#measurement_wrapper .top-panel`).hasClass("active")) {
    $(`#measurement_wrapper, #measurement_wrapper .top-panel `).removeClass(
      "active"
    );
    $(`#measurement_wrapper .top-panel-title`).html("");
    $(`#measurement_wrapper .top-panel-subtitle`).html("");
    $(`#measurement_wrapper .top-panel-body`).html("");
    $(`#measurement_wrapper .top-panel-footer`).html("");
  } else {
    $(`#measurement_wrapper, #measurement_wrapper .top-panel`).addClass(
      "active"
    );
  }
};

const get_measurement_table = (data) => {
  let div = `<p class="text-center text-uppercase text-danger w-100">no measurement added !!!</p>`;
  if (data && data.length != 0) {
    div = "";
    data.forEach((row) => {
      const {
        cmt_id,
        cmt_measurement_id,
        cmt_measurement_name,
        cmt_value1,
        cmt_value2,
        cmt_ot_id,
        cmt_apparel_id
      } = row;  

      const unique_id = `${cmt_ot_id}_${cmt_apparel_id}_${cmt_measurement_id}`;

      div += `<div class="col-12 col-sm-12 col-md-2 col-lg-1 floating-label mt-4" id="rowmeasurement_${cmt_measurement_id}">
                <p class="text-uppercase">${cmt_measurement_name}</p> 
                <input
                  type="hidden"
                  id="cmt_id_${unique_id}"
                  name="cmt_id[${unique_id}]"
                  value="${cmt_id}"
                />
                <div class="d-flex flex-column" style="gap: 10px;">
                  <input 
                      type="text" 
                      class="form-control floating-input" 
                      id="cmt_value1_${unique_id}"
                      name="cmt_value1[${unique_id}]"
                      value="${cmt_value1}" />
                 
                </div>
              </div>`;
    });
    div += `<div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label mt-4" id="rowmeasurement_0">
              <p class="text-uppercase">remark</p> 
              <textarea
                class="form-control floating-textarea"
                id="cmt_remark_${data[0]['cmt_apparel_id']}"
                name="cmt_remark[${data[0]['cmt_apparel_id']}]"
              >${data[0]["cmt_remark"]}</textarea>
            </div>`;
  }
  return div;
};

const add_edit_measurement = (ot_id) => {
  event.preventDefault();
  const find = trans_data.find((value) => value.ot_id == ot_id);
  const path = `${link}/${sub_link}/handler`;
  let form_data = $(`#_form`).serialize();
  form_data += `&func=add_edit_measurement`;
  form_data += `&_ot_id=${ot_id}`;
  form_data += `&_apparel_id=${find.ot_apparel_id}`;
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      const { data, msg } = resp;
      if (handle_response(resp)) {
        toggle_measurement_popup();
        toastr.success("", msg, {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
      }
    },
    (errmsg) => {}
  );
};
// measurement

const convert_to_order = (id) => { 
  swalButtonInfo
    .fire({
      title:
        '<span class="text-info">Do you want to convert estimate into order?</span>',
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, convert it!",
    })
    .then((result) => {
      if (result.isConfirmed) {
        const path = `${link}/${sub_link}/handler`;
        const form_data = { func: "add_edit_order", id };
        ajaxCall(
          "POST",
          path,
          form_data,
          "JSON",
          (resp) => {
            if (handle_response(resp)) {
              const { msg } = resp;
              swalButtonSuccess.fire({
                title: `<div class="text-success"><p>${msg}</p></div>`,
                icon: "success",
                timer: 3000,
                timerProgressBar: true,
              });
            }
            setTimeout(() => {
              window.location.reload();
            }, 3000);
          },
          (errmsg) => {}
        );
      }
    });
};


const get_sku_tab = data => {
    let html = `<ul class="nav nav-pills nav-fill nav-pills-secondary" id="pills-tab" role="tablist">`;
    data.forEach((value, index) => {
      const {apparel_data} = value;
      html += `<li class="nav-item">
                    <a 
                        class="nav-link ${index == 0 ? 'active' : ''} text-uppercase" 
                        id="apparel_${apparel_data['apparel_id']}_tab" 
                        data-toggle="tab"
                        href="#apparel_${apparel_data['apparel_id']}_content" 
                        role="tab" 
                        aria-controls="apparel_${apparel_data['apparel_id']}_content" 
                        aria-selected="${index == 0 ? true : false}"
                        style="font-size:0.8rem;"
                    >${apparel_data['apparel_name']}</a>
                </li>`;
    });
    html += `</ul>`;
    html += `<table class="table table-sm table-dark w-100 text-uppercase m-0">
                <tr>
                  <td class="text-left border-0" width="50%">entry no.: ${$("#om_em_entry_no").val()}</td>
                  <td class="text-right border-0" width="50%">customer : ${$("#om_customer_id :selected").text()}</td>
                </tr>
              </table>`;
    return html;
}

const get_sku_tab_content = data => {
  let html = `<div class="tab-content" id="pills-tabContent">`;
  data.forEach((value, index) => {
      const {apparel_data, measurement_data} = value;
      const measurement_bill_no   = measurement_data && measurement_data.length != 0 ? measurement_data[0]["bill_no"]: "";
      const measurement_bill_date = measurement_data && measurement_data.length != 0 ? measurement_data[0]["bill_date"] : "";
      let measurement_table       = get_measurement_table(measurement_data);

      html += `<div 
              class="tab-pane fade ${index == 0 ? 'show active' : ''}" 
              id="apparel_${apparel_data['apparel_id']}_content" 
              role="tabpanel" 
              aria-labelledby="apparel_${apparel_data['apparel_id']}_tab">
              <div class="d-flex flex-column w-100 mb-2">
                  <div class="d-flex flex-wrap justify-content-between bg-secondary text-white text-uppercase p-2 m-0">
                  <h5 class="mb-0">
                      <a 
                      type="button" 
                      class="btn btn-sm btn-secondary" 
                      id="measurement_${apparel_data['apparel_id']}_tabs"
                      data-toggle="collapse" 
                      data-target="#measurement_${apparel_data['apparel_id']}_tab" 
                      aria-expanded="true" 
                      aria-controls="measurement_${apparel_data['apparel_id']}_tab"
                      >measurement</a>
                  </h5>
                  <span>${measurement_bill_no} / ${measurement_bill_date}</span>
                  </div>
                  <div 
                  id="measurement_${apparel_data['apparel_id']}_tab" 
                  class="collapse show" 
                  aria-labelledby="measurement_${apparel_data['apparel_id']}_tabs" 
                  data-parent="#accordion"
                  >
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-form">
                      <div class="d-flex flex-wrap">${measurement_table}</div>
                  </div>
                  </div>
              </div>
              </div>`;
  });
  html += `</div>`;
  return html;
}



const order_status_popup = ({ om_id, om_em_entry_no, billing_name }) => { 
  const url = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_order_status", id: om_id };
  ajaxCall(
    "POST",
    url,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        // console.log(data);
        const title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12 d-flex flex-wrap justify-content-between">
                            <p class="text-uppercase text-center font-weight-bold">Entry no. : ${om_em_entry_no}</p>
                            <p class="text-uppercase text-center font-weight-bold">${billing_name}</p>
                        </div>`;
        const body = `<div class="row"><div class="col-12 col-sm-12 col-md-12 col-lg-12">${data}</div></div>`;
        const footer = `<button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">CANCEL</button>
        <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" style="color: white; background-color: #131223;" onclick="update_delivery_status()">SUBMIT</button>`;

        $(".modal-title-sm").html(title);
        $(".modal-body-sm").html(body);
        $(".modal-footer-sm").html(footer);
        $("#popup_modal_sm").modal("show");
      }
    },
    (errmsg) => {}
  );
};


function update_delivery_status()
{
	var ans = confirm("Are you sure? You want to get delivered.");
	if(ans == true)
	{
		var path        = `${link}/${sub_link}/update_delivery_status`;   
		var form_data   = $("#estimate_done_form").serialize();
		console.log(form_data);

    //  var isChecked = document.getElementById("estimate_delivery_done").checked; 

		$("#loading").show();
		$("#sbt_btn").attr("disabled", true);
		ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
			console.log(10);
			if (handle_response(resp)) {
        const { msg } = resp;
        toastr.success("", msg, {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
      }
      // setTimeout(() => {
      //   window.location.reload();
      // }, 3000);
			$("#loading").hide();
		},
		function(errmsg) {
			console.log(errmsg);
			$("#sbt_btn").attr("disabled", false);
		});
	}
}
