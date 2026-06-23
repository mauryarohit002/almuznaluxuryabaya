$(document).ready(function () {
  $(`#hm_karigar_id`)
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_karigar_id`,
        placeholder: "select",
        param: true,
      })
    )
    .on("change", (event) => get_job_data(event.target.value));
});
// core_functions
let trans_data = [];
const get_transaction = () => {
  if (["edit", "read"].includes(get_url_string("action"))) {
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
              trans_data = data;
              let result = paginate(trans_data, page);
              if (result && result.length != 0) {
                result.forEach((value) => add_wrapper_data(value, true));
              }
            }
            calculate_master();
            $("#transaction_count").html(trans_data.length);
          }
        },
        (errmsg) => {}
      );
    }
  }
};
const calculate_master = () => {
  let total_qty = trans_data.length;
  let total_amt = 0;
  trans_data.forEach((value, index) => {
    const { ht_rate } = value;

    total_amt = parseFloat(total_amt) + parseFloat(ht_rate);
    if (isNaN(total_amt) || total_amt == "") total_amt = 0;
  });
  $("#hm_total_qty").val(total_qty);
  $("#hm_total_amt").val(total_amt.toFixed(2));

  if (total_amt > 0) {
    $(".master_block_btn").prop("disabled", false);
    notifier("hm_total_amt");
  } else {
    $(".master_block_btn").prop("disabled", true);
    notifier("hm_total_amt", "Required");
  }
};
const add_wrapper_data = (data) => {
  const {
    ht_jrt_id,
    entry_no,
    entry_date,
    apparel_name,
    qrcode,
    ht_rate,
    isExist = false,
  } = data;
  let tr = `<tr id="row_${ht_jrt_id}">
                <td id="entry_no_${ht_jrt_id}">${entry_no}</td>
                <td id="entry_date_${ht_jrt_id}">${entry_date}</td>
                <td id="apparel_name_${ht_jrt_id}">${apparel_name}</td>
                <td id="qrcode_${ht_jrt_id}">${qrcode}</td>
                <td id="rate_${ht_jrt_id}">${ht_rate}</td>
                <td>
                    ${
                      isExist
                        ? `<button 
                            type="button" 
                            class="btn btn-sm"
                            ><i class="text-danger fa fa-ban"></i></button>`
                        : `<a 
                            type="button" 
                            class="btn btn-sm" 
                            onclick="remove_transaction(${ht_jrt_id})"
                            ><i class="text-danger fa fa-trash"></i></a>`
                    }
                  </td>
              </tr>`;
  $("#transaction_wrapper").append(tr);
};

const add_edit = () => {
  event.preventDefault();
  let check = true;
  notifier(`hm_karigar_id`);
  notifier(`hm_total_qty`);
  notifier(`hm_total_amt`);

  if ($(`#hm_karigar_id`).val() == null) {
    notifier(`hm_karigar_id`, "Required");
    check = false;
  }
  if ($(`#hm_total_qty`).val() <= 0) {
    notifier(`hm_total_qty`, "Required");
    check = false;
  }
  if ($(`#hm_total_amt`).val() <= 0) {
    notifier(`hm_total_amt`, "Required");
    check = false;
  }
  if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
    return;
  }
  const path = `${link}/${sub_link}/handler`;
  let form_id = document.getElementById("_form");
  let form_data = new FormData(form_id);
  form_data.append("func", "add_edit");
  form_data.append("trans_data", JSON.stringify(trans_data));
  fileUpAjaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      const { status, data, msg } = resp;
      if (handle_response(resp)) {
        if (id == 0) {
        } else {
        }
        toastr.success("", msg, {
          closeButton: true,
          progressBar: true,
          preventDuplicates: true,
        });
        setTimeout(() => {
            window.location.reload();
        }, RELOAD_TIME);
        $("body, html").animate({ scrollTop: 0 }, 1000);
      }
    },
    (errmsg) => {}
  );
};
const remove_master_notifier = () => {
  notifier("fabric_id");
  notifier("design_id");
  notifier("color_id");
  notifier("qty");
  notifier("mtr");
  notifier("total_mtr");
  notifier("rate");
  notifier("amt");
};
const remove_transaction = (jim_id) => {
  trans_data = trans_data.filter((value) => value.ht_jim_id != jim_id);
  let qrcode = $(`#qrcode_${jim_id}`).html();
  toastr.success(`${qrcode}`, "ITEM REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#row_${jim_id}`).detach();
  $("#transaction_count").html(trans_data.length);
  calculate_master();
};
// core_functions

// additional_functions
const get_job_data = (id) => { 
  trans_data = [];
  calculate_master();
  $("#transaction_wrapper").html("");
  if (!id) return false;
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "get_job_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          trans_data = data;
          data.forEach((value) => add_wrapper_data(value));
        }
        calculate_master();
        $("#transaction_count").html(trans_data.length);
      }
    },
    (errmsg) => {}
  );
};
// additional_functions
