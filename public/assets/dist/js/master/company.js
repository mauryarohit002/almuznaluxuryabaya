$(document).ready(function () {});
const set_company_field = (id) => {
  const path = `master/company/handler`;
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
            company_initial,
            company_name,
            company_person,
            company_address,
            company_city_id,
            company_state_id,
            company_country_id,
            company_pincode,
            city_name,
            state_name,
            country_name,
            company_mobile,
            company_phone1,
            company_phone2,
            company_email,
            company_code,
            company_status,
            company_protocol,
            company_smtp_host,
            company_smtp_port,
            company_smtp_user,
            company_smtp_pass,
            company_test_email,
          } = data[0];
          $(`#company_initial`).val(company_initial);
          $(`#company_name`).val(company_name);
          $(`#company_person`).val(company_person);
          $(`#company_mobile`).val(company_mobile);
          $(`#company_mobile_length`).html(
            `(${10 - parseInt(company_mobile.length)})`
          );
          $(`#company_address`).val(company_address);
          company_city_id != 0 &&
            $(`#company_city_id`).html(
              `<option value="${company_city_id}">${city_name}</option>`
            );
          company_state_id != 0 &&
            $(`#company_state_id`).html(
              `<option value="${company_state_id}">${state_name}</option>`
            );
          company_country_id != 0 &&
            $(`#company_country_id`).html(
              `<option value="${company_country_id}">${country_name}</option>`
            );
          $(`#company_pincode`).val(company_pincode);
          $(`#company_phone1`).val(company_phone1);
          $(`#company_phone2`).val(company_phone2);
          $(`#company_email`).val(company_email);
          $(`#company_code`).val(company_code);
          $(`#company_protocol`).val(company_protocol);
          $(`#company_smtp_host`).val(company_smtp_host);
          $(`#company_smtp_port`).val(company_smtp_port);
          $(`#company_smtp_user`).val(company_smtp_user);
          $(`#company_smtp_pass`).val(company_smtp_pass);
          $(`#company_test_email`).val(company_test_email);
          $(`#company_status`).bootstrapToggle(
            company_status == 1 ? "on" : "off"
          );
          $("#popup_modal_lg").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const company_popup = (args) => {
  const { action = "add", id = 0, auth = [], field = undefined } = args;
  const { isTestEmail } = auth;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">${action} company</p>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <ul class="nav nav-pills nav-fill nav-pills-primary text-light" id="company-pills-tab" role="tablist">
                    <li class="nav-item">
                      <a 
                        class="nav-link active text-uppercase" 
                        id="popup_company_general_tab"
                        data-toggle="tab"
                        role="tab"
                        aria-controls="company_general_content" 
                        aria-selected="true"
                      >general detail</a>
                    </li>
                    <li class="nav-item">
                      <a 
                          class="nav-link text-uppercase" 
                          id="popup_company_email_tab"
                          data-toggle="tab"
                          role="tab"
                          aria-controls="company_email_content" 
                          aria-selected="false"
                      >email configuration</a>
                    </li>
                  </ul>
                </div>`;
  let data = `<form class="form-horizontal" id="company_form" onsubmit="add_update_company(${id}, ${field})">              
                  <div class="row pt-1">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="tab-content" id="company-pills-tabContent">
                        <div 
                          class="tab-pane fade show active company-tab-pane" 
                          id="popup_company_general_tab_content" 
                          role="tabpanel" 
                          aria-labelledby="popup_company_general_tab"
                        >
                          <div class="d-flex flex-wrap form-group floating-form">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="text"
                                class="form-control floating-input" 
                                id="company_initial" 
                                name="company_initial" 
                                onkeyup="validate_textfield(this, ${true})" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">initial <span class="text-danger">*</span></label>
                              <small class="form-text text-muted helper-text" id="company_initial_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="text"
                                class="form-control floating-input" 
                                id="company_name" 
                                name="company_name" 
                                onkeyup="validate_textfield(this, ${true})" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">name <span class="text-danger">*</span></label>
                              <small class="form-text text-muted helper-text" id="company_name_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="company_person" 
                                name="company_person" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">contact person</label>
                              <small class="form-text text-muted helper-text" id="company_person_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="number" 
                                class="form-control floating-input" 
                                id="company_mobile" 
                                name="company_mobile" 
                                placeholder=" " 
                                onkeyup="set_mobile_no(this)" 
                                onfocusout="validate_mobile_no(this)" 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">mobile no <span id="company_mobile_length">(10)</span></label>
                              <small class="form-text text-muted helper-text" id="company_mobile_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="number" 
                                class="form-control floating-input" 
                                id="company_phone1" 
                                name="company_phone1" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">telephone 1</label>
                              <small class="form-text text-muted helper-text" id="company_phone1_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="number" 
                                class="form-control floating-input" 
                                id="company_phone2" 
                                name="company_phone2" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">telephone 2</label>
                              <small class="form-text text-muted helper-text" id="company_phone2_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="email" 
                                class="form-control floating-input text-lowercase" 
                                id="company_email" 
                                name="company_email" 
                                onkeyup="validate_email(this)" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">email</label>
                              <small class="form-text text-muted helper-text" id="company_email_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="company_code" 
                                name="company_code" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">code</label>
                              <small class="form-text text-muted helper-text" id="company_code_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-8 floating-label">
                              <textarea 
                                class="form-control floating-textarea" 
                                id="company_address" 
                                name="company_address" 
                                placeholder=" " 
                                autocomplete="off"
                                rows="6"
                              ></textarea>
                              <label class="text-uppercase">address</label>
                              <small class="form-text text-muted helper-text" id="company_address_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <input 
                                  type="number" 
                                  class="form-control floating-input text-lowercase" 
                                  id="company_pincode" 
                                  name="company_pincode" 
                                  onkeyup="validate_textfield(this)" 
                                  placeholder=" " 
                                  autocomplete="off" 
                                />   
                                <label class="text-uppercase">pincode</label>
                                <small class="form-text text-muted helper-text" id="company_pincode_msg"></small>
                              </div>
                              <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                                <p class="text-uppercase">city</p>
                                <select 
                                  class="form-control floating-select" 
                                  id="company_city_id" 
                                  name="company_city_id" 
                                  placeholder=" " 
                                ></select>
                                <small class="form-text text-muted helper-text" id="company_city_id_msg"></small>
                              </div>                      
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                              <p class="text-uppercase">state</p>
                              <select 
                                class="form-control floating-select" 
                                id="company_state_id" 
                                name="company_state_id" 
                                placeholder=" " 
                              ></select>
                              <small class="form-text text-muted helper-text" id="company_state_id_msg"></small>
                            </div>                      
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                              <p class="text-uppercase">country</p>
                              <select 
                                class="form-control floating-select" 
                                id="company_country_id" 
                                name="company_country_id" 
                                placeholder=" " 
                              ></select>
                              <small class="form-text text-muted helper-text" id="company_country_id_msg"></small>
                            </div>                      
                            ${
                              field == undefined
                                ? `
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                                  <input 
                                    type="checkbox" 
                                    id="company_status" 
                                    name="company_status" 
                                    data-toggle="toggle" 
                                    data-on="ACTIVE" 
                                    data-off="INACTIVE" 
                                    data-onstyle="primary" 
                                    data-offstyle="primary" 
                                    data-width="100" 
                                    data-size="normal" 
                                    checked
                                  />
                                </div>`
                                : `<input type="hidden" name="company_status" value="1">`
                            }
                          </div>              
                        </div>   
                        <div 
                          class="tab-pane fade company-tab-pane" 
                          id="popup_company_email_tab_content" 
                          role="tabpanel" 
                          aria-labelledby="popup_company_email_tab"
                        >
                          <div class="d-flex flex-wrap form-group floating-form">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="text" 
                                class="form-control floating-input text-lowercase" 
                                id="company_protocol" 
                                name="company_protocol" 
                                onkeyup="validate_textfield(this)" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">protocol</label>
                              <small class="form-text text-muted helper-text" id="company_protocol_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="text" 
                                class="form-control floating-input text-lowercase" 
                                id="company_smtp_host" 
                                name="company_smtp_host" 
                                onkeyup="validate_textfield(this)" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">smtp host</label>
                              <small class="form-text text-muted helper-text" id="company_smtp_host_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="number" 
                                class="form-control floating-input text-lowercase" 
                                id="company_smtp_port" 
                                name="company_smtp_port" 
                                onkeyup="validate_textfield(this)" 
                                placeholder=" " 
                                autocomplete="off" 
                              />   
                              <label class="text-uppercase">smtp port</label>
                              <small class="form-text text-muted helper-text" id="company_smtp_port_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="company_smtp_user" 
                                name="company_smtp_user" 
                                onkeyup="validate_textfield(this)" 
                                placeholder=" " 
                                autocomplete="off" 
                                style="text-transform:none"
                              />   
                              <label class="text-uppercase">smtp user</label>
                              <small class="form-text text-muted helper-text" id="company_smtp_user_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label">
                              <input 
                                type="text" 
                                class="form-control floating-input" 
                                id="company_smtp_pass" 
                                name="company_smtp_pass" 
                                onkeyup="validate_textfield(this)" 
                                placeholder=" " 
                                autocomplete="off" 
                                style="text-transform:none"
                              />   
                              <label class="text-uppercase">smpt password</label>
                              <small class="form-text text-muted helper-text" id="company_smtp_pass_msg"></small>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4 floating-label 
                              ${isTestEmail ? "" : "d-none"}"
                            >
                              <input 
                                type="email" 
                                class="form-control floating-input" 
                                id="company_test_email" 
                                name="company_test_email" 
                                onkeyup="validate_textfield(this)" 
                                placeholder=" " 
                                autocomplete="off" 
                                style="text-transform:none"
                              />   
                              <label class="text-uppercase">test email</label>
                              <small class="form-text text-muted helper-text" id="company_test_email_msg"></small>
                            </div>
                          </div>
                        </div>           
                      </div>              
                    </div>              
                  </div>              
                </form>`;
  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              onclick="add_update_company(${id}, ${field})" 
              style="width:15%;"
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
    $(`#company_status`).bootstrapToggle();
  } else {
    set_company_field(id);
  }
  setTimeout(() => {
    $(`#company_name`).focus();
  }, RELOAD_TIME);

  $(`#company_city_id`).select2(
    select2_default({
      url: `master/city/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#company_state_id`).select2(
    select2_default({
      url: `master/state/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#company_country_id`).select2(
    select2_default({
      url: `master/country/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $("#company-pills-tab a").on("click", function (e) {
    e.preventDefault();
    $(this).tab("show");
    let id = this.id;
    $(`.company-tab-pane`).removeClass("show active");
    $(`#${id}_content`).addClass("show active");
  });
};

const remove_company_notifier = (term) => {
  notifier(`company_initial`);
  notifier(`company_name`);
};
const add_update_company = (id, field) => {
  event.preventDefault();
  const term = "company";
  let check = true;
  remove_company_notifier(term);
  if ($(`#company_initial`).val() == "") {
    notifier(`company_initial`, "Required");
    check = false;
  }
  if ($(`#company_name`).val() == "") {
    notifier(`company_name`, "Required");
    check = false;
  }
  if ($(`#company_email`).val().length > 0) {
    if (!validate_email_value($(`#company_email`).val())) {
      notifier(`company_email`, "Invalid Email");
      check = false;
    }
  }
  if ($(`#company_mobile`).val().length > 0) {
    if ($(`#company_mobile`).val().length !== 10) {
      notifier(`company_mobile`, "Invalid Mobile No");
      check = false;
    }
  }
  if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    let path = `master/company/handler`;
    let form_data = $(`#company_form`).serialize();
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
              $(`#company_form`)[0].reset();
              $(`#company_name`).focus();
              $(`#company_city_id`).val(null).trigger("change");
              $(`#company_state_id`).val(null).trigger("change");
              $(`#company_country_id`).val(null).trigger("change");
              remove_company_notifier();
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
const company_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.company_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">name : </td>
                    <td width="70%">${data.company_name}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">contact person : </td>
                    <td width="70%">${data.company_person}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">status : </td>
                    <td width="70%">
                      ${data.company_status == 1 ? "active" : "inactive"}
                    </td>
                  </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
