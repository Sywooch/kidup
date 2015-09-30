<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\helpers\SelectData;
use kartik\widgets\Select2;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model \user\forms\Settings
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
        <h4><?= Yii::t("user.settings.address.header", "Billing address") ?>
            <br>
            <small><?= Yii::t("user.settings.address.sub_header", "Address that is used for payment purposes.") ?></small>
        </h4>

        <br/>


        <?= $form->field($model, 'country')->widget(Select2::classname(), [
            'data' => SelectData::nationality(),
            'options' => ['placeholder' => \Yii::t('user.settings.address.select_country_placeholder', 'Select a Country')],
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
                'body' => \Yii::t('user.settings.address.not_found', "We couldn't find that adress, are you sure it's correct?")
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
        <?= Html::submitButton(Yii::t('user.settings.address.save_button', 'Save'),
            ['class' => 'btn btn-primary btn-fill btn-lg']) ?>
    </div>
</div>
<br><br>
