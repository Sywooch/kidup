<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\models\helpers\SelectData;
use \user\models\Setting;
use kartik\checkbox\CheckboxX;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

/**
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model \user\forms\Settings
 */
?>

<?php $form = ActiveForm::begin([
    'id' => 'profile-form',
    'type' => ActiveForm::TYPE_VERTICAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}{input} \n {error} {hint}",
        // 'labelOptions' => ['class' => 'control-label'],
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
]); ?>
<div class="row">
    <div class="col-md-12">
        <h4><?= Yii::t("user.settings.account.header", "User") ?>
            <br>
            <small><?= Yii::t("user.settings.account.sub_header", "Your user settings") ?></small>
        </h4>
        <?= $form->field($model, 'email')->input(['class' => 'form-control']) ?>

        <?= $form->field($model, 'language')->widget(Select2::classname(), [
            'data' => SelectData::languages(),
            'options' => ['placeholder' => \Yii::t('user.settings.account.select_language_placeholder', 'Select a language')],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ])->label(\Yii::t('user.settings.account.language_label', 'Language')); ?>

        <?= $form->field($model, 'currency_id')->widget(Select2::classname(), [
            'data' => SelectData::currencies(),
            'options' => [
                'placeholder' => \Yii::t('user.settings.account.select_country_placeholder', 'Select a currency'),
            ],
            'pluginOptions' => [
                'allowClear' => false
            ],
        ])->label(\Yii::t('user.settings.account.currency', 'Currency')); ?>
        <div class="form-group field-profile-description">
            <label class="control-label" for="profile-description">
                <?= Yii::t("user.settings.account.phone_number", "Phone number") ?>
            </label>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'phone_country')->widget(Select2::classname(), [
                        'data' => SelectData::phoneCountries(),
                        'options' => ['placeholder' => \Yii::t('user.settings.account.country_code_phone', 'Country code')],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ])->label(false); ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'phone_number')->label(false)->textInput([
                        'placeholder' => \Yii::t('user.settings.account.phone_number_placeholder', 'e.g. 26415315')
                    ]); ?>
                </div>
            </div>
        </div>
        <h4>
            <?= Yii::t("user.settings.account.email_settings", "Email settings") ?>
            <br>
            <small><?= \Yii::t('user.settings.account.notify_me_of', 'Notify me of the following:') ?></small>
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
        <?= \yii\helpers\Html::submitButton(Yii::t('user.settings.account.save', 'Save'),
            ['class' => 'btn btn-primary btn-fill btn-lg']) ?>
        <br/><br/>

    </div>

</div>
<?php ActiveForm::end(); ?>
