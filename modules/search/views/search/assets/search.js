(function () {
    setTimeout(function () {
        initSearch()
    }, 10);

    function initSearch() {
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
                container: '#pagination-container',
                cssClasses: {
                    root: 'pagination',
                    active: 'active'
                }
            })
        );
        search.addWidget(
            instantsearch.widgets.hits({
                container: '#hits-container',
                templates: {
                    empty: 'No results',
                    item: document.getElementById("item-template").innerHTML
                },
                hitsPerPage: 20
            })
        );

        search.addWidget(
            instantsearch.widgets.hierarchicalMenu({
                container: '#categories',
                attributes: ['hierarchicalCategories_da.lvl0', 'hierarchicalCategories_da.lvl1'],
                templates: {
                    header: 'Categories'
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
                    format: function (formattedValue) {
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

        search.addWidget(
            instantsearch.widgets.refinementList({
                container: '#conditions',
                attributeName: 'facet_condition_da',
                operator: 'or',
                limit: 10,
                templates: {
                    header: 'Condition'
                }
            })
        );

        search.addWidget(
            instantsearch.widgets.stats({
                container: '#stats-container'
            })
        );

        search.templatesConfig.helpers.location = function (/*text, render*/) {
            var location = this.location.city;

            if (typeof this._rankingInfo != "undefined") {
                if (typeof this._rankingInfo.geoDistance !== "undefined") {
                    var dist = Math.round(this._rankingInfo.geoDistance / 100) / 10;
                    if (dist < 1) {
                        location = dist * 1000 + ' m';
                    } else {
                        location = Math.round(this._rankingInfo.geoDistance / 100) / 10 + " km";
                    }
                }
            }

            return location;
        };

        search.templatesConfig.helpers.reviews = function (/*text, render*/) {
            var starCount = this.review_score;

            if (starCount == false) {
                stars = 'No reviews yet';
            } else {
                stars = '<div class="user-review-stars">';
                for (var i = starCount; i > 0; i--) {
                    stars += '<i class="fa fa-star"></i>';
                }
                for (i = starCount-1; i < 5; i++) {
                    stars += '<i class="fa fa-star-o"></i>';
                }
                stars += '</div>';
            }

            return stars;
        };

        search.templatesConfig.helpers.subCategory = function (/*text, render*/) {
            return this.hierarchicalCategories_da.lvl1.split(" > ")[1];
        };

        search.start();
    }
})();
