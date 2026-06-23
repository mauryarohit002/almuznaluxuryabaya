$(document).ready(function () {
  $("#_module_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_module_name`,
      placeholder: "select",
    })
  );
  $("#_entry_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_entry_no`,
      placeholder: "select",
    })
  );
  $("#_billing_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_billing_name`,
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
  $("#_billing_mobile").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_billing_mobile`,
      placeholder: "select",
    })
  );
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      module_name,
      entry_no,
      entry_date,
      entry_date1,
      billing_name,
      billing_mobile,
      user_name,
      total_mtr,
      sub_amt,
      disc_amt,
      taxable_amt,
      bill_disc_amt,
      total_amt,
      advance_amt,
      balance_amt,
    } = data;
    return `<tr>
                  <td width="3%">${sr_no + index}</td>
                  <td width="3%">${module_name}</td>
                  <td width="4%">${entry_no}</td>
                  <td width="5%">${entry_date1}</td>
                  <td width="10%">${billing_name}</td>
                  <td width="8%">${billing_mobile}</td>
                  <td width="8%">${user_name}</td>
                  <td width="5%">${sub_amt}</td>
                  <td width="5%">${disc_amt}</td>
                  <td width="6%">${taxable_amt}</td>
                  <td width="5%">${bill_disc_amt}</td>
                  <td width="5%">${total_amt}</td>
                  <td width="6%">${advance_amt}</td>
                  <td width="6%">${balance_amt}</td>
              </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_module_name",
  "_entry_no",
  "_billing_name",
  "_billing_mobile",
  "_entry_date_from",
  "_entry_date_to",
  "_sub_amt_from",
  "_sub_amt_to",
  "_disc_amt_from",
  "_disc_amt_to",
  "_taxable_amt_from",
  "_taxable_amt_to",
  "_bill_disc_from",
  "_bill_disc_to",
  "_total_amt_from",
  "_total_amt_to",
  "_advance_amt_from",
  "_advance_amt_to",
  "_balance_amt_from",
  "_balance_amt_to",
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
        $("#totals_total_amt").html(totals["total_amt"]);
        $("#totals_advance_amt").html(totals["advance_amt"]);
        $("#totals_balance_amt").html(totals["balance_amt"]);
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
