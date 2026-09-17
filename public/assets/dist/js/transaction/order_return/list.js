$(document).ready(function () {
  $("#_entry_no")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_entry_no`,
        placeholder: "entry no",
      })
    )
    .on("change", () => trigger_search());
  $("#_customer_name")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_customer_name`,
        placeholder: "customer",
      })
    )
    .on("change", () => trigger_search());
  
});

const record_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.orm_id };
  console.log(data);
  let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                    <tr>
                      <td class="font-weight-bold" width="30%" align="right">entry no : </td>
                      <td width="70%">${data.orm_entry_no}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold" width="30%" align="right">entry date : </td>
                      <td width="70%">${data.orm_entry_date}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold" width="30%" align="right">customer : </td>
                      <td width="70%">${data.customer_name}</td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold" width="30%" align="right">total qty : </td>
                      <td width="70%">${data.orm_total_qty}</td>
                    </tr>
                </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};




