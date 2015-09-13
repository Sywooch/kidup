<?php
use yii\bootstrap\ActiveForm;
use \app\modules\item\widgets\GoogleAutoComplete;
/**
 * @var \app\modules\search\forms\Filter $model
 * @var bool $mobile
 */

?>
<!-- query -->

<?php
$form = ActiveForm::begin([
    'enableClientValidation' => false,
    'method' => 'get',
    'options' => ['name' => 'data-pjax', 'data-pjax' => true, 'id' => 'search-form'],
]); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Search") ?>
        </h6>
    </div>
    <div id="refineQuery" class="panel-collapse collapse in">
        <div class="panel-body">
            <?= $form->field($model, 'query')->textInput() ?>
        </div>
    </div>
</div>

<!--location-->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Location") ?>
        </h6>
    </div>
    <div id="refine-location" class="panel-collapse collapse in">
        <div class="panel-body">
            <?= $form->field($model, 'query')->widget(GoogleAutoComplete::className(), [
                'options' => [
                    'class' => 'form-control location-input',
                    'ng-model' => 'searchCtrl.filter.location',
                    'autocompleteName' => 'search-' . ($mobile ? 'mobile' : 'default')
                ],
                'autocompleteOptions' => [
                    'types' => ['geocode']
                ],
                'name' => 'autoCompleteLocation'
            ]); ?>
        </div>
    </div>
</div>

<!-- price -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Price") ?>
        </h6>
    </div>
    <div id="refinePrice" class="panel-collapse collapse in">
        <div class="panel-body">
            <?= $form->field($model, 'priceUnit')->dropDownList(\app\models\helpers\SelectData::priceUnits()) ?>
            <br />
            <div id="price-slider<?php echo $mobile == true ? '-mobile' : '' ?>"></div>
        </div>
        <div class="minPrice">
            {{searchCtrl.filter.priceMin}} DKK
        </div>
        <div class="maxPrice">
            {{searchCtrl.filter.priceMax}} DKK
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>