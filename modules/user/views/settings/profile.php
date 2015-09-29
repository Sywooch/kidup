<?php

use \images\components\ImageHelper;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \user\models\Profile $profile
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
<h4><?= Yii::t("user.settings.profile.header", "Profile") ?>
    <br>
    <small><?= Yii::t("user.settings.profile.sub_header", "How other people see you") ?></small>
</h4>
<?= $form->field($model, 'first_name') ?>
<?= $form->field($model, 'last_name') ?>
<?= $form->field($model, 'description')->textarea() ?>

<?= $form->field($model, 'birthday')->widget(\yii\widgets\MaskedInput::className(), [
    'clientOptions' => ['alias' => 'dd-mm-yyyy'],
    'options' => ['placeholder' => 'dd-mm-yyyy', 'class' => 'form-control']
]); ?>

<?php
$settings = [
    'previewFileType' => 'image',
    'overwriteInitial' => true
];
if ($model->getAttribute('img') !== null) {
    $settings['initialPreview'] = ImageHelper::img($model->getAttribute('img'),
        ['q' => 70, 'w' => 200, 'h' => 200, 'fit' => 'crop'],
        ['class' => 'file-preview-image', 'title' => 'Profile Image']);
}
echo $form->field($model, 'img')->widget(FileInput::classname(), [
    'options' => ['multiple' => false, 'accept' => 'image/*'],
    'pluginOptions' => $settings,
    'language' => \Yii::$app->session->get('lang')
]); ?>

<?= \yii\helpers\Html::submitButton(Yii::t('user.settings.profile.save_button', 'Save'),
    ['class' => 'btn btn-primary btn-fill btn-lg']) ?>
<br/><br/>

<?php ActiveForm::end(); ?>
