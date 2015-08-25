<?php

$this->title = \app\components\ViewHelper::getPageTitle(\Yii::t('title', 'Search KidStuff'));
\app\modules\search\assets\ItemSearchAsset::register($this);
?>
<div ng-app="kidup.search" id="search">
    <div ng-controller="SearchCtrl as searchCtrl">
        <section class="section" id="search-cards">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-lg-3" id="search-sidebar">
                        <div class="card card-refine hidden-sm hidden-xs">
                            <div class="header">
                                <h4 class="title">
                                    <?= Yii::t("item", "Filter") ?>
                                    <button class="btn btn-danger btn-xs pull-right"
                                            ng-click="searchCtrl.removeAllActiveFilters()">
                                        <i class="fa fa-close"></i><?= Yii::t("item", "Clear") ?>
                                    </button>
                                </h4>
                            </div>
                            <div class="content">
                                <?= $this->render('filters', [
                                    'model' => $model,
                                    'mobile' => false
                                ]) ?>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <div class="col-md-9 col-log-10">

                        <div class="row" style="margin:10px 0px;padding:0px;">
                            <div class="col-md-12 hidden-xs hidden-sm">
                                <div style="display: inline-block">
                                    <?= Yii::t("item", "Active filters:") ?>
                                </div>

                                <i ng-init='searchCtrl.activeFilter.search = <?= $model->query == null ? 'false' : 'true' ?>'></i>

                                <div class="btn btn-default btn-sm smallBottomMargin"
                                     ng-show="searchCtrl.activeFilter.search"
                                     ng-click="searchCtrl.activeFilterRemove('search')">
                                    <strong>
                                        <i class="fa fa-close" style="font-size: 16px"></i>
                                    </strong>
                                    <?= Yii::t("item", "Search") ?>
                                </div>

                                <i ng-init='searchCtrl.activeFilter.location = <?= $model->location == null ? 'false' : 'true' ?>'></i>

                                <div class="btn btn-default btn-sm smallBottomMargin"
                                     ng-show="searchCtrl.activeFilter.location"
                                     ng-click="searchCtrl.activeFilterRemove('location')">
                                    <strong>
                                        <i class="fa fa-close" style="font-size: 16px"></i>
                                    </strong>
                                    <?= Yii::t("item", "Location") ?>
                                </div>

                                <div class="btn btn-default btn-sm smallBottomMargin"
                                     ng-show="searchCtrl.activeFilter.price"
                                     ng-click="searchCtrl.activeFilterRemove('price')">
                                    <strong>
                                        <i class="fa fa-close" style="font-size: 16px"></i>
                                    </strong>
                                    <?= Yii::t("item", "Price") ?>
                                </div>
                                <i ng-init='searchCtrl.activeFilter.age = <?= count($model->getActiveCategories('age')) == 0 ? 'false' : 'true' ?>'></i>

                                <div class="btn btn-default btn-sm smallBottomMargin"
                                     ng-show="searchCtrl.activeFilter.age"
                                     ng-click="searchCtrl.activeFilterRemove('age')">
                                    <strong>
                                        <i class="fa fa-close" style="font-size: 16px"></i>
                                    </strong>
                                    <?= Yii::t("item", "Age") ?>
                                </div>

                                <i ng-init='searchCtrl.activeFilter.category = <?= count($model->getActiveCategories('main')) == 0 ? 'false' : 'true' ?>'></i>

                                <div class="btn btn-default btn-sm smallBottomMargin"
                                     ng-show="searchCtrl.activeFilter.category"
                                     ng-click="searchCtrl.activeFilterRemove('category')">
                                    <strong>
                                        <i class="fa fa-close" style="font-size: 16px"></i>
                                    </strong>
                                    <?= Yii::t("item", "Categories") ?>
                                </div>
                            </div>
                            <br/><br/>

                            <div class="loader" ng-show="searchCtrl.loading">
                                <img alt="" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPgo8c3ZnIHdpZHRoPSI0MHB4IiBoZWlnaHQ9IjQwcHgiIHZpZXdCb3g9IjAgMCA0MCA0MCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWw6c3BhY2U9InByZXNlcnZlIiBzdHlsZT0iZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5kO3N0cm9rZS1taXRlcmxpbWl0OjEuNDE0MjE7IiB4PSIwcHgiIHk9IjBweCI+CiAgICA8ZGVmcz4KICAgICAgICA8c3R5bGUgdHlwZT0idGV4dC9jc3MiPjwhW0NEQVRBWwogICAgICAgICAgICBALXdlYmtpdC1rZXlmcmFtZXMgc3BpbiB7CiAgICAgICAgICAgICAgZnJvbSB7CiAgICAgICAgICAgICAgICAtd2Via2l0LXRyYW5zZm9ybTogcm90YXRlKDBkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICAgIHRvIHsKICAgICAgICAgICAgICAgIC13ZWJraXQtdHJhbnNmb3JtOiByb3RhdGUoLTM1OWRlZykKICAgICAgICAgICAgICB9CiAgICAgICAgICAgIH0KICAgICAgICAgICAgQGtleWZyYW1lcyBzcGluIHsKICAgICAgICAgICAgICBmcm9tIHsKICAgICAgICAgICAgICAgIHRyYW5zZm9ybTogcm90YXRlKDBkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICAgIHRvIHsKICAgICAgICAgICAgICAgIHRyYW5zZm9ybTogcm90YXRlKC0zNTlkZWcpCiAgICAgICAgICAgICAgfQogICAgICAgICAgICB9CiAgICAgICAgICAgIHN2ZyB7CiAgICAgICAgICAgICAgICAtd2Via2l0LXRyYW5zZm9ybS1vcmlnaW46IDUwJSA1MCU7CiAgICAgICAgICAgICAgICAtd2Via2l0LWFuaW1hdGlvbjogc3BpbiAxLjVzIGxpbmVhciBpbmZpbml0ZTsKICAgICAgICAgICAgICAgIC13ZWJraXQtYmFja2ZhY2UtdmlzaWJpbGl0eTogaGlkZGVuOwogICAgICAgICAgICAgICAgYW5pbWF0aW9uOiBzcGluIDEuNXMgbGluZWFyIGluZmluaXRlOwogICAgICAgICAgICB9CiAgICAgICAgXV0+PC9zdHlsZT4KICAgIDwvZGVmcz4KICAgIDxnIGlkPSJvdXRlciI+CiAgICAgICAgPGc+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMCwwQzIyLjIwNTgsMCAyMy45OTM5LDEuNzg4MTMgMjMuOTkzOSwzLjk5MzlDMjMuOTkzOSw2LjE5OTY4IDIyLjIwNTgsNy45ODc4MSAyMCw3Ljk4NzgxQzE3Ljc5NDIsNy45ODc4MSAxNi4wMDYxLDYuMTk5NjggMTYuMDA2MSwzLjk5MzlDMTYuMDA2MSwxLjc4ODEzIDE3Ljc5NDIsMCAyMCwwWiIgc3R5bGU9ImZpbGw6YmxhY2s7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNNS44NTc4Niw1Ljg1Nzg2QzcuNDE3NTgsNC4yOTgxNSA5Ljk0NjM4LDQuMjk4MTUgMTEuNTA2MSw1Ljg1Nzg2QzEzLjA2NTgsNy40MTc1OCAxMy4wNjU4LDkuOTQ2MzggMTEuNTA2MSwxMS41MDYxQzkuOTQ2MzgsMTMuMDY1OCA3LjQxNzU4LDEzLjA2NTggNS44NTc4NiwxMS41MDYxQzQuMjk4MTUsOS45NDYzOCA0LjI5ODE1LDcuNDE3NTggNS44NTc4Niw1Ljg1Nzg2WiIgc3R5bGU9ImZpbGw6cmdiKDIxMCwyMTAsMjEwKTsiLz4KICAgICAgICA8L2c+CiAgICAgICAgPGc+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik0yMCwzMi4wMTIyQzIyLjIwNTgsMzIuMDEyMiAyMy45OTM5LDMzLjgwMDMgMjMuOTkzOSwzNi4wMDYxQzIzLjk5MzksMzguMjExOSAyMi4yMDU4LDQwIDIwLDQwQzE3Ljc5NDIsNDAgMTYuMDA2MSwzOC4yMTE5IDE2LjAwNjEsMzYuMDA2MUMxNi4wMDYxLDMzLjgwMDMgMTcuNzk0MiwzMi4wMTIyIDIwLDMyLjAxMjJaIiBzdHlsZT0iZmlsbDpyZ2IoMTMwLDEzMCwxMzApOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTI4LjQ5MzksMjguNDkzOUMzMC4wNTM2LDI2LjkzNDIgMzIuNTgyNCwyNi45MzQyIDM0LjE0MjEsMjguNDkzOUMzNS43MDE5LDMwLjA1MzYgMzUuNzAxOSwzMi41ODI0IDM0LjE0MjEsMzQuMTQyMUMzMi41ODI0LDM1LjcwMTkgMzAuMDUzNiwzNS43MDE5IDI4LjQ5MzksMzQuMTQyMUMyNi45MzQyLDMyLjU4MjQgMjYuOTM0MiwzMC4wNTM2IDI4LjQ5MzksMjguNDkzOVoiIHN0eWxlPSJmaWxsOnJnYigxMDEsMTAxLDEwMSk7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNMy45OTM5LDE2LjAwNjFDNi4xOTk2OCwxNi4wMDYxIDcuOTg3ODEsMTcuNzk0MiA3Ljk4NzgxLDIwQzcuOTg3ODEsMjIuMjA1OCA2LjE5OTY4LDIzLjk5MzkgMy45OTM5LDIzLjk5MzlDMS43ODgxMywyMy45OTM5IDAsMjIuMjA1OCAwLDIwQzAsMTcuNzk0MiAxLjc4ODEzLDE2LjAwNjEgMy45OTM5LDE2LjAwNjFaIiBzdHlsZT0iZmlsbDpyZ2IoMTg3LDE4NywxODcpOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTUuODU3ODYsMjguNDkzOUM3LjQxNzU4LDI2LjkzNDIgOS45NDYzOCwyNi45MzQyIDExLjUwNjEsMjguNDkzOUMxMy4wNjU4LDMwLjA1MzYgMTMuMDY1OCwzMi41ODI0IDExLjUwNjEsMzQuMTQyMUM5Ljk0NjM4LDM1LjcwMTkgNy40MTc1OCwzNS43MDE5IDUuODU3ODYsMzQuMTQyMUM0LjI5ODE1LDMyLjU4MjQgNC4yOTgxNSwzMC4wNTM2IDUuODU3ODYsMjguNDkzOVoiIHN0eWxlPSJmaWxsOnJnYigxNjQsMTY0LDE2NCk7Ii8+CiAgICAgICAgPC9nPgogICAgICAgIDxnPgogICAgICAgICAgICA8cGF0aCBkPSJNMzYuMDA2MSwxNi4wMDYxQzM4LjIxMTksMTYuMDA2MSA0MCwxNy43OTQyIDQwLDIwQzQwLDIyLjIwNTggMzguMjExOSwyMy45OTM5IDM2LjAwNjEsMjMuOTkzOUMzMy44MDAzLDIzLjk5MzkgMzIuMDEyMiwyMi4yMDU4IDMyLjAxMjIsMjBDMzIuMDEyMiwxNy43OTQyIDMzLjgwMDMsMTYuMDA2MSAzNi4wMDYxLDE2LjAwNjFaIiBzdHlsZT0iZmlsbDpyZ2IoNzQsNzQsNzQpOyIvPgogICAgICAgIDwvZz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZD0iTTI4LjQ5MzksNS44NTc4NkMzMC4wNTM2LDQuMjk4MTUgMzIuNTgyNCw0LjI5ODE1IDM0LjE0MjEsNS44NTc4NkMzNS43MDE5LDcuNDE3NTggMzUuNzAxOSw5Ljk0NjM4IDM0LjE0MjEsMTEuNTA2MUMzMi41ODI0LDEzLjA2NTggMzAuMDUzNiwxMy4wNjU4IDI4LjQ5MzksMTEuNTA2MUMyNi45MzQyLDkuOTQ2MzggMjYuOTM0Miw3LjQxNzU4IDI4LjQ5MzksNS44NTc4NloiIHN0eWxlPSJmaWxsOnJnYig1MCw1MCw1MCk7Ii8+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4K" />
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <?php
                                    // render the results
                                    echo $this->render('results', [
                                        'results' => $results
                                    ]);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- mobile filters -->

        <div class="buttonContainer">
            <button type="button" class="btn btn-danger btn-md visible-xs visible-sm btn-fill" data-toggle="modal"
                    data-target="#mobileFiltersModal" id="filter-button">
                <?= Yii::t("item", "Filters") ?>
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="mobileFiltersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            <?= Yii::t("item", "Filters") ?><br>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <?= $this->render('filters', [
                            'model' => $model,
                            'mobile' => true
                        ]) ?>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <!--                            <div class="col-xs-4">-->
                            <!--                                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 100%">-->
                            <!--                                    --><? //= Yii::t("item", "Cancel") ?>
                            <!--                                </button>-->
                            <!--                            </div>-->
                            <div class="col-xs-12">
                                <button type="button" class="btn btn-danger btn-fill" data-dismiss="modal"
                                        style="width: 100%">
                                    <?= Yii::t("item", "Apply Filters") ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
