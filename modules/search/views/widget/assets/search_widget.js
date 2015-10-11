/**
 * Initialize a search widget.
 *
 * @param queryField         the field (jQuery object) the user uses for inserting a search query
 * @param locationField      (optional) the field (jQuery object) the user uses to put in a location
 * @param submitButton       the submit button (jQuery object) of the search field
 * @param testMode           when true, all the objects are logged to the console
 */
function initializeSearchWidget(queryField, locationField, submitButton, testMode) {
    testMode = false;
    // check the test mode
    if (testMode !== undefined && testMode === true) {
        console.log('Search widget test mode is on.');
        console.log('Query field:', queryField);
        console.log('Location field:', locationField);
        console.log('Submit button:', submitButton);
    }

    // check whether a location field is available (this field is optional)
    var hasLocationField = true;
    if (locationField === undefined || locationField === null || locationField.length === 0) {
        hasLocationField = false;
    }

    // check the other input fields (and display a message when required fields are missing)
    if (queryField === undefined || queryField === null || !queryField instanceof Object) {
        console.log('Search widget error: required field "queryField" is not set correctly.');
    }
    if (submitButton === undefined || submitButton === null || !submitButton instanceof Object) {
        console.log('Search widget error: required field "submitButton" is not set correctly.');
    }

    // if a location field was given, reset any default text when clicking on it
    if (hasLocationField) {
        var numLocationFieldFocus = 0;
        // check the focus event
        locationField.on('focus', function() {
            // if this is the first focus, empty the location field
            if (numLocationFieldFocus === 0) {
                locationField.val('');
            }
            numLocationFieldFocus += 1;
        })
    }

    // trigger an event when the user presses the search button
    submitButton.on('click', function (event) {
        // prevent that the form is submitted (in combination with "return false" which can be found slightly under
        // this line)
        event.preventDefault();

        // a list of all the search parameters
        var params = [];

        // the query the user has typed
        var query = queryField.val();

        // fetch the geocoder
        var geocoder = new google.maps.Geocoder;

        // when the user filled in a location then that location should be used
        if (hasLocationField) {
            // find the address the user has filled in
            var address = locationField.val();
            // if there was a focus in the location field and the field is not empty, then try to use the geocoder
            // to fetch the position of the user
            if (numLocationFieldFocus !== 0 && address.length > 0) {
                geocoder.geocode({'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results.length > 0) {
                            // set the address as an input parameter for the search
                            var place = results[0];
                            params.push("search-filter[latitude]=" + place.geometry.location.lat());
                            params.push("search-filter[longitude]=" + place.geometry.location.lng());
                            params.push("search-filter[location]=" + place.formatted_address);
                            // redirect the user to the search page
                            window.location = '/search/' + query + '?' + params.join('&');
                        }
                    }
                });
                // prevent form submitting
                return false;
            }
        }

        // check whether geo location is available
        if (navigator.geolocation) {
            // try to fetch the current position of the user
            navigator.geolocation.getCurrentPosition(function (position) {
                // find the coordinates of the location
                var latlng = {
                    lat: parseFloat(position['coords']['latitude']),
                    lng: parseFloat(position['coords']['longitude'])
                };
                // set the latitude and longitude parameters of the search
                params.push("search-filter[latitude]=" + latlng.lat);
                params.push("search-filter[longitude]=" + latlng.lng);

                // now try to find the name of the found address
                geocoder.geocode({'location': latlng}, function (results, status) {
                    // some name was found
                    if (status === google.maps.GeocoderStatus.OK) {
                        // and if there were results
                        if (results.length > 0) {
                            // set the address as an input parameter for the search
                            var result = results[0];
                            var location = result['formatted_address'];
                            params.push("search-filter[location]=" + location);
                        }
                    }
                    // redirect the user to the search page
                    window.location = '/search/' + query + '?' + params.join('&');
                });
            });
            // prevent form submitting
            return false;
        } else {
            // it is not possible to fetch the geo location of the user
            // redirect the user to the search page
            window.location = '/search/' + query + '?' + params.join('&');
            // prevent form submitting
            return false;
        }
    });
}