$(document).ready(function () {
    $("#_sku_name").select2(select2_default({
        url: `${link}/${sub_link}/get_select2/_sku_name`,
        placeholder: "sku",
    })).on("change", () => trigger_search());

    $("#_apparel_name").select2(select2_default({
        url: `${link}/${sub_link}/get_select2/_apparel_name`,
        placeholder: "apparel",
    })).on("change", () => trigger_search());

    lazy_loading('master_loading');
});

const sku_popup = (id) => {
  let action = "UPDATE";
  let title = `<div class="col-12 col-sm-12 col-md-12 col-lg-12">
                  <p class="text-uppercase text-center font-weight-bold">${action} sku prices</p>
                </div>`;
  let data = `<form class="form-horizontal" id="_sku_form" onsubmit="update_price(${id})">              
                <div class="row pt-1">
                  <div class="col-12">
                    <div class="d-flex flex-wrap form-group floating-form">
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_fabric" 
                          name="sku_fabric" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">fabric</label>
                        <small class="form-text text-muted helper-text" id="sku_fabric_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_cutting" 
                          name="sku_cutting" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">cutting</label>
                        <small class="form-text text-muted helper-text" id="sku_cutting_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_silai" 
                          name="sku_silai" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">stitch</label>
                        <small class="form-text text-muted helper-text" id="sku_silai_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_stone" 
                          name="sku_stone" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">stone</label>
                        <small class="form-text text-muted helper-text" id="sku_stone_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_lagwayi" 
                          name="sku_lagwayi" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">lagway</label>
                        <small class="form-text text-muted helper-text" id="sku_lagwayi_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_hand_work" 
                          name="sku_hand_work" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">hand work</label>
                        <small class="form-text text-muted helper-text" id="sku_hand_work_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_material" 
                          name="sku_material" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">material</label>
                        <small class="form-text text-muted helper-text" id="sku_material_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_exp" 
                          name="sku_exp" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">exp</label>
                        <small class="form-text text-muted helper-text" id="sku_exp_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_cp" 
                          name="sku_cp" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">cp</label>
                        <small class="form-text text-muted helper-text" id="sku_cp_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_mrp" 
                          name="sku_mrp" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">mrp</label>
                        <small class="form-text text-muted helper-text" id="sku_mrp_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_offer_price" 
                          name="sku_offer_price" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">offer price</label>
                        <small class="form-text text-muted helper-text" id="sku_offer_price_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_last_price" 
                          name="sku_last_price" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">last price</label>
                        <small class="form-text text-muted helper-text" id="sku_last_price_msg"></small>
                      </div>
                      <div class="col-12 col-sm-12 col-md-4 col-lg-4 floating-label">
                        <input 
                          type="number" 
                          class="form-control floating-input" 
                          id="sku_jobber_price" 
                          name="sku_jobber_price" 
                          placeholder=" " 
                          autocomplete="off" 
                        />   
                        <label class="text-uppercase">jobber price</label>
                        <small class="form-text text-muted helper-text" id="sku_jobber_price_msg"></small>
                      </div>
                      
                    </div>              
                  </div>              
                </div>              
              </form>`;

  let btn = `<button 
              type="button" 
              class="btn btn-sm btn-primary" 
              id="sbt_btn" 
              onclick="update_price(${id})" 
              style="width:15%;">
              <div class="stage d-none"><div class="dot-flashing"></div></div>
              <div class="dot-text text-primary text-uppercase">${action}</div>
            </button>
            <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal">CANCEL</button>`;

  $(".modal-title-sm").html(title);
  $(".modal-body-sm").html(data);
  $(".modal-footer-sm").html(btn);
  set_sku_field(id);
  
};

const set_sku_field = (id) => {
  const path = `master/sku/handler`;
  const form_data = { func: "get_data", id };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data, msg } = resp;
        if (data && data.length != 0) {
          $(`#sku_fabric`).val(data[0][`sku_fabric`]);
          $(`#sku_cutting`).val(data[0][`sku_cutting`]);
          $(`#sku_silai`).val(data[0][`sku_silai`]);
          $(`#sku_stone`).val(data[0][`sku_stone`]);
          $(`#sku_lagwayi`).val(data[0][`sku_lagwayi`]);
          $(`#sku_hand_work`).val(data[0][`sku_hand_work`]);
          $(`#sku_material`).val(data[0][`sku_material`]);
          $(`#sku_exp`).val(data[0][`sku_exp`]);
          $(`#sku_cp`).val(data[0][`sku_cp`]);
          $(`#sku_mrp`).val(data[0][`sku_mrp`]);
          $(`#sku_offer_price`).val(data[0][`sku_offer_price`]);
          $(`#sku_last_price`).val(data[0][`sku_last_price`]);
          $(`#sku_jobber_price`).val(data[0][`sku_jobber_price`]);
          $("#popup_modal_sm").modal("show");
        }
      }
    },
    (errmsg) => {}
  );
};

const update_price = (id) => {
  event.preventDefault();
  let check = true;
  
  if (!check) {
    toastr.error("You forgot to enter some information.", "Oh snap!!!", {
      closeButton: true,
      progressBar: true,
      preventDuplicates: true,
    });
    $("body, html").animate({ scrollTop: 0 }, 1000);
  } else {
    let path = `master/sku/handler`;
    let form_data = $(`#_sku_form`).serialize();
    form_data += `&func=update_price&id=${id}`;
    ajaxCall(
      "POST",
      path,
      form_data,
      "JSON",
      (resp) => {
        if (handle_response(resp)) {
          const { data, msg } = resp;
          $("#popup_modal_sm").modal("hide");
          toastr.success("", msg, { closeButton: true, progressBar: true });
          $("body, html").animate({ scrollTop: 0 }, 1000);
        }
      },
      (errmsg) => {}
    );
  }
};

const sku_remove = (data) => { 
    const path = `${link}/${sub_link}/handler`;
    const form_data = { func: "remove", id: data.sku_id };
    let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">sku : </td>
                          <td width="70%">${data.sku_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">apparel : </td>
                          <td width="70%">${data.apparel_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">rate : </td>
                          <td width="70%">${data.sku_rate}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">STATUS : </td>
                          <td width="70%">${data.sku_status == 1 ? "active" : "inactive"}</td>
                      </tr>
                  </tbody>
              </table>`;
    remove_datav3({ path, form_data, html });
    setTimeout(() => {lazy_loading("master_loading")}, RELOAD_TIME);
  };