$(document).ready(function () {
  $("#_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_entry_no`,
      placeholder: "select",
      multiple:true
    })
  );

  $("#_om_em_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_om_entry_no`,
      placeholder: "select",
      multiple:true
    })
  );
  $("#_qrcode").select2( 
    select2_default({
      url: `${link}/${sub_link}/get_select2/_qrcode`,
      placeholder: "select",
    })
  );
  $("#_branch").select2( 
    select2_default({
      url: `${link}/${sub_link}/get_select2/_branch_name`,
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
      om_em_entry_no,
      om_em_entry_date1,
      qrcode,
      apparel_name,
      status,
      status_notes,
      branch_name
    } = data;
    return `<tr>
                    <td width="3%">${sr_no + index}</td>
                    <td width="5%">${entry_no}</td>
                    <td width="5%">${entry_date1}</td>
                    <td width="5%">${om_em_entry_no}</td>
                    <td width="5%">${om_em_entry_date1}</td>
                    <td width="8%">${qrcode}</td>
                    <td width="8%">${apparel_name}</td>
                    <td width="5%">${branch_name}</td>
                    <td width="5%">${status}</td>
                    <td width="5%">${status_notes}</td>

                </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_entry_no",
  "_entry_date_from",
  "_entry_date_to",
  "_om_em_entry_no",
  "_om_em_entry_date_from",
  "_om_em_entry_date_to",
  "_qrcode",
  "_apparel_name",
  "_branch_name",
  "_status",
  "_status_notes",
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
