angular.module('searchModule')
    .factory('SearchFactory', SearchFactory);

function SearchFactory() {
    document.title = 'test';
}