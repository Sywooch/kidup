var SearchController = function ($location, $http, $scope, $rootScope) {
    var scope = {};

    scope.filter = {
        query: '',
        prices: [],
        location: '',
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
            'url': 'search-results?q=' + getUrl() + '&p=0'
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
        if (scope.filter.priceMin != 0 || scope.filter.priceMax != 499) {
            scope.activeFilter.price = true;
        }
        if (getActiveCategories('category').length > 0) {
            scope.activeFilter.category = true;
        }
        if (getActiveCategories('age').length > 0) {
            scope.activeFilter.age = true;
        }
        if(!$scope.$$phase) {
            $scope.$apply();
        }
    };

    scope.activeFilterRemove = function (filter) {
        if (filter == 'search') scope.filter.query = '';
        if (filter == 'price') {
            scope.filter.priceMin = 0;
            scope.filter.priceMax = 499;
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
        if (getActiveCategories().length > 0) {
            $location.search('categories', getActiveCategories());
        }
    };

    getUrl = function () {
        var q = [];
        if (scope.filter.query !== '') q.push('query|' + scope.filter.query);
        if (scope.filter.location !== '') q.push('location|' + scope.filter.location);
        q.push('price|' + scope.filter.priceMin + ',' + scope.filter.priceMax);
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
                $scope.$apply();
            },
            change: function (val, ui) {
                scope.filterChange();
            }
        };
        $("#price-slider").slider(sliderConf);
        $("#price-slider-mobile").slider(sliderConf);
        $('.location-input').attr('oldValue', $('.location-input').val());
        setInterval(function() {
            var oldValue = $('.location-input').attr('oldValue');
            var value = $('.location-input').val();
            if (value != oldValue) {
                $('.location-input').attr('oldValue', value);
                scope.filter.location = value;
                if (value.length > 0) {
                    scope.activeFilter['location'] = true;
                } else {
                    scope.activeFilter['location'] = false;
                }
            }
        }, 500);
    };

    scope.init();

    return scope;
};

angular.module('kidup.search', []);

angular.module('kidup.search').controller('SearchCtrl', SearchController);
