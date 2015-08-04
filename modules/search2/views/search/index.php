<?php
use yii\helpers\Url;

$this->title = \app\components\ViewHelper::getPageTitle(\Yii::t('title', '{0} KidStuff'));
\app\modules\search2\assets\ItemSearchAsset::register($this);
?>
<div ng-app="kidup.search" id="search">
    <div ng-controller="SearchCtrl as searchCtrl">
        <section class="section" id="search-cards" ng-cloak>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-lg-3 ">
                        <div class="card card-refine hidden-sm hidden-xs">
                            <div class="header">
                                <h4 class="title">
                                    <?= Yii::t("item", "Filter") ?>
                                    <a href="<?= Url::to('@web/search') ?>">
                                        <button class="btn btn-danger btn-xs pull-right">
                                            <i class="fa fa-close"></i><?= Yii::t("item", "Clear") ?>
                                        </button>
                                    </a>
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
                        <div class="row col-xs-12 hidden-xs hidden-sm">
                            <div>
                                <?= Yii::t("item", "Active filters:") ?>
                            </div>
                            <div class="btn btn-default btn-sm smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close" style="font-size: 16px"></i>
                                </strong>
                                <?= Yii::t("item", "Search") ?>
                            </div>
                            <div class="btn btn-default btn-sm smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close" style="font-size: 16px"></i>
                                </strong>
                                <?= Yii::t("item", "Location") ?>
                            </div>
                            <div class="btn btn-default btn-sm smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close" style="font-size: 16px"></i>
                                </strong>
                                <?= Yii::t("item", "Price") ?>
                            </div>
                            <div class="btn btn-default btn-sm smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close" style="font-size: 16px"></i>
                                </strong>
                                <?= Yii::t("item", "Age") ?>
                            </div>
                            <div class="btn btn-default btn-sm smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close" style="font-size: 16px"></i>
                                </strong>
                                <?= Yii::t("item", "Categories") ?>
                            </div>
                        </div>
                        <br/><br/>

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
            <button type="button" class="btn btn-danger btn-md visible-xs visible-sm btn-fill" data-toggle="modal" data-target="#mobileFiltersModal">
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
                    <div class="modal-body" >
                        <?= $this->render('filters', [
                            'model' => $model,
                            'mobile' => true
                        ]) ?>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-xs-4">
                                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 100%">
                                    <?= Yii::t("item", "Cancel") ?>
                                </button>
                            </div>
                            <div class="col-xs-8">
                                <button type="button" class="btn btn-danger btn-fill" style="width: 100%">
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
