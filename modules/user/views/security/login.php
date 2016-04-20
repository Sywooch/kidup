<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \app\components\view\View $this
 * @var \user\forms\Login $model
 * @var \user\Module $module
 */

$this->title = ucfirst(\Yii::t('user.login.title', 'Log In')) . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::USER;
?>

<section class="section container">
    <div id="login" class="row">
        <br/><br/>

        <div class="col-md-4 col-md-offset-4 card col-sm-8 col-sm-offset-2">
            <h3>
                <div style="text-align: center;"><?= Yii::t('user.login.header', "Login to KidUp") ?></div>
            </h3>
            <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
                'baseAuthUrl' => ['/user/security/auth'],
                'options' => ['style' => 'overflow:none'] // needed to counter some yii stuff
            ]) ?>

            <div class="social-area">
                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <?php $authAuthChoice->clientLink($client,
                        '<i class="fa fa-' . strtolower($client->getTitle()) . '-square"></i> ' .
                        \Yii::t('user.login.login_with_social_account', 'Login with {socialNetwork}',
                            ['socialNetwork' => $client->getTitle()])
                        , ['class' => 'btn btn-fill btn-social btn-' . strtolower($client->getTitle())]) ?>
                    <br/><br/>
                <?php endforeach; ?>
            </div>
            <?php \yii\authclient\widgets\AuthChoice::end(); ?>
            <hr/>
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false,
                'validateOnBlur' => false,
                'validateOnType' => false,
                'validateOnChange' => false,
            ]) ?>

            <?= $form->field($model, 'login',
                ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]) ?>

            <?= $form->field($model, 'password', [
                'inputOptions' => [
                    'class' => 'form-control',
                    'tabindex' => '2'
                ]
            ])->passwordInput()->label(Yii::t('user.login.password',
                    'Password') . ' (' . Html::a(Yii::t('user.login.forgot_password_link',
                    'Forgot your password?'), ['/user/recovery/request'], ['tabindex' => '5']) . ')') ?>

            <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

            <?= Html::submitButton(Yii::t('user.login.sign_in_button', 'Sign in'),
                ['class' => 'btn btn-danger btn-fill', 'tabindex' => '3']) ?>

            <?php ActiveForm::end(); ?>
            <hr/>
            <div class="text-center">
                <?= Html::a(Yii::t('user.login.new_to_kidup_link', 'New to KidUp?'), ['/user/registration/register']) ?>
            </div>
            <br/>
        </div>
    </div>
</section>
