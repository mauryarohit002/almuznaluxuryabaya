$(document).ready(function () {
    $("#_sku_name").select2(select2_default({
        url: `${link}/${sub_link}/get_select2/_sku_name`,
        placeholder: "sku",
    })).on("change", () => trigger_search());

    $("#_apparel_name").select2(select2_default({
        url: `${link}/${sub_link}/get_select2/_apparel_name`,
        placeholder: "apparel",
    })).on("change", () => trigger_search());

    lazy_loading('master_loading');
});

const sku_remove = (data) => {
    const path = `${link}/${sub_link}/handler`;
    const form_data = { func: "remove", id: data.sku_id };
    let html = `<table class="table table-sm table-hover text-uppercase">
                  <tbody>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">sku : </td>
                          <td width="70%">${data.sku_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">apparel : </td>
                          <td width="70%">${data.apparel_name}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">rate : </td>
                          <td width="70%">${data.sku_rate}</td>
                      </tr>
                      <tr>
                          <td class="font-weight-bold" width="30%" align="right">STATUS : </td>
                          <td width="70%">${data.sku_status == 1 ? "active" : "inactive"}</td>
                      </tr>
                  </tbody>
              </table>`;
    remove_datav3({ path, form_data, html });
    setTimeout(() => {lazy_loading("master_loading")}, RELOAD_TIME);
  };