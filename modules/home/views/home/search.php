<?php
use yii\helpers\Url;
use kartik\typeahead\Typeahead;
use \yii\bootstrap\ActiveForm;
use app\modules\item\widgets\GoogleAutoComplete;
/**
 * @var \app\modules\home\forms\Search $model
 */

\app\assets\AngularAsset::register($this);
\app\assets\JQueryTextRangeAsset::register($this)
?>

<div id="search-area" class="visible-sm visible-md visible-lg">
    <div class="row search-area">
        <div class="container">
            <div class="col-sm-12 col-md-9 col-md-offset-2">
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
                                        'suggestion' => new \yii\web\JsExpression("Handlebars.compile('{<div>{{value}}</div>}')")
                                    ]
                                ]
                            ]
                        ])->label(false); ?>
                        <div style="display: none">
                            <?= $form->field($model, 'location')->widget(GoogleAutoComplete::className(), [
                                'options' => [
                                    'class' => 'form-control location-input',
//                                    'ng-model' => 'searchCtrl.filter.location',
                                    'autocompleteName' => 'search'
                                ],
                                'autocompleteOptions' => [
                                    'types' => ['geocode']
                                ],
                                'name' => 'autoCompleteLocation'
                            ]); ?>
                        </div>
                    </div>

                    <div class="col-sm-3 col-md-4">
                        <?= \yii\bootstrap\Html::submitButton(Yii::t("item", "Search Nearby"),
                            ['class' => 'btn btn-danger btn-fill btn-wide']) ?>
                        <br>
                        <a href="">
                            <?= Yii::t("item", "Or for somewhere else") ?>
                        </a>
                    </div>

                    <?php
                    ActiveForm::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>