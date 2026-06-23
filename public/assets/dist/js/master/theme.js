$(document).ready(function () {
  $("#_name")
    .select2(
      select2_default({
        url: `master/theme/get_select2/_name`,
        placeholder: "name",
      })
    )
    .on("change", () => trigger_search());
});
let theme_cnt = 1;
let mat_cnt = 1;
const get_transaction = () => {
  if (get_url_string("action") == "edit") {
    let id = get_url_string("id");
    if (id) {
      let path = `master/theme/get_transaction/${id}`;
      ajaxCall(
        "GET",
        path,
        "",
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
const remove_trans_notifier = () => {
  notifier("tt_variable");
  notifier("tt_value");
};
const add_transaction = (id) => {
  remove_trans_notifier();
  let check = true;
  let dup_check = true;
  let total_tr = $("#theme_wrapper > tbody").length;
  if ($("#tt_variable").val() == "") {
    notifier("tt_variable", "Required");
    check = false;
  }
  if ($("#tt_value").val() == "") {
    notifier("tt_value", "Required");
    check = false;
  }
  if (total_tr > 0) {
    let new_variable = $("#tt_variable").val();
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#theme_wrapper > tbody:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];

      let old_variable = $(`#tt_variable_${id}`).val();
      if (new_variable == old_variable) {
        notifier("tt_variable", "Already added.");
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
    toastr.error("Duplicate variable found!!!", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    add_wrapper({
      tt_id: 0,
      tt_variable: $("#tt_variable").val(),
      tt_value: $("#tt_value").val(),
      tt_status: $("#tt_status").is(":checked"),
    });

    toastr.success($("#tt_variable").val(), "VARIABLE ADDED TO LIST.", {
      closeButton: true,
      progressBar: true,
    });
    $("#tt_variable").val("").focus();
    $("#tt_value").val("");
    $("#tt_status").bootstrapToggle("on");
  }
};
const add_wrapper = (data) => {
  const { tt_id, tt_variable, tt_value, tt_status } = data;
  let tbody = `<tbody class="border" id="rowtheme_${theme_cnt}">
                <tr class="floating-form">
                    <td class="floating-label border-0" width="10%">
                      <input 
                        type="hidden" 
                        name="tt_id[${theme_cnt}]" 
                        id="tt_id_${theme_cnt}" 
                        value="${tt_id}" 
                      />
                      <input 
                        type="text" 
                        class="form-control floating-input" 
                        name="tt_variable[${theme_cnt}]" 
                        id="tt_variable_${theme_cnt}" 
                        value="${tt_variable}"
                        placeholder=" " 
                        autocomplete="off"
                        onkeyup="validate_textfield(this)"  
                        style="text-transform:none"
                      />
                    </td>
                    <td class="floating-label border-0" width="10%">
                      <input 
                        type="text" 
                        class="form-control floating-input text-lowercase" 
                        name="tt_value[${theme_cnt}]" 
                        id="tt_value_${theme_cnt}" 
                        value="${tt_value}"
                        placeholder=" " 
                        autocomplete="off"
                        onkeyup="validate_textfield(this)"  
                        style="text-transform:none"
                      />
                    </td>
                    <td class="border-0" width="2%">
                      <input 
                        type="checkbox" 
                        id="tt_status_${theme_cnt}" 
                        name="tt_status[${theme_cnt}]" 
                        data-toggle="toggle" 
                        data-on="ACTIVE" 
                        data-off="INACTIVE" 
                        data-onstyle="primary" 
                        data-offstyle="primary" 
                        data-width="100" 
                        data-size="normal" 
                        ${tt_status == 1 ? "checked" : ""}
                      />
                    </td>
                    <td class="border-0" width="2%">
                      <button 
                        type="button" 
                        class="btn btn-md btn-primary" 
                        onclick="remove_trans(${theme_cnt})"
                      ><i class="text-danger fa fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>`;
  $("#theme_wrapper").prepend(tbody);
  $(`#tt_status_${theme_cnt}`).bootstrapToggle();
  theme_cnt++;
};
const remove_trans = (cnt) => {
  toastr.success(
    `${$(`#tt_variable_${cnt}`).val()}`,
    "VARIABLE REMOVED FROM LIST.",
    { closeButton: true, progressBar: true }
  );
  $(`#rowtheme_${cnt}`).detach();
};
const remove_master_notifier = () => {
  notifier(`theme_name`);
};
const check_trans = () => {
  let last_id = 0;
  let flag = true;
  let total_tr = $("#theme_wrapper > tbody").length;
  if (total_tr > 0) {
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#theme_wrapper > tbody:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];
      if ($(`#tt_variable_${id}`).val() == "") {
        notifier(`tt_variable_${id}`, "Required");
        last_id = id;
        flag = false;
      } else {
        notifier(`tt_variable_${id}`);
      }
    }
  }
  return flag;
};
const check_duplicate_trans = (cntCheck) => {
  let total_tr = $("#theme_wrapper > tbody").length;
  let new_variable = $(`#tt_variable_${cntCheck}`).val();
  for (let i = 1; i <= total_tr; i++) {
    let cnt = $(`#theme_wrapper > tbody:nth-child(${i})`).attr("id");
    let explode = cnt.split("_");
    let id = explode[1];
    if (cntCheck != id) {
      let old_variable = $(`#tt_variable_${id}`).val();
      if (new_variable == old_variable) {
        notifier(`tt_variable_${cntCheck}`, "Already added.");
        notifier(`tt_variable_${id}`, "Already added.");
        return 0;
      } else {
        notifier(`tt_variable_${cntCheck}`);
        notifier(`tt_variable_${id}`);
      }
    }
  }
  return 1;
};
const add_update = (id, field) => {
  event.preventDefault();
  let check = true;
  let duplicate_check = true;
  let required_check = true;
  remove_master_notifier();
  if ($(`#theme_name`).val() == "") {
    notifier(`theme_name`, "Required");
    check = false;
  }
  if ($("#theme_wrapper > tbody").length > 0) {
    if (check_trans()) {
      for (let i = 1; i <= $("#theme_wrapper > tbody").length; i++) {
        let cnt = $(`#theme_wrapper > tbody:nth-child(${i})`).attr("id");
        let explode = cnt.split("_");
        let id = explode[1];
        if (check_duplicate_trans(id) == 0) {
          duplicate_check = false;
        }
      }
    } else {
      required_check = false;
    }
  } else {
    required_check = false;
  }

  if (!required_check) {
    toastr.error(
      "You forgot to enter some variable information.",
      "Oh snap!!!",
      { closeButton: true, progressBar: true, preventDuplicates: true }
    );
  } else if (!duplicate_check) {
    toastr.error("Duplicate variable found!!!", "", {
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
    let path = `master/theme/add_update/${id}`;
    let form_data = $(`#theme_form`).serialize();
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
          remove_master_notifier();
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
const theme_remove = (data) => {
  let path = `master/theme/remove/${data.theme_id}`;
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">name : </td>
                        <td width="70%">${data.theme_name}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">status : </td>
                        <td width="70%">${
                          data.theme_status == 1 ? "active" : "inactive"
                        }</td>
                    </tr>
                </tbody>
            </table>`;
  remove_datav2(html, path);
};
