// instantsearch.js custom widget with jQuery
// First, we add ourselves to the instantsearch.widgets namespace
instantsearch.widgets.location = function location($container) {
    // See more details in our documentation:
    // https://community.algolia.com/instantsearch.js/documentation/#custom-widgets
    //
    // You can use any jQuery plugin you want.
    //
    // This is the custom widget interface (just an object). You need to implement
    // at least render OR init.
    return {
        getConfiguration: function (/*currentSearchParams*/) {
            // Here we only want one hit in the results, so we configure `hitsPerPage`.
            //
            // This parameter is one of Algolia's REST API: https://www.algolia.com/doc/rest#pagination-parameters
            //
            // See all the parameters here: https://www.algolia.com/doc/rest
            //
            // In the end, the underlying JS object being configured is the JavaScript helper of Algolia.
            // See https://community.algolia.com/algoliasearch-helper-js/docs/SearchParameters.html
            return {
                //hitsPerPage: 1
            }
        },
        init: function (params) {
            var helper = params.helper;
            
            setTimeout(function () {
                var el = $($container.container);
                //var facetName = helper.state.addGeoRefinement("_geo", '>=');

                el.geocomplete()
                    .bind("geocode:result", function (event, result) {
                        // set the actual position
                        helper.setQueryParameter("aroundLatLng", result.geometry.location.lat()+','+result.geometry.location.lng());
                        // returns the distance
                        helper.setQueryParameter("getRankingInfo", 1);
                        helper.search();
                    });
            }, 10);
        },
        render: function (params) {
        }
    }
};