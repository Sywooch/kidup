<?php
use yii\widgets\ActiveForm;

\yii\bootstrap\Modal::begin([
    'id' => 'registerModal',
    'options' => [
        'class' => 'modal modal-small'
    ]
]); ?>

    <!--register-->
    <div class="modal-header">
        <h2 class="modal-title text-center"><?= Yii::t("user.register.header", "Register") ?></h2>
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
                    \Yii::t('user.register.register_with_social', 'Register with {socialNetwork}',
                        ['socialNetwork' => $client->getTitle()])
                    , ['class' => 'btn btn-fill btn-social btn-' . strtolower($client->getTitle())]) ?>
            <?php endforeach; ?>
        </div>

        <?php \yii\authclient\widgets\AuthChoice::end() ?>

        <span class="divider"> <?= Yii::t("user.register.register_options_devider", "or") ?> </span>


        <?php $form = ActiveForm::begin([
            'id' => 'registration-form',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'action' => ['/user/register']
        ]); ?>

        <?= $form->field($model, 'email')->input('text',
            [
                'class' => "form-control",
                'placeholder' => \Yii::t('user.register.email_placeholder', 'Email')
            ])->label(false) ?>


        <?= $form->field($model, 'password')->passwordInput([
            'class' => "form-control",
            'placeholder' => \Yii::t('user.register.password_paceholder', "Password")
        ])->label(false) ?>

        <?= \yii\helpers\Html::submitButton(Yii::t('user.register.register_button', 'Register'),
            ['class' => 'btn btn-danger btn-fill btn-block']) ?>

        <?php ActiveForm::end(); ?>

    </div>
    <div class="modal-footer">
        <!-- this dismisses the current modal and calls the login modal-->
        <div data-dismiss="modal">
            <button class="btn btn-simple" data-toggle="modal" data-target="#loginModal">
                <span class="text-muted"><a><?= Yii::t("user.register.already_have_account_link", "Already have an account?") ?></a></span>
            </button>
        </div>
    </div>

<?php

\yii\bootstrap\Modal::end();
?>