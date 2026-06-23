$(document).ready(function () {
  $("#_company_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_company_name`,
      placeholder: "select",
    })
  );
  $("#_bill_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_bill_no`,
      placeholder: "select",
    })
  );
  $("#_supplier_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_supplier_name`,
      placeholder: "select",
    })
  );
  $("#_supplier_mobile").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_supplier_mobile`,
      placeholder: "select",
    })
  );
  get_sort_by();
});
const filters_arr = [
  "_bill_no",
  "_company_name",
  "_supplier_name",
  "_supplier_mobile",
  "_bill_date_from",
  "_bill_date_to",
  "_total_qty_from",
  "_total_qty_to",
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
const render = (data) => {
  let content = data.map((data) => {
    const {
      cnt,
      page,
      bill_no,
      bill_date,
      supplier_name,
      supplier_mobile,
      total_qty,
      sub_amt,
      disc_amt,
      taxable_amt,
      sgst_amt,
      cgst_amt,
      igst_amt,
      bill_disc_amt,
      total_amt,
    } = data;
    return `<tr>
              <td width="3%">
                ${cnt + 1}
                <span 
                  class="${ENV == DEV ? "" : "d-none"}"
                >/ ${page}</span>
              </td>
              <td width="4%">${bill_no}</td>
              <td width="5%">${bill_date}</td>
              <td width="10%">${supplier_name}</td>
              <td width="8%">${supplier_mobile}</td>
              <td width="5%">${total_qty}</td>
              <td width="5%">${sub_amt}</td>
              <td width="5%">${disc_amt}</td>
              <td width="6%">${taxable_amt}</td>
              <td width="5%">${sgst_amt}</td>
              <td width="5%">${cgst_amt}</td>
              <td width="5%">${igst_amt}</td>
              <td width="5%">${bill_disc_amt}</td>
              <td width="5%">${total_amt}</td>
            </tr>`;
  });
  $("#tbody_wrapper").append(content);
};
