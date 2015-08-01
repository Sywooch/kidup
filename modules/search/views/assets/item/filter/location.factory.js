angular.module('ItemSearchModule')
    .factory('LocationFilterFactory', LocationFilterFactory);

/**
 * The factory for the location filter.
 */
function LocationFilterFactory() {

    return {
        'clear': clear
    };

    /**
     * Clear the location.
     */
    function clear() {
        // @todo implement
        console.log('clear location');
    }

}