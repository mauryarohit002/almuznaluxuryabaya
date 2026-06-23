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
  $("#_memo_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_memo_no`,
      placeholder: "select",
    })
  );
  $("#_billing_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_billing_name`,
      placeholder: "select",
    })
  );
  $("#_billing_mobile").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_billing_mobile`,
      placeholder: "select",
    })
  );
  $("#_hsn_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_hsn_name`,
      placeholder: "select",
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
      memo_no,
      entry_date1,
      billing_name,
      apparel_name,
      hsn_name,
      total_mtr,
      rate,
      amt,
      tax_per,
      sgst_amt,
      cgst_amt,
      igst_amt,
      tax_amt,
      total_amt,
      advance_amt,
      balance_amt,
    } = data;
    return `<tr>
              <td width="10%">${apparel_name}</td>
              <td width="4%">${hsn_name}</td>
              <td width="5%">${total_mtr}</td>
              <td width="5%">${amt}</td>
              <td width="5%">${sgst_amt}</td>
              <td width="5%">${cgst_amt}</td>
              <td width="5%">${igst_amt}</td>
              <td width="5%">${tax_amt}</td>
              <td width="5%">${total_amt}</td>
            </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_company_name",
  "_module_name",
  "_entry_no",
  "_memo_no",
  "_billing_name",
  "_billing_mobile",
  "_hsn_name",
  "_apparel_name",
  "_entry_date_from",
  "_entry_date_to",
  "_total_mtr_from",
  "_total_mtr_to",
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
  const url = `${params.length > 0 ? `?${params}` : ``}`;
  window.history.pushState({}, "", `${base_url}/${link}/${sub_link}${url}`);
  $("#report_excel_export").attr(
    "href",
    `${base_url}/${link}/${sub_link}/excel${url}`
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
        $("#totals_total_mtr").html(totals["total_mtr"]);
        $("#totals_sub_amt").html(totals["sub_amt"]);
        $("#totals_disc_amt").html(totals["disc_amt"]);
        $("#totals_taxable_amt").html(totals["taxable_amt"]);
        $("#totals_sgst_amt").html(totals["sgst_amt"]);
        $("#totals_cgst_amt").html(totals["cgst_amt"]);
        $("#totals_igst_amt").html(totals["igst_amt"]);
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
