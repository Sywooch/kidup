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
        $("#create-booking-form").submit();
    };

    api.singleLoad = function () {

        window.singleIsLoaded = true;
        $("#request-booking-btn").click(function (event) {
            if (window.userIsGuest) {
                event.preventDefault();
                $('#bookingModal').modal('hide');
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


        $(document).on('pjax:beforeSend', function (xhr, options, settings) {
            var getParams = function (queryString) {
                var query = (queryString || window.location.search).substring(1); // delete ?
                if (!query) {
                    return false;
                }
                query = query.split('?')[1];
                return _.map(query.split('&'), function (params) {
                    var p = params.split('=');
                    return [p[0], decodeURIComponent(p[1])];
                });
            };

            var params = {};
            var baseUrl = 'http://' + window.location.hostname + window.location.pathname;
            _.map(getParams(settings.url), function (param) {
                params[param[0]] = param[1];
            });

            var i = 0;
            var added = _.map(params, function (param, name) {
                var url = '';
                if (i == 0) {
                    url += '?';
                } else {
                    url += '&';
                }
                url += name + '=' + param;
                i++;
                return url;
            });

            settings.url = baseUrl + added.join('');
            console.log(settings.url);
            return settings;
        });

        $("#create-booking-form").on('submit', function (event) {
            $("#booking-widget .overlay").fadeTo(0.3, 0.6);
            $("#booking-widget .overlay").css("visibility", "visible");
        });

        $("#wrapper").css("margin-bottom", "0px"); /// visual misalignment with wrapper on item view page
        $(document).scroll(scrollFunc);
        $(document).ready(scrollFunc);

        $(".mobileBookingRequestButton").click(function () {
            $("#pageInfo").hide();
            $("#bookingWidget").show();
            $(".mobileBookingRequestButton").css("visibility", "hidden");
            $("#mobileCloseBookingRequest").show();
            window.scrollTo(0, 0);
        });

        $("#bookingWidget").hide();
        $("#mobileCloseBookingRequest").hide();

        $("#mobileCloseBookingRequest").click(function () {
            $("#pageInfo").show();
            $("#bookingWidget").hide();
            $(".mobileBookingRequestButton").css("visibility", "visible");
        });
    };


    var scrollFunc = function () {
        if ($(document).width() < 768) return false;
        var docScroll = $(document).scrollTop();
        var navHeight = $('.navbar').height();
        if (typeof $('#footer').offset() === "undefined") {
            var mapHeight = 1000;
        } else {
            var mapHeight = $('#footer').offset().top;
        }
        var widgetHeight = $("#booking-widget").height();
        if (docScroll > navHeight - 40 && docScroll < mapHeight - widgetHeight - 98) {
            $("#booking-widget").css("margin-top", $(document).scrollTop() + 30 + "px");
        } else if (docScroll <= 120) {
            $("#booking-widget").css("margin-top", "40px");
        }
    };

    api.load = function () {
        if (typeof redirect === "undefined") {
            //if (typeof window.widget === "undefined") {
            //    window.widget = widgetFactory();
            //}
            //$("#booking-widget .overlay").css("visibility", "hidden");
            //jQuery(document).pjax("#pjax-create-booking-form a", "#pjax-create-booking-form", {
            //    "push": true,
            //    "replace": false,
            //    "timeout": 2000,
            //    "scrollTo": false
            //});
            //jQuery(document).on('submit', "#pjax-create-booking-form form['#create-booking-form']", function (event) {
            //    console.log('event');
            //    jQuery.pjax.submit(event, '#pjax-create-booking-form', {
            //        "push": true,
            //        "replace": false,
            //        "timeout": 2000,
            //        "scrollTo": false
            //    });
            //});
            //$.pjax.defaults.maxCacheLength = 0;
        }
    };

    api.singleLoad();

    return api;
};

window.widget = widgetFactory();