$('#menu-search-submit-button').on('click', function (event) {
    // prevent the default submit action, also note the many "return false"
    // statements, which block the action and will redirect the user
    event.preventDefault();

    var vals = [];
    var val = $("#search-filter-query").val();
    var location = $("#search-filter-location").val();
    if(val == ''){
        val = ' ';
    }
    var location = $("#search-filter-location").val();
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
                        window.location = "/search" + "/" + val + "?" + vals.join("&");
                    }
                }
            });
            window.location = "/search" + "/" + val + "?" + vals.join("&");
            return false;
        }, function () {
            // in the case that it was declined
            window.location = "/search" + "/" + val + "?" + vals.join("&");
            return false;
        });
        // the submit while executing an asynchronous action needs to be blocked
        return false;
    } else if (window.emptyLocation) {
        // some autocompleted field has been filled in
        // note here that we don't know the location if it was not choosen by Google Autocomplete!
        var autocomplete = $(window).attr('autocomplete-home-search');
        var place = autocomplete.getPlace();
        if (typeof place !== 'undefined' && place.length > 0) {
            vals.push("search-filter[latitude]=" + place.geometry.location.lat());
            vals.push("search-filter[longitude]=" + place.geometry.location.lng());
            vals.push("search-filter[location]=" + place.formatted_address);
        }
        window.location = "/search" + "/" + val + "?" + vals.join("&");
        return false;
    } else {
        window.location = "/search" + "/" + val + "?" + vals.join("&");
        return false;
    }
    //catcher
    window.location = "/search" + "/" + val + "?" + vals.join("&");
    return false;

    /*event.preventDefault();

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
                        window.location = "/search" + "/" + val + "?" + vals.join("&");
                    }
                }
            });
            window.location = "/search" + "/" + val + "?" + vals.join("&");
            return false;
        }, function() {
            // in the case that it was declined
            window.location = "/search" + "/" + val + "?" + vals.join("&");
            return false;
        });
        window.location = "/search" + "/" + val + "?" + vals.join("&");
        return false;
    }else{
        window.location = "/search" + "/" + val + "?" + vals.join("&");
    }
    window.location = "/search" + "/" + val + "?" + vals.join("&");*/
});

// on select of filled out location
$("#search-filter-location").on('focus', function () {
    var location = $("#search-filter-location").val();
    if (window.emptyLocation == location) {
        $("#search-filter-location").val('');
    }
});
/**
 * Yii auth choice widget.
 *
 * This is the JavaScript widget used by the yii\authclient\widgets\AuthChoice widget.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
jQuery(function($) {
    $.fn.authchoice = function(options) {
        options = $.extend({
            popup: {
                resizable: 'yes',
                scrollbars: 'no',
                toolbar: 'no',
                menubar: 'no',
                location: 'no',
                directories: 'no',
                status: 'yes',
                width: 450,
                height: 380
            }
        }, options);

        return this.each(function() {
            var $container = $(this);

            $container.find('a').on('click', function(e) {
                e.preventDefault();

                var authChoicePopup = $container.data('authChoicePopup');

                if (authChoicePopup) {
                    authChoicePopup.close();
                }

                var url = this.href;
                var popupOptions = $.extend({}, options.popup); // clone

                var localPopupWidth = this.getAttribute('data-popup-width');
                if (localPopupWidth) {
                    popupOptions.width = localPopupWidth;
                }
                var localPopupHeight = this.getAttribute('data-popup-height');
                if (localPopupWidth) {
                    popupOptions.height = localPopupHeight;
                }

                popupOptions.left = (window.screen.width - popupOptions.width) / 2;
                popupOptions.top = (window.screen.height - popupOptions.height) / 2;

                var popupFeatureParts = [];
                for (var propName in popupOptions) {
                    if (popupOptions.hasOwnProperty(propName)) {
                        popupFeatureParts.push(propName + '=' + popupOptions[propName]);
                    }
                }
                var popupFeature = popupFeatureParts.join(',');

                authChoicePopup = window.open(url, 'yii_auth_choice', popupFeature);
                authChoicePopup.focus();

                $container.data('authChoicePopup', authChoicePopup);
            });
        });
    };
});
