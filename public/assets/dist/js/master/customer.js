$(document).ready(function () {
  $(`#customer_refer_id`).select2(
    select2_default({
      url: `master/customer/get_select2/_refer_id`,
      placeholder: "SELECT",
      param: () => $("#customer_refer_type").val(),
    })
  );
  $(`#customer_city_id`).select2(
    select2_default({
      url: `master/city/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#customer_state_id`).select2(
    select2_default({
      url: `master/state/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#customer_country_id`).select2(
    select2_default({
      url: `master/country/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#customer_branch_id`).select2(
    select2_default({
      url: `master/branch/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $("#customer_mobile").on("keyup", () => set_whatsapp_no());
});
const set_whatsapp_no = () => {
  if ($("#customer_same_as_mobile").is(":checked")) {
    $("#customer_whatsapp").val($("#customer_mobile").val());
    let mobile_no = $("#customer_whatsapp").val();
    let length = 10 - parseInt(mobile_no.length);
    if (length >= 0) {
      $("#customer_whatsapp_length").html(`(${length})`);
    } else {
      $("#customer_whatsapp").val(mobile_no.substring(0, 10));
      let len = parseInt(10 - $("#customer_whatsapp").val().length);
      $("#customer_whatsapp_length").html(`(${len})`);
    }
  }
};
const disable_dnd = () => $("#customer_dnd_service").prop("checked", false);
const disable_service = () =>
  $(
    "#customer_sms_service, #customer_whatsapp_service, #customer_email_service"
  ).prop("checked", false);
const upload_customer_document = (element, validTypes) => {
  if (validate_multiple_document(element, validTypes)) {
    let path = `master/customer/handler/`;
    let form_id = document.getElementById("_form");
    let form_data = new FormData(form_id);
    form_data.append("func", "upload_document");
    fileUpAjaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          preview_customer_image(data);
          lazy_loading("form_loading");
          toastr.success("Document attached to form.", "", {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
          });
        }
      },
      (errmsg) => {
        $("#customer_attachment").val("");
      }
    );
  }
};
const preview_customer_image = (data) => {
  if (data && data.length != 0) {
    let preview = data.map((d) => {
      let { cat_id, cat_customer_id, cat_path } = d;
      let is_pdf = cat_path.includes(".pdf");
      return `<div class="d-flex flex-column p-2" id="preview_${cat_id}">
                <span class="d-flex justify-content-center" style="width: 9rem; height:15rem;">
				    ${
              is_pdf
                ? `<object 
                        class="img-thumbnail pan form_loading" 
                        type="application/pdf" 
                        data="${cat_path}"
                        style="max-width: 100%; max-height: 100%; object-fit: contain;"
                    ></object>`
                : `<img 
                        class="img-thumbnail pan form_loading" 
                        onclick="zoom(this)" 
                        title="click to zoom in and zoom out" 
                        src="${LAZYLOADING}" 
                        data-big="${cat_path}" 
                        data-src="${cat_path}" 
                        style="max-width: 100%; max-height: 100%; object-fit: contain;"
                    />`
            }
                </span>
                <button 
                    type="button" 
                    class="btn btn-sm btn-primary mt-2" 
                    onclick="remove_preview_image('preview_${cat_id}')"
                >REMOVE <i class="text-danger fa fa-trash"></i></button>
                <a 
                    type="button" 
                    class="btn btn-sm btn-primary mt-2"
                    href="${cat_path}"
                    download
                >DOWNLOAD <i class="text-info fa fa-download"></i></a>
                <input type="hidden" id="cat_id_${cat_id}" name="cat_id[]" value="${cat_id}">
                <input type="hidden" id="cat_customer_id_${cat_id}" name="cat_customer_id[]" value="${cat_customer_id}">
                <input type="hidden" id="cat_path_${cat_id}" name="cat_path[]" value="${cat_path}">
            </div>`;
    });
    $("#customer_attachment").val("");
    $(".preview").append(preview);
  }
};
const remove_customer_notifier = () => {
  notifier(`customer_name`);
  notifier(`customer_email`);
  notifier(`customer_mobile`);
  notifier(`customer_whatsapp`);
  notifier(`customer_disc_per`);
  notifier(`customer_credit_amt`);
  notifier(`customer_credit_day`);
  notifier(`customer_opening_amt`);
  notifier(`customer_branch_id`);
};
const add_edit = () => {
  event.preventDefault();
  let check = true;
  remove_customer_notifier();
  if ($(`#customer_name`).val() == "") {
    notifier(`customer_name`, "Required");
    check = false;
  }
  if ($(`#customer_email`).val().length > 0) {
    if (!validate_email_value($(`#customer_email`).val())) {
      notifier(`customer_email`, "Invalid Email");
      check = false;
    }
  }
  if ($(`#customer_mobile`).val().length > 0) {
    if ($(`#customer_mobile`).val().length !== 10) {
      notifier(`customer_mobile`, "Invalid Mobile No");
      check = false;
    }
  } else {
    notifier(`customer_mobile`, "Required");
    check = false;
  }
  if ($(`#customer_whatsapp`).val().length > 0) {
    if ($(`#customer_whatsapp`).val().length !== 10) {
      notifier(`customer_whatsapp`, "Invalid Mobile No");
      check = false;
    }
  }
  if ($(`#customer_whatsapp`).val().length > 0) {
    if ($(`#customer_whatsapp`).val().length !== 10) {
      notifier(`customer_whatsapp`, "Invalid Whatsapp No");
      check = false;
    }
  }
  if ($(`#customer_disc_per`).val() != "") {
    if ($(`#customer_disc_per`).val() < 0) {
      notifier(`customer_disc_per`, "Invalid disc %");
      check = false;
    }
  }
  if ($(`#customer_credit_amt`).val() != "") {
    if ($(`#customer_credit_amt`).val() < 0) {
      notifier(`customer_credit_amt`, "Invalid credit limit");
      check = false;
    }
  }
  if ($(`#customer_credit_day`).val() != "") {
    if ($(`#customer_credit_day`).val() < 0) {
      notifier(`customer_credit_day`, "Invalid credit day");
      check = false;
    }
  }
  if ($(`#customer_opening_amt`).val() != "") {
    if ($(`#customer_opening_amt`).val() < 0) {
      notifier(`customer_opening_amt`, "Invalid opening amt");
      check = false;
    }
  }
  if ($(`#customer_branch_id`).val() == null) {
    notifier(`customer_branch_id`, "Required");
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
    const id = $("#customer_id").val();
    const path = `master/customer/handler`;
    let form_id = document.getElementById(`_form`);
    let form_data = new FormData(form_id);
    form_data.append("func", "add_edit");
    fileUpAjaxCall(
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
          remove_customer_notifier();
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
const customer_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.customer_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">name : </td>
                        <td width="70%">${data.customer_name}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">mobile no : </td>
                        <td width="70%">${data.customer_mobile}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">status : </td>
                        <td width="70%">
                            ${data.customer_status == 1 ? "active" : "inactive"}
                        </td>
                    </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
// measurement
const get_measurement = (apparel_id) => {
  $("._apparel_name").html($(`#_apparel_name_${apparel_id}`).html());
  $("#measurement_bill_no").html("");
  $(".apparel_tr").removeClass("text-success");
  $(`#apparel_tr_${apparel_id}`).addClass("text-success");
  const id = $("#id").val();
  const path = `master/customer/handler`;
  const form_data = {
    func: "get_measurement",
    id,
    apparel_id,
  };
  $("#measurement_wrapper").html(
    `<tr><td class="text-info text-center font-weight-bold">fetching record ...</td></tr>`
  );
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data } = resp;
        const {
          latest_measurement,
          measurement_data,
        } = data;
        if (latest_measurement) {
          if (latest_measurement["_bill_date"] != "") {
            $("#measurement_bill_no").html(
              `${latest_measurement["bill_no"]} / ${latest_measurement["_bill_date"]}`
            );
          }
        }
        if (measurement_data && measurement_data.length != 0) {
          let tr = "";
          let remark = measurement_data[0]['remark'];
          measurement_data.forEach((value, index) => {
            const { cmt_id, measurement_name, value1, value2, remark } = value;
            // console.log(remark);
            tr += `<tr style="font-size:14px;">
                      <td width="5%">${index + 1}</td>
                      <td width="25%">${measurement_name}</td>
                      <td width="10%">${value1}</td>
                  </tr>`;
          });
          tr += `<tr style="font-size:14px;">
                  <td width="5%"></td>
                  <td width="25%">Remark</td>
                  <td width="10%">${remark}</td>
                </tr>`;
          $("#measurement_wrapper").html(tr);
        } else {
          $("#measurement_wrapper").html(
            `<tr><td class="text-danger text-center font-weight-bold">no record found.</td></tr>`
          );
        }

        // <td width="10%">${value2}</td>

      }
    },
    (errmsg) => {
      $("#measurement_wrapper").html(
        `<tr><td class="text-danger text-center font-weight-bold">error occur while fetching record.</td></tr>`
      );
    }
  );
};
// measurement
