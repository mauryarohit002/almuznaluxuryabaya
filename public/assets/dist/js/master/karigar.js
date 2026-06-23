$(document).ready(function () {
  $(`#proces_id`)
    .select2(
      select2_default({
        url: `master/proces/get_select2/_id`,
        placeholder: "SELECT",
        param: true,
      })
    )
    .on("change", (event) => add_proces_row(event.target.value));

  $(`#apparel_id`)
    .select2(
      select2_default({
        url: `master/apparel/get_select2/_id`,
        placeholder: "SELECT",
        param: true,
      })
    )
    .on("change", (event) => add_apparel_row(event.target.value));
  $(`#karigar_city_id`).select2(
    select2_default({
      url: `master/city/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#karigar_state_id`).select2(
    select2_default({
      url: `master/state/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#karigar_country_id`).select2(
    select2_default({
      url: `master/country/get_select2/_id`,
      placeholder: "SELECT",
      param: true,
    })
  );
  $(`#_proces_id`)
    .select2(
      select2_default({
        url: `master/karigar/get_select2/_proces_id`,
        placeholder: "SELECT",
        param: true,
        param1: () => $("#id").val(),
      })
    )
    .on("change", (event) => add_proces(event.target.value));

  $(`#_apparel_id`)
    .select2(
      select2_default({
        url: `master/karigar/get_select2/_apparel_id`,
        placeholder: "SELECT",
        param: true,
        param1: () => $("#id").val(),
      })
    )
    .on("change", (event) => add_apparel(event.target.value));
});
let proces_cnt = 1;
let apparel_cnt = 1;
const get_transaction = () => {
  if (["edit", "view"].includes(get_url_string("action"))) {
    let id = get_url_string("id");
    if (id) {
      let path = `${link}/${sub_link}/handler`;
      const form_data = { func: "get_transaction", id };
      ajaxCall(
        "POST",
        path,
        form_data,
        "JSON",
        (resp) => {
          if (handle_response(resp)) {
            const { data, msg } = resp;
            if (data["proces_data"] && data["proces_data"].length != 0) {
              data["proces_data"].forEach((row) => add_proces_wrapper(row));
            }
            if (data["apparel_data"] && data["apparel_data"].length != 0) {
              data["apparel_data"].forEach((row) => add_apparel_wrapper(row));
            }
            if (
              data["attachment_data"] &&
              data["attachment_data"].length != 0
            ) {
              preview_karigar_image(data["attachment_data"]);
            }
          }
        },
        (errmsg) => {}
      );
    }
  }
};
const remove_karigar_notifier = () => {
  notifier(`karigar_code`);
  notifier(`karigar_name`);
  notifier(`karigar_mobile`);
  notifier(`karigar_email`);
};
const add_edit = (id) => {
  event.preventDefault();
  let check = true;
  let duplicate_contact = true;
  let required_contact = true;
  remove_karigar_notifier();
  if ($(`#karigar_code`).val() == "") {
    notifier(`karigar_code`, "Required");
    check = false;
  }
  if ($(`#karigar_name`).val() == "") {
    notifier(`karigar_name`, "Required");
    check = false;
  }
  if ($(`#karigar_email`).val().length > 0) {
    if (!validate_email_value($(`#karigar_email`).val())) {
      notifier(`karigar_email`, "Invalid Email");
      check = false;
    }
  }
  if ($(`#karigar_mobile`).val().length > 0) {
    if ($(`#karigar_mobile`).val().length !== 10) {
      notifier(`karigar_mobile`, "Invalid Mobile No");
      check = false;
    }
  }
  if (!required_contact) {
    toastr.error(
      "You forgot to enter some contact person information.",
      "Oh snap!!!",
      {
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
      }
    );
  } else if (!duplicate_contact) {
    toastr.error("Duplicate contact person detail found!!!", "", {
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
    compress_image("karigar_image")
      .then((compressedImage) => {
        let path = `master/karigar/handler`;
        let form_id = document.getElementById(`_form`);
        let form_data = new FormData(form_id);
        form_data.append("func", "add_edit");
        if (compressedImage.name) {
          form_data.append(
            `karigar_image`,
            compressedImage,
            compressedImage.name
          );
        }
        fileUpAjaxCall(
          "POST",
          path,
          form_data,
          "JSON",
          (resp) => {
            if (handle_response(resp)) {
              const { data, msg } = resp;
              toastr.success("", msg, { closeButton: true, progressBar: true });
              setTimeout(() => {
                window.location.reload();
              }, RELOAD_TIME);
            }
          },
          (errmsg) => {}
        );
      })
      .catch((err) => {
        console.log(err);
      });
  }
};
const karigar_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.karigar_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">name : </td>
                        <td width="70%">${data.karigar_name}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">mobile no : </td>
                        <td width="70%">${data.karigar_mobile}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">status : </td>
                        <td width="70%">
                            ${data.karigar_status == 1 ? "active" : "inactive"}
                        </td>
                    </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
const upload_document = (element, validTypes) => {
  if (validate_multiple_document(element, validTypes)) {
    let path = `master/karigar/handler/`;
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
          preview_karigar_image(data);
          toastr.success("Document attached to form.", "", {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
          });
        }
      },
      (errmsg) => {
        $("#karigar_attachment").val("");
      }
    );
  }
};
const preview_karigar_image = (data) => {
  if (data && data.length != 0) {
    let preview = data.map((d) => {
      let { kat_id, kat_karigar_id, kat_path } = d;
      let is_pdf = kat_path.includes(".pdf");
      return `<div class="d-flex flex-column p-2" id="preview_${kat_id}">
                <span class="d-flex justify-content-center" style="width: 9rem; height:9rem;">
                    ${
                      is_pdf
                        ? `<object 
                                class="img-thumbnail pan form_loading" 
                                type="application/pdf" 
                                data="${kat_path}"
                                style="max-width: 100%; max-height: 100%; object-fit: contain;"
                            ></object>`
                        : `<img 
                                class="img-thumbnail pan form_loading" 
                                onclick="zoom(this)" 
                                title="click to zoom in and zoom out" 
                                src="${LAZYLOADING}" 
                                data-big="${kat_path}" 
                                data-src="${kat_path}" 
                                style="max-width: 100%; max-height: 100%; object-fit: contain;"
                            />`
                    }
                </span>
                <button 
                    type="button" 
                    class="btn btn-sm btn-primary mt-2" 
                    onclick="remove_preview_image('preview_${kat_id}')"
                >REMOVE <i class="text-danger fa fa-trash"></i></button>
                <a 
                    type="button" 
                    class="btn btn-sm btn-primary mt-2"
                    href="${kat_path}"
                    download
                >DOWNLOAD <i class="text-info fa fa-download"></i></a>
                <input type="hidden" id="kat_id_${kat_id}" name="kat_id[]" value="${kat_id}">
                <input type="hidden" id="kat_karigar_id_${kat_id}" name="kat_karigar_id[]" value="${kat_karigar_id}">
                <input type="hidden" id="kat_path_${kat_id}" name="kat_path[]" value="${kat_path}">
            </div>`;
    });
    $("#karigar_attachment").val("");
    $(".preview").append(preview);
    lazy_loading("form_loading");
  }
};
const remove_karigar_image = () => {
  $("#preview").html(`<img 
							class="img-thumbnail pan form_loading" 
							onclick="zoom(this)" 
							title="click to zoom in and zoom out" 
							src="${LAZYLOADING}" 
							data-src="${USERIMAGE}" 
							data-big="${USERIMAGE}" 
							style="max-width: 100%; max-height: 100%; object-fit: contain;"
						/>`);
  lazy_loading("form_loading");
  $("#karigar_image").val("");
  $("#karigar_pic").val(USERIMAGE);
};

// process
const add_proces = (id) => {
  if (!id) return false;
  let path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_proces", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          data.forEach((row) => add_proces_wrapper(row));
        }
      }
    },
    (errmsg) => {}
  );
};
const add_proces_row = (id) => {
  notifier("proces_id");
  let check = true;
  let dup_check = true;
  let total_tr = $("#proces_wrapper > div").length;
  if ($("#proces_id").val() == null) {
    // notifier("proces_id", "Required");
    check = false;
  }
  if (total_tr > 0) {
    let new_proces_id = $("#proces_id").val();
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#proces_wrapper > div:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];

      let old_proces_id = $(`#kpt_proces_id_${id}`).val();
      if (new_proces_id == old_proces_id) {
        notifier("proces_id", "Already added.");
        dup_check = false;
      }
    }
  }
  if (!check) {
    // toastr.error("You forgot to enter some information.", "Oh snap!!!", {
    // closeButton: true,
    // progressBar: true,
    // preventDuplicates: true,
    // });
    // $("body, html").animate({ scrollTop: 0 }, 1000);
  } else if (!dup_check) {
    toastr.error("Duplicate process found!!!", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    add_proces_wrapper({
      kpt_id: 0,
      kpt_proces_id: $("#proces_id").val(),
      proces_name: $("#proces_id :selected").text(),
      isExist: false,
    });
    toastr.success($("#proces_id :selected").text(), "PROCESS ADDED TO LIST.", {
      closeButton: true,
      progressBar: true,
    });
    $("#proces_id").val(null).trigger("change");
  }
};
const add_proces_wrapper = (data) => {
  const { kpt_id, kpt_proces_id, proces_name, isExist } = data;
  let tr = `<div class="d-flex align-items-baseline" id="rowproces_${proces_cnt}">
				<div class="mr-1" style="width: 70%;">
					<input 
						type="hidden" 
						id="kpt_id_${proces_cnt}" 
						name="kpt_id[]" 
						value="${kpt_id}" 
					/>
					<input 
						type="hidden" 
						id="kpt_proces_id_${proces_cnt}" 
						name="kpt_proces_id[]" 
						value="${kpt_proces_id}" 
					/>
					<input 
						type="text" 
						class="form-control floating-input" 
						id="proces_name_${proces_cnt}" 
						value="${proces_name}"
						placeholder=" " 
						autocomplete="off"
						readonly
					/>
					<small class="form-text text-muted helper-text" id="proces_name_${proces_cnt}_msg"></small>
				</div>
				${
          isExist
            ? `<button 
                type="button" 
                class="btn btn-md btn-primary ml-1" 
                style="width: 30%;"
            ><i class="text-danger fa fa-ban"></i></button>`
            : `<button 
                type="button" 
                class="btn btn-md btn-primary ml-1" 
                onclick="remove_proces_trans(${proces_cnt})"
                style="width: 30%;"
            ><i class="text-danger fa fa-trash"></i></button>`
        }
				
			</div>`;
  $("#proces_wrapper").prepend(tr);
  proces_cnt++;
};
const remove_proces_trans = (cnt) => {
  let name = $(`#proces_name_${cnt}`).val();
  toastr.success(`${name}`, "PROCESS REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#rowproces_${cnt}`).detach();
};

// apparel
const add_apparel = (id) => {
  if (!id) return false;
  let path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_apparel", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          data.forEach((row) => add_apparel_wrapper(row));
        }
      }
    },
    (errmsg) => {}
  );
};
const add_apparel_row = (id) => {
  notifier("apparel_id");
  let check = true;
  let dup_check = true;
  let total_tr = $("#apparel_wrapper > div").length;
  if ($("#apparel_id").val() == null) {
    // notifier("apparel_id", "Required");
    check = false;
  }
  if (total_tr > 0) {
    let new_apparel_id = $("#apparel_id").val();
    for (let i = 1; i <= total_tr; i++) {
      let cnt = $(`#apparel_wrapper > div:nth-child(${i})`).attr("id");
      let explode = cnt.split("_");
      let id = explode[1];

      let old_apparel_id = $(`#kapt_apparel_id_${id}`).val();
      if (new_apparel_id == old_apparel_id) {
        notifier("apparel_id", "Already added.");
        dup_check = false;
      }
    }
  }
  if (!check) {
    // toastr.error("You forgot to enter some information.", "Oh snap!!!", {
    // closeButton: true,
    // progressBar: true,
    // preventDuplicates: true,
    // });
    // $("body, html").animate({ scrollTop: 0 }, 1000);
  } else if (!dup_check) {
    toastr.error("Duplicate apparel found!!!", "", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    add_apparel_wrapper({
      kapt_id: 0,
      kapt_apparel_id: $("#apparel_id").val(),
      apparel_name: $("#apparel_id :selected").text(),
      kapt_qty: 0,
      kapt_rate: 0,
      isExist: false,
    });
    toastr.success(
      $("#apparel_id :selected").text(),
      "PROCESS ADDED TO LIST.",
      {
        closeButton: true,
        progressBar: true,
      }
    );
    $("#apparel_id").val(null).trigger("change");
  }
};
const add_apparel_wrapper = (data) => {
  const {
    kapt_id,
    kapt_apparel_id,
    apparel_name,
    kapt_qty,
    kapt_rate,
    isExist,
  } = data;
  let tr = `<div class="d-flex align-items-baseline" id="rowapparel_${apparel_cnt}">
                  <div class="mx-1" style="width: 40%;">
                      <input 
                          type="hidden" 
                          id="kapt_id_${apparel_cnt}" 
                          name="kapt_id[]" 
                          value="${kapt_id}" 
                      />
                      <input 
                          type="hidden" 
                          id="kapt_apparel_id_${apparel_cnt}" 
                          name="kapt_apparel_id[]" 
                          value="${kapt_apparel_id}" 
                      />
                      <input 
                          type="text" 
                          class="form-control floating-input" 
                          id="apparel_name_${apparel_cnt}" 
                          value="${apparel_name}"
                          placeholder=" " 
                          autocomplete="off"
                          readonly
                      />
                      <small class="form-text text-muted helper-text" id="apparel_name_${apparel_cnt}_msg"></small>
                  </div>
                  <div class="mx-1" style="width: 40%;">
                      <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="kapt_qty_${apparel_cnt}" 
                          name="kapt_qty[]" 
                          value="${kapt_qty}"
                          placeholder=" " 
                          autocomplete="off"
                      />
                      <small class="form-text text-muted helper-text" id="kapt_qty_${apparel_cnt}_msg"></small>
                  </div>
                  <div class="mx-1" style="width: 30%;">
                      <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="kapt_rate_${apparel_cnt}" 
                          name="kapt_rate[]" 
                          value="${kapt_rate}"
                          placeholder=" " 
                          autocomplete="off"
                      />
                      <small class="form-text text-muted helper-text" id="kapt_rate_${apparel_cnt}_msg"></small>
                  </div>
                  ${
                    isExist
                      ? `<button 
                          type="button" 
                          class="btn btn-md btn-primary mx-1" 
                          style="width: 20%;"
                      ><i class="text-danger fa fa-ban"></i></button>`
                      : `<button 
                          type="button" 
                          class="btn btn-md btn-primary mx-1" 
                          onclick="remove_apparel_trans(${apparel_cnt})"
                          style="width: 20%;"
                      ><i class="text-danger fa fa-trash"></i></button>`
                  }
                  
              </div>`;
  $("#apparel_wrapper").prepend(tr);
  apparel_cnt++;
};
const remove_apparel_trans = (cnt) => {
  let name = $(`#apparel_name_${cnt}`).val();
  toastr.success(`${name}`, "PROCESS REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#rowapparel_${cnt}`).detach();
};

function add_family_row(){
  document.querySelector('#family_wrapper .text-muted')?.remove();
    let html = `
    <div class="row mb-2 family-row">
        <input type="hidden" name="family_id[]" value="0">

        <div class="col-md-3">
            <select name="family_relation[]" class="form-control">
                <option value="">Select</option>
                <option>Father</option>
                <option>Mother</option>
                <option>Wife</option>
                <option>Husband</option>
                <option>Son</option>
                <option>Daughter</option>
                <option>Brother</option>
                <option>Sister</option>
                <option>Other</option>
            </select>
        </div>

        <div class="col-md-4">
            <input type="text" name="family_name[]" class="form-control">
        </div>

        <div class="col-md-3">
            <input type="text" name="family_mobile[]" class="form-control"
                maxlength="10"
                onkeyup="this.value=this.value.replace(/[^0-9]/g,'')">
        </div>

        <div class="col-md-2 text-center">
            <button type="button" class="btn btn-sm btn-danger"
                onclick="remove_family_row(this)">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </div>`;
    
    document.getElementById('family_wrapper').insertAdjacentHTML('beforeend', html);
}

function remove_family_row(el){
    el.closest('.family-row').remove();
}