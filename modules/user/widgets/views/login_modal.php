<?php
use \yii\bootstrap\ActiveForm;
use \yii\bootstrap\Html;
use \yii\helpers\Url;

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
                \Yii::t('user', 'Login with {socialNetwork}', ['socialNetwork' => $client->getTitle()])
                , ['class' => 'btn btn-fill btn-social btn-' . strtolower($client->getTitle())]) ?>
        <?php endforeach; ?>
    </div>

    <?php \yii\authclient\widgets\AuthChoice::end() ?>

    <span class="divider"> <?= Yii::t("user", "or") ?> </span>

    <?php $form = ActiveForm::begin([
        'id' => 'login-widget-form',
        'fieldConfig' => [
            'template' => "{input}\n{error}"
        ],
        'action' => $action
    ]) ?>

    <?= $form->field($model, 'login')->textInput(['placeholder' => 'Email']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>

    <?= $form->field($model, 'rememberMe')->checkbox(['style' => 'margin-left:-15px']) ?>

    <?= \yii\helpers\Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-danger btn-fill btn-block']) ?>
    <?php ActiveForm::end(); ?>
</div>
<div class="modal-footer">
    <!-- this dismisses the current modal and calls the register modal-->
    <div data-dismiss="modal">
        <button class="btn btn-simple" data-toggle="modal" data-target="#registerModal">
            <span class="text-muted"><a id="ChangeToRegisterModal"><?= Yii::t("user", "New on KidUp?") ?></a></span>
        </button>
    </div>
    <span class="text-muted"><a href="<?= Url::to('@web/user/recovery/request') ?>"><?= Yii::t("user",
                "Forgot your password?") ?></a></span>
</div>
<?php

\yii\bootstrap\Modal::end();
?>
