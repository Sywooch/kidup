angular.module('ItemSearchModule')
    .controller('ItemSearchController', ItemSearchController);

/**
 * The controller of the item search.
 */
function ItemSearchController($scope, ItemSearchFactory) {

    $scope.ItemSearchFactory = ItemSearchFactory;
    $scope.applyFilter = applyFilter;

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
     * Apply a filter.
     *
     * @param filter
     */
    function applyFilter(filter) {
        filter.apply();
    }

    /**
     * Initialize the search module.
     */
    initialize();

}