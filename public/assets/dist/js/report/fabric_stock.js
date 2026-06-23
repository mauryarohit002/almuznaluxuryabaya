$(document).ready(function () {
  $("#_fabric_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_fabric_name`,
      placeholder: "select",
    })
  );
  $("#_design_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_design_name`,
      placeholder: "select",
    })
  );
   $("#_category_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_category_name`,
      placeholder: "select",
    })
  );

  $("#_color_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_color_name`,
      placeholder: "select",
    })
  );
  $("#_width_name").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_width_name`,
      placeholder: "select",
    })
  );
  $("#_description").select2(
    select2_default({
      url: `${link}/${sub_link}/get_select2/_description`,
      placeholder: "select",
    })
  );
});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const {
      fabric_name,
      design_name,
      category_name,
      color_name,
      rate,
      mrp,
      pt_mtr,
      prt_mtr,
      ot_mtr,
      bal_mtr,
      bal_amt,
      bal_mrp,

    } = data;
    return `<tr>
                <td width="3%">${sr_no + index}</td>
                <td width="5%">${fabric_name}</td>
                <td width="5%">${design_name}</td>
                <td width="5%">${category_name}</td>
                <td width="5%">${color_name}</td>
                <td width="5%">${rate}</td>
                <td width="5%">${mrp}</td>
                <td width="5%">${pt_mtr}</td>
                <td width="5%">${prt_mtr}</td>
                <td width="5%">${ot_mtr}</td>
                <td width="5%">${bal_mtr}</td>
                <td width="5%">${bal_amt}</td>
                <td width="5%">${bal_mrp}</td>
                
            </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_fabric_name",
  "_design_name",
  "_category_name",
  "_color_name",
  "_width_name",
  "_description",
  "_rate_from",
  "_rate_to",
  "_mrp_from",
  "_mrp_to",
  "_pt_mtr_from",
  "_pt_mtr_to",
  "_ot_mtr_from",
  "_ot_mtr_to",
  "_bal_mtr_from",
  "_bal_mtr_to",
  "_bal_amt_from",
  "_bal_amt_to",
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
        sorting_data("-bal_mtr");
        $("#totals_pt_mtr").html(totals["pt_mtr"]);
        $("#totals_ot_mtr").html(totals["ot_mtr"]);
        $("#totals_bal_mtr").html(totals["bal_mtr"]);
        $("#totals_bal_amt").html(totals["bal_amt"]);
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};
