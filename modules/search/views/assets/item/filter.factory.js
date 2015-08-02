angular.module('ItemSearchModule')
    .factory('FilterFactory', FilterFactory);

/**
 * A factory with functionality for filters.
 */
function FilterFactory() {

    return {
        'delayedInputChange': delayedInputChange
    };

    /**
     * Delay the input change event.
     *
     * @param selector the element to apply the event on
     * @param callback the callback function
     */
    function delayedInputChange(selector, callback) {
        $(selector).on('input propertychange', function(evt) {
            // if it's the propertychange event, make sure it's the value that changed.
            if (window.event && event.type == "propertychange" && event.propertyName != "value")
                return;

            // clear any previously set timer before setting a fresh one
            window.clearTimeout($(this).data("timeout"));
            $(this).data("timeout", setTimeout(function () {
                callback(evt);
            }, 700));
        });
    }

}