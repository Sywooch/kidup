<?php

use app\modules\item\components\MediaManager;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\Profile $profile
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
<h4><?= Yii::t("user", "Profile") ?>
    <br>
    <small><?= Yii::t("user", "How other people see you") ?></small>
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
    $settings['initialPreview'] = [
        Html::img(MediaManager::getUrl($model->getAttribute('img'), MediaManager::MEDIUM),
            ['class' => 'file-preview-image', 'title' => 'Profile Image']),
    ];
}
echo $form->field($model, 'img')->widget(FileInput::classname(), [
    'options' => ['multiple' => false, 'accept' => 'image/*'],
    'pluginOptions' => $settings,
    'language' => \Yii::$app->session->get('lang')
]); ?>

<?= \yii\helpers\Html::submitButton(Yii::t('user', 'Save'),
    ['class' => 'btn btn-primary btn-fill btn-lg']) ?>
<br/><br/>

<?php ActiveForm::end(); ?>
