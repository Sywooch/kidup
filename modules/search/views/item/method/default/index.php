<?php
use yii\helpers\Html;
?>

<section class="section show-lg show-md hidden-sm hidden-xs" id="search-sidebar">
    <div class="container-fluid">

        <h1>Hello {{ sometext }}</h1>

        <!-- Search filters -->
        <div class="row">

            <div class="col-md-3 col-lg-3 hidden-sm hidden-xs">
                <div class="card card-refine">
                    <div class="header">

                        <!-- Button to clear all filters -->
                        <?= Html::button(\Yii::t('item', "Clear"),
                            ['class' => 'btn btn-danger btn-xs pull-right']) ?>

                        <!-- Title -->
                        <h4 class="title"><?= Yii::t("item", "Filter") ?></h4>

                    </div>
                    <div class="content">
                        Content
                    </div>
                </div>
            </div>

            <div class="col-lg-9 col-md-9">
                <?= $this->render('../../results'); ?>
            </div>

        </div>
    </div>
</section>