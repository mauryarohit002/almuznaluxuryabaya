$(document).ready(function () {
  $("#_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_entry_no`,
      placeholder: "select",
    })
  );
  $("#_customer_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_customer_name`,
      placeholder: "select",
    })
  );
  $("#_customer_mobile").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_customer_mobile`,
      placeholder: "select",
    })
  );
  $("#_apparel_group").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_apparel_group`,
      placeholder: "select",
    })
  );
  $("#_apparel_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_apparel_name`,
      placeholder: "select",
      multiple: true,
    })
  );
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      entry_no,
      customer_name,
      customer_mobile,
      entry_date,
      trial_date,
      delivery_date,
      apparel_name,
      notes,
      apparel_data,
    } = data;
    return `<tr>
                  <td width="3%">${sr_no + index}</td>
                  <td width="5%">${entry_no}</td>
                  <td width="5%">
                    ${customer_name}
                    ${apparel_data}
                  </td>
                  <td width="5%">${customer_mobile}</td>
                  <td width="5%">${entry_date}</td>
                  <td width="5%">${trial_date}</td>
                  <td width="5%">${delivery_date}</td>
                  <td width="5%">${notes}</td>
              </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_entry_no",
  "_customer_name",
  "_customer_mobile",
  "_apparel_group",
  "_apparel_name",
  "_trial_date_from",
  "_trial_date_to",
  "_delivery_date_from",
  "_delivery_date_to",
];
const get_record = (call = false) => {
  event.preventDefault();
  const { filters, params } = get_filter_value();
  const path = `${link}/${sub_link}/handler/`;
  let form_data = { ...filters, func: "get_record", sub_func: "get_record" };
  if (!call) return false;
  $("#delivery_schedule_pdf_btn").attr(
    "href",
    `${base_url}/${link}/${sub_link}/pdf${
      params.length > 0 ? `?${params}` : ``
    }`
  );
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
        raw = data["data"] && data["data"].length != 0 ? data["data"] : [];
        sorting_data("-delivery_date");
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
