$(document).ready(function () {});
const set_measurement_field = (id) => {
  const path = `master/measurement/handler`;
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
          $(`#measurement_name`).val(data[0][`measurement_name`]);
          // $(`#measurement_group`).val(data[0][`measurement_group`]);
          $(`#measurement_status`).bootstrapToggle(
            data[0][`measurement_status`] == 1 ? "on" : "off"
          );
          $("#popup_modal_sm").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const measurement_popup = (args) => {
  const { action = "add", id = 0, field = undefined } = args;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                      <p class="text-uppercase text-center font-weight-bold">${action} measurement</p>
                  </div>`;
  let data = `<form class="form-horizontal" id="measurement_form" onsubmit="add_update_measurement(${id}, ${field})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 floating-label">
                        <input 
                          type="text" 
                          name="measurement_name" 
                          class="form-control floating-input" 
                          id="measurement_name" 
                          onkeyup="validate_textfield(this, ${true})" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">measurement <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="measurement_name_msg"></small>
                      </div>
                     
                      ${
                        field == undefined
                          ? `<div class="col-12 floating-label">
                                <input type="checkbox" id="measurement_status" name="measurement_status" data-toggle="toggle" data-on="ACTIVE" data-off="INACTIVE" data-onstyle="primary" data-offstyle="primary" data-width="100" data-size="normal" checked>
                            </div>`
                          : `<input type="hidden" name="measurement_status" value="1">`
                      }                            
                    </div>              
                  </div>              
                </div>              
              </form>`;
  let btn = `<button 
                type="button" 
                class="btn btn-sm btn-primary" 
                id="sbt_btn" 
                onclick="add_update_measurement(${id}, ${field})" 
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
    $(`#measurement_status`).bootstrapToggle();
  } else {
    set_measurement_field(id);
  }
  setTimeout(() => {
    $(`#measurement_name`).focus();
  }, RELOAD_TIME);
};
const remove_measurement_notifier = () => {
  notifier(`measurement_name`);
  notifier(`measurement_group`);
};
const add_update_measurement = (id, field) => {
  event.preventDefault();
  let check = true;
  remove_measurement_notifier();
  if ($(`#measurement_name`).val() == "") {
    notifier(`measurement_name`, "Required");
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
    const path = `master/measurement/handler/${id}`;
    let form_data = $(`#measurement_form`).serialize();
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
              $(`#measurement_form`)[0].reset();
              $(`#measurement_name`).focus();
              remove_measurement_notifier();
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
const measurement_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.measurement_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">measurement : </td>
                        <td width="70%">${data.measurement_name}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">group : </td>
                        <td width="70%">${data.measurement_group}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">status : </td>
                        <td width="70%">
                          ${
                            data.measurement_status == 1 ? "active" : "inactive"
                          }
                        </td>
                    </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
