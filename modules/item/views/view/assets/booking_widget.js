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
        if (date < startdate) {
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

    $("#request-booking-btn").click(function(e){
        var dateFrom = $('#create-booking-datefrom').val();
        var dateTo = $('#create-booking-dateto').val();
        //if()
        e.preventDefault();
        console.log(e);
    });

    return api;
};

var widget = widgetFactory();
