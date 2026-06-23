$(document).ready(function () {
  $("#_bill_no").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_bill_no`,
      placeholder: "select",
    })
  );
  $("#_company_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_company_name`,
      placeholder: "select",
    })
  );
  $("#_supplier_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_supplier_name`,
      placeholder: "select",
    })
  );
  $("#_product_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_product_name`,
      placeholder: "select",
    })
  );
  $("#_design_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_design_name`,
      placeholder: "select",
    })
  );
  $("#_color_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_color_name`,
      placeholder: "select",
    })
  );
  $("#_size_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_size_name`,
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
  "_product_name",
  "_design_name",
  "_color_name",
  "_size_name",
  "_bill_date_from",
  "_bill_date_to",
  "_qty_from",
  "_qty_to",
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
      product_name,
      design_name,
      color_name,
      size_name,
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
              <td width="3%">
                ${cnt + 1}
                <span 
                  class="${ENV == DEV ? "" : "d-none"}"
                >/ ${page}</span>
              </td>
              <td width="4%">${bill_no}</td>
              <td width="5%">${bill_date}</td>
              <td width="8%">${supplier_name}</td>
              <td width="5%">${product_name}</td>
              <td width="5%">${design_name}</td>
              <td width="5%">${color_name}</td>
              <td width="4%">${size_name}</td>
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
  $("#tbody_wrapper").append(content);
};
