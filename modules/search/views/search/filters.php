<?php
use yii\bootstrap\ActiveForm;
use \app\modules\item\widgets\GoogleAutoComplete;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;
use yii\web\JsExpression;

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
    <!--location-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <?= Yii::t("item", "Location") ?>
            </h6>
        </div>
        <div id="refine-location" class="panel-collapse collapse in">
            <div class="panel-body">
                <i ng-init="searchCtrl.filter.location='<?= $model->location ?>';"></i>
                <?= $form->field($model, 'location')->widget(GoogleAutoComplete::className(), [
                    'options' => [
                        'class' => 'form-control location-input',
                        'ng-model' => 'searchCtrl.filter.location',
                        'autocompleteName' => 'search-' . ($mobile ? 'mobile' : 'default')
                    ],
                    'autocompleteOptions' => [
                        'types' => ['geocode']
                    ],
                    'name' => 'autoCompleteLocation'
                ])->label(false); ?>
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
                <?= $form->field($model,
                    'priceUnit')->dropDownList(\app\models\helpers\SelectData::priceUnits())->label(false) ?>
                <br/>

                <div id="price-slider<?php echo $mobile == true ? '-mobile' : '' ?>"></div>
                <?= $form->field($model, 'priceMin')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'priceMax')->hiddenInput()->label(false) ?>
            </div>
            <div ng-cloak>
                <div class="minPrice">
                    {{searchCtrl.filter.priceMin}} DKK
                </div>
                <div class="maxPrice">
                    {{searchCtrl.filter.priceMax}} DKK
                </div>
            </div>
        </div>
    </div>

<?php foreach ($model->featureFilters as $feature):
    if ($feature->is_singular) {
        continue;
    }
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <?= Yii::t("item", $feature->name) ?>
            </h6>
        </div>
        <div id="refinePrice" class="panel-collapse collapse in">
            <div class="panel-body">
                <?php foreach ($feature->featureValues as $val) {
                    echo $form->field($model, "features[{$feature->id}][{$val->id}]")
                        ->checkbox(['label' => $val->name]);
                }
                ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h6 class="panel-title">
                <?= Yii::t("item", "Features") ?>
            </h6>
        </div>
        <div id="refinePrice" class="panel-collapse collapse in">
            <div class="panel-body">
                <?php foreach ($model->featureFilters as $feature):
                    if (!$feature->is_singular) {
                        continue;
                    }

                    echo $form->field($model, "singularFeatures[{$feature->id}]")
                        ->checkbox(['label' => $feature->name]);
                    ?>

                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default visible-xs">
        <div class="panel-body">
            <?= \yii\helpers\Html::submitButton(Yii::t("item", "Apply Filters"), ['class' => 'btn btn-danger btn-fill', 'data-dismiss' => 'modal', 'style' => 'width:100%;', 'onclick' => 'window.submitFromModal()']) ?>
            <br><br>
        </div>
    </div>


<?php ActiveForm::end() ?>