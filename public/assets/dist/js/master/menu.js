$(document).ready(function () {});
let menu_cnt = 1;
let mat_cnt = 1;
const get_transaction = () => {
  if (get_url_string("action") == "edit") {
    let id = get_url_string("id");
    if (id) {
      const path = `${link}/${sub_link}/handler`;
      const form_data = { func: "get_transaction", id };
      ajaxCall(
        "POST",
        path,
        form_data,
        "JSON",
        (resp) => {
          if (handle_response(resp)) {
            const { data, msg } = resp;
            if (data && data.length != 0) {
              data.forEach((row) => add_wrapper(row));
            }
          }
        },
        (errmsg) => {}
      );
    }
  }
};
const remove_sub_menu_notifier = () => {
  notifier("mt_name");
  notifier("mt_js");
  notifier("mt_url");
};
const add_sub_menu_row = () => {
  remove_sub_menu_notifier();
  let check = true;
  let dup_check = true;
  let total_tr = $("#menu_wrapper > tbody").length;
  if ($("#mt_name").val() == "") {
    notifier("mt_name", "Required");
    check = false;
  }
  if ($("#mt_js").val() == "") {
    notifier("mt_js", "Required");
    check = false;
  }
  if ($("#mt_url").val() == "") {
    notifier("mt_url", "Required");
    check = false;
  }
  if (total_tr > 0) {
    let new_js = $("#mt_js").val();
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#menu_wrapper > tbody:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];

      let old_js = $(`#mt_js_${id}`).val();
      if (new_js == old_js) {
        notifier("mt_js", "Already added.");
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
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else if (!dup_check) {
    toastr.error("Duplicate sub menu found!!!", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    add_wrapper({
      mt_id: 0,
      mt_name: $("#mt_name").val(),
      mt_js: $("#mt_js").val(),
      mt_url: $("#mt_url").val(),
      mt_status: $("#mt_status").is(":checked"),
      mt_type: 2,
    });

    toastr.success($("#mt_name").val(), "SUB MENU ADDED TO LIST.", {
      closeButton: true,
      progressBar: true,
    });
    $("#mt_name").val("").focus();
    $("#mt_js").val("");
    $("#mt_url").val("");
    $("#mt_status").bootstrapToggle("on");
  }
};
const add_action_row = (_cnt) => {
  notifier(`_action_${_cnt}`);
  let check = true;
  let dup_check = true;
  let total_tr = $(`#action_wrapper_${_cnt} > div`).length;
  if ($(`#_action_${_cnt}`).val() == "") {
    notifier(`_action_${_cnt}`, "Required");
    check = false;
  }
  if (total_tr > 0) {
    let new_action = $(`#_action_${_cnt}`).val();
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#action_wrapper_${_cnt} > div:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];
      let old_action = $(`#mat_action_${id}`).val();
      if (new_action == old_action) {
        notifier(`_action_${_cnt}`, "Already added.");
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
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else if (!dup_check) {
    toastr.error("Duplicate action found!!!", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    add_action_wrapper(
      {
        mat_id: 0,
        mat_action: $(`#_action_${_cnt}`).val(),
        mat_status: 1,
        mat_type: 2,
      },
      _cnt
    );

    toastr.success($(`#_action_${_cnt}`).val(), "ACTION ADDED TO LIST.", {
      closeButton: true,
      progressBar: true,
    });
    $(`#_action_${_cnt}`).val("").focus();
  }
};
const add_wrapper = (data) => {
  const { mt_id, mt_name, mt_js, mt_url, mt_status, mt_type, action_data } =
    data;
  let tbody = `<tbody class="border" id="rowmenu_${menu_cnt}">
                <tr class="floating-form">
                  <td class="floating-label border-0" width="10%">
                    <input 
                      type="hidden" 
                      name="mt_id[${menu_cnt}]" 
                      id="mt_id_${menu_cnt}" 
                      value="${mt_id}" 
                    />
                    <input 
                      type="hidden" 
                      name="mt_type[${menu_cnt}]" 
                      id="mt_type_${menu_cnt}" 
                      value="${mt_type}" 
                    />
                    <input 
                      type="text" 
                      class="form-control floating-input" 
                      name="mt_name[${menu_cnt}]" 
                      id="mt_name_${menu_cnt}" 
                      value="${mt_name}"
                      placeholder=" " 
                      autocomplete="off"
                      onkeyup="validate_textfield(this)"  
                    />
                  </td>
                  <td class="floating-label border-0" width="10%">
                    <input 
                      type="text" 
                      class="form-control floating-input text-lowercase" 
                      name="mt_js[${menu_cnt}]" 
                      id="mt_js_${menu_cnt}" 
                      value="${mt_js}"
                      placeholder=" " 
                      autocomplete="off"
                      onkeyup="validate_textfield(this)"  
                    />
                  </td>
                  <td class="floating-label border-0" width="10%">
                    <input 
                      type="text" 
                      class="form-control floating-input text-lowercase" 
                      name="mt_url[${menu_cnt}]" 
                      id="mt_url_${menu_cnt}" 
                      value="${mt_url}"
                      placeholder=" " 
                      autocomplete="off"
                      onkeyup="validate_textfield(this)"  
                    />
                  </td>
                  <td class="border-0" width="2%">
                    <input 
                      type="checkbox" 
                      id="mt_status_${menu_cnt}" 
                      name="mt_status[${menu_cnt}]" 
                      data-toggle="toggle" 
                      data-on="ACTIVE" 
                      data-off="INACTIVE" 
                      data-onstyle="primary" 
                      data-offstyle="primary" 
                      data-width="100" 
                      data-size="normal" 
                      ${mt_status == 1 ? "checked" : ""}
                    />
                  </td>
                  <td class="border-0" width="2%">
                    <button 
                      type="button" 
                      class="btn btn-md btn-primary" 
                      onclick="remove_sub_menu_trans(${menu_cnt})"
                    ><i class="text-danger fa fa-trash"></i></button>
                  </td>
                </tr>
                <tr>
                  <td class="border-0" width="10%">
                    <div class="d-flex flex-wrap">
                      <div>
                        <input 
                          type="text" 
                          class="form-control floating-input text-lowercase" 
                          id="_action_${menu_cnt}" 
                          placeholder=" " 
                          autocomplete="off"
                        />
                        <small class="form-text text-muted helper-text" id="_action_${menu_cnt}_msg"></small>
                      </div>
                      <div class="px-2">
                        <button 
                          type="button" 
                          class="btn btn-md btn-primary" 
                          data-toggle="tooltip" 
                          title="ADD ACTION" 
                          data-placement="top"
                          onclick="add_action_row(${menu_cnt})"   
                        ><i class="text-success fa fa-plus"></i></button>
                      </div>
                    </div>
                  </td>
                  <td  width="90%" colspan="5">
                    <div class="d-flex flex-wrap" id="action_wrapper_${menu_cnt}"></div>
                  </td>
                </tr>
              </tbody>`;
  $("#menu_wrapper").prepend(tbody);
  $(`#mt_status_${menu_cnt}`).bootstrapToggle();
  if (action_data && action_data.length != 0) {
    action_data.forEach((row) => add_action_wrapper(row, menu_cnt));
  }
  menu_cnt++;
};
const add_action_wrapper = (data, _cnt) => {
  const { mat_id, mat_action, mat_status, mat_type } = data;
  let div = `<div class="px-4 pt-2" id="rowaction_${mat_cnt}">
              <input 
                  type="hidden" 
                  name="mat_id[${_cnt}][${mat_cnt}]" 
                  id="mat_id_${mat_cnt}" 
                  value="${mat_id}" 
              />
              <input 
                  type="hidden" 
                  name="mat_status[${_cnt}][${mat_cnt}]" 
                  id="mat_status_${mat_cnt}" 
                  value="${mat_status}" 
              />
              <input 
                  type="hidden" 
                  name="mat_type[${_cnt}][${mat_cnt}]" 
                  id="mat_type_${mat_cnt}" 
                  value="${mat_type}" 
              />
              <label class="custom-control material-checkbox">
                <input 
                  type="checkbox" 
                  class="material-control-input parent_action_${_cnt}" 
                  id="_mat_action_${mat_cnt}"
                  onchange="set_action_status(${mat_cnt})"
                  ${mat_status == 1 ? "checked" : ""}
                />
                <span class="material-control-indicator"></span>
                <span class="material-control-description text-uppercase">
                  <input 
                    type="text"
                    class="form-control"
                    name="mat_action[${_cnt}][${mat_cnt}]" 
                    id="mat_action_${mat_cnt}" 
                    value="${mat_action}" 
                    style="width:8rem; height:1.5rem; background:none;"
                  />
                </span>
              </label>
            </div>`;
  $(`#action_wrapper_${_cnt}`).prepend(div);
  mat_cnt++;
};
const set_action_status = (cnt) => {
  if ($(`#_action_${cnt}`).is(":checked")) {
    $(`#mat_status_${cnt}`).val(1);
  } else {
    $(`#mat_status_${cnt}`).val(0);
  }
};
const remove_sub_menu_trans = (cnt) => {
  toastr.success(
    `${$(`#mt_name_${cnt}`).val()}`,
    "SUB MENU REMOVED FROM LIST.",
    { closeButton: true, progressBar: true }
  );
  $(`#rowmenu_${cnt}`).detach();
};
const remove_menu_notifier = () => {
  notifier(`menu_name`);
  notifier(`menu_js`);
};
const check_sub_menu_trans = () => {
  let last_id = 0;
  let flag = true;
  let total_tr = $("#menu_wrapper > tbody").length;
  if (total_tr > 0) {
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#menu_wrapper > tbody:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];
      if ($(`#mt_name_${id}`).val() == "") {
        notifier(`mt_name_${id}`, "Required");
        last_id = id;
        flag = false;
      } else {
        notifier(`mt_name_${id}`);
      }
      if ($(`#mt_js_${id}`).val() == "") {
        notifier(`mt_js_${id}`, "Required");
        last_id = id;
        flag = false;
      } else {
        notifier(`mt_js_${id}`);
      }
      if ($(`#mt_url_${id}`).val() == "") {
        notifier(`mt_url_${id}`, "Required");
        last_id = id;
        flag = false;
      } else {
        notifier(`mt_url_${id}`);
      }
    }
  }
  return flag;
};
const check_duplicate_sub_menu = (cntCheck) => {
  let total_tr = $("#menu_wrapper > tbody").length;
  let new_js = $(`#mt_js_${cntCheck}`).val();
  for (let i = 1; i <= total_tr; i++) {
    let cnt = $(`#menu_wrapper > tbody:nth-child(${i})`).attr("id");
    let explode = cnt.split("_");
    let id = explode[1];
    if (cntCheck != id) {
      let old_js = $(`#mt_js_${id}`).val();
      if (new_js == old_js) {
        notifier(`mt_js_${cntCheck}`, "Already added.");
        notifier(`mt_js_${id}`, "Already added.");
        return 0;
      } else {
        notifier(`mt_js_${cntCheck}`);
        notifier(`mt_js_${id}`);
      }
    }
  }
  return 1;
};
const add_edit = () => {
  event.preventDefault();
  let check = true;
  let duplicate_sub_menu = true;
  let required_sub_menu = true;
  remove_menu_notifier();
  if ($(`#menu_name`).val() == "") {
    notifier(`menu_name`, "Required");
    check = false;
  }
  if ($(`#menu_js`).val() == "") {
    notifier(`menu_js`, "Required");
    check = false;
  }
  if ($("#menu_wrapper > tbody").length > 0) {
    if (check_sub_menu_trans()) {
      for (let i = 1; i <= $("#menu_wrapper > tbody").length; i++) {
        let cnt = $(`#menu_wrapper > tbody:nth-child(${i})`).attr("id");
        let explode = cnt.split("_");
        let id = explode[1];
        if (check_duplicate_sub_menu(id) == 0) {
          duplicate_sub_menu = false;
        }
      }
    } else {
      required_sub_menu = false;
    }
  } else {
    required_sub_menu = false;
  }

  if (!required_sub_menu) {
    toastr.error(
      "You forgot to enter some sub menu information.",
      "Oh snap!!!",
      { closeButton: true, progressBar: true, preventDuplicates: true }
    );
  } else if (!duplicate_sub_menu) {
    toastr.error("Duplicate sub menu found!!!", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
  } else if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    const id = $("#menu_id").val();
    const path = `${link}/${sub_link}/handler`;
    let form_data = $(`#_form`).serialize();
    form_data += `&func=add_edit`;
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { msg } = resp;
          if (id == 0) {
          } else {
          }
          remove_menu_notifier();
          toastr.success("", msg, {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
          });
          $("body, html").animate({ scrollTop: 0 }, 1000);
          setTimeout(function () {
            window.location.reload();
          }, 2000);
        }
      },
      (errmsg) => {}
    );
  }
};
const menu_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.menu_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">name : </td>
                        <td width="70%">${data.menu_name}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">js : </td>
                        <td width="70%">${data.menu_js}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">status : </td>
                        <td width="70%">
                          ${data.menu_status == 1 ? "active" : "inactive"}
                        </td>
                    </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
