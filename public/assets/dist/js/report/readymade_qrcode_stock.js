$(document).ready(function () {
  $("#_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_entry_no`,
      placeholder: "select",
    })
  );
  $("#_item_code").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_item_code`,
      placeholder: "select",
    })
  );
  $("#_supplier_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_supplier_name`,
      placeholder: "select",
    })
  );
  $("#_category_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_category_name`,
      placeholder: "select",
    })
  );
  $("#_product_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_product_name`,
      placeholder: "select",
    })
  );
  $("#_design_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_design_name`,
      placeholder: "select",
    })
  );
  $("#_color_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_color_name`,
      placeholder: "select",
    })
  );
  $("#_size_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_size_name`,
      placeholder: "select",
    })
  );
  $("#_description").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_description`,
      placeholder: "select",
    })
  );
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      entry_no,
      entry_date,
      nod,
      item_code,
      supplier_name,
      category_name,
      product_name,
      design_name,
      color_name,
      size_name,
      description,
      rate,
      mrp,
      prmt_qty,
      ot_qty,
      bal_qty,
      bal_amt,
    } = data;
    return `<tr>
                  <td width="3%">${sr_no + index}</td>
                  <td width="3%">${entry_no}</td>
                  <td width="5%">${entry_date}</td>
                  <td width="3%">${nod}</td>
                  <td width="5%">${item_code}</td>
                  <td width="10%">${supplier_name}</td>
                  <td width="5%">${product_name}</td>
                  <td width="5%">${design_name}</td>
                  <td width="5%">${category_name}</td>
                  <td width="5%">${color_name}</td>
                  <td width="5%">${size_name}</td>
                  <td width="10%">${description}</td>
                  <td width="5%">${rate}</td>
                  <td width="5%">${mrp}</td>
                  <td width="5%">${prmt_qty}</td>
                  <td width="5%">${ot_qty}</td>
                  <td width="5%">${bal_qty}</td>
                  <td width="5%">${bal_amt}</td>
              </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_entry_no",
  "_item_code",
  "_supplier_name",
  "_design_name",
  "_product_name",
  "_category_name",
  "_color_name",
  "_size_name",
  "_description",
  "_entry_date_from",
  "_entry_date_to",
  "_nod_from",
  "_nod_to",
  "_rate_from",
  "_rate_to",
  "_mrp_from",
  "_mrp_to",
  "_prmt_qty_from",
  "_prmt_qty_to",
  "_ot_qty_from",
  "_ot_qty_to",
  "_bal_qty_from",
  "_bal_qty_to",
  "_bal_amt_from",
  "_bal_amt_to",
];
const get_record = (call = false) => {
  event.preventDefault();
  const { filters, params } = get_filter_value();
  const path = `${link}/${sub_link}/handler/`;
  let form_data = { ...filters, func: "get_record", sub_func: "get_record" };
  if (!call) return false;
  window.history.pushState(
    {},
    "",
    `${base_url}/${link}/${sub_link}${params.length > 0 ? `?${params}` : ``}`
  );
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data } = resp;
        const { totals } = data;
        raw = data["data"] && data["data"].length != 0 ? data["data"] : [];
        sorting_data("-bal_qty");
        $("#totals_prmt_qty").html(totals["prmt_qty"]);
        $("#totals_ot_qty").html(totals["ot_qty"]);
        $("#totals_bal_qty").html(totals["bal_qty"]);
        $("#totals_bal_amt").html(totals["bal_amt"]);
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
