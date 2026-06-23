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
      karigar_name,
      opening_amt,
      hisab_amt,
      payment_amt,
      closing_amt,
      label,
    } = data;
    return `<tr>
                <td width="3%">${sr_no + index}</td>
                <td width="10%">${karigar_name}</td>
                <td width="10%">${opening_amt}</td>
                <td width="10%">${hisab_amt}</td>
                <td width="10%">${payment_amt}</td>
                <td width="10%">${closing_amt} ${label}</td>
            </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = ["_karigar_name", "_close_amt_from", "_close_amt_to"];
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
    `${base_url}/${link}/${sub_link}/pdf${params.length > 0 ? `?${params}` : ``
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
        sorting_data("-karigar_name");
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => { }
  );
};
