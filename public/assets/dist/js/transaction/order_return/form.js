$(document).ready(function () { 
    $(`#brmm_id`)
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_brmm_id`,
        placeholder: "scan",
        // barcode: "brmm_id",
        // param:$('#orm_id').val(),
      })
    ).on("change", (event) => get_readymade_barcode_data(event.target.value));
});

let productIndex = 0;
let trans_data = [];
const get_readymade_barcode_data = (id)=>{  
    if (id) {
      trans_data.forEach((value) => {
        if (value["ort_brmm_id"] == id) {
            toastr.error("Duplicate item found!");
            notifier('brmm_id', 'Barcode already added')
            return;
        }
      });

      const path = `${link}/${sub_link}/handler`;
      let form = document.getElementById("_form"); 
      let form_data = new FormData(form);
      form_data.append("func", "get_readymade_barcode_data");
      form_data.append("id", id);
      form_data.append("customer_id", $("#orm_customer_id").val());
      form_data.append("trans_data", JSON.stringify(trans_data));
      fileUpAjaxCall(
        "POST",
        path,
        form_data,
        "JSON",
        (resp) => {
          if (handle_response(resp)) { 
            const { data, msg } = resp;
              if (data && data.length != 0) { 
                 trans_data.unshift(data);
                  add_wrapper_data(data);
                   toastr.success('Item Added in list', msg, {
                      closeButton: true,
                      progressBar: true,
                      preventDuplicates: true,
                  });

                  $('#customer_name').val(`${data['customer_name']}}`)
                  $('#orm_customer_id').val(`${data['ort_customer_id']}`);
                  calculate_master();
              }
           
            setTimeout(() => {
              $("#brmm_id").val(null).trigger("change");
              $("#brmm_id").select2("open");
            }, RELOAD_TIME);
          } else {
            setTimeout(() => {
              $("#brmm_id").val(null).trigger("change");
              $("#brmm_id").select2("open");
            }, RELOAD_TIME);
          }
        },
        (errmsg) => {
          setTimeout(() => {
            $("#brmm_id").val(null).trigger("change");
            $("#brmm_id").select2("open");
          }, RELOAD_TIME);
        }
      );
    }
}

const remove_transaction_notifier = () => {
  notifier("apparel_id");
  notifier("brmm_id");
  notifier("qty");
  notifier("mtr");
  notifier("rate");
  notifier("amt");
};

const get_transaction = () => { 
    if (!["edit", "read"].includes(get_url_string("action"))) return;
    let id = get_url_string("id");
    if (!id) return;
        ajaxCall("POST", `${link}/${sub_link}/handler`, { func: "get_transaction", id }, "JSON", (resp) => {
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
    }, (err) => console.error(err));
};


function add_wrapper_data(data, append = false) {  
    const {
      ort_id,
      ort_orm_id,
      ort_brmm_id,
      item_code,
      ort_qty,
      ort_rate,
      ort_amt,
      ort_disc_per,
      ort_disc_amt,
      ort_taxable_amt,
      ort_sgst_per,
      ort_sgst_amt,
      ort_cgst_per,
      ort_cgst_amt,
      ort_igst_per,
      ort_igst_amt,
      ort_total_amt,
      isExist,
      sr_no = ''
    } = data;

    let tr = `<tr id="row_${ort_id}">
              <td id="sr_no_${ort_id}">${sr_no}</td> 
              <td id="item_code_${ort_id}">${item_code}</td>
              <td id="qty_${ort_id}">${ort_qty}</td>
              <td id="rate_${ort_id}">${ort_rate}</td>
              <td id="amt_${ort_id}">${ort_amt}</td>
              <td id="disc_per_${ort_id}">${ort_disc_per}</td>
              <td id="disc_amt_${ort_id}">${ort_disc_amt}</td>
              <td id="taxable_amt_${ort_id}">${ort_taxable_amt}</td>
              <td id="sgst_per_${ort_id}">${ort_sgst_per}</td>
              <td id="sgst_amt_${ort_id}">${ort_sgst_amt}</td>
              <td id="cgst_per_${ort_id}">${ort_cgst_per}</td>
              <td id="cgst_amt_${ort_id}">${ort_cgst_amt}</td>
              <td id="igst_per_${ort_id}">${ort_igst_per}</td>
              <td id="igst_amt_${ort_id}">${ort_igst_amt}</td>
              <td id="total_amt_${ort_id}">${ort_total_amt}</td>
              <td>
                  <div class="navigationn_wrapper">
                      <div class="navigationn">
                          <div class="menuToggle" id="menu_toggle_${ort_id}" onclick="toggle_menuu(this)"></div>
                          <div class="menuu">
                              <ul>
                                  <li>
                                      ${
                                        isExist
                                          ? `<button 
                                              type="button" 
                                              class="btn btn-md"
                                              ><i class="text-danger fa fa-ban"></i></button>`
                                          : `<a 
                                              type="button" 
                                              class="btn btn-md" 
                                              onclick="remove_transaction(${ort_id})"
                                              ><i class="text-danger fa fa-trash"></i></a>`
                                      }
                                  </li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </td>
          </tr>`;
     if (append) {
      $("#transaction_wrapper").append(tr);
    } else {
      $("#transaction_wrapper").prepend(tr);
    }

}

const calculate_master = () => { 
  let total_qty = 0;
  let sub_total_amt = 0;
  let total_disc_amt    = 0;
  let total_taxable_amt = 0;
  let total_sgst_amt = 0;
  let total_cgst_amt = 0;
  let total_igst_amt = 0;
  let total_total_amt = 0;
  trans_data.forEach((value, index) => {
      const {
        ort_id,
        ort_qty,
        ort_amt,
        ort_disc_per,
        ort_disc_amt,
        ort_sgst_per,
        ort_cgst_per,
        ort_igst_per,
      } = value;
      
      let sgst_amt = 0;
      let cgst_amt = 0;
      let igst_amt = 0;

      let taxable_amt = parseFloat(ort_amt) - parseFloat(ort_disc_amt); 
      if (isNaN(taxable_amt) || taxable_amt == "") taxable_amt = 0;
      
      let total_amt =
        parseFloat(taxable_amt) +
        parseFloat(sgst_amt) +
        parseFloat(cgst_amt) +
        parseFloat(igst_amt);
      if (isNaN(total_amt) || total_amt == "") total_amt = 0;

      taxable_amt = taxable_amt > 0 ? taxable_amt.toFixed(2) : 0.0;
      sgst_amt = sgst_amt > 0 ? sgst_amt.toFixed(2) : 0.0;
      cgst_amt = cgst_amt > 0 ? cgst_amt.toFixed(2) : 0.0;
      igst_amt = igst_amt > 0 ? igst_amt.toFixed(2) : 0.0;
      total_amt = total_amt > 0 ? total_amt.toFixed(2) : 0.0;

      trans_data[index].ot_taxable_amt = taxable_amt;
      trans_data[index].ot_sgst_amt = sgst_amt;
      trans_data[index].ot_cgst_amt = cgst_amt;
      trans_data[index].ot_igst_amt = igst_amt;
      trans_data[index].ot_total_amt = total_amt;
      $(`#total_amt_${ort_id}`).val(total_amt);  

      total_qty = parseValue(total_qty) + parseValue(ort_qty);
      if (isNaN(total_qty) || total_qty == "") total_qty = 0;

      sub_total_amt = parseValue(sub_total_amt) + parseValue(ort_amt);
      if (isNaN(sub_total_amt) || sub_total_amt == "") sub_total_amt = 0;
      
      total_disc_amt = parseFloat(total_disc_amt) + parseFloat(ort_disc_amt);
      if (isNaN(total_disc_amt) || total_disc_amt == "") total_disc_amt = 0;

      total_taxable_amt = parseFloat(total_taxable_amt) + parseFloat(taxable_amt);
      if (isNaN(total_taxable_amt) || total_taxable_amt == "") total_taxable_amt = 0;

      total_sgst_amt = parseFloat(total_sgst_amt) + parseFloat(sgst_amt);
      if (isNaN(total_sgst_amt) || total_sgst_amt == "") total_sgst_amt = 0;

      total_cgst_amt = parseFloat(total_cgst_amt) + parseFloat(cgst_amt);
      if (isNaN(total_cgst_amt) || total_cgst_amt == "") total_cgst_amt = 0;

      total_igst_amt = parseFloat(total_igst_amt) + parseFloat(igst_amt);
      if (isNaN(total_igst_amt) || total_igst_amt == "") total_igst_amt = 0;

      total_total_amt = parseFloat(total_total_amt) + parseFloat(total_amt);
      if (isNaN(total_total_amt) || total_total_amt == "") total_total_amt = 0;

   }); 

  $("#orm_total_qty").val(total_qty);
  $("#orm_sub_amt").val(sub_total_amt.toFixed(2));
  $("#orm_disc_amt").val(total_disc_amt.toFixed(2));

  $("#orm_taxable_amt").val(total_taxable_amt.toFixed(2));
  $("#orm_sgst_amt").val(total_sgst_amt.toFixed(2));
  $("#orm_cgst_amt").val(total_cgst_amt.toFixed(2));
  $("#orm_igst_amt").val(total_igst_amt.toFixed(2));
  $("#orm_total_amt").val(total_total_amt.toFixed(2));

  if (total_total_amt > 0) {
      $(".master_block_btn").prop("disabled", false);
  } else {
      $(".master_block_btn").prop("disabled", true);
  }


};


const remove_transaction = (ort_id) => { 
  trans_data = trans_data.filter((value) => value.ort_id != ort_id);
  let trans_type = $(`#trans_type_${ort_id}`).html();
  toastr.success(`${trans_type}`, "ITEM REMOVED FROM LIST.", {
    closeButton: true,
    progressBar: true,
  });
  $(`#row_${ort_id}`).detach();
  $("#transaction_count").html(trans_data.length);
  calculate_master();
};

function add_edit(){   
    let check = true;
    if(!$('#orm_customer_id').val()){ notifier('orm_customer_id','Required'); check=false; }
    if($('#orm_entry_no').val() == ""){ notifier('orm_entry_no','Required'); check=false; }
    if(!check){ toastr.error("Please fill all required fields."); return; }
    let form = document.getElementById("_form");
    let form_data = new FormData(form);
    form_data.append("func","add_edit");
    form_data.append("trans_data", JSON.stringify(trans_data));
    fileUpAjaxCall("POST", `${link}/${sub_link}/handler`, form_data, "JSON", (resp)=>{
        if(handle_response(resp)){
            const { data, msg } = resp;
                toastr.success("", resp.msg);
                setTimeout(()=>window.location.reload(),500);
            
        }
    });
}









