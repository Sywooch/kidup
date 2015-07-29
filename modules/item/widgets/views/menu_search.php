<?php
use yii\helpers\Url;
?>

<!-- Search button -->
<form class="form-inline hidden-xs" action="<?= Url::to('@web/search') ?>" method="get">
    <div class="form-group">
        <?= \yii\helpers\Html::activeInput('text', $model, 'query', [
            'placeholder' => \Yii::t('item', 'What are you looking for?'),
            'class' => 'form-control menubar-search-query',
        ]) ?>
    </div>
    <button class="btn btn-fill btn-danger"><?= Yii::t("item", "Search") ?></button>
</form>
