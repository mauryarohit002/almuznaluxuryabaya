$(document).ready(function () {
  $("#_entry_no")
    .select2(
      select2_default({
        url: `${link}/${sub_link}/get_select2/_entry_no`,
        placeholder: "entry no",
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
});
const record_remove = (data) => {
  const path = `${link}/${sub_link}/handler`;
  const form_data = { func: "remove", id: data.hm_id };
  let html = `<table class="table table-sm table-hover text-uppercase">
                <tbody>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">estimate no : </td>
                        <td width="70%">${data.hm_entry_no}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">estimate date : </td>
                        <td width="70%">${data.hm_entry_date}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">karigar : </td>
                        <td width="70%">${data.karigar_name}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">total qty : </td>
                        <td width="70%">${data.hm_total_qty}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold" width="30%" align="right">total amt : </td>
                        <td width="70%">${data.hm_total_amt}</td>
                    </tr>
                </tbody>
            </table>`;
  remove_datav3({ path, form_data, html });
};
