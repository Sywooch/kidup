<?php
use yii\helpers\Url;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use \yii\bootstrap\ActiveForm;

/**
 * @var \app\modules\home\forms\Search $model
 */

\app\assets\AngularAsset::register($this);

\app\modules\item\widgets\GoogleAutoComplete::widget(['options' => ['style' => 'display:none'], 'name' => 'x', 'autocompleteName' => 'x']);
?>

<div id="search-area" class="visible-sm visible-md visible-lg">
    <div class="row search-area">
        <div class="container">
            <div class="col-sm-12 col-md-11 col-md-offset-1">
                <div class="row">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => Url::to('search'),
                        'method' => 'get',
                        'id' => 'main-search'
                    ]);
                    ?>
                    <div class="col-sm-9 col-md-8">
                        <?php
                        $template = '<div><p class="repo-language">{{value}}</p>' .
                            '<p class="repo-name">{{name}}</p>' .
                            '<p class="repo-description">{{description}}</p></div>';
                        echo $form->field($model, 'query')->widget(Typeahead::className(), [
                            'options' => ['placeholder' => 'test'],
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
//                                    'limit' => 10,
                                    'display' => 'value',
                                    'templates' => [

                                        'notFound' => '<div class="text-danger" style="padding:0 8px">Unable to find repositories for selected query.</div>',
                                        'suggestion' => new \yii\web\JsExpression("Handlebars.compile('{$template}')")
                                    ]
                                ]
                            ]
                        ])->label(false); ?>
                        <span class="right"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                    <div class="col-sm-3 col-md-3">
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