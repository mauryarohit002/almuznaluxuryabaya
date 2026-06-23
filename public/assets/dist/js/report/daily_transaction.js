$(document).ready(function () {

    $("#_customer").select2(
        select2_default({
            url: `${link}/${sub_link}/get_select2/_customer_name`,
            placeholder: "select",
        })
    );

    $("#_branch").select2(
        select2_default({
            url: `${link}/${sub_link}/get_select2/_branch_name`,
            placeholder: "select",
        })
    );

    $("#_payment_mode_name").select2(
        select2_default({
            url: `${link}/${sub_link}/get_select2/_payment_mode_name`,
            placeholder: "select",
        })
    );

});
const render = (data, page) => {
  let sr_no = PER_PAGE * page + 1;
  let content = data.map((data, index) => {
    const { 
      module_name,
      entry_no,
      entry_date,
      customer_name,
      payment_mode_name,
      order_amt,
      advance_amt,
      receipt_amt,
      closing_amt 
    } = data;
    return `<tr>
                  <td width="3%">${sr_no + index}</td>
                  <td width="5%">${module_name}</td>
                  <td width="5%">${entry_no}</td>
                  <td width="5%">${entry_date}</td>
                  <td width="10%">${customer_name}</td> 
                  <td width="10%">${payment_mode_name}</td> 
                  <td width="5%">${advance_amt}</td> 
                
                  
              </tr>`;
  });
  $("#table_tbody").append(content);
};
const filters_arr = [
  "_module_name",
  "_payment_mode_name",
  "_entry_date_from",
  "_entry_date_to",
  "_payment_mode_amt_from",
  "_payment_mode_amt_to",
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
        sorting_data("-entry_date");
        $("#totals_payment_mode_amt").html(totals["payment_mode_amt"]);
        $("#filter_count").html(
          params.length > 0 ? window.location.search.split("&").length : ""
        );
      }
    },
    (errmsg) => {}
  );
};



/* =========================================================
   DAILY TRANSACTION STATUS TOGGLE
========================================================= */

$(document).off('change', '#daily_transaction_status');

$(document).on('change', '#daily_transaction_status', function (e) {

    e.preventDefault();

    let checkbox = $(this);

    if(checkbox.prop('disabled')){
        return false;
    }

    let status = checkbox.is(':checked') ? 1 : 0;

    if(status !== 1){

        checkbox.prop('checked', false);

        return false;
    }

    let entry_date_from = $('#_entry_date_from').val();

    let entry_date_to = $('#_entry_date_to').val();

    let customer_id = $('#_customer').val();

    let branch_id = $('#_branch').val();



    /* =========================
       VALIDATIONS
    ========================= */

    if(entry_date_from == '' || entry_date_to == ''){

        alert('SELECT ENTRY DATE');

        checkbox.prop('checked', false);

        return false;
    }

    // if(customer_id == '' || customer_id == null){

    //     alert('SELECT CUSTOMER');

    //     checkbox.prop('checked', false);

    //     return false;
    // }

    if(branch_id == '' || branch_id == null){

        alert('SELECT BRANCH');

        checkbox.prop('checked', false);

        return false;
    }



    /* =========================
       CONFIRMATION
    ========================= */

    if(!confirm('Once marked as RECEIVED, it cannot be changed. Continue?')){

        checkbox.prop('checked', false);

        return false;
    }



    /* =========================
       AJAX
    ========================= */

    $.ajax({

        url : `${base_url}/${link}/${sub_link}/update_daily_transaction_status`,

        type : 'POST',

        dataType : 'JSON',

        data : {
            entry_date_from,
            entry_date_to,
            customer_id,
            branch_id,
            status
        },

        beforeSend:function(){

            checkbox.prop('disabled', true);

            $('.premium-toggle-wrapper').addClass('opacity-50');

            $('.premium-toggle-badge')
                .text('UPDATING...')
                .css({
                    background : 'rgba(255,193,7,.12)',
                    color : '#ff9800'
                });
        },

        success:function(resp){

            console.log(resp);

            if(resp.status){

                alert(resp.msg);

                checkbox
                    .prop('checked', true)
                    .prop('disabled', true);

                $('#daily_transaction_status_text')
                    .text('Received')
                    .css('color', '#00a63e');

                $('.premium-toggle-badge')
                    .text('LOCKED')
                    .css({
                        background : 'rgba(0,200,83,.12)',
                        color : '#00a63e'
                    });

                $('.premium-toggle-wrapper')
                    .removeClass('opacity-50')
                    .addClass('border-success');

            }else{

                alert(resp.msg);

                checkbox
                    .prop('checked', false)
                    .prop('disabled', false);

                $('#daily_transaction_status_text')
                    .text('Pending')
                    .css('color', '#dc3545');

                $('.premium-toggle-badge')
                    .text('ACTION REQUIRED')
                    .css({
                        background : 'rgba(220,53,69,.12)',
                        color : '#dc3545'
                    });

                $('.premium-toggle-wrapper')
                    .removeClass('opacity-50');
            }
        },

        error:function(xhr){

            console.log(xhr.responseText);

            alert('SOMETHING WENT WRONG');

            checkbox
                .prop('checked', false)
                .prop('disabled', false);

            $('#daily_transaction_status_text')
                .text('Pending')
                .css('color', '#dc3545');

            $('.premium-toggle-badge')
                .text('ACTION REQUIRED')
                .css({
                    background : 'rgba(220,53,69,.12)',
                    color : '#dc3545'
                });

            $('.premium-toggle-wrapper')
                .removeClass('opacity-50');
        }

    });

});