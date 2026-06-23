$(document).ready(function () {
  $("#_module_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_module_name`,
      placeholder: "select",
    })
  );
  $("#_payment_mode_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_payment_mode_name`,
      placeholder: "select",
    })
  );
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const { module_name, entry_date, payment_mode_name, payment_mode_amt } =
      data;
    return `<tr>
                  <td width="3%">${sr_no + index}</td>
                  <td width="5%">${module_name}</td>
                  <td width="5%">${entry_date}</td>
                  <td width="5%">${payment_mode_name}</td>
                  <td width="5%">${payment_mode_amt}</td>
              </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_module_name",
  "_payment_mode_name",
  "_entry_date_from",
  "_entry_date_to",
  "_payment_mode_amt_from",
  "_payment_mode_amt_to",
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
        sorting_data("-entry_date");
        $("#totals_payment_mode_amt").html(totals["payment_mode_amt"]);
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
