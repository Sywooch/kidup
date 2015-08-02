angular.module('ItemSearchModule')
    .factory('ItemSearchFactory', ItemSearchFactory);

/**
 * A factory with functionality for the item search.
 */
function ItemSearchFactory(QueryFilterFactory) {

    var factory = {
        'initialize': initialize,
        'getURL': getURL,
        'clear': clear
    };

    return factory;

    /**
     * Get a list of all filters.
     *
     * @returns {Array} a list of all filters
     * @private
     */
    function _getFilters() {
        var filters = {};
        filters['query'] = QueryFilterFactory;
        return filters;
    }

    /**
     * Initialize all filters.
     */
    function initialize() {
        factory.filters = _getFilters();
        angular.forEach(_getFilters(), function(filter) {
            filter.initialize();
            filter.setUpdateStateCallback(updateState);
            $('#itemSearch .search-default .filterButtons').append(filter.loadFilterButton());
            $('#itemSearch .search-modal .filterButtons').append(filter.loadFilterButton());
            filter.updateFilterButton();
        });
        updateState(false);
    }

    /**
     * Clear all filters.
     */
    function clear() {
        angular.forEach(_getFilters(), function(filter) {
            filter.clear();
        });
        updateState(true);
    }

    /**
     * Get the URL.
     */
    function getURL() {
        var parts = {};

        // load parts from all filters
        angular.forEach(_getFilters(), function(filter) {
            var part = filter.getURLPart();
            angular.forEach(part, function(value, key) {
                parts[key] = value;
            });
        });

        // construct the URL
        var url = '?q=';
        var hasParts = false;
        angular.forEach(parts, function(value, key) {
            url += encodeURIComponent(key) + "|" + encodeURIComponent(value) + '|';
            hasParts = true;
        });

        // remove the trailing character
        if (hasParts) {
            url = url.substr(0, url.length - 1);
        }

        return url;
    }

    /**
     * Update the state.
     *
     * @param retrieveResults whether or not to retrieve results
     */
    function updateState(retrieveResults) {
        var url = getURL();
        var title = document.title;
        window.history.pushState({}, title, url);

        // retrieve results
        if (retrieveResults == true) {
            $.ajax({
                'method': 'GET',
                'url': 'results' + url
            }).done(function (data) {
                $('.searchResults').replaceWith(data);
            });
        }
    }

}