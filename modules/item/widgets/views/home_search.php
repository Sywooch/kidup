<?php
use \yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\AngularAsset::register($this);
?>

<div id="search-area" class="visible-sm visible-md visible-lg">
    <div class="row search-area">
        <div class="container">
            <div class="col-sm-12 col-md-11 col-md-offset-1">
                <div class="row">
                    <div class="col-sm-9 col-md-8">
                        <input type="text" id="query" class="form-control home-search-query"
                               placeholder="<?= Yii::t('app', 'What are you looking for?') ?>">
                        <span class="right"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <button class="btn btn-danger btn-fill btn-wide"
                                onclick="window.location.href = '<?= Url::base(true) ?>/search?q=query|'+$('.home-search-query').val()">
                            <?= Yii::t("item", "Search") ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>