$(document).ready(function () {});
const set_apparel_field = (id) => {
  const path = `master/apparel/handler`;
  const form_data = { func: "get_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
         const {master_data, apparel_data} = data;
        if (master_data && master_data.length != 0) {
          $(`#apparel_name`).val(master_data[0][`apparel_name`]);
          $(`#apparel_charges`).val(master_data[0][`apparel_charges`]);
          $(`#apparel_status`).bootstrapToggle(
            master_data[0][`apparel_status`] == 1 ? "on" : "off"
          );
          $("#popup_modal_sm").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};
const apparel_popup = (args) => {
  const { action = "add", id = 0, field = undefined } = args;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">${action} apparel</p>
                </div>`;
  let data = `<form class="form-horizontal" id="apparel_form" onsubmit="add_update_apparel(${id}, ${field})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-8 col-lg-8 floating-label">
                        <input 
                          type="text" 
                          class="form-control floating-input" 
                          id="apparel_name" 
                          name="apparel_name" 
                          onkeyup="validate_textfield(this, ${true})" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">apparel <span class="text-danger">*</span></label>
                        <small class="form-text text-muted helper-text" id="apparel_name_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="apparel_charges" 
                          name="apparel_charges" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">charges</label>
                        <small class="form-text text-muted helper-text" id="apparel_charges_msg"></small>
                      </div>
                      ${
                        field == undefined
                          ? `<div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                              <input 
                                type="checkbox" 
                                id="apparel_status" 
                                name="apparel_status" 
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
                          : `<input type="hidden" name="apparel_status" value="1">`
                      }
                    </div>              
                  </div>              
                </div>              
              </form>`;

  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              onclick="add_update_apparel(${id}, ${field})" 
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
    $(`#apparel_status`).bootstrapToggle();
    setTimeout(() => {
      $(`#apparel_name`).focus();
    }, RELOAD_TIME);
  } else {
    set_apparel_field(id);
    setTimeout(() => {
      $(`#pincode`).focus();
    }, RELOAD_TIME);
  }
  
};
const add_update_apparel = (id, field) => {
  event.preventDefault();
  let check = true;
  notifier(`apparel_name`);
  if ($(`#apparel_name`).val() == "") {
    notifier(`apparel_name`, "Required");
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
    let path = `master/apparel/handler`;
    let form_data = $(`#apparel_form`).serialize();
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
              $(`#apparel_name`).val("").focus();
              $("#transaction_wrapper").html("");
              notifier(`apparel_name`);
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
const apparel_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.apparel_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">apparel : </td>
                          <td width="70%">${data.apparel_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">category : </td>
                          <td width="70%">${data.category_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">STATUS : </td>
                          <td width="70%">
                            ${data.apparel_status == 1 ? "active" : "inactive"}
                          </td>
                      </tr>
                  </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};

// apparel_process_trans
let proces_cnt = 1;
const get_process_data = (id, isNew = false) => {
  const path = `master/apparel/handler`;
  const form_data = { func: "get_process_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          if (isNew) {
            data.forEach((row) =>
              add_wrapper_data({
                apt_id: 0,
                apt_sequence: row["apt_sequence"],
                apt_proces_id: row["apt_proces_id"],
                proces_name: row["proces_name"],
                isExist: row["isExist"],
              })
            );
          } else {
            data.forEach((row) => add_wrapper_data(row));
          }
        }
      }
    },
    (errmsg) => {}
  );
};
const apparel_process_popup = (id) => {
  const process_args = JSON.stringify({
    sub_menu: "proces",
    field: "apt_proces_id",
  });
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">define apparel process</p>
                </div>`;
  let body = `<form class="form-horizontal" id="apparel_process_form" onsubmit="add_update_apparel_process(${id})">              
                <div class="d-flex flex-wrap form-group floating-form">
                  <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label">
                    <p class="text-uppercase">copy from</p>
                    <select 
                      class="form-control floating-select" 
                      id="copy_from" 
                      placeholder=" " 
                    ></select>
                    <small class="form-text text-muted helper-text" id="copy_from_msg"></small>
                  </div>
                  <div class="col-12 col-sm-12 col-md-5 col-lg-5 floating-label">
                    <p class="text-uppercase">process&nbsp;<span class="text-danger">*</span>
                      <span>
                        <a 
                          data-toggle="tooltip" 
                          data-placement="top" 
                          title="ADD PROCESS"
                          style="cursor: pointer;" 
                          onclick='popup(${process_args})'
                        ><i class="fa fa-plus"></i></a>
                      </span>
                    </p>
                    <select 
                      class="form-control floating-select" 
                      id="apt_proces_id" 
                      name="apt_proces_id" 
                      placeholder=" " 
                      onkeyup="validate_dropdown(this, ${false})"
                    ></select>
                    <small class="form-text text-muted helper-text" id="apt_proces_id_msg"></small>
                  </div>
                  <div class="col-12 col-sm-12 col-md-2 col-lg-2 floating-label">
                    <button 
                      type="button" 
                      class="btn btn-md btn-block btn-primary" 
                      onclick="add_transaction(${id})"   
                    ><i class="text-success fa fa-plus"></i></button>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12" style="max-height:52vh; overflow-x: auto;">
                    <table class="table table-sm">
                      <tbody id="transaction_wrapper"></tbody>
                    </table>
                  </div>                            
                </div>               
              </form>`;

  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              onclick="add_update_apparel_process(${id})" 
              style="width:15%;"
            >
              <div class="stage d-none"><div class="dot-flashing"></div></div>
              <div class="dot-text text-primary text-uppercase">add</div>
            </button>
            <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">CANCEL</button>`;

  $(".modal-title-sm").html(title);
  $(".modal-body-sm").html(body);
  $(".modal-footer-sm").html(btn);
  $("#popup_modal_sm").modal("show");
  get_process_data(id);
  setTimeout(() => {
    $(`#apt_sequence`).focus();
  }, RELOAD_TIME);

  $("#copy_from")
    .select2(
      select2_default({
        url: `master/apparel/get_select2/_copy`,
        placeholder: "SELECT",
        param: true,
        param1: id,
      })
    )
    .on("change", (event) => get_process_data(event.target.value, true));
  $("#apt_proces_id").select2(
    select2_default({
      url: `master/proces/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
};
const add_transaction = (id) => {
  event.preventDefault();
  notifier("apt_proces_id");
  let check = true;
  let dup_check = true;
  if ($("#apt_proces_id").val() == null) {
    notifier("apt_proces_id", "Required");
    check = false;
  }
  let total_tr = $("#transaction_wrapper > tr").length;
  if (total_tr > 0) {
    let new_proces_id = $(`#apt_proces_id`).val();
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#transaction_wrapper > tr:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];

      let old_proces_id = $(`#apt_proces_id_${id}`).val();
      if (new_proces_id == old_proces_id) {
        notifier("apt_proces_id", "Already added.");
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
    toastr.error("Process already added.", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
  } else {
    add_wrapper_data({
      apt_id: 0,
      apt_sequence: 0,
      apt_proces_id: $("#apt_proces_id").val(),
      proces_name: $("#apt_proces_id :selected").text(),
      isExist: false,
    });
    set_serial_no();
    toastr.success(
      $("#apt_proces_id :selected").text(),
      "Process added to list.",
      {
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
      }
    );
    $("#apt_proces_id").val(null).trigger("change");
  }
};
const add_wrapper_data = (data) => {
  const { apt_id, apt_sequence, apt_proces_id, proces_name, isExist } = data;
  let tr = `<tr class="floating-form" id="rowid_${proces_cnt}">
              <td class="floating-label border-0" width="10%">
                <input 
                  type="hidden" 
                  id="apt_id_${proces_cnt}" 
                  name="apt_id[${proces_cnt}]" 
                  value="${apt_id}" 
                />
                <input 
                  type="number" 
                  class="form-control floating-input" 
                  id="apt_sequence_${proces_cnt}" 
                  name="apt_sequence[${proces_cnt}]" 
                  value="${apt_sequence}" 
                  placeholder=" "
                  autocomplete="off"
                  readonly
                />
              </td>
              <td class="floating-label border-0" width="40%">
                  <select 
                    class="form-control floating-select" 
                    id="apt_proces_id_${proces_cnt}" 
                    name="apt_proces_id[${proces_cnt}]" 
                    placeholder=" " 
                    onkeyup="validate_dropdown(this, ${true})"
                  ><option value="${apt_proces_id}">${proces_name}</option></select>
                  <small class="form-text text-muted helper-text" id="apt_proces_id_${proces_cnt}_msg"></small>
              </td>
              <td class="border-0" width="15%">
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
                        onclick="remove_transaction('${proces_cnt}')" 
                        data-toggle="tooltip" 
                        title="REMOVE PROCESS" 
                        data-placement="top"
                      ><i class="text-danger fa fa-trash"></i></button>`
                }
              </td>
              <td width="10%"></td>
            </tr>`;
  $("#transaction_wrapper").prepend(tr);
  $(`#apt_proces_id_${proces_cnt}`).select2(
    select2_default({
      url: `master/proces/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  proces_cnt++;
};
const remove_transaction = (cnt) => {
  let sequence = $(`#apt_sequence_${cnt}`).val();
  toastr.success(`${sequence}`, "PROCESS REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#rowid_${cnt}`).detach();
  set_serial_no();
};
const check_transaction = () => {
  let flag = true;
  let total_tr = $("#transaction_wrapper > tr").length;
  if (total_tr > 0) {
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#transaction_wrapper > tr:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];

      if (
        $(`#apt_proces_id_${id}`).val() == 0 ||
        $(`#apt_proces_id_${id}`).val() == "" ||
        $(`#apt_proces_id_${id}`).val() == null
      ) {
        notifier(`apt_proces_id_${id}`, "Required");
        flag = false;
      } else {
        notifier(`apt_proces_id_${id}`);
      }
    }
  } else {
    flag = false;
  }
  return flag;
};
const check_duplicate = (cntCheck) => {
  let total_tr = $("#transaction_wrapper > tr").length;
  let new_proces_id = $(`#apt_proces_id_${cntCheck}`).val();
  for (let i = 1; i <= total_tr; i++) {
    let cnt = $(`#transaction_wrapper > tr:nth-child(${i})`).attr("id");
    let explode = cnt.split("_");
    let id = explode[1];
    if (cntCheck != id) {
      let old_proces_id = $(`#apt_proces_id_${id}`).val();
      if (new_proces_id == old_proces_id) {
        notifier(`apt_proces_id_${cntCheck}`, "Already added.");
        notifier(`apt_proces_id_${id}`, "Already added.");
        return 0;
      } else {
        notifier(`apt_proces_id_${cntCheck}`);
        notifier(`apt_proces_id_${id}`);
      }
    }
  }
  return 1;
};
const add_update_apparel_process = (id) => {
  event.preventDefault();
  let check = true;
  let duplicate_row = true;
  let required_row = true;
  if (check_transaction()) {
    if ($("#transaction_wrapper > tr").length > 0) {
      for (let i = 1; i <= $("#transaction_wrapper > tr").length; i++) {
        let cnt = $(`#transaction_wrapper > tr:nth-child(${i})`).attr("id");
        let explode = cnt.split("_");
        let id = explode[1];
        if (check_duplicate(id) == 0) {
          duplicate_row = false;
        }
      }
    }
  } else {
    required_row = false;
  }
  if ($("#transaction_wrapper > tr").length <= 0) {
    toastr.error("Process not define.", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else if (!required_row) {
    toastr.error("You forgot to enter some item information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
  } else if (!duplicate_row) {
    toastr.error("Duplicate process found!!!", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
  } else {
    let path = `master/apparel/handler`;
    let form_data = $(`#apparel_process_form`).serialize();
    form_data += `&func=add_update_apparel_process&id=${id}`;
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;

          toastr.success("", msg, { closeButton: true, progressBar: true });
          $("#popup_modal_sm").modal("hide");
        }
      },
      (errmsg) => {}
    );
  }
};
const set_serial_no = () => {
  let total_tr = $("#transaction_wrapper > tr").length;
  let sr_no = total_tr;
  for (let i = 1; i <= total_tr; i++) {
    let cnt = $(`#transaction_wrapper > tr:nth-child(${i})`).attr("id");
    let explode = cnt.split("_");
    let id = explode[1];
    $(`#apt_sequence_${id}`).val(sr_no);
    sr_no = sr_no - 1;
  }
};
// apparel_process_trans
