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
<div class="panel panel-default">
    <div class="panel-heading">
        <h6 class="panel-title">
            <?= Yii::t("item", "Search") ?>
        </h6>
    </div>
    <div id="refineQuery" class="panel-collapse collapse in">
        <div class="panel-body">
            <?= $form->field($model, 'query')->widget(Typeahead::className(), [
                'options' => ['placeholder' => 'E.g. Strollers'],
                'pluginOptions' => ['highlight' => true, 'hint' => false],
                'pluginEvents' => [
                    "typeahead:render" => "function(a,b) { window.onTypeaheadRender(b); }",
                ],
                'dataset' => [
                    [
                        'remote' => [
                            'url' => Url::to('@web/search/auto-complete/index?q=%q'),
                            'wildcard' => '%q'
                        ],
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                        'limit' => 5,
                        'display' => 'value',
                        'templates' => [
                            'notFound' => '<div class="text-danger" style="padding:0 8px">Unable to find repositories for selected query.</div>',
                            'suggestion' => new JsExpression("Handlebars.compile('<div>{{value}}</div>')")
                        ]
                    ]
                ]
            ])->label(false); ?>
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
    <div id="refinePrice" class="panel-collapse collapse in" >
        <div class="panel-body">
            <?= $form->field($model, 'priceUnit')->dropDownList(\app\models\helpers\SelectData::priceUnits())->label(false) ?>
            <br />
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
<?php ActiveForm::end() ?>