<?php
use \app\modules\item\models\Item;
use yii\helpers\Html;

?>

<h4>
    <?= Yii::t("item", "Condition") ?> <br>
</h4>
<?= $form->field($model, 'condition')->dropDownList(
    Item::getConditions()
)->label(false) ?>

<h4><?= Yii::t("item", "Categories") ?>
    <br>
    <small><?= Yii::t("item", "What categories describe your item best?") ?></small>
</h4>
<?php foreach ($categories['main'] as $category): ?>
    <div class="btn btn-default category-clickable-button"
         data-id="<?= $category->id ?>">
        <?= $category->name ?>
    </div>
    <?= Html::activeHiddenInput($model,
        'categories[main][' . $category->id . ']'); ?>
<?php endforeach; ?>

<h4><?= Yii::t("item", "Age") ?>
    <br>
    <small><?= Yii::t("item", "Which ages is this product ment for?") ?></small>
</h4>
<?php foreach ($categories['age'] as $category): ?>
    <div class="btn btn-default category-clickable-button"
         data-id="<?= $category->id ?>">
        <?= $category->name ?>
    </div>
    <?= Html::activeHiddenInput($model,
        'categories[age][' . $category->id . ']'); ?>
<?php endforeach; ?>

<h4><?= Yii::t("item", "Special") ?>
    <br>
    <small><?= Yii::t("item", "Anything special about this product?") ?></small>
</h4>
<?php foreach ($categories['special'] as $category): ?>
    <div class="btn btn-default category-clickable-button"
         data-id="<?= $category->id ?>">
        <?= $category->name ?>
    </div>
    <?= Html::activeHiddenInput($model,
        'categories[special][' . $category->id . ']'); ?>
<?php endforeach; ?>
