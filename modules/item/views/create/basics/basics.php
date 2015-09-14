<?php
use \app\modules\item\models\Item;
use yii\helpers\Html;
use kartik\checkbox\CheckboxX;

/**
 * @var \app\modules\item\forms\Edit $model
 * @var \yii\widgets\ActiveForm $form
 */
?>

    <h4>
        <?= Yii::t("item", "Help parents find the right product") ?>
    </h4>
<?= Yii::t("item",
    "Help parents by selecting the right categories for your product: this makes it easier for them to find their needs.") ?>
    <hr>

<?php
foreach ($model->item->category->nonSingularFeatures as $feature) {
    /**
     * @var \app\models\base\Feature $feature
     */
    $dropDownItems = [];
    foreach ($feature->featureValues as $f) {
        $dropDownItems[$f->id] = $f->name;
    }
    echo $form->field($model, "features[{$feature->id}]")->dropDownList($dropDownItems)->label($feature->name);
}
?>

<?php
foreach ($model->item->category->singularFeatures as $feature) {
    echo $form->field($model, "singularFeatures[{$feature->id}]")->checkbox([
        'label' => $feature->name
    ])->label(false);
}
?>