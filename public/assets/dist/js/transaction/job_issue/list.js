$(document).ready(function () {
  $("#_entry_no")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_entry_no`,
        placeholder: "entry no",
      })
    )
    .on("change", () => trigger_search());
  $("#_order_no")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_order_no`,
        placeholder: "order no",
      })
    )
    .on("change", () => trigger_search());
  $("#_item_code")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_item_code`,
        placeholder: "barcode",
      })
    )
    .on("change", () => trigger_search());
  $("#_apparel_name")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_apparel_name`,
        placeholder: "apparel",
      })
    )
    .on("change", () => trigger_search());
  $("#_proces_name")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_proces_name`,
        placeholder: "process",
      })
    )
    .on("change", () => trigger_search());
  $("#_karigar_name")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_karigar_name`,
        placeholder: "karigar",
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
  const form_data = { func: "remove", id: data.jim_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                    <tr>
                      <td class="font-weight-bold" width="30%" align="right">entry no : </td>
                      <td width="70%">${data.jim_entry_no}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">entry date : </td>
                        <td width="70%">${data.jim_entry_date}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">process : </td>
                        <td width="70%">${data.proces_name}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">karigar : </td>
                        <td width="70%">${data.karigar_name}</td>
                    </tr>
                  </tbody>
              </table>`;
  remove_datav3({ path, form_data, html });
};
