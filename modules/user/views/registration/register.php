<?php

use app\components\ViewHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\modules\user\models\User $user
 * @var app\modules\user\Module $module
 */

$this->title = ViewHelper::getPageTitle(\Yii::t('title', 'Sign up'));
$this->assetPackage = \app\assets\Package::USER;
?>

<section class="section container">
    <div id="login" class="row">
        <br/><br/>

        <div class="col-md-4 col-md-offset-4 card col-sm-8 col-sm-offset-2">
            <h3>
                <center><?= Yii::t('user', "Register on KidUp") ?></center>
            </h3>
            <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
                'baseAuthUrl' => ['/user/security/auth'],
                'options' => ['style' => 'overflow:none'] // needed to counter some yii stuff
            ]) ?>

            <div class="social-area">
                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <?php $authAuthChoice->clientLink($client,
                        '<i class="fa fa-' . strtolower($client->getTitle()) . '-square"></i> ' .
                        \Yii::t('user', 'Login with {socialNetwork}', ['socialNetwork' => $client->getTitle()])
                        , ['class' => 'btn btn-fill btn-social btn-' . strtolower($client->getTitle())]) ?>
                    <br/><br/>
                <?php endforeach; ?>
            </div>
            <?php \yii\authclient\widgets\AuthChoice::end(); ?>
            <hr/>
            <?php $form = ActiveForm::begin([
                'id' => 'registration-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ]); ?>

            <?= $form->field($model, 'email') ?>

            <?php if ($module->enableGeneratingPassword == false): ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
            <?php endif ?>

            <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-danger btn-fill']) ?>

            <?php ActiveForm::end(); ?>
            <hr/>
            <div class="text-center">
                <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
            </div>
            <br/>
        </div>
    </div>
</section>