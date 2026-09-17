$(document).ready(function () {
  
  $("#_karigar_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_karigar_name`,
      placeholder: "select",
    })
  );
  
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      module_name,
     
    } = data;
    return `<tr>
                  <td width="3%">${sr_no + index}</td>
                  <td width="3%">${module_name}</td>
                 
              </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_module_name",
  "_entry_no",
  "_billing_name",
  "_billing_mobile",
  "_entry_date_from",
  "_entry_date_to",
  "_total_mtr_from",
  "_total_mtr_to",
  "_sub_amt_from",
  "_sub_amt_to",
  "_disc_amt_from",
  "_disc_amt_to",
  "_taxable_amt_from",
  "_taxable_amt_to",
  "_sgst_amt_from",
  "_sgst_amt_to",
  "_cgst_amt_from",
  "_cgst_amt_to",
  "_igst_amt_from",
  "_igst_amt_to",
  "_bill_disc_from",
  "_bill_disc_to",
  "_total_amt_from",
  "_total_amt_to",
  "_advance_amt_from",
  "_advance_amt_to",
  "_balance_amt_from",
  "_balance_amt_to",
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
