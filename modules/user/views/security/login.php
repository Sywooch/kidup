<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\LoginForm $model
 * @var app\modules\user\Module $module
 */

$this->title = ucfirst(\Yii::t('title', 'Log In')) . ' - ' . Yii::$app->name;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<section class="section container">
    <div id="login" class="row">
        <br/><br/>

        <div class="col-md-4 col-md-offset-4 card col-sm-8 col-sm-offset-2">
            <h3>
                <center><?= Yii::t('user', "Login to KidUp") ?></center>
            </h3>
            <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
                'baseAuthUrl' => ['/user/security/auth'],
                'options' => ['style' => 'overflow:none'] // needed to counter some yii stuff
            ]) ?>

            <div class="social-area">
                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <?php $authAuthChoice->clientLink($client, '<i class="fa fa-'.strtolower($client->getTitle()).'-square"></i> '.
                        \Yii::t('user', 'Login with {socialNetwork}', ['socialNetwork' => $client->getTitle()])
                        , ['class' => 'btn btn-fill btn-social btn-'.strtolower($client->getTitle())]) ?>
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
            ])->passwordInput()->label(Yii::t('user', 'Password') . ' (' . Html::a(Yii::t('user',
                    'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']) . ')') ?>

            <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>

            <?= Html::submitButton(Yii::t('user', 'Sign in'),
                ['class' => 'btn btn-danger btn-fill', 'tabindex' => '3']) ?>

            <?php ActiveForm::end(); ?>
            <hr/>
            <div class="text-center">
                <?= Html::a(Yii::t('user', 'New to KidUp?'), ['/user/registration/register']) ?>
            </div>
            <br/>
        </div>
    </div>
</section>
