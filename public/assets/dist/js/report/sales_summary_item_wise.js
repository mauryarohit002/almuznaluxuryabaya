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

  $(`#_user_name`).select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_user_name`,
      placeholder: "select",
      param: true,
      param1:()=>'salesman'
    })
  );
  
  $("#_apparel_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_apparel_name`,
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
      user_name,
      trans_type,
      apparel_name,
      
      qty,
      rate,
      sub_amt,
      disc_amt,
      taxable_amt,
      sgst_amt,
      cgst_amt,
      igst_amt,
      total_amt,
    } = data;
    return `<tr>
                <td width="3%">${sr_no + index}</td>
                <td width="4%">${entry_no}</td>
                <td width="5%">${entry_date1}</td>
                <td width="8%">${customer_name}</td>
                <td width="8%">${user_name}</td>
                <td width="8%">${trans_type}</td>
                <td width="5%">${apparel_name}</td>
                <td width="4%">${qty}</td>
                <td width="4%">${rate}</td>
                <td width="5%">${sub_amt}</td>
                <td width="5%">${disc_amt}</td>
                <td width="6%">${taxable_amt}</td>
                <td width="5%">${sgst_amt}</td>
                <td width="5%">${cgst_amt}</td>
                <td width="5%">${igst_amt}</td>
                <td width="5%">${total_amt}</td>
              </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_entry_no",
  "_customer_name",
  "_user_name",
  "_trans_type",
  "_customer_mobile",
  "_entry_date_from",
  "_entry_date_to",
  "_sub_amt_from",
  "_sub_amt_to",
  "_disc_amt_from",
  "_disc_amt_to",
  "_taxable_amt_from",
  "_taxable_amt_to",
  "_sgst_amt_from",
  "_sgst_amt_to",
  "_cgst_amt_from",
  "_cgst_amt_to",
  "_igst_amt_from",
  "_igst_amt_to",
  "_bill_disc_from",
  "_bill_disc_to",
  "_total_amt_from",
  "_total_amt_to",
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
        sorting_data("-entry_no");
        $("#totals_sub_amt").html(totals["sub_amt"]);
        $("#totals_disc_amt").html(totals["disc_amt"]);
        $("#totals_taxable_amt").html(totals["taxable_amt"]);
        $("#totals_sgst_amt").html(totals["sgst_amt"]);
        $("#totals_cgst_amt").html(totals["cgst_amt"]);
        $("#totals_igst_amt").html(totals["igst_amt"]);
        $("#totals_total_amt").html(totals["total_amt"]);
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
