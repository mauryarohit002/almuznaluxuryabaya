$(document).ready(function () {});
const set_supplier_field = (id) => {
  const path = `master/supplier/handler`;
  const form_data = { func: "get_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data) {
          const {
            supplier_name,
            supplier_person,
            supplier_address,
            supplier_pincode,
            supplier_city_id,
            supplier_state_id,
            supplier_country_id,
            city_name,
            state_name,
            country_name,
            supplier_mobile,
            supplier_phone1,
            supplier_phone2,
            supplier_email,
            supplier_code,
            supplier_pan_no,
            supplier_gst_no,
            supplier_opening_amt,
            supplier_credit_amt,
            supplier_credit_day,
            supplier_refer_by,
            supplier_referer_mobile,
            supplier_remark,
            supplier_notes,
            supplier_constant,
            supplier_status,
          } = data[0];
          $(`#supplier_name`).val(supplier_name);
          $(`#supplier_person`).val(supplier_person);
          $(`#supplier_mobile`).val(supplier_mobile);
          $(`#supplier_mobile_length`).html(
            `(${10 - parseInt(supplier_mobile.length)})`
          );
          $(`#supplier_address`).val(supplier_address);
          supplier_city_id != 0 &&
            $(`#supplier_city_id`).html(
              `<option value="${supplier_city_id}">${city_name}</option>`
            );
          supplier_state_id != 0 &&
            $(`#supplier_state_id`).html(
              `<option value="${supplier_state_id}">${state_name}</option>`
            );
          supplier_country_id != 0 &&
            $(`#supplier_country_id`).html(
              `<option value="${supplier_country_id}">${country_name}</option>`
            );
          $(`#supplier_pincode`).val(supplier_pincode);
          $(`#supplier_phone1`).val(supplier_phone1);
          $(`#supplier_phone2`).val(supplier_phone2);
          $(`#supplier_email`).val(supplier_email);
          $(`#supplier_code`).val(supplier_code);
          $(`#supplier_pan_no`).val(supplier_pan_no);
          $(`#supplier_gst_no`).val(supplier_gst_no);
          $(`#supplier_opening_amt`).val(supplier_opening_amt);
          $(`#supplier_credit_amt`).val(supplier_credit_amt);
          $(`#supplier_credit_day`).val(supplier_credit_day);
          $(`#supplier_refer_by`).val(supplier_refer_by);
          $(`#supplier_referer_mobile`).val(supplier_referer_mobile);
          $(`#supplier_remark`).val(supplier_remark);
          $(`#supplier_notes`).val(supplier_notes);
          $(`#supplier_constant`).val(supplier_constant);
          $(`#supplier_status`).bootstrapToggle(
            supplier_status == 1 ? "on" : "off"
          );
          $("#popup_modal_lg").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const supplier_popup = (args) => {
  const { action = "add", id = 0, auth = [], field = undefined } = args;
  const { isConstant } = auth;
  console.log({ isConstant });
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <p class="text-uppercase text-center font-weight-bold">${action} supplier </p>
              </div>`;
  let data = `<form class="form-horizontal" id="supplier_form" onsubmit="add_update_supplier(${id}, ${field})">              
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                    
                      <div class="d-flex flex-wrap form-group floating-form mt-2">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="text" 
                            name="supplier_name" 
                            class="form-control floating-input" 
                            id="supplier_name" 
                            onkeyup="validate_textfield(this, ${true})" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">name <span class="text-danger">*</span></label>
                          <small class="form-text text-muted helper-text" id="supplier_name_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="text" 
                            name="supplier_person" 
                            class="form-control floating-input" 
                            id="supplier_person" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">contact person</label>
                          <small class="form-text text-muted helper-text" id="supplier_person_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="number" 
                            name="supplier_mobile" 
                            class="form-control floating-input" 
                            id="supplier_mobile" 
                            placeholder=" " 
                            onkeyup="set_mobile_no(this)" 
                            onfocusout="validate_mobile_no(this)" 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">mobile no <span id="supplier_mobile_length">(10)</span></label>
                          <small class="form-text text-muted helper-text" id="supplier_mobile_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="number" 
                            name="supplier_phone1" 
                            class="form-control floating-input" 
                            id="supplier_phone1" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">telephone 1</label>
                          <small class="form-text text-muted helper-text" id="supplier_phone1_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="number" 
                            name="supplier_phone2" 
                            class="form-control floating-input" 
                            id="supplier_phone2" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">telephone 2</label>
                          <small class="form-text text-muted helper-text" id="supplier_phone2_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="email" 
                            name="supplier_email" 
                            class="form-control floating-input text-lowercase" 
                            id="supplier_email" 
                            onkeyup="validate_email(this)" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">email</label>
                          <small class="form-text text-muted helper-text" id="supplier_email_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="text" 
                            name="supplier_code" 
                            class="form-control floating-input" 
                            id="supplier_code" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">code</label>
                          <small class="form-text text-muted helper-text" id="supplier_code_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="text" 
                            name="supplier_pan_no" 
                            class="form-control floating-input" 
                            id="supplier_pan_no" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">pan no</label>
                          <small class="form-text text-muted helper-text" id="supplier_pan_no_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="text" 
                            name="supplier_gst_no" 
                            class="form-control floating-input" 
                            id="supplier_gst_no" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">gst no</label>
                          <small class="form-text text-muted helper-text" id="supplier_gst_no_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="number" 
                            name="supplier_opening_amt" 
                            class="form-control floating-input" 
                            id="supplier_opening_amt" 
                            placeholder=" " 
                            autocomplete="off" 
                            min="0" 
                            oninput="this.value = Math.abs(this.value)"
                          />   
                          <label class="text-uppercase">opening amt</label>
                          <small class="form-text text-muted helper-text" id="supplier_opening_amt_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="number" 
                            name="supplier_credit_amt" 
                            class="form-control floating-input" 
                            id="supplier_credit_amt" 
                            placeholder=" " 
                            autocomplete="off" 
                            min="0" 
                            oninput="this.value = Math.abs(this.value)"
                          />   
                          <label class="text-uppercase">credit limit amt</label>
                          <small class="form-text text-muted helper-text" id="supplier_credit_amt_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="number" 
                            name="supplier_credit_day" 
                            class="form-control floating-input" 
                            id="supplier_credit_day" 
                            placeholder=" " 
                            autocomplete="off" 
                            min="0" 
                            oninput="this.value = Math.abs(this.value)"
                          />   
                          <label class="text-uppercase">credit limit day</label>
                          <small class="form-text text-muted helper-text" id="supplier_credit_day_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="text" 
                            name="supplier_refer_by" 
                            class="form-control floating-input" 
                            id="supplier_refer_by" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">refer by</label>
                          <small class="form-text text-muted helper-text" id="supplier_refer_by_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="number" 
                            name="supplier_referer_mobile" 
                            class="form-control floating-input" 
                            id="supplier_referer_mobile" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">referer contact no</label>
                          <small class="form-text text-muted helper-text" id="supplier_referer_mobile_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <textarea 
                            name="supplier_remark" 
                            class="form-control floating-textarea" 
                            id="supplier_remark" 
                            placeholder=" " 
                            autocomplete="off"
                          ></textarea>
                          <label class="text-uppercase">remark</label>
                          <small class="form-text text-muted helper-text" id="supplier_remark_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                          <textarea 
                            name="supplier_notes" 
                            class="form-control floating-textarea" 
                            id="supplier_notes" 
                            placeholder=" " 
                            autocomplete="off"
                          ></textarea>
                          <label class="text-uppercase">notes</label>
                          <small class="form-text text-muted helper-text" id="supplier_notes_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 floating-label">
                          <textarea 
                            name="supplier_address" 
                            class="form-control floating-textarea" 
                            id="supplier_address" 
                            placeholder=" " 
                            autocomplete="off"
                          ></textarea>
                          <label class="text-uppercase">address</label>
                          <small class="form-text text-muted helper-text" id="supplier_address_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <input 
                            type="number" 
                            name="supplier_pincode" 
                            class="form-control floating-input" 
                            id="supplier_pincode" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">pincode</label>
                          <small class="form-text text-muted helper-text" id="supplier_pincode_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <p class="text-uppercase">city</p>
                          <select 
                            class="form-control floating-select" 
                            id="supplier_city_id" 
                            name="supplier_city_id" 
                            placeholder=" " 
                          ></select>
                          <small class="form-text text-muted helper-text" id="supplier_city_id_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <p class="text-uppercase">state</p>
                          <select 
                            class="form-control floating-select" 
                            id="supplier_state_id" 
                            name="supplier_state_id" 
                            placeholder=" " 
                          ></select>
                          <small class="form-text text-muted helper-text" id="supplier_state_id_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                          <p class="text-uppercase">country</p>
                          <select 
                            class="form-control floating-select" 
                            id="supplier_country_id" 
                            name="supplier_country_id" 
                            placeholder=" " 
                          ></select>
                          <small class="form-text text-muted helper-text" id="supplier_country_id_msg"></small>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label 
                          ${isConstant ? "" : "d-none"}"
                        >
                          <input 
                            type="text" 
                            name="supplier_constant" 
                            class="form-control floating-input" 
                            id="supplier_constant" 
                            placeholder=" " 
                            autocomplete="off" 
                          />   
                          <label class="text-uppercase">constant</label>
                          <small class="form-text text-muted helper-text" id="supplier_constant_msg"></small>
                        </div> 
                        ${
                          field == undefined
                            ? `<div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                                <input 
                                  type="checkbox" 
                                  id="supplier_status" 
                                  name="supplier_status" 
                                  data-toggle="toggle" 
                                  data-on="ACTIVE" 
                                  data-off="INACTIVE" 
                                  data-onstyle="primary" 
                                  data-offstyle="primary" 
                                  data-width="100" 
                                  data-size="normal" 
                                  checked>
                              </div>`
                            : `<input type="hidden" name="supplier_status" value="1">`
                        }                           
                      </div>
                  </div>              
                </div>              
              </form>`;

  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              style="width:15%;"
              onclick="add_update_supplier(${id}, ${field})" 
              ${id == 0 && "disabled"}
            >
              <div class="stage d-none"><div class="dot-flashing"></div></div>
              <div class="dot-text text-primary text-uppercase">${action}</div>
            </button>
            <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">CANCEL</button>`;

  $(".modal-title-lg").html(title);
  $(".modal-body-lg").html(data);
  $(".modal-footer-lg").html(action == "read" ? "" : btn);
  if (id == 0) {
    $("#popup_modal_lg").modal("show");
    $(`#supplier_status`).bootstrapToggle();
  } else {
    set_supplier_field(id);
  }
  setTimeout(() => {
    $(`#supplier_name`).focus();
  }, RELOAD_TIME);
  $(`#supplier_city_id`).select2(
    select2_default({
      url: `master/city/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#supplier_state_id`).select2(
    select2_default({
      url: `master/state/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#supplier_country_id`).select2(
    select2_default({
      url: `master/country/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $("#supplier-pills-tab a").on("click", function (e) {
    e.preventDefault();
    $(this).tab("show");
    let id = this.id;
    $(`.supplier-tab-pane`).removeClass("show active");
    $(`#${id}_content`).addClass("show active");
  });
};
const remove_supplier_notifier = (term) => {
  notifier(`supplier_name`);
};
const add_update_supplier = (id, field) => {
  event.preventDefault();
  const term = "supplier";
  let check = true;
  remove_supplier_notifier(term);
  if ($(`#supplier_name`).val() == "") {
    notifier(`supplier_name`, "Required");
    check = false;
  }
  if ($(`#supplier_email`).val().length > 0) {
    if (!validate_email_value($(`#supplier_email`).val())) {
      notifier(`supplier_email`, "Invalid Email");
      check = false;
    }
  }
  if ($(`#supplier_mobile`).val().length > 0) {
    if ($(`#supplier_mobile`).val().length !== 10) {
      notifier(`supplier_mobile`, "Invalid Mobile No");
      check = false;
    }
  }
  if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $(
      "#popup_supplier_address_tab, #popup_supplier_address_tab_content"
    ).removeClass("active show");
    $(
      "#popup_supplier_general_tab, #popup_supplier_general_tab_content"
    ).addClass("active show");
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    const path = `master/supplier/handler`;
    let form_data = $(`#supplier_form`).serialize();
    form_data += `&func=add_update&id=${id}`;
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          if (id == 0) {
            if (field != undefined) {
              $("#popup_modal_lg").modal("hide");
              refresh_dropdown_select2(data, field);
            } else {
              $(`#supplier_form`)[0].reset();
              $(`#supplier_name`).focus();
              $(`#supplier_cpt_id`).val(null).trigger("change");
              remove_supplier_notifier();
            }
          } else {
            $("#popup_modal_lg").modal("hide");
          }
          toastr.success("", msg, { closeButton: true, progressBar: true });
          $("body, html").animate({ scrollTop: 0 }, 1000);
        }
      },
      (errmsg) => {}
    );
  }
};
const supplier_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.supplier_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">name : </td>
                    <td width="70%">${data.supplier_name}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">code : </td>
                    <td width="70%">${data.supplier_code}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">status : </td>
                    <td width="70%">
                      ${data.supplier_status == 1 ? "active" : "inactive"}
                    </td>
                  </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
