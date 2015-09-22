window.initAngular = function () {
    var SearchController = function ($location, $http, $scope, $rootScope) {
        var scope = {};

        scope.filter = {
            query: '',
            priceUnit: 'week',
            prices: [],
            latitude: null,
            categories: [],
            ages: []
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
            $("#search-filter-pricemin").val(scope.filter.priceMin);
            $("#search-filter-pricemax").val(scope.filter.priceMax);
            $("#price-slider-mobile").slider(values);
        };

        scope.loadCurrentLocation = function () {
            //if (navigator.geolocation) {
            //    navigator.geolocation.getCurrentPosition(function(position){
            //        var geocoder = new google.maps.Geocoder;
            //        var latlng = {
            //            lat: parseFloat(position['coords']['latitude']),
            //            lng: parseFloat(position['coords']['longitude'])
            //        };
            //        scope.filter.longitude = latlng.lng;
            //        scope.filter.latitude = latlng.lat;
            //        if (!$scope.$$phase) $scope.$apply();
            //        geocoder.geocode({'location': latlng}, function(results, status) {
            //            if (status === google.maps.GeocoderStatus.OK) {
            //                if (results.length > 0) {
            //                    var result = results[0];
            //                    var location = result['formatted_address'];
            //                    scope.filter.location = location;
            //                    if (!$scope.$$phase) $scope.$apply();
            //                    update();
            //                }
            //            }
            //        });
            //    });
            //}
        };

        var update = function () {
            // using $http here fucks up something with the url, use $.ajax!
            if (!$("body").hasClass("modal-open")) {
                scope.loading = true;
                $("#search-form").submit();
            }
        };

        scope.filterChange = function () {
            if (new Date().getTime() - _startTime < 500) return false;
            if (scope._timer !== null) {
                clearTimeout(scope._timer)
            }

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
                    $("#search-filter-pricemin").val(scope.filter.priceMin);
                    $("#search-filter-pricemax").val(scope.filter.priceMax);
                    if (!$scope.$$phase) $scope.$apply();
                },
                change: function (val, ui) {
                    scope.filterChange();
                }
            };
            $("#price-slider").slider(sliderConf);
            $("#price-slider-mobile").slider(sliderConf);

            scope.updateSlider($("#search-filter-pricemin").val(), $("#search-filter-pricemax").val());
            //$('.location-input').on('keyup', function() {
            //    var oldValue = $('.location-input').val();
            //    $('.location-input').attr('oldValue', oldValue);
            //    scope.locationByGoogle = false;
            //    setTimeout(function() {
            //        var value = $('.location-input').val()
            //        if (value === oldValue) {
            //            if (scope.locationByGoogle === false) {
            //                if (value.length > 0) {
            //                    scope.activeFilter['location'] = true;
            //                    scope.filter.location = value;
            //                    scope.filter.longitude = null;
            //                    scope.filter.latitude = null;
            //                    update();
            //                    console.log(9);
            //                } else {
            //                    scope.activeFilter['location'] = false;
            //                }
            //            }
            //        }
            //    }, 2500);
            //})

            // what does this do?

            //var timer = setInterval(function() {
            //    if ($(window).attr('autocomplete-search-default') !== undefined
            //        && $(window).attr('autocomplete-search-mobile') !== undefined) {
            //        var autocomplete1 = $(window).attr('autocomplete-search-default');
            //        var autocomplete2 = $(window).attr('autocomplete-search-mobile');
            //        function placeChanged(autocomplete) {
            //            var place = autocomplete.getPlace();
            //            var location = place['geometry']['location'];
            //            scope.activeFilter['location'] = true;
            //            scope.filter.location = place.formatted_address;
            //            scope.filter.latitude = place.geometry.location.lat();
            //            scope.filter.longitude = place.geometry.location.lng();
            //            scope.locationByGoogle = true;
            //            if (!$scope.$$phase) $scope.$apply();
            //            update();
            //            console.log(10);
            //        }
            //        autocomplete1.addListener('place_changed', function() {
            //            placeChanged(autocomplete1);
            //        });
            //        autocomplete2.addListener('place_changed', function() {
            //            placeChanged(autocomplete2);
            //        });
            //        clearInterval(timer);
            //    }
            //}, 500);

            $(document).on('pjax:beforeSend', function (xhr, options, settings) {
                $(".overlay").fadeTo(0.3, 0.8);
                $(".overlay").css("visibility", "visible");
                if(!$scope.$digest) $scope.$apply();
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
                return settings;
            });

            // only auto resubmit if modal is not open
            if (!$("body").hasClass("modal-open")) {
                // resubmit the form on any type of change
                $("#search-form").change(function () {
                    $("#search-form").submit();
                });
            }
        };

        scope.init();

        return scope;
    };

    angular.module('kidup.search', []);

    angular.module('kidup.search').controller('SearchCtrl', SearchController);

    angular.bootstrap($("#search"), ['kidup.search']);
};
window.initAngular();
$(document).on('pjax:success', function () {
    $(".overlay").fadeTo(0.3, 0);
    $(".overlay").css("visibility", "none");
    window.initAngular();
});
window.submitFromModal = function(){
    setTimeout(function(){
        $("#search-form").submit();
    },50);
};