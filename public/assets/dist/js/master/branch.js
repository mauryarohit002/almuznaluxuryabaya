$(document).ready(function () {});
const set_branch_field = (id) => {
  const path = `master/branch/handler`;
  const form_data = { func: "get_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
         const {master_data, branch_data} = data;
        if (master_data && master_data.length != 0) {
          $(`#branch_name`).val(master_data[0][`branch_name`]);
          $(`#branch_mobile_no`).val(master_data[0][`branch_mobile_no`]);
          $(`#branch_address`).val(master_data[0][`branch_address`]);
          $(`#branch_status`).bootstrapToggle(
            master_data[0][`branch_status`] == 1 ? "on" : "off"
          );
          $("#popup_modal_sm").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const branch_popup = (args) => {
  const { action = "add", id = 0, field = undefined } = args;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">${action} branch</p>
                </div>`;
  let data = `<form class="form-horizontal" id="branch_form" onsubmit="add_update_branch(${id}, ${field})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 floating-label">
                        <input 
                          type="text" 
                          class="form-control floating-input" 
                          id="branch_name" 
                          name="branch_name" 
                          onkeyup="validate_textfield(this, ${true})" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">branch <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="branch_name_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="branch_mobile_no" 
                          name="branch_mobile_no" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">mobile no</label>
                        <small class="form-text text-muted helper-text" id="branch_mobile_no_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 floating-label">
                          <textarea
                              class="form-control floating-input"
                              id="branch_address"
                              name="branch_address"
                              placeholder=" "
                              autocomplete="off"
                              rows="3"
                          ></textarea>

                          <label class="text-uppercase">address</label>
                          <small class="form-text text-muted helper-text" id="branch_address_msg"></small>
                      </div>
                      ${
                        field == undefined
                          ? `<div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                              <input 
                                type="checkbox" 
                                id="branch_status" 
                                name="branch_status" 
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
                          : `<input type="hidden" name="branch_status" value="1">`
                      }
                    </div>              
                  </div>              
                </div>              
              </form>`;

  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              onclick="add_update_branch(${id}, ${field})" 
              style="width:15%;"
              ${id == 0 && "disabled"}
            >
              <div class="stage d-none"><div class="dot-flashing"></div></div>
              <div class="dot-text text-primary text-uppercase">${action}</div>
            </button>
            <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">CANCEL</button>`;

  $(".modal-title-sm").html(title);
  $(".modal-body-sm").html(data);
  $(".modal-footer-sm").html(action == "read" ? "" : btn);
  if (id == 0) {
    $("#popup_modal_sm").modal("show");
    $(`#branch_status`).bootstrapToggle();
    setTimeout(() => {
      $(`#branch_name`).focus();
    }, RELOAD_TIME);
  } else {
    set_branch_field(id);
    setTimeout(() => {
      $(`#pincode`).focus();
    }, RELOAD_TIME);
  }
  
};
const add_update_branch = (id, field) => {
  event.preventDefault();
  let check = true;
  notifier(`branch_name`);
  if ($(`#branch_name`).val() == "") {
    notifier(`branch_name`, "Required");
    check = false;
  }
  if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    let path = `master/branch/handler`;
    let form_data = $(`#branch_form`).serialize();
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
              $("#popup_modal_sm").modal("hide");
              // refresh_dropdown_select2(data, field);
            } else {
              $(`#branch_name`).val("").focus();
              $("#transaction_wrapper").html("");
              notifier(`branch_name`);
            }
          } else {
            $("#popup_modal_sm").modal("hide");
          }
          toastr.success("", msg, { closeButton: true, progressBar: true });
          $("body, html").animate({ scrollTop: 0 }, 1000);
        }
      },
      (errmsg) => {}
    );
  }
};
const branch_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.branch_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">branch : </td>
                          <td width="70%">${data.branch_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">STATUS : </td>
                          <td width="70%">
                            ${data.branch_status == 1 ? "active" : "inactive"}
                          </td>
                      </tr>
                  </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};