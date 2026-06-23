$(document).ready(function () {});
const set_user_field = (id) => {
  const path = `master/user/handler/${id}`;
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
            user_fullname,
            user_name,
            user_role_id,
            role_name,
            user_branch_id,
            branch_name,
            user_mobile,
            user_email,
            user_address,
            user_pincod3,
            user_city_id,
            city_name,
            user_state_id,
            state_name,
            user_country_id,
            country_name,
            user_constant,
            isExist,
          } = data[0];
          $(`#user_fullname`).val(user_fullname);
          $(`#user_name`).val(user_name);
          $(`#user_role_id`).html(
            `<option value="${user_role_id}">${role_name}</option>`
          );
          $(`#user_branch_id`).html(
            `<option value="${user_branch_id}">${branch_name}</option>`
          );
          $(`#user_mobile`).val(user_mobile);
          let user_mobile1 = $('#user_mobile').val() || '';

          $('#user_mobile_length').html(
              `(${10 - user_mobile1.length})`
          );

          $(`#user_email`).val(user_email);
          $(`#user_address`).val(user_address);
          $(`#user_pincode`).val(user_pincode);
          $(`#user_constant`).val(user_constant);
          user_city_id != 0 &&
            $(`#user_city_id`).html(
              `<option value="${user_city_id}">${city_name}</option>`
            );
          user_state_id != 0 &&
            $(`#user_state_id`).html(
              `<option value="${user_state_id}">${state_name}</option>`
            );
          user_country_id != 0 &&
            $(`#user_country_id`).html(
              `<option value="${user_country_id}">${country_name}</option>`
            );
          $(`#user_status`).bootstrapToggle(
            data[0][`user_status`] == 1 ? "on" : "off"
          );
          $("#popup_modal_lg").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const user_popup = (args) => {
  const { action = "add", id = 0, auth = [], field = undefined } = args;
  const { isConstant } = auth;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <p class="text-uppercase text-center font-weight-bold">${action} user</p>
                </div>`;
  let data = `<form class="form-horizontal" id="user_form" onsubmit="add_update_user(${id}, ${field})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input type="text" name="user_fullname" class="form-control floating-input" id="user_fullname" onkeyup="validate_textfield(this, ${true})" placeholder=" " autocomplete="off" />   
                        <label class="text-uppercase">fullname <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="user_fullname_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input type="text" name="user_name" class="form-control floating-input" id="user_name" onkeyup="validate_length(this, 'Length alteast 4 character.', 0, 4)" placeholder=" " autocomplete="off" style="text-transform: none;" />   
                        <label class="text-uppercase">username <span class="text-danger">*</span><span class="font-italic" style="font-size:12px;">min. length(4)</span></label>
                        <small class="form-text text-muted helper-text" id="user_name_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input type="password" name="user_password" class="form-control floating-input" id="user_password" onkeyup="validate_length(this, 'Length alteast 4 character.', ${id}, 4)" placeholder=" " autocomplete="off" style="text-transform: none;" />   
                        <label class="text-uppercase">password <span class="text-danger">*</span><span class="font-italic" style="font-size:12px;">min. length(4)</span></label>
                        <small class="form-text text-muted helper-text" id="user_password_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input type="password" class="form-control floating-input" id="user_repassword" onkeyup="validate_password(this, 'user_password', ${id})" placeholder=" " autocomplete="off" style="text-transform: none;" />   
                        <label class="text-uppercase">retype - password <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="user_repassword_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <p class="text-uppercase">role <span class="text-danger">*</span></p>
                        <select class="form-control floating-select" id="user_role_id" name="user_role_id"></select>
                        <small class="form-text text-muted helper-text" id="user_role_id_msg"></small>
                      </div>

                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <p class="text-uppercase">branch <span class="text-danger">*</span></p>
                        <select class="form-control floating-select" id="user_branch_id" name="user_branch_id"></select>
                        <small class="form-text text-muted helper-text" id="user_branch_id_msg"></small>
                      </div>
                      
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input type="number" id="user_mobile" name="user_mobile" value="" class="form-control floating-input" placeholder=" " onkeyup="set_mobile_no(this)" onfocusout="validate_mobile_no(this)" autocomplete="off" />   
                        <label class="text-uppercase">mobile no. <span id="user_mobile_length">(10)</span></label>
                        <small class="form-text text-muted helper-text" id="user_mobile_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input type="email" id="user_email" name="user_email" value="" class="form-control floating-input" onfocusout="validate_email(this)" style="text-transform: lowercase;" placeholder=" " autocomplete="off" />   
                        <label class="text-uppercase">email</label>
                        <small class="form-text text-muted helper-text" id="user_email_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <textarea class="form-control floating-textarea" id="user_address" name="user_address" placeholder=" " autocomplete="off"></textarea>
                        <label class="text-uppercase">address</label>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input type="number" id="user_pincode" name="user_pincode" value="" class="form-control floating-input" placeholder=" " autocomplete="off" />   
                        <label class="text-uppercase">pincode</label>
                        <small class="form-text text-muted helper-text" id="user_pincode_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <p class="text-uppercase">city</p>
                        <select class="form-control floating-select" id="user_city_id" name="user_city_id" placeholder=" " ></select>
                        <small class="form-text text-muted helper-text" id="user_city_id_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <p class="text-uppercase">state</p>
                        <select class="form-control floating-select" id="user_state_id" name="user_state_id" placeholder=" " ></select>
                        <small class="form-text text-muted helper-text" id="user_state_id_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <p class="text-uppercase">country</p>
                        <select class="form-control floating-select" id="user_country_id" name="user_country_id" placeholder=" " ></select>
                        <small class="form-text text-muted helper-text" id="user_country_id_msg"></small>
                      </div>
                      ${
                        field == undefined
                          ? `<div class="col-4 floating-label">
                              <input 
                                type="checkbox" 
                                id="user_status" 
                                name="user_status" 
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
                          : `<input type="hidden" name="user_status" value="1">`
                      }
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label ${
                        isConstant ? "" : "d-none"
                      }">
                        <input type="text" id="user_constant" name="user_constant" value="" class="form-control floating-input" placeholder=" " autocomplete="off" />   
                        <label class="text-uppercase">constant</label>
                        <small class="form-text text-muted helper-text" id="user_constant_msg"></small>
                      </div>                           
                    </div>              
                  </div>              
                </div>              
              </form>`;
  let btn = `<button 
                type="button" 
                class="btn btn-sm btn-primary" 
                id="sbt_btn" 
                onclick="add_update_user(${id}, ${field})" 
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
    $(`#user_status`).bootstrapToggle();
  } else {
    set_user_field(id);
  }
  setTimeout(() => {
    $(`#user_fullname`).focus();
  }, RELOAD_TIME);

  $(`#user_role_id`).select2(
    select2_default({
      url: `master/role/get_select2/_id`,
      placeholder: "SELECT",
    })
  );
  $(`#user_branch_id`).select2(
    select2_default({
      url: `master/branch/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#user_city_id`).select2(
    select2_default({
      url: `master/city/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#user_state_id`).select2(
    select2_default({
      url: `master/state/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#user_country_id`).select2(
    select2_default({
      url: `master/country/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
};
const remove_user_notifier = (term) => {
  notifier(`user_fullname`);
  notifier(`user_name`);
  notifier(`user_password`);
  notifier(`user_repassword`);
  notifier(`user_role_id`);
  notifier(`user_branch_id`);
  notifier(`user_mobile`);
  notifier(`user_email`);
};
const add_update_user = (id, field) => {
  event.preventDefault();
  const term = "user";
  let check = true;
  remove_user_notifier(term);
  if ($(`#user_fullname`).val() == "") {
    notifier(`user_fullname`, "Required");
    check = false;
  }
  if ($(`#user_name`).val() == "") { 
    notifier(`user_name`, "Required");
    check = false;
  } else {
    if ($(`#user_name`).val().length <= 4) {
      notifier(`user_name`, "Length alteast 4 character.");
      check = false;
    }
  }
  if (id == 0 && ![6, 9].includes(parseInt($('#user_role_id').val()))) {
    if ($(`#user_password`).val() == "") {
      notifier(`user_password`, "Required");
      check = false;
    } else {
      if ($(`#user_password`).val().length <= 4) {
        notifier(`user_password`, "Length alteast 4 character.");
        check = false;
      }
    }
    if ($(`#user_repassword`).val() == "") {
      notifier(`user_repassword`, "Required");
      check = false;
    } else {
      if ($(`#user_password`).val() != $(`#user_repassword`).val()) {
        notifier(`user_repassword`, "Password mismatch.");
        check = false;
      }
    }
  }
  if ($(`#user_role_id`).val() == null) {
    notifier(`user_role_id`, "Required");
    check = false;
  }
  if ($(`#user_branch_id`).val() == null) {
    notifier(`user_branch_id`, "Required");
    check = false;
  }
  if ($(`#user_email`).val().length > 0) {
    if (!validate_email_value($(`#user_email`).val())) {
      notifier(`user_email`, "Invalid Email");
      check = false;
    }
  }
  if ($(`#user_mobile`).val().length > 0) {
    if ($(`#user_mobile`).val().length !== 10) {
      notifier(`user_mobile`, "Invalid Mobile No");
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
    const path = `master/user/handler`;
    let form_data = $(`#user_form`).serialize();
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
              $(`#user_form`)[0].reset();
              $(`#user_fullname`).focus();
              $(`#user_role_id`).val(null).trigger("change");
              $(`#user_branch_id`).val(null).trigger("change");
              setTimeout(() => {
                remove_user_notifier();
              }, RELOAD_TIME);
            }
          } else {
            $("#popup_modal_lg").modal("hide");
          }
          toastr.success("", msg, { closeButton: true, progressBar: true });
        }
      },
      (errmsg) => {}
    );
  }
};
const user_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.user_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                    <tbody>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">user : </td>
                            <td width="70%">${data.user_fullname}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">role : </td>
                            <td width="70%">${data.role_name}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">branch : </td>
                            <td width="70%">${data.branch_name}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">mobile no : </td>
                            <td width="70%">${data.user_mobile}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">email : </td>
                            <td width="70%" class="text-lowercase">${
                              data.user_email
                            }</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold" width="30%" align="right">status : </td>
                            <td width="70%">${
                              data.user_status == 1 ? "active" : "inactive"
                            }</td>
                        </tr>
                    </tbody>
                </table>`;
  remove_datav3({ path, form_data, html });
};
