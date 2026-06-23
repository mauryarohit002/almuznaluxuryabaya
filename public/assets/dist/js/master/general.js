$(document).ready(function () {});
const set_general_field = (id) => {
  const path = `master/general/handler`;
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
          $(`#general_name`).val(data[0][`general_name`]);
          $(`#general_opening_amt`).val(data[0][`general_opening_amt`]);
          $(`#general_drcr`).val(data[0]["general_drcr"]);
          $(`#general_status`).bootstrapToggle(
            data[0][`general_status`] == 1 ? "on" : "off"
          );
          $(`#general_grp_id`).html(
            `<option value="${data[0][`general_grp_id`]}">${
              data[0]["grp_name"]
            }</option>`
          );
          $("#popup_modal_sm").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const general_popup = (args) => {
  const { action = "add", id = 0, field = undefined } = args;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">${action} general a/c</p>
                </div>`;
  let data = `<form class="form-horizontal" id="general_form" onsubmit="add_update_general(${id}, ${field})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 floating-label">
                        <input 
                          type="text" 
                          class="form-control floating-input" 
                          id="general_name" 
                          name="general_name" 
                          onkeyup="validate_textfield(this, ${true})" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">general <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="general_name_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 floating-label">
                        <p class="text-uppercase">grp</p>
                        <select
                            class="form-contrl floating-select"
                            id="general_grp_id"
                            name="general_grp_id"
                        ></select>
                        <small class="form-text text-muted helper-text" id="general_grp_id_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 floating-label d-none">
                        <p class="text-uppercase">DR/CR</p>
                        <select
                            class="form-contrl floating-select"
                            id="general_drcr"
                            name="general_drcr"
                        >
                          <option value="DR">DR</option>
                          <option value="CR">CR</option>
                        </select>
                        <small class="form-text text-muted helper-text" id="general_drcr_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label d-none">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="general_opening_amt" 
                          name="general_opening_amt" 
                          value="0.00"
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">opening amt</label>
                        <small class="form-text text-muted helper-text" id="general_opening_amt_msg"></small>
                      </div>
                      ${
                        field == undefined
                          ? `<div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                              <input 
                                type="checkbox" 
                                id="general_status" 
                                name="general_status" 
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
                          : `<input type="hidden" name="general_status" value="1">`
                      }
                    </div>              
                  </div>              
                </div>              
              </form>`;

  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              onclick="add_update_general(${id}, ${field})" 
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
    $(`#general_status`).bootstrapToggle();
  } else {
    set_general_field(id);
  }
  setTimeout(() => {
    $(`#general_name`).focus();
  }, RELOAD_TIME);
  $("#general_grp_id").select2(
    select2_default({
      url: `master/grp/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
};
const add_update_general = (id, field) => {
  event.preventDefault();
  let check = true;
  notifier(`general_name`);
  notifier(`general_category_id`);
  if ($(`#general_name`).val() == "") {
    notifier(`general_name`, "Required");
    check = false;
  }
  if ($(`#general_grp_id`).val() == null) {
    notifier(`general_grp_id`, "Required");
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
    let path = `master/general/handler`;
    let form_data = $(`#general_form`).serialize();
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
              $(`#general_name`).val("").focus();
              $("#transaction_wrapper").html("");
              notifier(`general_name`);
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
const general_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.general_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">general : </td>
                    <td width="70%">${data.general_name}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">group : </td>
                    <td width="70%">${data.grp_name}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">dr/cr : </td>
                    <td width="70%">${data.general_drcr}</td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold" width="30%" align="right">STATUS : </td>
                    <td width="70%">
                      ${data.general_status == 1 ? "active" : "inactive"}
                    </td>
                  </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
