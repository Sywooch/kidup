<?php
use yii\helpers\Url;

$this->title = \app\components\ViewHelper::getPageTitle(\Yii::t('title', '{0} KidStuff'));
\app\modules\search2\assets\ItemSearchAsset::register($this);
?>
<div ng-app="kidup.search" >
    <div ng-controller="SearchCtrl as searchCtrl">
        <header id="navbar-sub-menu" class="nav-down">
            <div class="fluid-container">
                <div class="row">
                    <div class="pull-left leftPadding">
                        <button class="btn btn-neutral btn-sm filter" data-toggle="modal" data-target="#filterModal">
                            <i class="fa fa-filter"></i> <?= Yii::t("item", "Filter") ?>
                        </button>
                    </div>
                    <div class="pull-left leftPadding">
                        <div class="itemCount">x
                            <?= Yii::t("item", "items") ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="section" id="search-cards">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-lg-3 hidden-sm hidden-xs">
                        <div class="card card-refine">
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
                                <?= $this->render('filters') ?>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <div class="col-md-9 col-log-10">
                        <div class="row col-xs-12">
                            <div class="btn btn-info btn-xs smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close"></i>
                                </strong>
                            </div>
                            <div class="btn btn-info btn-xs smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close"></i>
                                </strong>
                            </div>
                            <div class="btn btn-info btn-xs smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close"></i>
                                </strong>
                            </div>
                            <div class="btn btn-info btn-xs smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close"></i>
                                </strong>
                                <?= Yii::t("item", "Age") ?>
                            </div>
                            <div class="btn btn-info btn-xs smallBottomMargin">
                                <strong>
                                    <i class="fa fa-close"></i>
                                </strong>
                                <?= Yii::t("item", "Categories") ?>
                            </div>
                        </div>
                        <br/><br/>

                        <div class="row col-xs-12">
                            <?php
                            // render the results
                            //                    echo $this->render('results', [
                            ////                        'dataProvider' => $dataProvider
                            //                    ]);
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

</div>


