<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\models\helpers\SelectData;
use kartik\widgets\Select2;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model app\modules\user\forms\Settings
 */
?>
<?php $form = ActiveForm::begin([
    'id' => 'location-form',
    'options' => ['class' => 'form-vertical'],
    'fieldConfig' => [
        'template' => "{label}\n<br>{input} \n {error}\n{hint}",
//                                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
]); ?>
<div class="row">
    <div class="col-md-12">
        <h4><?= Yii::t("user", "Billing address") ?>
            <br>
            <small><?= Yii::t("user", "Address that is used for payment purposes.") ?></small>
        </h4>

        <br/>


        <?= $form->field($model, 'country')->widget(Select2::classname(), [
            'data' => SelectData::nationality(),
            'options' => ['placeholder' => 'Select a country'],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
        <?= $form->field($model, 'city') ?>
        <?= $form->field($model, 'zip_code') ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'street_name') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'street_number') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'street_suffix') ?>
            </div>
        </div>
    </div>
</div>
<div class="row col-md-12">
    <?php
    if ($model->latitude == 0 || $model->longitude == 0) {
        if (isset($model->street_name) && isset($model->street_number)) {
            echo Alert::widget([
                'options' => [
                    'class' => 'alert-info',
                ],
                'body' => 'We couldn\'t find that adress, are you sure it\'s correct?'
            ]);
        }
    } else {
//        echo \app\widgets\Map::widget([
//            'longitude' => $model->longitude,
//            'latitude' => $model->latitude,
//        ]);
    }
    ?>
</div>
<br/><br/>
<div class="row save">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('user', 'Save'),
            ['class' => 'btn btn-primary btn-fill btn-lg']) ?>
    </div>
</div>
<br/><br/>
