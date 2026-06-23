$(document).ready(function () {
  $("#_role_name").select2(
    select2_default({
      url: `master/role/get_select2/_role_name`,
      placeholder: "role",
    })
  );
});
const set_role_field = (id) => {
  const sub_menu = "role";
  const path = `master/${sub_menu}/handler`;
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
          $(`#${sub_menu}_name`).val(data[0][`${sub_menu}_name`]);
          $(`#${sub_menu}_name`).prop("readonly", true);
          $(`#${sub_menu}_status`).bootstrapToggle(
            data[0][`${sub_menu}_status`] == 1 ? "on" : "off"
          );
          $("#popup_modal_sm").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const role_popup = (id, action = "add", field = undefined) => {
  const sub_menu = "role";
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                <p class="text-uppercase text-center font-weight-bold">${action} ${sub_menu}</p>
                </div>`;
  let data = `<form class="form-horizontal" id="${sub_menu}_form" onsubmit="add_update_${sub_menu}(${id}, ${field})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                        <input type="text" name="${sub_menu}_name" class="form-control floating-input" id="${sub_menu}_name" onkeyup="validate_textfield(this, ${true})" placeholder=" " autocomplete="off" />   
                        <label class="text-uppercase">${sub_menu} <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="${sub_menu}_name_msg"></small>
                      </div>
                      ${
                        field == undefined
                          ? `<div class="col-4 floating-label">
                              <input 
                                type="checkbox" 
                                id="${sub_menu}_status" 
                                name="${sub_menu}_status" 
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
                          : `<input type="hidden" name="${sub_menu}_status" value="1">`
                      }                            
                    </div>              
                  </div>              
                </div>              
              </form>`;
  let btn = `<button 
                type="button" 
                class="btn btn-sm btn-primary" 
                id="sbt_btn" 
                onclick="add_update_${sub_menu}(${id}, ${field})" 
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
    $(`#${sub_menu}_status`).bootstrapToggle();
  } else {
    set_role_field(id);
  }
  setTimeout(() => {
    $(`#${sub_menu}_name`).focus();
  }, RELOAD_TIME);
};
const remove_role_notifier = (sub_menu) => {
  notifier(`${sub_menu}_name`);
};
const add_update_role = (id, field) => {
  event.preventDefault();
  const sub_menu = "role";
  let check = true;
  remove_role_notifier(sub_menu);
  if ($(`#${sub_menu}_name`).val() == "") {
    notifier(`${sub_menu}_name`, "Required");
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
    let path = `master/${sub_menu}/handler/${id}`;
    let form_data = $(`#${sub_menu}_form`).serialize();
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
              refresh_dropdown_select2(data, field);
            } else {
              $(`#${sub_menu}_form`)[0].reset();
              $(`#${sub_menu}_name`).focus();
              remove_role_notifier();
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
const remove_role = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.role_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">role : </td>
                    <td width="70%">${data.role_name}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">STATUS : </td>
                    <td width="70%">
                      ${data.role_status == 1 ? "active" : "inactive"}
                    </td>
                  </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
