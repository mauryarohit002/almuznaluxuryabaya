$(document).ready(function () {
  $("#_debit_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_debit_name`,
      placeholder: "select",
    })
  );
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      action,
      entry_no,
      entry_date,
      customer_name,
      order_amt,
      estimate_amt,
      advance_amt,
      receipt_amt,
      closing_amt,
      label,
    } = data;
    return `<tr>
                <td width="3%">${sr_no + index}</td>
                <td width="5%">${action}</td>
                <td width="5%">${entry_no}</td>
                <td width="10%">${entry_date}</td>
                <td width="10%">${customer_name}</td>
                <td width="8%">${order_amt}</td>
                <td width="8%">${estimate_amt}</td>
                <td width="8%">${advance_amt}</td>
                <td width="8%">${receipt_amt}</td>
                <td width="8%">${closing_amt} ${label}</td>
            </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = ["_date_from", "_date_to"];
const get_record = (call = false) => {
  event.preventDefault();
  $("#debit_name").html("");
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
        const { label, totals } = data;
        raw = data["data"] && data["data"].length != 0 ? data["data"] : [];
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
        $("#label_wrapper").html(`${label} ${totals}`);
      }
    },
    (errmsg) => {}
  );
};
