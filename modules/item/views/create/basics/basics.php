<?php
/**
 * @var \item\forms\Edit $model
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
    <label class="control-label"><?= Yii::t("item.create.basics.category",
            "Category") ?></label>
    <br>
    <?= $model->item->category->parent->getTranslatedName(); ?> - <?= $model->item->category->getTranslatedName(); ?>
    <?= \yii\helpers\Html::a(\Yii::t('item.create.basics.change_category_link', '(change)'), '#',
        ['id' => 'showCategoryChange']) ?>

    <?= $this->registerJs('$("#showCategoryChange").click(function(){$("#categoryChange").show();});') ?>
    <div style="display: none;" id="categoryChange">
        <?= $form->field($model, 'category_id')->widget(\kartik\select2\Select2::className(), [
            'data' => $model->categoryData,
            'options' => ['placeholder' => \Yii::t('item.create.basics.find_category', 'Find a category')],
        ])->label(false) ?>
    </div>
</div>

<?php
foreach ($model->item->category->itemFacets as $facet) {
    /**
     * @var \item\models\base\ItemFacet $facet
     */
    $dropDownItems = [];

    $isRequiredText = ($facet->is_required == 1) ? \Yii::t('item.create.basics.required',
        '(required)') : \Yii::t('item.create.basics.optional', '(optional)');
    if($facet->allow_multiple == false){
        foreach ($facet->itemFacetValues as $f) {
            $dropDownItems[$f->id] = $f->getTranslatedName();
        }
        echo $form->field($model, "item_facets[{$facet->id}]")->widget(\kartik\select2\Select2::className(), [
            'data' => $dropDownItems,
            'options' => [
                'placeholder' => $facet->getTranslatedName() . ' ' . $isRequiredText
            ]
        ])->label($facet->getTranslatedName());
    }else{
        foreach ($facet->itemFacetValues as $f) {
            echo $form->field($model, "item_facets[{$facet->id}][{$f->id}]")->checkbox()->label($f->getTranslatedName());
        }
    }
}
?>
