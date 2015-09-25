<?php
use yii\helpers\Url;
use kartik\typeahead\Typeahead;
use \yii\bootstrap\ActiveForm;
use app\modules\item\widgets\GoogleAutoComplete;

/**
 * @var \app\modules\home\forms\Search $model
 */

$emptyLocation = \Yii::t('search', 'Location: Near Me');
$emptySearch = \Yii::t('categories_and_features', 'Baby Toys');
$this->registerJs("window.emptyLocation='{$emptyLocation}';window.emptySearch='{$emptySearch}';");
?>

<div id="search-area" class="hidden-sm visible-md visible-lg">
    <div class="row search-area">
        <div class="container">
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
                            'options' => ['placeholder' => \Yii::t('home', 'e.g. {0}',[
                                $emptySearch
                            ])],
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
                                        'notFound' => '<div class="text-danger" style="padding:0 8px">'.
                                            \Yii::t('home', "We couldn't find that, perhaps try Stroller, Trampoline or Toy?").'</div>',
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
                                'placeholder' => \Yii::t('search', 'Location e.g. Copenhagen'),
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
                        <?= \yii\bootstrap\Html::submitButton(Yii::t("item", "Search"),
                            ['class' => 'btn btn-danger btn-fill btn-wide']) ?>
                    </div>

                    <?php
                    ActiveForm::end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>