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

    scope.selectCategory = function (id) {
        $.map(scope.filter.categories, function (el, i) {
            if (el.id == id) {
                scope.activeFilter['category'] = true;
                return scope.filter.categories[i].value = (scope.filter.categories[i].value == 1) ? 0 : 1;
            }
        });
        $.map(scope.filter.ages, function (el, i) {
            if (el.id == id) {
                scope.activeFilter['age'] = true;
                return scope.filter.ages[i].value = (scope.filter.ages[i].value == 1) ? 0 : 1;
            }
        });
        scope.filterChange();
    };

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
    }

    var getActiveCategories = function (type) {
        var res = [];
        if (typeof type === "undefined" || type == "category") {
            $.map(scope.filter.categories, function (cat) {
                if (cat.value == 1) res.push(cat.id);
            });
        }

        if (typeof type === "undefined" || type == "age") {
            $.map(scope.filter.ages, function (cat) {
                if (cat.value == 1) res.push(cat.id);
            });
        }

        return res;
    };

    var update = function () {
        // using $http here fucks up something with the url, use $.ajax!
        scope.loading = true;
        if (!$scope.$$phase) $scope.$apply();
        $.ajax({
            'method': 'GET',
            'url': '/search-results?q=' + getUrl() + '&p=0'
        }).done(function (data) {
            scope.loading = false;
            $('.searchResults').replaceWith(data);
            if (!$scope.$$phase) $scope.$apply();
        });
        scope.setUrl();
        updateActiveFilters();
    };

    var updateActiveFilters = function () {
        scope.activeFilter = {
            search: false,
            price: false,
            age: false,
            category: false,
            location: false
        };
        if (scope.filter.query.length > 0)      scope.activeFilter.search = true;
        if (scope.filter.location.length > 0)   scope.activeFilter.location = true;
        if (scope.filter.priceMin != 0 || scope.filter.priceMax != 499 || scope.filter.priceUnit != 'week') {
            scope.activeFilter.price = true;
        }
        if (getActiveCategories('category').length > 0) {
            scope.activeFilter.category = true;
        }
        if (getActiveCategories('age').length > 0) {
            scope.activeFilter.age = true;
        }

        // Don't display the label "active filters" when there are no filters active
        var numActiveFilters = 0;
        angular.forEach(scope.activeFilter, function(enabled, filter) {
            if (enabled) numActiveFilters++;
        });
        if (numActiveFilters > 0) {
            $('.filters-label').show();
        } else {
            $('.filters-label').hide();
        }

        if(!$scope.$$phase) {
            $scope.$apply();
        }
    };

    scope.setPriceUnit = function(priceUnit) {
        if (priceUnit !== undefined && priceUnit.length > 0) {
            scope.filter.priceUnit = priceUnit;
        }
    }

    scope.activeFilterRemove = function (filter) {
        if (filter == 'search') scope.filter.query = '';
        if (filter == 'price') {
            scope.filter.priceMin = 0;
            scope.filter.priceMax = 499;
            scope.filter.priceUnit = 'week';
            $("#price-slider").slider({"values": [0, 499]});
            $("#price-slider-mobile").slider({"values": [0, 499]});
        }
        if (filter == 'location') scope.filter.location = '';
        if (filter == 'category') {
            $.map(scope.filter.categories, function (x) {
                x.value = 0;
            })
        }
        if (filter == 'age') {
            $.map(scope.filter.ages, function (x) {
                x.value = 0;
            })
        }
        if(!$scope.$$phase) {
            $scope.$apply();
        }
        scope.filterChange();
        scope.activeFilter[filter] = false;
    };

    scope.removeAllActiveFilters = function () {
        $.map(['search', 'price', 'category', 'age', 'location'], function (i) {
            scope.activeFilterRemove(i);
        });
    };

    scope.setUrl = function () {
        $location.path('/');
        $location.search('query', scope.filter.query);
        $location.search('location', scope.filter.location);
        $location.search('price', [scope.filter.priceMin, + scope.filter.priceMax]);
        $location.search('priceUnit', scope.filter.priceUnit);
        if (getActiveCategories().length > 0) {
            $location.search('categories', getActiveCategories());
        }
    };

    getUrl = function () {
        var q = [];
        if (scope.filter.query !== '') q.push('query|' + scope.filter.query);
        if (scope.filter.location !== '') q.push('location|' + scope.filter.location);
        if (scope.filter.longitude !== null) q.push('longitude|' + scope.filter.longitude);
        if (scope.filter.latitude !== null) q.push('latitude|' + scope.filter.latitude);
        q.push('price|' + scope.filter.priceMin + ',' + scope.filter.priceMax);
        q.push('priceUnit|' + scope.filter.priceUnit);
        if (getActiveCategories().length > 0) q.push('categories|' + getActiveCategories());
        return q.join("|");
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

    scope.loadParam = function(key, defaultValue) {
        if (scope.params[key] !== undefined && scope.params[key].length > 0) {
            return scope.params[key];
        } else {
            return defaultValue;
        }
    };

    scope.loadCategories = function() {
        angular.forEach(scope.filter.categories, function(category) {
            angular.forEach(scope.params['categories'], function (catID) {
                if (category.id == catID) {
                    category.value = true;
                }
            })
        });
        angular.forEach(scope.filter.ages, function(category) {
            angular.forEach(scope.params['categories'], function (catID) {
                if (category.id == catID) {
                    category.value = true;
                }
            })
        });
        updateActiveFilters();
        if (!$scope.$$phase) $scope.$apply();
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
