$(document).ready(function () {
  $("#_customer_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_customer_name`,
      placeholder: "select",
    })
  );
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      customer_name,
      opening_amt,
      sales_amt,
      receipt_amt,
      closing_amt,
      label,
    } = data;
    return `<tr>
                <td width="3%">${sr_no + index}</td>
                <td width="10%">${customer_name}</td>
                <td width="10%">${opening_amt}</td>
                <td width="10%">${sales_amt}</td>
                <td width="10%">${receipt_amt}</td>
                <td width="10%">${closing_amt} ${label}</td>
            </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = ["_customer_name", "_close_amt_from", "_close_amt_to"];
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
