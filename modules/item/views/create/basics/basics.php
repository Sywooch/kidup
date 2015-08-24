<?php
use \app\modules\item\models\Item;
use yii\helpers\Html;

?>

<h4>
    <?= Yii::t("item", "Help parents find the right product") ?>
</h4>
<?= Yii::t("item", "Help parents by selecting the right categories for your product: this makes it easier for them to find their needs.") ?>
<hr>

<h5>
    <?= Yii::t("item", "Age") ?>
</h5>
<div class="categories">
    <?php foreach ($categories['age'] as $category): ?>
        <div class="btn btn-default category-clickable-button"
             data-id="<?= $category->id ?>">
            <?= $category->name ?>
        </div>
        <?= Html::activeHiddenInput($model,
            'categories[age][' . $category->id . ']'); ?>
    <?php endforeach; ?>
</div>


<h5>
    <?= Yii::t("item", "Categories") ?>
</h5>
<div class="categories">
    <?php foreach ($categories['main'] as $category): ?>
        <div class="btn btn-default category-clickable-button"
             data-id="<?= $category->id ?>">
            <?= $category->name ?>
        </div>
        <?= Html::activeHiddenInput($model,
            'categories[main][' . $category->id . ']'); ?>
    <?php endforeach; ?>

</div>


<h5>
    <?= Yii::t("item", "Special") ?>
</h5>
<div class="categories">
    <?php foreach ($categories['special'] as $category): ?>
        <div class="btn btn-default category-clickable-button"
             data-id="<?= $category->id ?>">
            <?= $category->name ?>
        </div>
        <?= Html::activeHiddenInput($model,
            'categories[special][' . $category->id . ']'); ?>
    <?php endforeach; ?>
</div>

<h5>
    <?= Yii::t("item", "Item condition") ?>
</h5>

<?= $form->field($model, 'condition')->dropDownList(
Item::getConditions()
)->label(false) ?>