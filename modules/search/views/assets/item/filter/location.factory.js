angular.module('ItemSearchModule')
    .factory('LocationFilterFactory', LocationFilterFactory);

/**
 * The factory for the location filter.
 */
function LocationFilterFactory(FilterFactory) {

    var filter = {
        'initialize': initialize,
        'clear': clear,
        'setValue': setValue,
        'getValue': getValue,
        'apply': apply,
        'loadFilterButton': loadFilterButton,
        'getFilterButton': getFilterButton,
        'updateFilterButton': updateFilterButton,
        'getURLPart': getURLPart,
        'setUpdateStateCallback': setUpdateStateCallback
    };

    return filter;

    /**
     * Initialize the filter.
     */
    function initialize() {
        filter._inputSelectors = '#itemSearch input[name=query]';
        FilterFactory.delayedInputChange($('#itemSearch .search-default input[name=query]'), function() {
            filter.updateState(true);
        });
        filter._value = $('#itemSearch .search-default input[name=query]').val();
        _updateTitle(filter._value);
        $('#itemSearch .search-default input[name=query]').on('keyup', function() {
            updateFilterButton();
            _updateTitle(filter._value);
        });
        $(filter._inputSelectors).on('keyup', function() {
            filter._value = $(this).val();
        });
    }

    /**
     * Clear the value.
     */
    function clear() {
        setValue('');
        filter.updateState(true);
        getFilterButton().hide();
    }

    /**
     * Set the value of the filter.
     *
     * @param value
     */
    function setValue(value) {
        $(filter._inputSelectors).val(value);
        filter._value = value;
    }

    /**
     * Get the value of the filter.
     *
     * @return value
     */
    function getValue() {
        return filter._value;
    }

    /**
     * Apply the filter.
     */
    function apply() {
        updateFilterButton();
        _updateTitle(filter._value);
        filter.updateState(true);
    }

    /**
     * Get the filter button.
     *
     * @return the filter button
     */
    function loadFilterButton() {
        var button = $('<button />')
                .addClass('btn btn-xs btn-filter-query')
                .html('<i class="fa fa-close"></i> Search term: <span class="value"></span>')
                .click(clear)
            ;
        return button;
    }

    /**
     * Get all filter buttons.
     *
     * @returns {*|jQuery|HTMLElement}
     */
    function getFilterButton() {
        return $('.btn-filter-query');
    }

    /**
     * Update the view of the filter button.
     */
    function updateFilterButton() {
        if (getValue().length == 0) {
            getFilterButton().hide();
        } else {
            getFilterButton().find('.value').text(getValue());
            getFilterButton().show();
        }
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

    /**
     * Update the page title.
     *
     * @param value the current search term
     * @private
     */
    function _updateTitle(value) {
        document.title = value + ' | KidStuff';
    }

}