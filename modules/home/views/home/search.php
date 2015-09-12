<?php
use yii\helpers\Url;
use kartik\typeahead\TypeaheadBasic;
use kartik\typeahead\Typeahead;
use \yii\bootstrap\ActiveForm;

/**
 * @var \app\modules\home\forms\Search $model
 */

\app\assets\AngularAsset::register($this);
//<?= $form->field($model, 'query')->widget(Typeahead::className(), [
//    'options' => ['placeholder' => 'test'],
//    'pluginOptions' => ['highlight' => true],
//    'dataset' => [
//        [
//            'local' => ['On the road - Stroller','Toys'],
//            'limit' => 10
//        ]
//    ]
//])->label(false);
?>

<div id="search-area" class="visible-sm visible-md visible-lg">
    <div class="row search-area">
        <div class="container">
            <div class="col-sm-12 col-md-11 col-md-offset-1">
                <div class="row">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => Url::to('search'),
                        'method' => 'get'
                    ]);
                    ?>
                    <div class="col-sm-9 col-md-8">
                        <?= $form->field($model, 'query')->input([
                            'options' => ['placeholder' => 'E.g. Stroller'],
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