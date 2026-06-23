$(document).ready(function () {

    /* =========================================================
       CUSTOMER SELECT2
    ========================================================= */

    $("#_customer").select2(
        select2_default({
            url: `${link}/${sub_link}/get_select2/_customer_name`,
            placeholder: "SELECT CUSTOMER",
        })
    );

    /* =========================================================
       BRANCH SELECT2
    ========================================================= */

    $("#_branch").select2(
        select2_default({
            url: `${link}/${sub_link}/get_select2/_branch_name`,
            placeholder: "SELECT BRANCH",
        })
    );

    /* =========================================================
       ORDER TYPE UI
    ========================================================= */

    $("#_order_type").on("change", function () {

        let value = $(this).val();

        if (value == "CUSTOM") {

            $(this).css({
                "background": "#eef2ff",
                "border-color": "#4f46e5",
                "color": "#312e81",
                "font-weight": "700"
            });

        } else if (value == "READYMADE") {

            $(this).css({
                "background": "#fff1f2",
                "border-color": "#e11d48",
                "color": "#9f1239",
                "font-weight": "700"
            });

        } else {

            $(this).css({
                "background": "#fff",
                "border-color": "#dbe2ea",
                "color": "#000",
                "font-weight": "600"
            });

        }

    });

    $("#_order_type").trigger("change");

    /* =========================================================
       TOOLTIP
    ========================================================= */

    $('[data-toggle="tooltip"]').tooltip();

    /* =========================================================
       SEARCH BUTTON LOADER
    ========================================================= */

    $("#search_form").on("submit", function () {

        $("#btn_search").html(`
            <i class="fa fa-spinner fa-spin"></i>
            SEARCHING...
        `);

    });

    /* =========================================================
       DATE VALIDATION
    ========================================================= */

    $("#btn_search").on("click", function (e) {

        let from = $("#_entry_date_from").val();
        let to   = $("#_entry_date_to").val();

        if (from != "" && to != "") {

            if (from > to) {

                e.preventDefault();

                toastr.error(
                    "FROM DATE CANNOT BE GREATER THAN TO DATE"
                );

                return false;
            }
        }

    });

    /* =========================================================
       PROFIT ROW COLOR
    ========================================================= */

    $(".profit_amount").each(function () {

        let value = parseFloat($(this).attr("data-profit"));

        if (value < 0) {

            $(this)
                .removeClass("text-success")
                .addClass("text-danger");

        } else {

            $(this)
                .removeClass("text-danger")
                .addClass("text-success");
        }

    });

    /* =========================================================
       CARD HOVER EFFECT
    ========================================================= */

    $(".dashboard-card").hover(

        function () {

            $(this).css({
                "transform": "translateY(-4px)",
                "transition": ".3s ease"
            });

        },

        function () {

            $(this).css({
                "transform": "translateY(0px)"
            });

        }

    );

    /* =========================================================
       COUNTER ANIMATION
    ========================================================= */

    $(".amount").each(function () {

        let $this = $(this);

        let countTo = parseFloat(
            $this.text().replace(/[^0-9.-]+/g, "")
        );

        $({ countNum: 0 }).animate({

            countNum: countTo

        },

        {

            duration: 1200,

            easing: "swing",

            step: function () {

                $this.text(

                    "₹ " +

                    this.countNum
                        .toFixed(2)
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                );

            },

            complete: function () {

                $this.text(

                    "₹ " +

                    countTo
                        .toFixed(2)
                        .replace(/\B(?=(\d{3})+(?!\d))/g, ",")

                );

            }

        });

    });

    /* =========================================================
       TABLE SEARCH
    ========================================================= */

    $("#table_search").on("keyup", function () {

        let value = $(this).val().toLowerCase();

        $(".profit-table tbody tr").filter(function () {

            $(this).toggle(

                $(this)
                    .text()
                    .toLowerCase()
                    .indexOf(value) > -1

            );

        });

    });

    /* =========================================================
       EXPORT BUTTON
    ========================================================= */

    $("#report_excel_export").on("click", function () {

        let btn = $(this);

        btn.html(`
            <i class="fa fa-spinner fa-spin"></i>
        `);

        setTimeout(() => {

            btn.html(`
                <i class="fa fa-file-excel-o"></i>
            `);

        }, 3000);

    });

    /* =========================================================
       STICKY NAVBAR
    ========================================================= */

    $(window).scroll(function () {

        if ($(window).scrollTop() > 100) {

            $(".sticky_top").css({
                "position": "sticky",
                "top": "0",
                "z-index": "999",
                "background": "#fff"
            });

        } else {

            $(".sticky_top").css({
                "position": "relative"
            });

        }

    });

});

/* =========================================================
   RESET FILTERS
========================================================= */

function reset_filters() {

    window.location.href = `${base_url}/${link}/${sub_link}`;

}

/* =========================================================
   PRINT REPORT
========================================================= */

function print_report() {

    window.print();

}

/* =========================================================
   EXPORT PDF
========================================================= */

function export_pdf() {

    toastr.success("PDF EXPORT STARTED");

    window.open(

        `${base_url}/${link}/${sub_link}/pdf?${window.location.search.replace("?", "")}`,

        "_blank"

    );

}

/* =========================================================
   REFRESH PAGE
========================================================= */

function refresh_summary() {

    location.reload();

}