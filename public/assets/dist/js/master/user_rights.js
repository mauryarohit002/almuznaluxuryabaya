$(document).ready(function () {
  $("#_user_id")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_user_id`,
        placeholder: "select",
      })
    )
    .on("change", (event) => add_action_user(event.target.value));

  $("#_role_id")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_role_id`,
        placeholder: "select",
      })
    )
    .on("change", (event) => add_action_role(event.target.value));
});
let user_action_state = [];
let role_action_state = [];
const show_hide = (id) => {
  $(`#multiCollapse_${id}`).toggleClass("d-none");
  if ($(`#multiCollapse_${id}`).hasClass("d-none")) {
    $(`#collapse_${id}`).html('<i class="text-success fa fa-plus"></i>');
  } else {
    $(`#collapse_${id}`).html('<i class="text-danger fa fa-minus"></i>');
  }
};
const get_assign_rights = (mt_id) => {
  $(".class_tr").removeClass("text-success");
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_assign_rights", mt_id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        $(`#mt_id_${mt_id}`).addClass("text-success");
        if (data["action_data"]) {
        }
        if (data["assign_data"] && data["assign_data"].length != 0) {
          add_action_user_wrapper(data);
          add_action_role_wrapper(data);
        }
      }
    },
    (errmsg) => {}
  );
};

// user wise rights
const add_action_user_wrapper = (data) => {
  let wrapper = ``;
  data["assign_data"].forEach((row) => {
    const { mat_id, mat_action, user_trans_data } = row;
    let action_user_data = set_action_user(
      data["action_data"],
      user_trans_data
    );
    wrapper += `<div class="card mb-1">
                  <div class="card-header text-uppercase">
                    <label class="custom-control material-checkbox-secondary mx-2">
                      <input 
                        type="checkbox" 
                        class="material-control-input-secondary action_user_class" 
                        id="useraction_${mat_id}" 
                        name="useraction_${mat_id}" 
                      />
                      <span class="material-control-indicator-secondary"></span>
                      <span class="material-control-description-secondary text-uppercase">${mat_action}</span>
                    </label>
                  </div>
                  <div class="card-body">
                    <div class="d-flex flex-wrap form-group floating-form py-2" id="user_action_wrapper_${mat_id}">
                      ${action_user_data}
                    </div>
                  </div>
              </div>`;
  });
  $("#user_action_wrapper").html(wrapper);
};
const set_action_user = (action_data, data) => {
  let wrapper = "";
  if (data && data.length != 0) {
    data.forEach((temp) => {
      const { maut_id, maut_mat_id, user_fullname } = temp;
      if (action_data.includes("delete")) {
        wrapper += `<a 
								type="button" 
								class="btn btn-sm btn-primary m-2"
								onclick="remove_action_user(${maut_id}, ${maut_mat_id})"
							>${user_fullname} <i class="text-danger fa fa-close"></i></a>`;
      } else {
        wrapper += `<a 
								type="button" 
								class="btn btn-sm btn-primary m-2"
							>${user_fullname}</a>`;
      }
    });
  }
  return wrapper;
};
const add_action_user = (_id) => {
  let arr = [];
  if ($(".action_user_class").length > 0) {
    $(".action_user_class").map((value, index) => {
      let cnt = index.name;
      if ($(`#${cnt}`).is(":checked")) {
        let explode = cnt.split("_");
        let id = explode[1];
        arr.push(id);
      }
    });
  }
  if (_id != "") {
    if (arr.length != 0) {
      const path = `${link}/${sub_link}/handler`;
      const form_data = { func: "add_action_user", _id, mat_id: arr };
      ajaxCall(
        "POST",
        path,
        form_data,
        "JSON",
        (resp) => {
          if (handle_response(resp)) {
            const { data, msg } = resp;
            if (data["assign_data"] && data["assign_data"].length != 0) {
              add_action_user_wrapper(data);
            }
            if (arr.length != 0) {
              arr.forEach((row) => {
                $(`#useraction_${row}`).prop("checked", true);
              });
            }
            $("#_user_id").val(null).trigger("change");
            $("#_user_id").select2("open");
          }
        },
        (errmsg) => {}
      );
    } else {
      toastr.error("Select atleast one action", " ", {
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
      });
    }
  }
};
const remove_action_user = (maut_id, mat_id) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove_action_user", maut_id, mat_id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        toastr.success(msg, " ", {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
        let wrapper = set_action_user(data["action_data"], data["assign_data"]);
        $(`#user_action_wrapper_${mat_id}`).html(wrapper);
      }
    },
    (errmsg) => {}
  );
};
// user wise rights

