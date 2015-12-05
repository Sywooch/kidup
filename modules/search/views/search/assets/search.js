(function(){
    setTimeout(function(){
        initSearch()
    },10);

    function initSearch(){
        var search = instantsearch({
            appId: '8M1ZPTMQEW',
            apiKey: 'c2e21bc85e28c4f8af28ade68186fa2c',
            indexName: 'items',
            urlSync: true
        });
        search.addWidget(
            instantsearch.widgets.searchBox({
                container: '#menu-search-input',
                placeholder: 'Search for products...'
            })
        );

        search.addWidget(
            instantsearch.widgets.pagination({
                container: '#pagination-container'
            })
        );
        search.addWidget(
            instantsearch.widgets.hits({
                container: '#hits-container',
                templates: {
                    empty: 'No results',
                    item: document.getElementById("item-template").innerHTML
                },
                hitsPerPage: 6
            })
        );

        search.addWidget(
            instantsearch.widgets.refinementList({
                container: '#brands',
                attributeName: 'categories',
                operator: 'or',
                limit: 10,
                templates: {
                    header: '<h6>Brands</h6>'
                }
            })
        );

        search.addWidget(
            instantsearch.widgets.rangeSlider({
                container: '#price',
                attributeName: 'price_week',
                templates: {
                    header: 'Price'
                },
                tooltips: {
                    format: function(formattedValue) {
                        return 'kr.' + Math.round(formattedValue);
                    }
                }
            })
        );

        search.addWidget(
            instantsearch.widgets.clearAll({
                container: '#clear-all',
                templates: {
                    link: 'Reset everything'
                },
                autoHideContainer: false
            })
        );

        search.addWidget(
            instantsearch.widgets.location({
                container: '#location',
                attributeName: '_geo'
            })
        );

            //search.addWidget(
            //    instantsearch.widgets.sortBySelector({
            //        container: '#sort-by-container',
            //        indices: [
            //            {name: 'instant_search', label: 'Most relevant'},
            //            {name: 'instant_search_price_asc', label: 'Lowest price'},
            //            {name: 'instant_search_price_desc', label: 'Highest price'}
            //        ]
            //    })
            //);

        search.start();
    }
})();
