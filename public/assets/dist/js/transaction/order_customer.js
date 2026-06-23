const customer_popup = (args) => {
  const { field } = args;
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">add client </p>
                </div>`;
  let data = `<form class="form-horizontal" id="customer_form" onsubmit="add_customer(${field})">              
                  <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="d-flex flex-wrap form-group floating-form mt-2">
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                            <input 
                              type="text" 
                              name="customer_name" 
                              class="form-control floating-input" 
                              id="customer_name" 
                              onkeyup="validate_textfield(this, ${true})" 
                              placeholder=" " 
                              autocomplete="off" 
                            />   
                            <label class="text-uppercase">name <span class="text-danger">*</span></label>
                            <small class="form-text text-muted helper-text" id="customer_name_msg"></small>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                            <input 
                              type="number" 
                              name="customer_mobile" 
                              class="form-control floating-input" 
                              id="customer_mobile" 
                              placeholder=" " 
                              onkeyup="set_mobile_no(this)" 
                              onfocusout="validate_mobile_no(this)" 
                              autocomplete="off" 
                            />   
                            <label class="text-uppercase">mobile no <span id="customer_mobile_length">(10)</span> <span class="text-danger">*</span></label>
                            <small class="form-text text-muted helper-text" id="customer_mobile_msg"></small>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                            <textarea 
                              name="customer_address" 
                              class="form-control floating-textarea" 
                              id="customer_address" 
                              placeholder=" " 
                              autocomplete="off"
                            ></textarea>
                            <label class="text-uppercase">address</label>
                            <small class="form-text text-muted helper-text" id="customer_address_msg"></small>
                          </div>
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                            <p class="text-uppercase">city - state - country - pincode</p>
                            <select 
                              class="form-control floating-select" 
                              id="customer_cpt_id" 
                              name="customer_cpt_id" 
                              placeholder=" " 
                            ></select>
                            <small class="form-text text-muted helper-text" id="customer_cpt_id_msg"></small>
                            <input type="hidden" name="customer_status" value="1">
                          </div>  
                          <div class="col-12 col-sm-12 col-md-12 col-lg-12 floating-label">
                            <p class="text-uppercase">branch <span class="text-danger">*</span></p>
                            <select 
                              class="form-control floating-select" 
                              id="customer_branch_id" 
                              name="customer_branch_id" 
                              placeholder=" " 
                            ></select>
                            <small class="form-text text-muted helper-text" id="customer_branch_id_msg"></small>
                          </div>                     
                        </div>
                    </div>              
                  </div>              
                </form>`;
  let btn = `<button 
                type="button" 
                class="btn btn-sm btn-primary" 
                id="sbt_btn" 
                style="width:15%;"
                onclick="add_customer(${field})" 
                disabled
              >
                <div class="stage d-none"><div class="dot-flashing"></div></div>
                <div class="dot-text text-primary text-uppercase">add</div>
              </button>
              <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">CANCEL</button>`;

  $(".modal-title-sm").html(title);
  $(".modal-body-sm").html(data);
  $(".modal-footer-sm").html(btn);
  $("#popup_modal_sm").modal("show");
  setTimeout(() => {
    $(`#customer_name`).focus();
  }, RELOAD_TIME);
  $(`#customer_cpt_id`).select2(
    select2_default({
      url: `master/city/get_select2/_id`,
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
};
const add_customer = (field) => {
  event.preventDefault();
  let check = true;
  notifier(`customer_name`);
  notifier(`customer_mobile`);

  if ($(`#customer_name`).val() == "") {
    notifier(`customer_name`, "Required");
    check = false;
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
  } else {
    const path = `master/customer/handler`; 
    let form_data = $(`#customer_form`).serialize();
    form_data += `&func=add&id=0`;
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          $("#popup_modal_lg").modal("hide");
          refresh_dropdown_select2(data, field);
          $("#popup_modal_sm").modal("hide");
          toastr.success("", msg, {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
          });
        }
      },
      (errmsg) => {}
    );
  }
};
