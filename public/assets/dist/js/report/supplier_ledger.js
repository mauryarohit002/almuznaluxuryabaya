$(document).ready(function () {
  $("#_supplier_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_supplier_name`,
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
      supplier_name,
      purchase_amt,
      payment_amt,
      closing_amt,
      label,
    } = data;
    return `<tr>
                <td width="3%">${sr_no + index}</td>
                <td width="5%">${action}</td>
                <td width="5%">${entry_no}</td>
                <td width="10%">${entry_date}</td>
                <td width="10%">${supplier_name}</td>
                <td width="8%">${purchase_amt}</td>
                <td width="8%">${payment_amt}</td>
                <td width="8%">${closing_amt} ${label}</td>
            </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = ["_supplier_name"];
const get_record = (call = false) => {
  event.preventDefault();
  $("#supplier_name").html("");
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
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
        if (filters["_supplier_name"])
          $("#supplier_name").html(`of ${filters["_supplier_name"]}`);
      }
    },
    (errmsg) => {}
  );
};
