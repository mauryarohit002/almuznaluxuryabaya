$(document).ready(function () {
  $(window).scrollTop(0);
});
let win = document.querySelector(".list_wrapper");
let page = 0;
win &&
  win.addEventListener("scroll", function () {
    if (win.scrollTop + win.clientHeight >= win.scrollHeight - 20) {
      page++;
      let data = paginate(raw, page);
      if (data && data.length != 0) {
        render(data, page);
      }
    }
  });
const paginate = (items, page) => {
  let start = parseInt(PER_PAGE) * parseInt(page);
  let end = PER_PAGE * (page + 1);
  return items.slice(start, end);
};
const sorting_data = (field) => {
  $("#table_tbody").scrollTop(0);
  let new_raw = raw.sort(dynamicSort(field));
  $("#table_tbody").html("");
  page = 0;
  let data = paginate(new_raw, page);
  if (data && data.length != 0) {
    render(data, page);
  }
  $(`.fa-fw`).removeClass("text-success").addClass("text-danger");
  $(`#${field}`).removeClass("text-danger").addClass("text-success");
};
const dynamicSort = (property) => {
  let sortOrder = 1;
  if (property[0] === "-") {
    sortOrder = -1;
    property = property.substr(1);
  }
  return function (a, b) {
    let result =
      a[property] < b[property] ? -1 : a[property] > b[property] ? 1 : 0;
    return result * sortOrder;
  };
};
const get_filter_value = () => {
  let params = "";
  let filters = {};
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
  return { filters, params };
};
const reset_filter = () => {
  filters_arr.forEach((value) => {
    if ($(`#${value}`)[0]["localName"] == "input") {
      $(`#${value}`).val("");
    }
    if ($(`#${value}`)[0]["localName"] == "select") {
      $(`#${value}`).val(null).trigger("change");
    }
  });
  get_record(true);
};
