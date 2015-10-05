$('#menu-search-form').on('submit', function (event) {
    event.preventDefault();

    var vals = [];
    var val = $("#menu-search-autocomplete").val();
    if(val == ''){
        val = ' ';
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geocoder = new google.maps.Geocoder;
            var latlng = {
                lat: parseFloat(position['coords']['latitude']),
                lng: parseFloat(position['coords']['longitude'])
            };
            vals.push("search-filter[latitude]="+latlng.lat);
            vals.push("search-filter[longitude]="+latlng.lng);
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
        window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
        return false;
    }else{
        window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
    }
    window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
});