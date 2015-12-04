<?php
use item\widgets\GoogleAutoComplete;
use kartik\typeahead\Typeahead;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \app\extended\web\View $this
 * @var \search\forms\Filter $model
 */
\app\assets\FullModalAsset::register($this);
\yii\bootstrap\Modal::begin([
    'id' => 'searchModal',
    'options' => [
        'class' => 'modal modal-fullscreen force-fullscreen'
    ],
    'closeButton' => false,
    'header' => "<b>".\Yii::t("item.mobile_search.header_name", 'KidUp Search')."</b>"
]);

$emptyLocation = \Yii::t('home.search.empty_location', 'Location: Near Me');
$this->registerJsVariables([
    'emptyLocation' => $emptyLocation,
]);
?>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    <i class="fa fa-close"></i>
</button>
<?php $form = ActiveForm::begin([
    'id' => 'mobile-search',
    'fieldConfig' => [
        'template' => "{input}"
    ],
    'action' => Url::to('@web/search', true),
    'method' => 'get',
    'options' => [
        'style' => 'padding:15px;padding-top:80px;',
        'data-pjax' => 0
    ],
]) ?>

<?//= $form->field($model, 'query')->widget(Typeahead::className(), [
//    'options' => [
//        'placeholder' => \Yii::t("item.mobile_search.placeholder", 'What do you like to get your child?')
//    ],
//    'pluginOptions' => ['highlight' => true, 'hint' => true],
//    'dataset' => [
//        [
//            'remote' => [
//                'url' => Url::to('@web/search/auto-complete/index?q=%q'),
//                'wildcard' => '%q'
//            ],
//            'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
//            'limit' => 5,
//            'display' => 'text',
//            'templates' => [
//                'notFound' => '<div class="text-danger" style="padding:0 8px">'.
//                    \Yii::t("item.mobile_search.no_results",
//                        "No results, perhaps try Stroller, Trampoline or Toy?").'</div>',
//                'suggestion' => new \yii\web\JsExpression("Handlebars.compile('<div>{{text}}</div>')")
//            ]
//        ]
//    ]
//])->label(false); ?>

<?//= $form->field($model, 'location')->widget(GoogleAutoComplete::className(), [
//    'options' => [
//        'class' => 'form-control location-input',
//        'placeholder' => \Yii::t("home.search.location_placeholder", 'Location e.g. Copenhagen'),
//        'autocompleteName' => 'menu-search-modal',
//        'id' => 'search-modal-location',
//        'value' => $emptyLocation
//    ],
//    'autocompleteOptions' => [
//        'types' => ['geocode']
//    ],
//    'name' => 'autoCompleteLocationMenuSearch'
//])->label(false); ?>

<?= Html::submitButton(Yii::t("item.mobile_search.search_button", 'Search'), [
    'class' => 'btn btn-danger btn-fill btn-block',
    'id' => 'menu-search-submit-button'
]) ?>

<?php ActiveForm::end(); ?>

<?php
\yii\bootstrap\Modal::end();
?>
