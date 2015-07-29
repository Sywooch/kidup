<?php
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;
?>

<div id="search-area" class="visible-sm visible-md visible-lg">
    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['/search']]); ?>

    <div class="row search-area">
        <div class="container">
            <div class="col-sm-12 col-md-11 col-md-offset-1">
                <div class="row">
                    <div class="col-sm-9 col-md-8">
                        <?= $form->field($model, 'query')->input('text', [
                            'class' => 'form-control',
                            'placeholder' => Yii::t('app', 'What are you looking for?')
                        ])->label(false) ?>
                        <span class="right"><i class="glyphicon glyphicon-search"></i></span>
                    </div>
                    <div class="col-sm-3 col-md-3">
                        <?= Html::submitButton(\Yii::t('app', "Search"),
                            ['class' => 'btn btn-danger btn-fill btn-wide']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>