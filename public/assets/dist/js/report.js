$(document).ready(function () {
  $(window).scrollTop(0);
});
let win = document.querySelector("#report_wrapper");
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
  let start = PER_PAGE * page;
  return items.slice(start, start + PER_PAGE);
};
const sorting_data = (field) => {
  $("#report_wrapper").scrollTop(0);
  let new_raw = raw.sort(dynamicSort(field));
  $("#report_wrapper").html("");
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
