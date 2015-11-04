<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

\yii\bootstrap\Modal::begin([
    'id' => 'loginModal',
    'options' => [
        'class' => 'modal modal-small'
    ]
]);

?>

<div class="modal-body">
    <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
        'baseAuthUrl' => ['/user/security/auth'],
        'options' => ['style' => 'overflow:none'], // needed to counter some yii stuff
    ]) ?>

    <div class="social-area">
        <?php foreach ($authAuthChoice->getClients() as $client): ?>
            <?php $authAuthChoice->clientLink($client,
                '<i class="fa fa-' . strtolower($client->getTitle()) . '-square"></i> ' .
                \Yii::t('user.login.login_social', 'Login with {socialNetwork}', ['socialNetwork' => $client->getTitle()])
                , ['class' => 'btn btn-fill btn-social btn-' . strtolower($client->getTitle())]) ?>
        <?php endforeach; ?>
    </div>

    <?php \yii\authclient\widgets\AuthChoice::end() ?>

    <span class="divider"> <?= Yii::t("user.login.or_login_options_devider", "or") ?> </span>

    <?php $form = ActiveForm::begin([
        'id' => 'login-widget-form',
        'fieldConfig' => [
            'template' => "{input}\n{error}"
        ],
        'action' => $action
    ]) ?>

    <?= $form->field($model, 'login')->textInput(['placeholder' => \Yii::t('user.login.email_placeholder', 'Email')]) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => \Yii::t('user.login.password_placeholder', 'Password')]) ?>

    <?= $form->field($model, 'rememberMe')->checkbox(['style' => 'margin-left:-15px']) ?>

    <?= \yii\helpers\Html::submitButton(Yii::t('user.login.login_button', 'Login'), ['class' => 'btn btn-danger btn-fill btn-block']) ?>
    <?php ActiveForm::end(); ?>
</div>
<div class="modal-footer">
    <!-- this dismisses the current modal and calls the register modal-->
    <div data-dismiss="modal">
        <button class="btn btn-simple" data-toggle="modal" data-target="#registerModal">
            <span class="text-muted"><a id="ChangeToRegisterModal"><?= Yii::t("user.login.new_to_kidup_link", "New to KidUp?") ?></a></span>
        </button>
    </div>
    <span class="text-muted"><a href="<?= Url::to('@web/user/recovery/request') ?>">
            <?= Yii::t("user.login.forgot_password_link", "Forgot your password?") ?></a></span>
</div>
<?php

\yii\bootstrap\Modal::end();
?>
