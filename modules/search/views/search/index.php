<?php

/**
 * @var \app\extended\web\View $this
 */

$this->title = \app\helpers\ViewHelper::getPageTitle(\Yii::t('search.title', 'Search KidStuff'));

\search\assets\SearchPageAsset::register($this);
?>
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
                            <div id="clear-all">Clear all</div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h6 class="panel-title">
                                        <?= Yii::t("search.filters.location", "Location") ?>
                                    </h6>
                                </div>
                                <div id="refine-location" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <input type="text" class="form-control" placeholder="Location" id="location">
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div id="refine-location" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div id="price"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div id="refine-location" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div id="brands"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="sort-by-container"></div>
                        </div>
                        <!-- end card -->
                    </div>
                    <div class="col-md-9 col-log-10">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="searchResults" id="results">
                                    <div class="row">
                                        <div id="hits-container"></div>
                                        <br>
                                        <div id="pagination-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div style="display: none;">
            <div id="item-template">
                <?= $this->render("search-result-template") ?>
            </div>
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