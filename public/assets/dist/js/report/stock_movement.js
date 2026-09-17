$(document).ready(function () {
  $("#_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_entry_no`,
      placeholder: "select",
    })
  );

  $("#_branch_name").select2( 
    select2_default({
      url: `${link}/${sub_link}/get_select2/_branch_name`,
      placeholder: "select",
    })
  );
  

});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      month,
      open_qty,
      pur_qty,
      pur_return_qty,
      outward_qty,
      inward_qty,
      sale_qty,
      close_qty
    } = data;
    return `<tr>
                    <td width="3%">${sr_no + index}</td>
                    <td width="5%">${month}</td>
                    <td width="5%">${open_qty}</td>
                    <td width="5%">${pur_qty}</td>
                    <td width="5%">${pur_return_qty}</td>
                    <td width="5%">${outward_qty}</td>
                    <td width="5%">${inward_qty}</td>
                    <td width="5%">${sale_qty}</td>
                    <td width="5%">${close_qty}</td>

                </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_entry_no",
  "_entry_date_from",
  "_entry_date_to",
  "_order_no",
  "_bm_item_code",
  "_order_date_from",
  "_order_date_to",
  "_customer_name",
  "_proces_name",
  "_karigar_name",
  "_apparel_name",
  "_job_status",
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
  $("#missing_mobile_pdf_btn").attr(
    "href",
    `${base_url}/${link}/${sub_link}/pdf${
      params.length > 0 ? `?${params}` : ``
    }`
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
        sorting_data("-customer_name");
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
