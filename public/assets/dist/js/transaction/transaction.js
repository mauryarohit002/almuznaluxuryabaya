let win = document.querySelector("#div_wrapper");
let page = 0;
win &&
  win.addEventListener("scroll", function () {
    if (win.scrollTop + win.clientHeight >= win.scrollHeight - 20) {
      page++;
      let data = paginate(trans_data, page);
      if (data && data.length != 0) {
        data.forEach((value) => add_wrapper_data(value, true));
      }
    }
  });
const paginate = (items, page) => {
  let start = parseInt(PER_PAGE) * parseInt(page);
  let end = PER_PAGE * (page + 1);
  return items.slice(start, end);
};
