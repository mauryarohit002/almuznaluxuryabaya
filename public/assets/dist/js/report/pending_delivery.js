$(document).ready(function () {
  $("#_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_entry_no`,
      placeholder: "select",
      multiple:true
    })
  );

  $("#_customer_name").select2( 
    select2_default({
      url: `${link}/${sub_link}/get_select2/_customer_name`,
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
      customer_name,
      customer_mobile,
      trial_date,
      delivery_date,
      total_amt,
      advance_amt,
      allocated_amt,
      balance_amt,
      status
    } = data;
    return `<tr>
                    <td width="3%">${sr_no + index}</td>
                    <td width="5%">${entry_no}</td>
                    <td width="5%">${entry_date1}</td>
                    <td width="8%">${customer_name}</td>
                    <td width="8%">${customer_mobile}</td>
                    <td width="5%">${trial_date}</td>
                    <td width="5%">${delivery_date}</td>
                    <td width="5%">${total_amt}</td>
                    <td width="5%">${advance_amt}</td>
                    <td width="5%">${allocated_amt}</td>
                    <td width="5%">${balance_amt}</td>
                    <td width="5%">${status}</td>

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
