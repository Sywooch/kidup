<?php
/**
 * @var \app\modules\item\forms\Edit $model
 * @var \yii\widgets\ActiveForm $form
 */
?>

    <h4>
        <?= Yii::t("item.create.basics.header", "Help parents find the right product") ?>
    </h4>
<?= Yii::t("item.create.basics.help_text",
    "Help parents by selecting the right properties for your product: this makes it easier for them to find their needs.") ?>
    <hr>

    <div class="form-group">
        <label class="control-label" for="edit-item-features-4"><?= Yii::t("item", "Category") ?></label>
        <br>
        <?= $model->item->category->parent->name; ?> - <?= $model->item->category->name; ?>
        <?= \yii\helpers\Html::a(\Yii::t('item.create.basics.change_category_link', '(change)'), '#', ['id' => 'showCategoryChange']) ?>

        <?= $this->registerJs('$("#showCategoryChange").click(function(){$("#categoryChange").show();});') ?>
        <div style="display: none;" id="categoryChange">
            <?= $form->field($model, 'category_id')->widget(\kartik\select2\Select2::className(), [
                'data' => $model->categoryData,
                'options' => ['placeholder' => \Yii::t('item.create.basics.find_category', 'Find a category')],
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
    $isRequiredText = ($feature->is_required == 1) ? \Yii::t('item.create.basics.required', '(required)') : \Yii::t('item.create.basics.optional', '(optional)');
    echo $form->field($model, "features[{$feature->id}]")->widget(\kartik\select2\Select2::className(), [
        'data' => $dropDownItems,
        'options' => [
            'placeholder' => $feature->getTranslatedName() . ' ' . $isRequiredText
        ]
    ])->label($feature->getTranslatedName());
}
?>
    <div class="form-group">
        <label class="control-label" for="edit-item-features-4"><?= Yii::t("item.create.basic.features", "Features") ?></label>
    </div>
<?php
foreach ($model->item->category->singularFeatures as $feature) {
    echo $form->field($model, "singularFeatures[{$feature->id}]")->checkbox([
        'label' => $feature->getTranslatedName()
    ])->label(false);
}
?>