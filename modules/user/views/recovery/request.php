<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \user\forms\Recovery $model
 */

$this->title = ucfirst(\Yii::t('user.recovery.title', 'Recover your password')) . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::USER;
?>
<section class="section container">

    <div class="row" id="login">
        <br/><br/>

        <div class="col-md-4 col-md-offset-4 card">
            <h4><?= \Yii::t('user.recovery.header', 'Recover your password') ?></h4>
            <small>
                <?= Yii::t("user.recovery.help_text",
                    "Enter your email address below and click continue, and we'll send you a recovery link.") ?>
            </small>
            <br/><br/>
            <?php $form = ActiveForm::begin([
                'id' => 'password-recovery-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ]); ?>

            <?= $form->field($model, 'email')->textInput([
                'autofocus' => true,
                'placeholder' => \Yii::t('user.attributes.email', 'Email')
            ])->label(false) ?>

            <?= Html::submitButton(Yii::t('user.recovery.continue_button', 'Continue'),
                ['class' => 'btn btn-danger btn-block']) ?><br>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>