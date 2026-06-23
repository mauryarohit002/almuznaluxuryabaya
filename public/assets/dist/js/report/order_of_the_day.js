$(document).ready(function () {
  $("#_module_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_module_name`,
      placeholder: "select",
    })
  );
 
}); 
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      module_name,
      type,
      apparel_name,
      qty,
      rate,
      amt,
      disc_amt,
      taxable_amt,
      gst_amt,
      total_amt
    } = data;
    return `<tr>
             
             
              <td width="5%">${type}</td>
              <td width="5%">${apparel_name}</td>
              <td width="5%">${qty}</td>
              <td width="5%">${rate}</td>
              <td width="5%">${amt}</td>
              <td width="5%">${disc_amt}</td>
              <td width="5%">${taxable_amt}</td>
              <td width="5%">${gst_amt}</td>
              <td width="5%">${total_amt}</td>
           
            </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_trans_type",
  "_entry_date_from",
  "_entry_date_to",
];
const get_record = (call = false) => {
  event.preventDefault();
  const { filters, params } = get_filter_value();
  const path = `${link}/${sub_link}/handler/`;
  let form_data = { ...filters, func: "get_record", sub_func: "get_record" };
  if (!call) return false;
  const url = `${params.length > 0 ? `?${params}` : ``}`;
  window.history.pushState({}, "", `${base_url}/${link}/${sub_link}${url}`);
  $("#report_excel_export").attr(
    "href",
    `${base_url}/${link}/${sub_link}/excel${url}`
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
        sorting_data("-entry_no");
        $("#totals_total_mtr").html(totals["total_mtr"]);
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
