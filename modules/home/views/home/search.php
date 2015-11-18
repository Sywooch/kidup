<?php
use app\helpers\ViewHelper;
use item\widgets\GoogleAutoComplete;
use kartik\typeahead\Typeahead;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/**
 * @var \app\extended\web\View $this
 * @var \home\forms\Search $model
 * @var \item\models\Category $defaultCategory
 */

$emptyLocation = \Yii::t('home.search.empty_location', 'Location: Near Me');
$emptySearch = $defaultCategory->getTranslatedName();
$this->registerJsVariables([
    'emptyLocation' => $emptyLocation,
    'emptySearch' => $emptySearch
]);
?>

<div id="search-area" class="hidden-sm visible-md visible-lg">
    <div class="row search-area">
        <div class="container">
            <h3 style="text-align: center;font-weight: 300;">
                <?= Yii::t("home.search_header", "Search your child need") ?>
            </h3>
            <div class="col-sm-12 col-md-10 col-md-offset-1">
                <div class="row">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => Url::to('search'),
                        'method' => 'get',
                        'id' => 'main-search'
                    ]);
                    ?>
                    <div class="col-sm-9 col-md-6">
                        <?= $form->field($model, 'query')->widget(Typeahead::className(), [
                            'options' => [
                                'placeholder' => \Yii::t("home.search.placeholder_suggestion", 'e.g. {0}', [
                                    $emptySearch
                                ])
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
                                        'notFound' => '<div class="text-danger" style="padding:0 8px">' .
                                            \Yii::t("home.search.empty_results",
                                                "We couldn't find that, perhaps try Stroller, Trampoline or Toy?") . '</div>',
                                        'suggestion' => new \yii\web\JsExpression("Handlebars.compile('<div>{{text}}</div>')")
                                    ]
                                ],

                            ]
                        ])->label(false); ?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model, 'location')->widget(GoogleAutoComplete::className(), [
                            'options' => [
                                'class' => 'form-control location-input',
                                'placeholder' => \Yii::t("home.search.location_placeholder",
                                    'Location e.g. Copenhagen'),
                                'autocompleteName' => 'home-search',
                                'value' => $emptyLocation
                            ],
                            'autocompleteOptions' => [
                                'types' => ['geocode']
                            ],
                            'name' => 'autoCompleteLocationHome'
                        ])->label(false); ?>
                    </div>

                    <div class="col-sm-3 col-md-2">
                        <?= \yii\bootstrap\Html::submitButton(Yii::t("home.search.search_button", "Search"),
                            [
                                'class' => 'btn btn-danger btn-fill btn-wide',
                                'onclick' => ViewHelper::trackClick('home.click_search', null, false)
                            ]) ?>
                    </div>

                    <?php
                    ActiveForm::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>