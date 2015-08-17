<?php
use yii\helpers\Url;

\app\assets\AngularAsset::register($this);
\app\modules\item\assets\MenuSearchAsset::register($this);
?>

<!-- Search button -->
<form class="form-inline hidden-xs" action="<?= Url::base(true) ?>/search" method="get">
    <div class="form-group">
        <input type="text"
               name="query"
               class="form-control menubar-search-query"
               placeholder="<?= \Yii::t('item', 'What are you looking for?') ?>">
    </div>
    <button class="btn btn-fill btn-danger" type="button">
        <?= Yii::t("item", "Search") ?>
    </button>
</form>

