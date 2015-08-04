var SearchController = function ($location, $http, $scope, $window) {
    var scope = {};

    _mainUrl = $location.absUrl().replace($location.url(), '');

    scope.filter = {
        query: '',
        prices: [],
        location: '',
        distance: '',
        categories: [],
        ages: []
    };

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
        $("#price-slider").slider({
            range: true,
            min: 0,
            max: 500
        });

        $("#distance-slider").slider({
            range: false,
            min: 0,
            max: 100000
        });

        $http.get(_mainUrl + 'categories').then(function (res) {
            $.map(res.data, function (category) {
                category.value = 0;
                if (category.type === 'main') {
                    scope.filter.categories.push(category);
                }
                if (category.type === 'age') {
                    scope.filter.ages.push(category);
                }
            });
        });
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
        setTimeout(function () {
            update();
        }, 400);
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