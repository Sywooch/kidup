<?php
use yii\bootstrap\Modal;

/**
 * @var \app\extended\web\View $this
 */

Modal::begin([
    'header' => '<h2>' . \Yii::t('search.signup-modal.title', "Sign Up, Get Involved, Win!") . '</h2>',
    'toggleButton' => false,
    'id' => 'signup-conversion-modal',
]);

?>
    <div class="modalSignupText">
        <?= Yii::t("search.signup-modal.why_signup_text",
            "Share the products loved by your children and discover new ones! Join KidUp today and win a trip to legoland for the whole family every month!") ?>
    </div>
<?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
    'baseAuthUrl' => ['/user/security/auth'],
    'options' => ['style' => 'overflow:none'] // needed to counter some yii stuff
]) ?>
<?= \images\components\ImageHelper::image('kidup/logo/horizontal-no-text.png',
    ['w' => 400, 'q' => 90],
    ['style' => 'margin-left:-100px;margin-top:-40px;margin-bottom:-140px;', 'class' => 'hidden-xs hidden-sm']) ?>
    <div class="social-area">
        <?php foreach ($authAuthChoice->getClients() as $client): ?>
            <?php $authAuthChoice->clientLink($client,
                '<i class="fa fa-' . strtolower($client->getTitle()) . '-square"></i> ' .
                \Yii::t('user.register.register_with_social', 'Register with {socialNetwork}',
                    ['socialNetwork' => $client->getTitle()])
                , [
                    'class' => 'btn btn-fill btn-social btn-' . strtolower($client->getTitle()),
                    'onclick' => \app\helpers\ViewHelper::trackClick("signup_modal.click_facebook", null, false)
                ]) ?>
        <?php endforeach; ?>
    </div>
<?php \yii\authclient\widgets\AuthChoice::end() ?>
    <br><br><br><br><br>
<?= Yii::t("search.signup-modal.already_a_member", "Already on KidUp? {link}Log in here!{linkOut}", [
    'link' => \yii\helpers\Html::beginTag('a',
        ['href' => "#", "data-dismiss" => "modal", "data-toggle" => "modal", "data-target" => "#loginModal"]),
    'linkOut' => "</a>",
]) ?>
    <br>
<?= Yii::t("search.signup-modal.signup_with_email", "Or {link}signup with your email{linkOut}.", [
    'link' => \yii\helpers\Html::beginTag('a',
        ['href' => "#", "data-dismiss" => "modal", "data-toggle" => "modal", "data-target" => "#registerModal"]),
    'linkOut' => "</a>",
]) ?>
    <br>

<?php

Modal::end();
if ($autoOpen) {
    $this->registerJs("$('#signup-conversion-modal').modal();");
}
