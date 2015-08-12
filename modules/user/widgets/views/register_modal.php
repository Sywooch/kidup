<?php
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\modules\images\components\ImageHelper;

\yii\bootstrap\Modal::begin([
    'id' => 'registerModal',
    'options' => [
        'class' => 'modal modal-small'
    ]
]); ?>

    <!--register-->
    <div class="modal-header">
        <h2 class="modal-title text-center"><?= Yii::t("user", "Register") ?></h2>
    </div>
    <div class="modal-body">
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
            <?php endforeach; ?>
        </div>

        <?php \yii\authclient\widgets\AuthChoice::end() ?>

        <span class="divider"> <?= Yii::t("user", "or") ?> </span>

        <?php $form = ActiveForm::begin([
            'id' => 'registration-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'action' => ['/user/register']
        ]); ?>

        <?= $form->field($model, 'email')->input('text',
            ['class' => "form-control", 'placeholder' => "Email"])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'class' => "form-control",
            'placeholder' => "Password"
        ])->label(false) ?>

        <?= Html::submitButton(Yii::t('user', 'Register'), ['class' => 'btn btn-danger btn-fill btn-block']) ?>

        <?php ActiveForm::end(); ?>

    </div>
    <div class="modal-footer">
        <!-- this dismisses the current modal and calls the login modal-->
        <div data-dismiss="modal">
            <button class="btn btn-simple" data-toggle="modal" data-target="#loginModal">
                <span class="text-muted"><a><?= Yii::t("user", "Already have an account?") ?></a></span>
            </button>
        </div>
    </div>

<?php

\yii\bootstrap\Modal::end();
?>