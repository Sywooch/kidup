<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\modules\user\helpers\SelectData;
use app\modules\user\models\Setting;
use kartik\checkbox\CheckboxX;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

/**
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model app\modules\user\models\SettingsForm
 */
?>

<?php $form = ActiveForm::begin([
    'id' => 'profile-form',
    'type' => ActiveForm::TYPE_VERTICAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}<br> {input} \n {error} {hint}",
        // 'labelOptions' => ['class' => 'control-label'],
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
]); ?>
<div class="row">
    <div class="col-md-12">
        <h4><?= Yii::t("user", "User") ?>
            <br>
            <small><?= Yii::t("user", "Your user settings") ?></small>
        </h4>
        <?= $form->field($model, 'email')->input(['class' => 'form-control']) ?>



        <?= $form->field($model, 'language')->widget(Select2::classname(), [
            'data' => SelectData::languages(),
            'options' => ['placeholder' => \Yii::t('user', 'Select a language')],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]); ?>
        <?= $form->field($model, 'currency_id')->widget(Select2::classname(), [
            'data' => SelectData::currencies(),
            'options' => [
                'placeholder' => \Yii::t('user', 'Select a currency'),
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
        <div class="form-group field-profile-description">
            <label class="control-label" for="profile-description"><?= Yii::t("user", "Phone number") ?></label>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'phone_country')->widget(Select2::classname(), [
                        'data' => SelectData::phoneCountries(),
                        'options' => ['placeholder' => \Yii::t('user', 'Select a country')],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ])->label(false); ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'phone_number')->label(false); ?>
                </div>
            </div>
        </div>
        <h4>
            <?= Yii::t("user", "Email settings") ?>
            <br>
            <small><?= \Yii::t('app', 'Notify me of the following:') ?></small>
        </h4>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <?php foreach (Setting::getEmailSettings() as $setting => $text) { ?>
                <div class="row">
                    <div class="col-md-1">
                        <?= CheckboxX::widget([
                            'model' => $model,
                            'attribute' => $setting,
                            'pluginOptions' => [
                                'threeState' => false,
                                'size' => 'lg'
                            ]
                        ]); ?>
                    </div>
                    <div class="col-md-8">
                        <?= $text ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?= \yii\helpers\Html::submitButton(Yii::t('user', 'Save'),
            ['class' => 'btn btn-primary btn-fill btn-lg']) ?>
        <br/><br/>

    </div>

</div>
<?php ActiveForm::end(); ?>
