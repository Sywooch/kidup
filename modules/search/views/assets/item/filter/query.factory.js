angular.module('ItemSearchModule')
    .factory('QueryFilterFactory', QueryFilterFactory);

/**
 * The factory for the query filter.
 */
function QueryFilterFactory(FilterFactory) {

    var filter = {
        'initialize': initialize,
        'clear': clear,
        'setValue': setValue,
        'getValue': getValue,
        'getFilterButton': getFilterButton,
        'getURLPart': getURLPart,
        'setUpdateStateCallback': setUpdateStateCallback
    };

    return filter;

    /**
     * Initialize the filter.
     */
    function initialize() {
        FilterFactory.delayedInputChange('#itemSearch input[name=query]', function() {
            filter.updateState(true);
        });
    }

    /**
     * Clear the value.
     */
    function clear() {
        setValue('');
        filter.updateState(true);
    }

    /**
     * Set the value of the filter.
     *
     * @param value
     */
    function setValue(value) {
        $('#itemSearch input[name=query]').val(value);
    }

    /**
     * Get the value of the filter.
     *
     * @return value
     */
    function getValue() {
        return $('#itemSearch input[name=query]').val();
    }

    /**
     * Get the filter button.
     *
     * @return the filter button
     */
    function getFilterButton() {
        console.log('query filter getFilterButton');
    }

    /**
     * Get a part of the URL for this filter.
     *
     * @returns {{query: value}}
     */
    function getURLPart() {
        return {
            'query': getValue()
        };
    }

    /**
     * Set the update state callback function.
     *
     * @param callback function that can update the state
     */
    function setUpdateStateCallback(callback) {
        filter.updateState = callback;
    }

}