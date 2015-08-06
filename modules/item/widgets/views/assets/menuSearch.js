var MenuSearch = function ($location) {
    var scope = {};

    scope.vars = {
        query: ''
    };

    scope.send = function(){
        window.location.href = scope.vars.url+'?q=query|'+scope.vars.query;
    };

    return scope;
};

angular.module('kidup.menuSearch', []);

angular.module('kidup.menuSearch').controller('MenuSearchCtrl', MenuSearch);