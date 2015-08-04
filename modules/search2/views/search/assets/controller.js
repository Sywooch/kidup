var SearchController = function ($location, $http, $scope, $window) {
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
                scope.filter.minPrice = ui.values[0];
                scope.filter.maxPrice = ui.values[1];
                $scope.$apply();
            },
            change: function(val, ui) {
                scope.filter.minPrice = ui.values[0];
                scope.filter.maxPrice = ui.values[1];
                $scope.$apply();
                scope.filterChange();
            }
        };
        $("#price-slider").slider(sliderConf);
        $("#price-slider-mobile").slider(sliderConf);
    };

    var getActiveCategories = function (type) {
        var res = [];
        if(typeof type === "undefined" || type == "category"){
            $.map(scope.filter.categories, function (cat) {
                if (cat.value == 1) res.push(cat.id);
            });
        }

        if(typeof type === "undefined" || type == "age") {
            $.map(scope.filter.ages, function (cat) {
                if (cat.value == 1) res.push(cat.id);
            });
        }

        return res;
    };

    var update = function () {
        updateActiveFilters();
        $.ajax({
            'method': 'GET',
            'url': 'results?q=' + getUrl()
        }).done(function (data) {
            $('.searchResults').replaceWith(data);
        });
        scope.setUrl();
    };

    var updateActiveFilters = function(){
        scope.activeFilter = {
            search: false,
            price: false,
            age: false,
            category: false,
            location: false
        };
        if (scope.filter.query !== '')      scope.activeFilter.search = true;
        if (scope.filter.location !== '')   scope.activeFilter.location = true;
        if (scope.filter.minPrice !== '' && scope.filter.maxPrice !== '') {
            scope.activeFilter.price = true;
        }
        if (getActiveCategories('category').length > 0){
            scope.activeFilter.category = true;
        }
        if (getActiveCategories('age').length > 0){
            scope.activeFilter.age = true;
        }
    };

    scope.activeFilterRemove = function(filter){
        if(filter == 'search') scope.filter.query = '';
        if(filter == 'price') scope.filter.prices = [0,499];
        if(filter == 'location') scope.filter.location = '';
        if(filter == 'category'){
            $.map(scope.filter.categories, function(x){
                x.value = 0;
            })
        }
        if(filter == 'age') {
            $.map(scope.filter.ages, function(x){
                x.value = 0;
            })
        }
        scope.activeFilter[filter] = false;
    };

    scope.removeAllActiveFilters = function(){
        scope.activeFilterRemove('search');
        scope.activeFilterRemove('price');
        scope.activeFilterRemove('category');
        scope.activeFilterRemove('age');
        scope.activeFilterRemove('location');
    };

    scope.setUrl = function () {
        window.history.pushState({}, document.title, '?q=' + getUrl());
    };

    getUrl = function () {
        var q = [];
        if (scope.filter.query !== '') q.push('query|' + scope.filter.query);
        if (scope.filter.location !== '') q.push('location|' + scope.filter.location);
        if (scope.filter.minPrice !== 0 && scope.filter.maxPrice !== 499) {
            q.push('price|' + scope.filter.minPrice + ',' + scope.filter.maxPrice);
        }
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
        }, 700);
    };

    scope.init();

    return scope;
};

angular.module('kidup.search', []);

angular.module('kidup.search').controller('SearchCtrl', SearchController);