// role wise rights
const add_action_role_wrapper = (data) => {
  let wrapper = ``;
  data["assign_data"].forEach((row) => {
    const { mat_id, mat_action, role_trans_data } = row;
    let action_role_data = set_action_role(
      data["action_data"],
      role_trans_data
    );
    wrapper += `<div class="card mb-1">
                  <div class="card-header text-uppercase">
                    <label class="custom-control material-checkbox-secondary mx-2">
                      <input 
                        type="checkbox" 
                        class="material-control-input-secondary action_role_class" 
                        id="roleaction_${mat_id}" 
                        name="roleaction_${mat_id}" 
                      />
                      <span class="material-control-indicator-secondary"></span>
                      <span class="material-control-description-secondary text-uppercase">${mat_action}</span>
                    </label>
                  </div>
                  <div class="card-body">
                    <div class="d-flex flex-wrap form-group floating-form py-2" id="role_action_wrapper_${mat_id}">
                      ${action_role_data}
                    </div>
                  </div>
              </div>`;
  });
  $("#role_action_wrapper").html(wrapper);
};
const set_action_role = (action_data, data) => {
  let wrapper = "";
  if (data && data.length != 0) {
    data.forEach((temp) => {
      const { mart_id, mart_mat_id, role_name } = temp;
      if (action_data.includes("delete")) {
        wrapper += `<a 
								type="button" 
								class="btn btn-sm btn-primary m-2"
								onclick="remove_action_role(${mart_id}, ${mart_mat_id})"
							>${role_name} <i class="text-danger fa fa-close"></i></a>`;
      } else {
        wrapper += `<a 
								type="button" 
								class="btn btn-sm btn-primary m-2"
							>${role_name}</a>`;
      }
    });
  }
  return wrapper;
};
const add_action_role = (_id) => {
  let arr = [];
  if ($(".action_role_class").length > 0) {
    $(".action_role_class").map((value, index) => {
      let cnt = index.name;
      if ($(`#${cnt}`).is(":checked")) {
        let explode = cnt.split("_");
        let id = explode[1];
        arr.push(id);
      }
    });
  }
  console.log({ arr });
  if (_id != "") {
    if (arr.length != 0) {
      const path = `${link}/${sub_link}/handler`;
      const form_data = { func: "add_action_role", _id, mat_id: arr };
      ajaxCall(
        "POST",
        path,
        form_data,
        "JSON",
        (resp) => {
          if (handle_response(resp)) {
            const { data, msg } = resp;
            if (data["assign_data"] && data["assign_data"].length != 0) {
              add_action_role_wrapper(data);
            }
            if (arr.length != 0) {
              arr.forEach((row) => {
                $(`#roleaction_${row}`).prop("checked", true);
              });
            }
            $("#_role_id").val(null).trigger("change");
            $("#_role_id").select2("open");
          }
        },
        (errmsg) => {}
      );
    } else {
      toastr.error("Select atleast one action", " ", {
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
      });
    }
  }
};
const remove_action_role = (mart_id, mat_id) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove_action_role", mart_id, mat_id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        toastr.success(msg, " ", {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
        let wrapper = set_action_role(data["action_data"], data["assign_data"]);
        $(`#role_action_wrapper_${mat_id}`).html(wrapper);
      }
    },
    (errmsg) => {}
  );
};
// role wise rights
