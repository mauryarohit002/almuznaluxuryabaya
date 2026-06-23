$(document).ready(function () {
  $("#_entry_no")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_entry_no`,
        placeholder: "entry no",
      })
    )
    .on("change", () => trigger_search());
  $("#_supplier_name")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_supplier_name`,
        placeholder: "supplier",
      })
    )
    .on("change", () => trigger_search());
});
const remove_record = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.payment_id };
  let html = `<table class="table table-sm table-hover" style="font-size:0.8rem;">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold text-uppercase" width="30%" align="right">entry no : </td>
                          <td width="70%">${data.payment_entry_no}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold text-uppercase" width="30%" align="right">entry date : </td>
                          <td width="70%">${data.entry_date}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold text-uppercase" width="30%" align="right">supplier : </td>
                          <td width="70%">${data.supplier_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold text-uppercase" width="30%" align="right">payment amt : </td>
                          <td class="font-weight-bold text-uppercase" width="70%">${data.payment_amt}</td>
                      </tr>
                  </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};
