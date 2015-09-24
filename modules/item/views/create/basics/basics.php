<?php
/**
 * @var \app\modules\item\forms\Edit $model
 * @var \yii\widgets\ActiveForm $form
 */
?>

    <h4>
        <?= Yii::t("item", "Help parents find the right product") ?>
    </h4>
<?= Yii::t("item",
    "Help parents by selecting the right properties for your product: this makes it easier for them to find their needs.") ?>
    <hr>

    <div class="form-group">
        <label class="control-label" for="edit-item-features-4"><?= Yii::t("item", "Category") ?></label>
        <br>
        <?= $model->item->category->parent->name; ?> - <?= $model->item->category->name; ?>
        <?= \yii\helpers\Html::a(\Yii::t('item', '(change)'), '#', ['id' => 'showCategoryChange']) ?>

        <?= $this->registerJs('$("#showCategoryChange").click(function(){$("#categoryChange").show();});') ?>
        <div style="display: none;" id="categoryChange">
            <?= $form->field($model, 'category_id')->widget(\kartik\select2\Select2::className(), [
                'data' => $model->categoryData,
                'options' => ['placeholder' => \Yii::t('item', 'Find a category')],
            ])->label(false) ?>
        </div>
    </div>

<?php
foreach ($model->item->category->nonSingularFeatures as $feature) {
    /**
     * @var \app\models\base\Feature $feature
     */
    $dropDownItems = [];
    foreach ($feature->featureValues as $f) {
        $dropDownItems[$f->id] = $f->name;
    }
    $isRequiredText = ($feature->is_required == 1) ? \Yii::t('item', '(required)') : \Yii::t('item', '(optional)');
    echo $form->field($model, "features[{$feature->id}]")->widget(\kartik\select2\Select2::className(), [
        'data' => $dropDownItems,
        'options' => [
            'placeholder' => \Yii::t('item',
                \Yii::t('categories_and_features', $feature->name)) . ' ' . $isRequiredText
        ]
    ])->label(\Yii::t('categories_and_features', $feature->name));
}
?>
    <div class="form-group">
        <label class="control-label" for="edit-item-features-4"><?= Yii::t("item", "Features") ?></label>
    </div>
<?php
foreach ($model->item->category->singularFeatures as $feature) {
    echo $form->field($model, "singularFeatures[{$feature->id}]")->checkbox([
        'label' => $feature->name
    ])->label(false);
}
?>