$(document).ready(function () {});
const set_size_field = (id) => {
  const path = `master/size/handler`;
  const form_data = { func: "get_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data["master_data"] && data["master_data"].length != 0) {
          $(`#size_name`).val(data["master_data"][0][`size_name`]);
          $(`#size_name`).prop("readonly", true);

          $(`#size_status`).bootstrapToggle(
            data["master_data"][0][`size_status`] == 1 ? "on" : "off"
          );
          $("#popup_modal_sm").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const size_popup = (args) => {
  const { action = "add", id = 0, field = undefined } = args;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">${action} size</p>
                </div>`;
  let data = `<form class="form-horizontal" id="size_form" onsubmit="add_update_size(${id}, ${field})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                        <input 
                          type="text" 
                          class="form-control floating-input" 
                          id="size_name" 
                          name="size_name" 
                          onkeyup="validate_textfield(this, ${true})" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">size <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="size_name_msg"></small>
                      </div>
                      ${
                        field == undefined
                          ? `<div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                              <input 
                                type="checkbox" 
                                id="size_status" 
                                name="size_status" 
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
                          : `<input type="hidden" name="size_status" value="1">`
                      }                           
                    </div>              
                  </div>              
                </div>              
              </form>`;

  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              onclick="add_update_size(${id}, ${field})" 
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
    $(`#size_status`).bootstrapToggle();
     setTimeout(() => {
      $(`#size_name`).focus();
    }, RELOAD_TIME);
  } else {
    set_size_field(id);
  }
};
const remove_size_notifier = () => {
  notifier(`size_name`);
};
const add_update_size = (id, field) => {
  event.preventDefault();
  let check = true;
  remove_size_notifier();
  if ($(`#size_name`).val() == "") {
    notifier(`size_name`, "Required");
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
    let path = `master/size/handler`;
    let form_data = $(`#size_form`).serialize();
    form_data += `&func=${
      field == undefined ? "add_update" : "add_update_size"
    }&id=${id}`;
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
              $(`#size_name`).val("").focus();
              $("#transaction_wrapper").html("");
              remove_size_notifier();
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
const size_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.size_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">size : </td>
                          <td width="70%">${data.size_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">STATUS : </td>
                          <td width="70%">
                            ${data.size_status == 1 ? "active" : "inactive"}
                          </td>
                      </tr>
                  </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};