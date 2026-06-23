$(document).ready(function () {
  $("#_customer_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_customer_name`,
      placeholder: "select",
    })
  );
  $("#_contact_person").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_contact_person`,
      placeholder: "select",
    })
  );
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      customer_name,
      contact_person,
      customer_mobile,
      customer_phone,
      customer_email,
      customer_address,
      city_name,
      state_name,
      country_name,
      customer_pincode,
      customer_status,
    } = data;
    return `<tr>
                    <td width="3%">${sr_no + index}</td>
                    <td width="10%">${customer_name}</td>
                    <td width="10%">${contact_person}</td>
                    <td width="5%">${customer_mobile}</td>
                    <td width="5%">${customer_phone}</td>
                    <td width="8%" class="text-lowercase">${customer_email}</td>
                    <td width="15%">${customer_address}</td>
                    <td width="8%">${city_name}</td>
                    <td width="8%">${state_name}</td>
                    <td width="8%">${country_name}</td>
                    <td width="5%">${customer_pincode}</td>
                </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = ["_customer_name", "_contact_person"];
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
