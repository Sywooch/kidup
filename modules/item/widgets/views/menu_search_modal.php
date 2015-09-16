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
        'template' => "{input}"
    ],
    'action' => Url::to('@web/search'),
    'method' => 'get',
    'options' => [
        'style' => 'padding:15px;padding-top:80px;',
        'data-pjax' => 0
    ],
]) ?>

<?= $form->field($model, 'query')->widget(Typeahead::className(), [
    'options' => [
        'placeholder' => \Yii::t('home', 'What do you like to get your child?')
    ],
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
                'notFound' => '<div class="text-danger" style="padding:0 8px">'.\Yii::t('home', "No results, perhaps try Stroller, Trampoline or Toy?").'</div>',
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
$this->registerJS('$("#mobile-search").on("submit", function (event) {
    event.preventDefault();

    var vals = [];
    var val = $("#search-filter-query").val();
    if(val == ""){
        val = "Strollers";
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var geocoder = new google.maps.Geocoder;
            var latlng = {
                lat: parseFloat(position["coords"]["latitude"]),
                lng: parseFloat(position["coords"]["longitude"])
            };
            vals.push("search-filter[latitude]="+latlng.lat);
            vals.push("search-filter[longitude]="+latlng.lng);
            geocoder.geocode({"location": latlng}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results.length > 0) {
                        var result = results[0];
                        var location = result["formatted_address"];
                        vals.push("search-filter[location]=" + location);
                        window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
                    }
                }
            });
        });
    }else{
        window.location = event.currentTarget.action + "/" + val + "?" + vals.join("&");
    }
});');

?>
