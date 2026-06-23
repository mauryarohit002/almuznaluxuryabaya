$(document).ready(function () {
    $("#sku_apparel_id").select2(select2_default({
        url: `master/apparel/get_select2/_id`,
        placeholder: "SELECT",
        param: true,
    }));
    $("#sku_supplier_id").select2(select2_default({
        url: `master/supplier/get_select2/_id`,
        placeholder: "SELECT",
        param: true,
    }));
});
const remove_sku_image = () => {
    $("#sku_photo").val("");
    $("#sku_pic").val(NOIMAGE);
    $("#preview").html(`<img class="img-thumbnail" width="145px" src="${NOIMAGE}" />`);
};
const add_edit = () => {
    event.preventDefault();
    notifier('sku_apparel_id');
    notifier('sku_name');
    notifier('sku_supplier_id');
    notifier('sku_piece');
    let check = true;
    
    if ($(`#sku_apparel_id`).val() == null) {
      notifier(`sku_apparel_id`, "Required");
      check = false;
    }
    
    if ($(`#sku_name`).val() == "") {
      notifier(`sku_name`, "Required");
      check = false;
    }

    if ($(`#sku_supplier_id`).val() == null) {
      notifier(`sku_supplier_id`, "Required");
      check = false;
    }
    
    if ($(`#sku_piece`).val() <= 0 || $(`#sku_piece`).val() == "") {
        notifier(`sku_piece`, "Required");
        check = false;
    }

    if (!check) {
      toastr.error("You forgot to enter some information.", "Oh snap!!!", {
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
      });
      return;
    }
    if ($(`#total_amt`).html() <= 0 || $(`#total_amt`).html() == "") {
        toastr.error("Total amt is required.", "", {
            closeButton: true,
            progressBar: true,
            preventDuplicates: true,
        });
        return;
    } 

    const path = `${link}/${sub_link}/handler`;
    let form_id = document.getElementById("_form");
    let form_data = new FormData(form_id);
    form_data.append("func", "add_edit");
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
            
            notifier('sku_apparel_id');
            notifier('sku_name');
            notifier('sku_supplier_id');
            notifier('sku_piece');
            toastr.success("", msg, {
                closeButton: true,
                progressBar: true,
                preventDuplicates: true,
            });
            setTimeout(() => {
              window.location.reload();
            }, RELOAD_TIME);
        }
    },
    (errmsg) => {}
    );
  };

  function calculate_cp() {

    let fabric      = parseFloat(document.getElementById('sku_fabric')?.value) || 0;
    let cutting     = parseFloat(document.getElementById('sku_cutting')?.value) || 0;
    let silai       = parseFloat(document.getElementById('sku_silai')?.value) || 0;
    let stone       = parseFloat(document.getElementById('sku_stone')?.value) || 0;
    let lagwayi     = parseFloat(document.getElementById('sku_lagwayi')?.value) || 0;
    let hand_work   = parseFloat(document.getElementById('sku_hand_work')?.value) || 0;
    let material    = parseFloat(document.getElementById('sku_material')?.value) || 0;
    let exp         = parseFloat(document.getElementById('sku_exp')?.value) || 0;

    let total = fabric + cutting + silai + stone + lagwayi + hand_work + material + exp;

    document.getElementById('sku_cp').value = total.toFixed(2);
}