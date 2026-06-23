$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();

  setInterval(() => {
    isConnected();
  }, RELOAD_TIME);

  $(".hamburger_button").on("click", function () {
    $(".hamburger_icon").toggleClass("open");
    $(".hamburger_button").toggleClass("pressed");
  });

  $(".dropdown").on("click", function () {
    $(".dropdown").removeClass("show");
    $(".dropdown > a").attr("aria-expanded", false);
    $(".dropdown > div").removeClass("show");

    $(`#${this.id}`).addClass("show");
    $(`#${this.id} > a`).attr("aria-expanded", true);
    $(`#${this.id} > div`).addClass("show");
  });
  $("#" + link).addClass("active");
  $("#" + sub_link).addClass("active");
  if (link) {
    let el = document.querySelector(`#${link}`);
    el && el.scrollIntoView(true);
  }
  // $(".select2").select2(select2default)
  $(".select2").select2({ width: "100%" });
  $("#_status")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2_status`,
        placeholder: "STATUS",
      })
    )
    .on("change", () => trigger_search());
  $("#_role")
    .select2(
      select2_default({
        url: `master/common/get_select2_role`,
        placeholder: "ROLE",
      })
    )
    .on("change", () => trigger_search());
  $("#drcr")
    .select2(
      select2_default({
        url: `master/common/get_select2_drcr`,
        placeholder: "DR/CR",
      })
    )
    .on("change", () => trigger_search());
  $(".search_sub_menu")
    .select2(
      select2_default({
        url: `master/menu/get_select2/_mt_name`,
        placeholder: "SEARCH",
        width: "10rem",
      })
    )
    .on("change", (event) => {
      if (event.target.value != "") {
        window.location.href = `${base_url}/${event.target.value}`;
      }
    });
  $(".search_sub_menu_sm")
    .select2(
      select2_default({
        url: `master/menu/get_select2/_mt_name`,
        placeholder: "SEARCH",
        width: "100%",
      })
    )
    .on("change", (event) => {
      if (event.target.value != "") {
        window.location.href = `${base_url}/${event.target.value}`;
      }
    });

  $(".datepicker").datepicker({
    format: "dd-mm-yyyy",
    todayHighlight: true,
    autoclose: true,
    orientation: "auto bottom",
    startDate: new Date($("#start_year").val()),
    endDate: new Date($("#end_year").val()),
  });
  $(".future-datepicker").datepicker({
    format: "dd-mm-yyyy",
    todayHighlight: true,
    autoclose: true,
    startDate: new Date(),
    minDate: 0,
  });
  $(".datepicker-top").datepicker({
    format: "dd-mm-yyyy",
    todayHighlight: true,
    autoclose: true,
    orientation: "auto top",
    startDate: new Date($("#start_year").val()),
    endDate: new Date($("#end_year").val()),
  });
});

const swalButtonDanger = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-primary text-danger mx-2",
    cancelButton: "btn btn-primary mx-2",
  },
  buttonsStyling: false,
});
const swalButtonSuccess = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-primary text-success mx-2",
    cancelButton: "btn btn-primary mx-2",
  },
  buttonsStyling: false,
});
const swalButtonInfo = Swal.mixin({
  customClass: {
    confirmButton: "btn btn-primary text-info mx-2",
    cancelButton: "btn btn-primary mx-2",
  },
  buttonsStyling: false,
});

const set_search_box = () =>
  $(`#search_status`).is(" :checked")
    ? $("#search_box").collapse("hide")
    : $("#search_box").collapse("show");
const toggle_right_panel = () => {
  $(`#master_right_panel`).hasClass("active")
    ? $(`#master_right_panel`).removeClass("active")
    : $(`#master_right_panel`).addClass("active");
  $(".btn-filter").tooltip("hide");
};

const isActive = (id) =>
  $(`#multiCollapse_${id}`).hasClass("show")
    ? $(`#up_down_${id}`).removeClass("fa-caret-down").addClass("fa-caret-up")
    : $(`#up_down_${id}`).removeClass("fa-caret-up").addClass("fa-caret-down");
const trigger_search = () => $("#btn_search").trigger("click");
const refresh_dropdown = (data, field) =>
  $(`#${field.id}`)
    .append(`<option value=${data.id}>${data.name}</option>`)
    .focus()
    .val(data.id);
