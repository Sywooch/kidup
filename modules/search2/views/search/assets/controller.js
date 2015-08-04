var SearchController = function ($location, $http, $scope, $window) {
    var scope = {};

    scope.filter = {
        query: '',
        prices: [],
        location: '',
        distance: '',
        categories: [],
        ages: []
    };

    scope.minPrice = 0;
    scope.maxPrice = 499;

    scope._timer = null;

    scope.selectCategory = function (id) {
        $.map(scope.filter.categories, function (el, i) {
            if (el.id == id) {
                return scope.filter.categories[i].value = (scope.filter.categories[i].value == 1) ? 0 : 1;
            }
        });
        $.map(scope.filter.ages, function (el, i) {
            if (el.id == id) {
                return scope.filter.ages[i].value = (scope.filter.ages[i].value == 1) ? 0 : 1;
            }
        });
        scope.filterChange();
    };

    scope.init = function () {
        var sliderConf = {
            range: true,
            min: 0,
            max: 499,
            slide: function(val, ui){
                scope.minPrice = ui.values[0];
                scope.maxPrice = ui.values[1];
                $scope.$apply();
            },
            change: function(val, ui) {
                scope.minPrice = ui.values[0];
                scope.maxPrice = ui.values[1];
                $scope.$apply();
                scope.filterChange();
            }
        };
        $("#price-slider").slider(sliderConf);
        $("#price-slider-mobile").slider(sliderConf);
    };

    var getActiveCategories = function () {
        var res = [];
        $.map(scope.filter.categories, function (cat) {
            if (cat.value == 1) res.push(cat.id);
        });

        $.map(scope.filter.ages, function (cat) {
            if (cat.value == 1) res.push(cat.id);
        });

        return res;
    };

    var update = function () {
        $.ajax({
            'method': 'GET',
            'url': 'results?q=' + getUrl()
        }).done(function (data) {
            $('.searchResults').replaceWith(data);
        });
    };

    scope.setUrl = function () {
        //window.history.pushState({}, 'title', url);
    };

    getUrl = function () {
        var q = [];
        if (scope.filter.query !== '') q.push('query|' + scope.filter.query);
        if (scope.filter.location !== '') q.push('location|' + scope.filter.location);
        if (scope.filter.distance !== '') q.push('distance|' + scope.filter.location);
        if (getActiveCategories().length > 0) q.push('categories|' + getActiveCategories());
        return q.join("|");
    };

    scope.filterChange = function () {
        scope.setUrl();
        if (scope._timer !== null) {
            clearTimeout(scope._timer)
        }
        scope._timer = setTimeout(function () {
            update();
        }, 600);
    };

    scope.init();

    return scope;
};

angular.module('kidup.search', []).config(function ($locationProvider) {
    //$locationProvider.html5Mode({
    //    enabled: true
    //});
});

angular.module('kidup.search').controller('SearchCtrl', SearchController);