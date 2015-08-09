<?php
use yii\helpers\Url;

\app\assets\AngularAsset::register($this);
\app\modules\item\assets\MenuSearchAsset::register($this);
?>

<!-- Search button -->
<form class="form-inline hidden-xs" method="get">
    <div class="form-group">
        <input type="text"
               class="form-control menubar-search-query"
               placeholder="<?= \Yii::t('item', 'What are you looking for?') ?>">
    </div>
    <button class="btn btn-fill btn-danger" type="button"
            onclick="window.location.href = '<?= Url::base(true) ?>/search?q=query|'+$('.menubar-search-query').val()">
        <?= Yii::t("item", "Search") ?>
    </button>
</form>

