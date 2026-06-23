$(document).ready(function () {
  $("#_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_entry_no`,
      placeholder: "select",
    })
  );
  $("#_order_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_order_no`,
      placeholder: "select",
    })
  );
  $("#_customer_name").select2( 
    select2_default({
      url: `${link}/${sub_link}/get_select2/_customer_name`,
      placeholder: "select",
    })
  );
  $("#_proces_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_proces_name`,
      placeholder: "select",
    })
  );
  $("#_karigar_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_karigar_name`,
      placeholder: "select",
    })
  );

  $("#_bm_item_code").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_bm_item_code`,
      placeholder: "select",
    })
  );

});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      entry_no,
      entry_date1,
      order_no,
      order_date1,
      obt_item_code,
      customer_name,
      proces_name,
      karigar_name,
      apparel_name,
      job_status,
    } = data;
    return `<tr>
                    <td width="3%">${sr_no + index}</td>
                    <td width="5%">${entry_no}</td>
                    <td width="5%">${entry_date1}</td>
                    <td width="5%">${order_no}</td>
                    <td width="5%">${order_date1}</td>
                    <td width="5%">${obt_item_code}</td>
                    <td width="8%">${customer_name}</td>
                    <td width="8%">${proces_name}</td>
                    <td width="8%">${karigar_name}</td>
                    <td width="8%">${apparel_name}</td>
                    <td width="5%">${job_status}</td>
                </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_entry_no",
  "_entry_date_from",
  "_entry_date_to",
  "_order_no",
  "_bm_item_code",
  "_order_date_from",
  "_order_date_to",
  "_customer_name",
  "_proces_name",
  "_karigar_name",
  "_apparel_name",
  "_job_status",
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
