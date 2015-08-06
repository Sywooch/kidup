<?php
use yii\helpers\Url;
\app\assets\AngularAsset::register($this);
\app\modules\item\assets\MenuSearchAsset::register($this);
?>
<div ng-app="kidup.menuSearch">
    <div ng-controller="MenuSearchCtrl as ctrl">

        <!-- Search button -->
        <form class="form-inline hidden-xs" method="get" ng-init="ctrl.vars.url='<?= Url::to('@web/search', true) ?>'">
            <div class="form-group">
                <input type="text" ng-model="ctrl.vars.query"
                       class="form-control menubar-search-query"
                       placeholder="<?= \Yii::t('item', 'What are you looking for?') ?>">
            </div>
            <button class="btn btn-fill btn-danger" ng-click="ctrl.send()" type="button">
                <?= Yii::t("item", "Search") ?>
            </button>
        </form>

    </div>
</div>