const remove_preview_image = (id) => $(`#${id}`).remove();
const zoom = () => $(".pan").pan();
const open_overlay = () => {
  $(".overlay").removeClass("d-none");
  document.getElementById("overlay_lg").style.width = "100%";
};
const close_overlay = () => {
  $(".overlay").addClass("d-none");
  $(".overlay-title").html("");
  $(".overlay-body").html("");
  $(".overlay-footer").html("");
  document.getElementById("overlay_lg").style.width = "0%";
};
const parseValue = (value) => {
  if (isNaN(value) || value == "") value = 0;
  return parseFloat(("0" + value).replace(/[^0-9-\.]/g, ""), 10);
};
const get_url_string = (key) => {
  const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, props) => searchParams.get(props),
  });
  return params[`${key}`];
};
const notifier = (id, msg = false) => {
  if (msg === false) {
    $(`#${id}_msg`).html("");
    return;
  }
  $("#" + id).addClass("shake");
  $("#" + id).on(
    "webkitAnimationEnd oanimationend msAnimationEnd animationend",
    function (e) {
      $("#" + id)
        .delay(200)
        .removeClass("shake");
    }
  );
  $("#" + id + "_msg").html(msg);
};
const redirectPage = (path) => {
  disable_enable_background(true);
  $("body").animate({ opacity: "0.5" }, RELOAD_TIME, "swing", function () {
    window.location.href = base_url + "/" + path;
  });
  disable_enable_background(false);
};
const session_expired = () => {
  $("body, html").animate({ scrollTop: 0 }, 1000);
  // toastr.error('Please wait...','Session Expired.', {timeOut:2000})
  Swal.fire({
    html: '<p class="text-danger">Please wait...</p>',
    title: `<h2 class="text-danger">Session Expired.</h2>`,
    icon: "warning",
    showCancelButton: false,
    timer: 2000,
  });
  setTimeout(() => {
    window.location.href = base_url;
  }, 2000);
};
const custom_confirm = (title) => {
  swalButtonDanger
    .fire({
      title,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, do it!",
    })
    .then((result) => {
      console.log(result.isConfirmed);
      return result.isConfirmed;
    });
};
const refresh_dropdown_select2 = (data, field) => {
  const newOption = new Option(data.name, data.id, true, true);
  $(`#${field.id}`).append(newOption).trigger("change");
  $(`#${field.id}`).select2("open");
};
const drop_down = (object) => {
  let entries = Object.entries(object);
  return entries.map(
    (entry) => `<option value="${entry[0]}">${entry[1]}</option>`
  );
};
const preview_image = (element) => {
  if (validate_img(element)) {
    if (typeof FileReader != "undefined") {
      let holder = $("#preview");
      holder.empty();
      let reader = new FileReader();
      reader.onload = function (e) {
        $(holder).append(
          `<img class="img-thumbnail pan" width="150px" onclick="zoom()" title="click to zoom in and zoom out" data-big="${e.target.result}" src="${e.target.result}" />`
        );
      };
      holder.show();
      reader.readAsDataURL($(element)[0].files[0]);
    } else {
      alert("This browser does not support FileReader.");
    }
  }
};
const handle_master_response = (id, field, term, resp, focus = "name") => {
  const { status, flag, data, msg } = resp;
  if (status) {
    if (flag == 1) {
      if (id == 0) {
        if (field != undefined) {
          $("#popup_modal_sm").modal("hide");
          refresh_dropdown(data, field);
        } else {
          $(`#${term}_form`)[0].reset();
          $(`#${term}_${focus}`).focus();
        }
      } else {
        $("#popup_modal_sm").modal("hide");
      }
      callToastify("success", msg, "right");
    } else {
      response_error(flag, msg);
    }
  } else {
    session_expired();
  }
};
const handle_master_response_select2 = (
  id,
  field,
  term,
  resp,
  focus = "name"
) => {
  const { status, flag, data, msg } = resp;
  if (status) {
    if (flag == 1) {
      if (id == 0) {
        if (field != undefined) {
          $("#popup_modal_sm").modal("hide");
          refresh_dropdown_select2(data, field);
        } else {
          $(`#${term}_form`)[0].reset();
          $(`#${term}_${focus}`).focus();
        }
      } else {
        $("#popup_modal_sm").modal("hide");
      }
      callToastify("success", msg, "right");
    } else {
      response_error(flag, msg);
    }
  } else {
    session_expired();
  }
};
const handle_response = (resp) => {
  const { msg, status = false, session = true, active = true } = resp;
  if (!session) {
    session_expired();
    return false;
  }
  if (!active) {
    toastr.error("", msg);
    setTimeout(() => {
      window.location.href = base_url;
    }, 3000);
    return false;
  }
  if (status == REFRESH) {
    toastr.error("", msg);
    setTimeout(() => {
      setTimeout(function () {
        window.location.reload();
      }, RELOAD_TIME);
    }, RELOAD_TIME);
    return false;
  } else {
    if (!status) {
      toastr.error("", msg, { closeButton: true, progressBar: true });
      return false;
    }
  }

  return true;
};
const remove_data = (path) => {
  if (confirm("Are you sure? You want to delete item.")) {
    ajaxCall(
      "GET",
      path,
      "",
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { msg } = resp;
          $("body, html").animate({ scrollTop: 0 }, 1000);
          toastr.success("", msg, { closeButton: true, progressBar: true });
          setTimeout(() => {
            lazy_loading("master_loading");
          }, RELOAD_TIME);
        }
      },
      (errmsg) => {}
    );
  }
};
const remove_datav2 = (html, path, className = "") => {
  swalButtonDanger
    .fire({
      html,
      title: '<span class="text-danger">Do you want to delete?</span>',
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
    })
    .then((result) => {
      if (result.isConfirmed) {
        ajaxCall(
          "GET",
          path,
          "",
          "JSON",
          (resp) => {
            if (handle_response(resp)) {
              const { msg } = resp;
              swalButtonSuccess.fire({
                title: `<div class="text-success"><p>${msg}</p></div>`,
                icon: "success",
                timer: 3000,
                timerProgressBar: true,
              });
            }
            className != "" &&
              setTimeout(() => {
                lazy_loading(className);
              }, 3000);
          },
          (errmsg) => {}
        );
      }
    });
};
const remove_datav3 = (args) => {
  const { path, form_data, html, className = "" } = args;
  swalButtonDanger
    .fire({
      html,
      title: '<span class="text-danger">Do you want to delete?</span>',
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
    })
    .then((result) => {
      if (result.isConfirmed) {
        ajaxCall(
          "POST",
          path,
          form_data,
          "JSON",
          (resp) => {
            if (handle_response(resp)) {
              const { msg } = resp;
              swalButtonSuccess.fire({
                title: `<div class="text-success"><p>${msg}</p></div>`,
                icon: "success",
                timer: 3000,
                timerProgressBar: true,
              });
            }
            className != "" &&
              setTimeout(() => {
                lazy_loading(className);
              }, 3000);
          },
          (errmsg) => {}
        );
      }
    });
};
const sync = (sub_menu) => {
  const path = `sync/${sub_menu}`;
  ajaxCall(
    "GET",
    path,
    "",
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data) {
          let html = `<table class="table table-sm table-hover text-uppercase">
                                <tbody>
                                    ${
                                      data.num_rows > 0
                                        ? `<tr>
                                        <td class="font-weight-bold" width="70%">no. of record fetched : </td>
                                        <td class="text-left" width="30%">${data.num_rows}</td>
                                    </tr>`
                                        : ""
                                    }
                                    ${
                                      data.add > 0
                                        ? `<tr>
                                        <td class="font-weight-bold" width="70%">record added : </td>
                                        <td class="text-left" width="30%">${data.add}</td>
                                    </tr>`
                                        : ""
                                    }
                                    ${
                                      data.edit > 0
                                        ? `<tr>
                                        <td class="font-weight-bold" width="70%">record edited : </td>
                                        <td class="text-left" width="30%">${data.edit}</td>
                                    </tr>`
                                        : ""
                                    }
                                    ${
                                      data.add_fail > 0
                                        ? `<tr>
                                        <td class="font-weight-bold" width="70%">record failed to add : </td>
                                        <td class="text-left" width="30%">${data.add_fail}</td>
                                    </tr>`
                                        : ""
                                    }
                                    ${
                                      data.edit_fail > 0
                                        ? `<tr>
                                        <td class="font-weight-bold" width="70%">record failed to edit : </td>
                                        <td class="text-left" width="30%">${data.edit_fail}</td>
                                    </tr>`
                                        : ""
                                    }
                                </tbody>
                            </table>`;

          swalButtonDanger.fire({
            html,
            title: `<span class="${
              data.num_rows == 0 ? "text-danger" : "text-success"
            }">${msg}</span>`,
            icon: "info",
            showCancelButton: false,
          });
        }
      }
    },
    (errmsg) => {}
  );
};

