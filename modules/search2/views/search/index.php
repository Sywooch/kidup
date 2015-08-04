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

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <?= $this->render('filters') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

