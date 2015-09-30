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

$this->title = ucfirst(\Yii::t('user.password_reset.title', 'reset your password')) . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::USER;
?>
<section class="section container">

    <div class="row" id="login">
        <br/><br/>

        <div class="col-md-4 col-md-offset-4 card">
            <h4><?= Yii::t("user.password_reset.reset_kidup_password", "Reset your KidUp password") ?></h4>
            <?php $form = ActiveForm::begin([
                'id' => 'password-recovery-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ]); ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => \Yii::t('user.password_reset.new_password', 'New Password')
            ])->label(false) ?>
            <br/><br/>

            <?= Html::submitButton(Yii::t('user.password_reset.finish_button', 'Finish'), ['class' => 'btn btn-danger btn-block']) ?><br>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>
