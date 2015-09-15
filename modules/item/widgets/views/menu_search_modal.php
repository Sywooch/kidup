<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \app\modules\item\widgets\GoogleAutoComplete;
use \kartik\typeahead\Typeahead;

/**
 * @var \app\modules\search\forms\Filter $model
 */
\app\assets\FullModalAsset::register($this);

\yii\bootstrap\Modal::begin([
    'id' => 'searchModal',
    'options' => [
        'class' => 'modal modal-fullscreen force-fullscreen'
    ],
    'closeButton' => false,
    'header' => "<b>".\Yii::t('home', 'KidUp Search')."</b>"
]);

?>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    <i class="fa fa-close"></i>
</button>
<?php $form = ActiveForm::begin([
    'id' => 'mobile-search',
    'fieldConfig' => [
        'template' => "{input}\n{error}"
    ],
    'action' => Url::to('@web/search'),
    'method' => 'get',
    'options' => [
        'style' => 'padding:15px;padding-top:80px;'
    ]
]) ?>

<?= $form->field($model, 'query')->widget(Typeahead::className(), [
    'options' => ['placeholder' => \Yii::t('home', 'What do you like to get your child?')],
    'pluginOptions' => ['highlight' => true, 'hint' => true],
    'dataset' => [
        [
            'remote' => [
                'url' => Url::to('@web/search/auto-complete/index?q=%q'),
                'wildcard' => '%q'
            ],
            'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
            'limit' => 5,
            'display' => 'text',
            'templates' => [
                'notFound' => '<div class="text-danger" style="padding:0 8px">Unable to find repositories for selected query.</div>',
                'suggestion' => new \yii\web\JsExpression("Handlebars.compile('<div>{{text}}</div>')")
            ]
        ]
    ]
])->label(false); ?>

<?= $form->field($model, 'location')->widget(GoogleAutoComplete::className(), [
    'options' => [
        'class' => 'form-control location-input',
        'autocompleteName' => 'searchMobile'
    ],
    'autocompleteOptions' => [
        'types' => ['geocode']
    ],
    'name' => 'autoCompleteLocationMobileWidget'
]); ?>

<?= Html::submitButton(Yii::t('home', 'Search'), ['class' => 'btn btn-danger btn-fill btn-block']) ?>

<?php ActiveForm::end(); ?>

<?php
\yii\bootstrap\Modal::end();
?>
