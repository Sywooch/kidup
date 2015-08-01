angular.module('ItemSearchModule')
    .controller('ItemSearchController', ItemSearchController);

/**
 * The controller of the item search.
 */
function ItemSearchController(ItemSearchFactory) {

    /**
     * Initialize the controller.
     */
    function initialize() {
        ItemSearchFactory.initialize();

        $('#itemSearch button[name=clear]').on('click', function() {
            ItemSearchFactory.clear();
        });
    }

    /**
     * Initialize the search module.
     */
    initialize();

}