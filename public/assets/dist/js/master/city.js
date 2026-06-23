$(document).ready(function () {});
let pincode_cnt = 1;
const set_city_field = (id) => {
  const path = `master/city/handler`;
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
          $(`#city_name`).val(data["master_data"][0][`city_name`]);
          $(`#city_name`).prop("readonly", true);
          $(`#city_state_id`).html(
            `<option value="${data["master_data"][0][`city_state_id`]}">${
              data["master_data"][0]["state_name"]
            }</option>`
          );
          $(`#city_state_id`).prop("disabled", true);
          $(`#state_id`).val(data["master_data"][0][`city_state_id`]);
          $(`#city_country_id`).html(
            `<option value="${data["master_data"][0][`city_country_id`]}">${
              data["master_data"][0]["country_name"]
            }</option>`
          );
          $(`#city_country_id`).prop("disabled", true);
          $(`#country_id`).val(data["master_data"][0][`city_country_id`]);
          $(`#city_status`).bootstrapToggle(
            data["master_data"][0][`city_status`] == 1 ? "on" : "off"
          );
          $("#popup_modal_lg").modal("show");
        }
        if (data["trans_data"] && data["trans_data"].length != 0) {
          data["trans_data"].forEach((row) => add_wrapper_data(row));
        }
      }
    },
    (errmsg) => {}
  );
};
const get_default_country = () => {
  const path = `master/city/handler`;
  const form_data = { func: "get_default_country" };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data) {
          $(`#city_country_id`).html(
            `<option value="${data[0]["country_id"]}">${data[0]["country_name"]}</option>`
          );
          $(`#country_id`).val(data[0]["country_id"]);
        }
      }
    },
    (errmsg) => {}
  );
};
const city_popup = (args) => {
  const { action = "add", id = 0, field = undefined } = args;
  const state_args = JSON.stringify({
    sub_menu: "state",
    field: "city_state_id",
  });
  const country_args = JSON.stringify({
    sub_menu: "country",
    field: "city_country_id",
  });
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">${action} city</p>
                </div>`;
  let data = `<form class="form-horizontal" id="city_form" onsubmit="add_update_city(${id}, ${field})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                        <input 
                          type="text" 
                          class="form-control floating-input" 
                          id="city_name" 
                          name="city_name" 
                          onkeyup="validate_textfield(this, ${true})" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">city <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="city_name_msg"></small>
                      </div>
								      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="text" 
                          class="form-control floating-input" 
                          id="pincode"
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">pincode <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="pincode_msg"></small>
                      </div>
								      <div class="col-12 col-sm-12 col-md-2 col-lg-2 floating-label">
                        <button 
                          type="button" 
                          class="btn btn-md btn-block btn-primary" 
                          onclick="add_transaction(${id})"   
                        ><i class="text-success fa fa-plus"></i></button>
								      </div>
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
									      <p class="text-uppercase">state&nbsp;<span class="text-danger">*</span>
                          <span>
                            <a 
                              data-toggle="tooltip" 
                              data-placement="top" 
                              title="ADD STATE"
                              style="cursor: pointer;" 
                              onclick='popup(${state_args})'
                            ><i class="fa fa-plus"></i></a>
                          </span>
                        </p>
                        <select 
                          class="form-control floating-select" 
                          id="city_state_id" 
                          name="city_state_id" 
                          placeholder=" " 
                          onkeyup="validate_dropdown(this, ${true})"
                        ></select>
                        <input type="hidden" name="state_id" id="state_id" />
                        <small class="form-text text-muted helper-text" id="city_state_id_msg"></small>
                      </div>
								      <div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
									      <p class="text-uppercase">country&nbsp;<span class="text-danger">*</span>
                          <span>
                            <a 
                              data-toggle="tooltip" 
                              data-placement="top" 
                              title="ADD COUNTRY"
                              style="cursor: pointer;" 
                              onclick='popup(${country_args})'
                            ><i class="fa fa-plus"></i></a></span>
									      </p>
                        <select 
                          class="form-control floating-select" 
                          id="city_country_id" 
                          name="city_country_id" 
                          placeholder=" " 
                          onkeyup="validate_dropdown(this, ${true})"
                        ></select>
                        <input type="hidden" name="country_id" id="country_id" />
                        <small class="form-text text-muted helper-text" id="city_country_id_msg"></small>
                      </div>
                      ${
                        field == undefined
                          ? `<div class="col-12 col-sm-12 col-md-6 col-lg-6 floating-label">
                              <input 
                                type="checkbox" 
                                id="city_status" 
                                name="city_status" 
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
                          : `<input type="hidden" name="city_status" value="1">`
                      }
                      <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <table class="table table-sm table-responsive" style="max-height:40vh;">
                          <tbody id="transaction_wrapper"></tbody>
                        </table>
                      </div>                            
                    </div>              
                  </div>              
                </div>              
              </form>`;

  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              onclick="add_update_city(${id}, ${field})" 
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
    $(`#city_status`).bootstrapToggle();
    get_default_country();
    setTimeout(() => {
      $(`#city_name`).focus();
    }, RELOAD_TIME);
  } else {
    set_city_field(id);
    setTimeout(() => {
      $(`#pincode`).focus();
    }, RELOAD_TIME);
  }

  $("#city_state_id").select2(
    select2_default({
      url: `master/state/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $("#city_country_id").select2(
    select2_default({
      url: `master/country/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
};
const remove_city_notifier = () => {
  notifier(`city_name`);
  notifier(`city_state_id`);
  notifier(`city_country_id`);
};
const add_update_city = (id, field) => {
  event.preventDefault();
  let check = true;
  remove_city_notifier();
  if ($(`#city_name`).val() == "") {
    notifier(`city_name`, "Required");
    check = false;
  }
  if ($(`#city_state_id`).val() == null) {
    notifier(`city_state_id`, "Required");
    check = false;
  }
  if ($(`#city_country_id`).val() == null) {
    notifier(`city_country_id`, "Required");
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
    let path = `master/city/handler`;
    let form_data = $(`#city_form`).serialize();
    form_data += `&func=${
      field == undefined ? "add_update" : "add_update_city"
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
              $("#popup_modal_lg").modal("hide");
              // refresh_dropdown_select2(data, field);
            } else {
              $(`#city_name`).val("").focus();
              $("#transaction_wrapper").html("");
              remove_city_notifier();
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
const city_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.city_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">city : </td>
                          <td width="70%">${data.city_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">state : </td>
                          <td width="70%">${data.state_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">STATUS : </td>
                          <td width="70%">
                            ${data.city_status == 1 ? "active" : "inactive"}
                          </td>
                      </tr>
                  </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};
const add_transaction = (id) => {
  event.preventDefault();
  notifier("pincode");
  let check = true;
  let dup_check = true;
  let new_pincode = $("#pincode").val();
  let total_tr = $("#transaction_wrapper > tr").length;
  if ($("#pincode").val() == "") {
    notifier("pincode", "Required");
    check = false;
  }
  if (total_tr > 0) {
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#transaction_wrapper > tr:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];

      let old_pincode = $(`#cpt_pincode_${id}`).val();
      if (new_pincode == old_pincode) {
        notifier("pincode", "Already added.");
        dup_check = false;
      }
    }
  }
  if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
  } else if (!dup_check) {
    toastr.error("Duplicate pincode found!!!", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
  } else {
    add_wrapper_data({
      cpt_id: 0,
      cpt_pincode: new_pincode,
      isExist: false,
    });
    toastr.success(new_pincode, "Pincode added to list.", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("#pincode").val("").focus();
  }
};
const add_wrapper_data = (data) => {
  const { cpt_id, cpt_pincode, isExist } = data;
  let tr = `<tr class="floating-form" id="rowid_${pincode_cnt}">
				<td class="floating-label border-0" width="95%">
					<input 
						type="hidden" 
						id="cpt_id_${pincode_cnt}" 
						name="cpt_id[${pincode_cnt}]" 
						value="${cpt_id}" 
					/>
					<input 
						type="text" 
						class="form-control floating-input" 
						id="cpt_pincode_${pincode_cnt}" 
						name="cpt_pincode[${pincode_cnt}]" 
						value="${cpt_pincode}" 
						placeholder=" "
						autocomplete="off"
					/>
				</td>
				<td class="border-0" width="5%">
					${
            isExist
              ? `<button 
									type="button" 
									class="btn btn-md btn-block btn-primary" 
									data-toggle="tooltip"
								><i class="text-danger fa fa-ban"></i></button>`
              : `<button 
									type="button" 
									class="btn btn-md btn-block btn-primary" 
									onclick="remove_transaction('${pincode_cnt}')" 
									data-toggle="tooltip" 
									title="REMOVE ITEM" 
									data-placement="top"
								><i class="text-danger fa fa-trash"></i></button>`
          }
				</td>
			</tr>`;
  $("#transaction_wrapper").prepend(tr);
  pincode_cnt++;
};
const remove_transaction = (cnt) => {
  let pincode = $(`#cpt_pincode_${cnt}`).val();
  toastr.success(`${pincode}`, "PINCODE REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#rowid_${cnt}`).detach();
};
