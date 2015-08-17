<?php
use yii\helpers\Url;

\app\assets\AngularAsset::register($this);
?>

<div id="search-area" class="visible-sm visible-md visible-lg">
    <div class="row search-area">
        <div class="container">
            <div class="col-sm-12 col-md-11 col-md-offset-1">
                <form action="<?= Url::base(true) ?>/search" method="get" class="row">
                    <div class="col-sm-9 col-md-8">
                        <input type="text" id="query" name="query" class="form-control home-search-query"
                               placeholder="<?= Yii::t('app', 'What are you looking for?') ?>">
                        <span class="right"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <button type="submit" class="btn btn-danger btn-fill btn-wide">
                            <?= Yii::t("item", "Search") ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>