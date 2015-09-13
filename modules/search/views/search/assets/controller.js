var SearchController = function ($location, $http, $scope, $rootScope) {
    var scope = {};

    scope.filter = {
        query: '',
        priceUnit: 'week',
        prices: [],
        location: '',
        longitude: null,
        latitude: null,
        categories: [],
        ages: []
    };

    scope.activeFilter = {
        search: false,
        price: false,
        age: false,
        category: false,
        location: false
    };

    scope.locationByGoogle = false;

    scope.params = $location.search();

    scope.loading = false;

    scope.priceMin = 0;
    scope.priceMax = 499;

    scope._timer = null;
    scope._lastLocation = null;

    _startTime = new Date().getTime();

    scope.updateSlider = function (priceMin, priceMax) {
        scope.filter.priceMin = priceMin;
        scope.filter.priceMax = priceMax;
        var values = {
            'values': [parseInt(scope.filter.priceMin), parseInt(scope.filter.priceMax)],
            'range': true,
            'min': 0,
            'max': 499
        };
        $("#price-slider").slider(values);
        $("#price-slider-mobile").slider(values);
    };

    scope.loadCurrentLocation = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position){
                var geocoder = new google.maps.Geocoder;
                var latlng = {
                    lat: parseFloat(position['coords']['latitude']),
                    lng: parseFloat(position['coords']['longitude'])
                };
                scope.filter.longitude = latlng.lng;
                scope.filter.latitude = latlng.lat;
                if (!$scope.$$phase) $scope.$apply();
                geocoder.geocode({'location': latlng}, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results.length > 0) {
                            var result = results[0];
                            var location = result['formatted_address'];
                            scope.filter.location = location;
                            if (!$scope.$$phase) $scope.$apply();
                            update();
                        }
                    }
                });
            });
        }
    };

    var update = function () {
        // using $http here fucks up something with the url, use $.ajax!
        scope.loading = true;
        $("#search-form").submit();
    };

    scope.filterChange = function () {
        if (new Date().getTime() - _startTime < 500) return false;
        if (scope._timer !== null) {
            clearTimeout(scope._timer)
        }

        // activate filter buttons
        updateActiveFilters();

        scope._timer = setTimeout(function () {
            update();
        }, 700);
    };

    scope.init = function () {
        if (scope.params['price'] == undefined) {
            scope.params['price'] = [0, 499];
        }
        if (scope.params['categories'] == undefined) {
            scope.params['categories'] = [];
        }
        if (scope.params['ages'] == undefined) {
            scope.params['ages'] = [];
        }
        var sliderConf = {
            range: true,
            min: 0,
            max: 499,
            slide: function (val, ui) {
                scope.filter.priceMin = ui.values[0];
                scope.filter.priceMax = ui.values[1];
                if (!$scope.$$phase) $scope.$apply();
            },
            change: function (val, ui) {
                scope.filterChange();
            }
        };
        $("#price-slider").slider(sliderConf);
        $("#price-slider-mobile").slider(sliderConf);
        $('.location-input').on('keyup', function() {
            var oldValue = $('.location-input').val();
            $('.location-input').attr('oldValue', oldValue);
            scope.locationByGoogle = false;
            setTimeout(function() {
                var value = $('.location-input').val()
                if (value === oldValue) {
                    if (scope.locationByGoogle === false) {
                        if (value.length > 0) {
                            scope.activeFilter['location'] = true;
                            scope.filter.location = value;
                            scope.filter.longitude = null;
                            scope.filter.latitude = null;
                            update();
                        } else {
                            scope.activeFilter['location'] = false;
                        }
                    }
                }
            }, 2500);
        })
        var timer = setInterval(function() {
            if ($(window).attr('autocomplete-search-default') !== undefined
                && $(window).attr('autocomplete-search-mobile') !== undefined) {
                var autocomplete1 = $(window).attr('autocomplete-search-default');
                var autocomplete2 = $(window).attr('autocomplete-search-mobile');
                function placeChanged(autocomplete) {
                    var place = autocomplete.getPlace();
                    var location = place['geometry']['location'];
                    scope.activeFilter['location'] = true;
                    scope.filter.location = place.formatted_address;
                    scope.filter.latitude = place.geometry.location.lat();
                    scope.filter.longitude = place.geometry.location.lng();
                    scope.locationByGoogle = true;
                    if (!$scope.$$phase) $scope.$apply();
                    update();
                }
                autocomplete1.addListener('place_changed', function() {
                    placeChanged(autocomplete1);
                });
                autocomplete2.addListener('place_changed', function() {
                    placeChanged(autocomplete2);
                });
                clearInterval(timer);
            }
        }, 500);
    };

    scope.init();

    return scope;
};

angular.module('kidup.search', []);

angular.module('kidup.search').controller('SearchCtrl', SearchController);
