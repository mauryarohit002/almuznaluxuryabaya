$(document).ready(function () {
  $(window).scrollTop(0);
  console.log({link, sub_link})
});
let win = document.querySelector(".list_wrapper");
let page = 1;
let total_rows = parseValue($("#total_rows").html());
let total_pages = Math.ceil(parseFloat(total_rows) / parseFloat(PER_PAGE));
win &&
  win.addEventListener("scroll", function () {
    if (win.scrollTop + win.clientHeight >= win.scrollHeight - 20) {
      if (page <= total_pages) {
        infinite_scroll(page);
        page = total_pages + 1;
      }
    }
  });
const get_record = (call = false) => {
  event.preventDefault();
  if (!call) return false;
  set_url();
};
const sorting_by = (order_by, sort_by) => {
  event.preventDefault();
  $("#tbody_wrapper").html();
  set_url({ order_by, sort_by });
};
const get_filter_value = () => {
  let params = "";
  let filters = {};
  const order_by = get_url_string("order_by");
  const sort_by = get_url_string("sort_by");
  filters_arr.forEach((value) => {
    if ($(`#${value}`).val() == "" || $(`#${value}`).val() == null) {
    } else {
      filters[value] = $(`#${value}`).val();
      params +=
        filters[value].length > 0
          ? params.length > 0
            ? `&${value}=${filters[value]}`
            : `${value}=${filters[value]}`
          : ``;
    }
  });
  return { filters, params, order_by, sort_by };
};
const set_url = (args) => {
  const { order_by, sort_by, params } = get_filter_value();
  let url = `${params.length > 0 ? `?${params}` : ``}`;
  if (args && args["order_by"] && args["sort_by"]) {
    url =
      url == ""
        ? `?order_by=${args["order_by"]}&sort_by=${args["sort_by"]}`
        : `${url}&order_by=${args["order_by"]}&sort_by=${args["sort_by"]}`;
  } else {
    if (order_by && sort_by) {
      url =
        url == ""
          ? `?order_by=${order_by}&sort_by=${sort_by}`
          : `${url}&order_by=${order_by}&sort_by=${sort_by}`;
    }
  }
  window.history.pushState({}, "", `${base_url}/${link}/${sub_link}${url}`);
  location.reload();
};
const reset_filter = () => {
  filters_arr.forEach((value) => {
    if ($(`#${value}`).length > 0) {
      if ($(`#${value}`)[0]["localName"] == "input") {
        $(`#${value}`).val("");
      }
      if ($(`#${value}`)[0]["localName"] == "select") {
        $(`#${value}`).val(null).trigger("change");
      }
    }
  });
  get_record(true);
};
const get_sort_by = () => {
  const { order_by, sort_by } = get_filter_value();
  if (order_by && sort_by) {
    $(`.fa-fw`).removeClass("text-success").addClass("text-danger");
    $(`#${order_by}_${sort_by}`)
      .removeClass("text-danger")
      .addClass("text-success");
  }
};
const infinite_scroll = (pg) => {
  event.preventDefault();
  const { order_by, sort_by, filters } = get_filter_value();
  const path = `${link}/${sub_link}/handler/`;
  let form_data = {
    ...filters,
    page: pg,
    order_by: order_by ? order_by : "",
    sort_by: sort_by ? sort_by : "",
    func: "get_record",
    sub_func: "get_record",
  };
  ajaxCall(
    "POST",
    path,
    form_data,
    "JSON",
    (resp) => {
      if (handle_response(resp)) {
        const { data } = resp;
        if (data["data"] && data["data"].length != 0) {
          render(data["data"]);
        }
        page = pg + 1;
      }
    },
    (errmsg) => {}
  );
};
