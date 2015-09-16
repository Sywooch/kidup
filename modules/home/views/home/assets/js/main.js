$(document).ready(function () {

    // how it works section
    $('.step-item').on("mouseover", function () {
        $('.step-item').removeClass('active');
        $(this).fadeIn().addClass('active');
        var selected = $(this).attr("id");
        var lastChar = selected[selected.length - 1];
        if (lastChar == 1) {
            $("div[class*='step-d']").removeClass('active');
            $('.step-d-1').addClass('active');
        } else if (lastChar == 2) {
            $("div[class*='step-d']").removeClass('active');
            $('.step-d-2').addClass('active');
        }
        else if (lastChar == 3) {
            $("div[class*='step-d']").removeClass('active');
            $('.step-d-3').addClass('active');
        }
        else if (lastChar == 4) {
            $("div[class*='step-d']").removeClass('active');
            $('.step-d-4').addClass('active');
        }
    });

    // slider
    $("#owl-kidup").owlCarousel({
        navigation: true,
        navigationText: [
            "<i class='fa fa-chevron-left'></i>",
            "<i class='fa fa-chevron-right'></i>"
        ],
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        loop: true,
        items: 1,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: false
    });

    // the changing words in top
    var typist = document.querySelector("#typist-element");
    new Typist(typist, {
        letterInterval: 60,
        textInterval: 3000,
        onSlide: function (text, options) {
            options.typist.getAttribute("data-typist-suffix");
            return false;
        }
    });

    window.autoSelectedCategory = false;

    // autoselect
    $('#main-search').on('submit', function (event) {
        event.preventDefault();

        var vals = [];
        var val = $("#search-home-query").val();
        var location = $("#search-home-location").val();
        if (val == '') {
            val = window.emptySearch;
        }
        if (window.emptyLocation == location && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var geocoder = new google.maps.Geocoder;
                var latlng = {
                    lat: parseFloat(position['coords']['latitude']),
                    lng: parseFloat(position['coords']['longitude'])
                };
                vals.push("search-filter[latitude]=" + latlng.lat);
                vals.push("search-filter[longitude]=" + latlng.lng);
                geocoder.geocode({'location': latlng}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results.length > 0) {
                            var result = results[0];
                            var location = result['formatted_address'];
                            vals.push("search-filter[location]=" + location);
                            window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
                        }
                    }
                });
                window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
            });
        } else if (window.emptyLocation) {
            var autocomplete = $(window).attr('autocomplete-home-search');
            var place = autocomplete.getPlace();
            if(typeof place !== 'undefined'){
                vals.push("search-filter[latitude]=" + place.geometry.location.lat());
                vals.push("search-filter[longitude]=" + place.geometry.location.lng());
                vals.push("search-filter[location]=" + place.formatted_address);
            }
            window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
        }else{
            window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
        }
        //catcher
        window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
    });
});