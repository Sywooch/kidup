$(document).ready(function () {
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
        // prevent the default submit action, also note the many "return false"
        // statements, which block the action and will redirect the user
        event.preventDefault();

        var vals = [];
        var val = $("#search-home-query").val();
        var location = $("#search-home-location").val();
        if (val == '') {
            val = window.emptySearch;
        }
        if ((window.emptyLocation == location || location.length == 0) && navigator.geolocation) {
            // ask permission to get the location of the user
            navigator.geolocation.getCurrentPosition(function (position) {
                // location accepted
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
                return false;
            }, function() {
                // in the case that it was declined
                window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
                return false;
            });
            // the submit while executing an asynchronous action needs to be blocked
            return false;
        } else if (window.emptyLocation) {
            // some autocompleted field has been filled in
            // note here that we don't know the location if it was not choosen by Google Autocomplete!
            var autocomplete = $(window).attr('autocomplete-home-search');
            var place = autocomplete.getPlace();
            if(typeof place !== 'undefined' && place.length > 0){
                vals.push("search-filter[latitude]=" + place.geometry.location.lat());
                vals.push("search-filter[longitude]=" + place.geometry.location.lng());
                vals.push("search-filter[location]=" + place.formatted_address);
            }
            window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
            return false;
        }else{
            window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
            return false;
        }
        //catcher
        window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
        return false;
    });

    // on select of filled out location
    $("#search-home-location").on('focus', function(){
        var location = $("#search-home-location").val();
        if(window.emptyLocation == location){
            $("#search-home-location").val('');
        }
    });
});