$(document).on("focus", ".select2.select2-container", function (e) {
  var isOriginalEvent = e.originalEvent; // don't re-open on closing focus event
  var isSingleSelect = $(this).find(".select2-selection--single").length > 0; // multi-select will pass focus to input

  if (isOriginalEvent && isSingleSelect) {
    $(this).siblings("select:enabled").select2("open");
  }
});
const select2_default = (options) => {
  let url = options.url ? `${base_url}/${options.url}` : "";
  url = options.full_url ? options.full_url : url;
  let placeholder = options.placeholder ? options.placeholder : "";
  let width = options.width ? options.width : "100%";
  let maximumSelectionLength = options.maximumSelectionLength
    ? options.maximumSelectionLength
    : 1;
  let maximumInputLength = options.maximumInputLength
    ? options.maximumInputLength
    : 30;
  let minimumInputLength = options.minimumInputLength
    ? options.minimumInputLength
    : 0;
  let minimumResultsForSearch = options.minimumResultsForSearch
    ? options.minimumResultsForSearch
    : 10;
  let multiple = options.multiple ? options.multiple : false;
  let selectOnClose = options.selectOnClose ? options.selectOnClose : false;
  let closeOnSelect = options.closeOnSelect ? options.closeOnSelect : true;
  let allowClear = options.allowClear ? options.allowClear : true;
  let param = options.param ? options.param : "";
  let param1 = options.param1 ? options.param1 : 0;
  let param2 = options.param2 ? options.param2 : 0;
  let param3 = options.param3 ? options.param3 : 0;
  let param4 = options.param4 ? options.param4 : 0;
  let barcode = options.barcode ? options.barcode : "";
  let limit = options.limit ? options.limit : 10;
  let delay = options.delay ? options.delay : 250;
  let dataType = "json";
  return {
    multiple,
    selectOnClose,
    closeOnSelect,
    allowClear,
    // maximumSelectionLength,
    maximumInputLength,
    minimumInputLength,
    // minimumResultsForSearch,
    placeholder,
    width,
    ajax: {
      url,
      dataType,
      delay,
      data: (params) => {
        return {
          name: params.term || "", // search term
          page: params.page || 1,
          limit,
          param,
          param1,
          param2,
          param3,
          param4,
        };
      },
      processResults: (data, params) => {
        if (barcode.length != 0) {
          const { term } = params;
          if (data && data.length != 0) {
            if (term.length == 12 || term.length == 27) {
              const matched = data.find((d) => {
                if (d.text.includes(term)) {
                  return d;
                }
              });
              if (matched) {
                $(`#${barcode}`)
                  .append(
                    $("<option />").attr("value", matched.id).html(matched.text)
                  )
                  .val(matched.id)
                  .trigger("change")
                  .select2("close");
              }
            }
          }
        }
        return { results: data, pagination: { more: data.length >= limit } };
      },
      cache: true,
    },
  };
};