const set_default_address = (field) => {
  let path = `master/common/get_default`;
  ajaxCall(
    "GET",
    path,
    "",
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data } = resp;
        if (data) {
          if (data.city && data.city.length != 0) {
            $(`#${field}_city_id`).html(
              `<option value="${data.city[0]["city_id"]}">${data.city[0]["city_name"]}</option>`
            );
          }
          if (data.state && data.state.length != 0) {
            $(`#${field}_state_id`).html(
              `<option value="${data.state[0]["state_id"]}">${data.state[0]["state_name"]}</option>`
            );
          }
          if (data.country && data.country.length != 0) {
            $(`#${field}_country_id`).html(
              `<option value="${data.country[0]["country_id"]}">${data.country[0]["country_name"]}</option>`
            );
          }
        }
        $(".master_block_btn, #sbt_btn").prop("disabled", true);
      }
    },
    (errmsg) => {}
  );
};

const toggle_menuu = (element) => {  
  let current_id = element.id;
  $(".menuToggle").each(function (index, value) {
    let id = value.id;
    if (current_id == id) {
      $(`#${current_id}`).toggleClass("active");
    } else {
      $(`#${id}`).removeClass("active");
    }
  });
};

const set_user_bg_color = () => {
  let color = $("#user_bgcolor").val();
  let path = `master/user/handler`;
  let form_data = { func: "set_user_bg_color", color };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        let ele = document.querySelector(":root");
        ele.style.setProperty("--bg-color-primary", color);
        ele.style.setProperty("--font-color-secondary", color);
      }
    },
    (errmsg) => {}
  );
};

const compress_image = async (id) => {
  const imageFile = $(`#${id}`)[0].files[0];
  if (!imageFile) return {};
  const options = {
    maxSizeMB: 1,
    maxWidthOrHeight: 1920,
    useWebWorker: true,
  };
  try {
    return await imageCompression(imageFile, options);
  } catch (error) {
    throw error;
  }
};
