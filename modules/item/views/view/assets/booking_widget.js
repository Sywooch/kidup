var widgetFactory = function () {
    var api = {
        dateFrom: {},
        dateTo: {}
    };

    api.dateFrom.beforeShowDay = function (date) {
        var tomorrow = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
        if (date < tomorrow) {
            return [false];
        }
        for (var i = 0; i < window.datesRents.length; i++) {
            var from = new Date(window.datesRents[i][0] * 1000);
            var to = new Date(window.datesRents[i][1] * 1000);
            if (date >= from && date <= to) {
                return [false]
            }
        }
        return [true];
    };

    api.dateFrom.onSelect = function (date) {
        window.startDate = date;
        $("#create-booking-datefrom").datepicker("hide");
        setTimeout(function () {
            $("#create-booking-dateto").datepicker("show");
        }, 100);
    };

    api.dateTo.beforeShowDay = function (date) {
        var startdate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
        var val = $('#create-booking-datefrom').val().split('-');
        var pickedDate = new Date(val[2], val[1] - 1, val[0]);
        if (pickedDate > startdate) {
            startdate = pickedDate;
        }
        if (date <= startdate) {
            return [false];
        }
        for (var i = 0; i < window.datesRents.length; i++) {
            var from = new Date(window.datesRents[i][0] * 1000);
            var to = new Date(window.datesRents[i][1] * 1000);
            if (date >= from && date <= to) {
                return [false]
            }
        }
        return [true];
    };

    // do a submit, which will trigger the pjax
    api.dateTo.onSelect = function (date) {
        $('form[data-pjax]').submit();
    };

    $("#request-booking-btn").click(function (event) {
        if (window.userIsGuest) {
            event.preventDefault();
            $('#loginModal').modal('show');
        }
        var val1 = $('#create-booking-datefrom').val();
        var val2 = $('#create-booking-datefrom').val();
        if (val1 == "" || val2 == "") {
            event.preventDefault();
            $("#create-booking-datefrom").datepicker("hide");
            $("#create-booking-dateto").datepicker("hide");
            $("#create-booking-datefrom").datepicker("show");
        }
    });

    $("form[data-pjax]").on('submit', function (event) {

        $("#booking-widget .overlay").fadeTo(0.3, 0.6);
        $("#booking-widget .overlay").css("visibility", "visible");
    });

    var scrollFunc = function () {
        if($(document).width() < 990) return false;
        var docScroll = $(document).scrollTop();
        var navHeight = $('.navbar').height();
        if (typeof $('.footer').offset() === "undefined") {
            var mapHeight = 1000;
        } else {
            var mapHeight = $('.footer').offset().top;
        }
        var widgetHeight = $("#booking-widget").height();
        if (docScroll > navHeight - 40 && docScroll < mapHeight - widgetHeight - 98) {
            $("#booking-widget").css("margin-top", $(document).scrollTop() + 10 + "px");
        } else if (docScroll <= 120) {
            $("#booking-widget").css("margin-top", "40px");
        }
    };

    $("#wrapper").css("margin-bottom", "0px"); /// visual misalignment with wrapper on item view page
    $(document).scroll(scrollFunc);
    $(document).ready(scrollFunc);

    api.load = function () {
        if (typeof redirect === "undefined") {
            var widget = widgetFactory();
            $("#booking-widget .overlay").css("visibility", "hidden");

            jQuery(document).pjax("#pjax-create-booking-form a", "#pjax-create-booking-form", {
                "push": true,
                "replace": true,
                "timeout": 1000,
                "scrollTo": false
            });
            jQuery(document).on('submit', "#pjax-create-booking-form form[data-pjax]", function (event) {
                jQuery.pjax.submit(event, '#pjax-create-booking-form', {
                    "push": true,
                    "replace": true,
                    "timeout": 1000,
                    "scrollTo": false
                });
            });
        }
    };

    return api;
};

var widget = widgetFactory();