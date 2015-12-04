<?php

/**
 * @var \app\extended\web\View $this
 */

\app\assets\LodashAsset::register($this);
\app\assets\JQueryTextRangeAsset::register($this);
\app\assets\FullModalAsset::register($this);

$this->title = \app\helpers\ViewHelper::getPageTitle(\Yii::t('search.title', 'Search KidStuff'));

$this->registerCssFile("//cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css");
$this->registerJsFile("//cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js", [
    'position' => \yii\web\View::POS_BEGIN
]);
?>

    <script>
        setTimeout(function () {
            var search = instantsearch({
                appId: '8M1ZPTMQEW',
                apiKey: 'c2e21bc85e28c4f8af28ade68186fa2c',
                indexName: 'items',
                urlSync: true
            });
            search.addWidget(
                instantsearch.widgets.searchBox({
                    container: '#search-box',
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
                        item:  document.getElementById("item-template").outerHTML
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
                        header: 'Brands'
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
                            return '$' + formattedValue;
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
                instantsearch.widgets.sortBySelector({
                    container: '#sort-by-container',
                    indices: [
                        {name: 'instant_search', label: 'Most relevant'},
                        {name: 'instant_search_price_asc', label: 'Lowest price'},
                        {name: 'instant_search_price_desc', label: 'Highest price'}
                    ]
                })
            );

            search.start();
        }, 10);
    </script>
    <div id="search">
        <section class="section" id="search-cards">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-lg-3" id="search-sidebar">
                        <div class="card card-refine hidden-sm hidden-xs">
                            <div class="header">
                                <h4 class="title">
                                    <?= Yii::t("search.header_filter", "Filter") ?>
                                </h4>
                            </div>

                            <input type="text" id="search-box"/>
                            <div id="brands"></div>
                            <div id="clear-all"></div>
                            <div id="price"></div>
                            <div id="sort-by-container"></div>
                        </div>
                        <!-- end card -->
                    </div>
                    <div class="col-md-9 col-log-10">
                        <div class="row">
                            <div class="col-xs-12">
                                <div id="hits-container"></div>
                                <div id="pagination-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="item-card card-width col-xs-12 col-sm-6 col-md-3 col-lg-3" id="item-template">
            <a href="<?= \yii\helpers\Url::to('@web/item') ?>/{{objectID}}" data-pjax="0">
                <div class="card">
                    <div class="image"
                         style="background-size: cover; background-position: 50% 50%;">
                        <div class="price-badge">
                    <span class="time">
                        <?= Yii::t("item.card.from", "from") ?>
                    </span>
                            <span class="currency">kr.</span>
                    <span class="price">
                        {{price_week}}
                    </span>
                        </div>
                        <div class="author">
                            img
                        </div>
                    </div>
                    <div class="content">
                        <h3 class="title" style="height:20px;">
                           {{title}}
                        </h3>

                        <div class="category">
                            {{test}}
                        </div>

                        <div class="footer-divs">
                            <div class="reviews">
                                {{score}}
                            </div>
                            <div class="location">
                                <i class="fa fa-map-marker"></i>
                                {{dist}}
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- mobile filters -->

        <div class="buttonContainer" style="z-index:10;">
            <button type="button" class="btn btn-danger btn-md visible-xs visible-sm btn-fill" data-toggle="modal"
                    data-target="#mobileFiltersModal" id="filter-button" style="z-index:10;">
                <?= Yii::t("saerch.filters_header", "Filters") ?>
            </button>
        </div>

        <?php
        \yii\bootstrap\Modal::begin([
            'options' => [
                'class' => 'modal modal-fullscreen force-fullscreen',
                'id' => 'mobileFiltersModal'
            ],
            'closeButton' => false,
            'header' => "<b>" . \Yii::t('search.mobile_search_filters', 'Search Filters') . "</b>"
        ])
        ?>

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="fa fa-close"></i>
        </button>

        <?php \yii\bootstrap\Modal::end() ?>
    </div>

<?= \app\widgets\SignupModal::widget() ?>