$(document).ready(function () {
  $("#_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_entry_no`,
      placeholder: "select",
    })
  );
  $("#_qrcode_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_qrcode_no`,
      placeholder: "select",
    })
  );
  $("#_apparel_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_apparel_name`,
      placeholder: "select",
    })
  );

  $("#_customer").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_customer`,
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
      item_code,
      apparel_name,
      customer_name,
    } = data;
    return `<tr>
                <td width="3%">${sr_no + index}</td>
                <td width="5%">${entry_no}</td>
                <td width="5%">${entry_date}</td>
                <td width="10%">${item_code}</td>
                <td width="5%">${apparel_name}</td>
                <td width="5%">${customer_name}</td>
            </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_entry_no",
  "_entry_date_from",
  "_entry_date_from",
  "_entry_date_to",
  "_qrcode_no",
  "_apparel_name",
  "_customer",
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
        sorting_data("-bal_mtr");
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
