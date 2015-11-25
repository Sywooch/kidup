<?php
use yii\bootstrap\Modal;

/**
 * @var \app\extended\web\View $this
 * @var \false|string $referral_user
 * @var \false|string $referral_user_image
 */

Modal::begin([
    'header' => $referral_user ? '<h2>' . \Yii::t('search.signup-modal.title_with_referral_user',
            "Join KidUp with {referral_user}!",
            ['referral_user' => $referral_user]) . '</h2>' : '<h2>' . \Yii::t('search.signup-modal.title',
            "Sign Up, Get Involved, Win!") . '</h2>',
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
<?php
$img = $referral_user_image ? $referral_user_image : 'kidup/logo/horizontal-no-text.png';
$settings = $referral_user_image ? ['w' => 100, 'q' => 100] : ['w' => 400, 'q' => 90];
$settings2 = $referral_user_image ? [
    'style' => 'margin-left:-350px;margin-top:-50px;margin-bottom:-140px;',
    'class' => 'hidden-xs hidden-sm'
] : [
    'style' => 'margin-left:-100px;margin-top:-32px;margin-bottom:-140px;',
    'class' => 'hidden-xs hidden-sm'
];

echo \images\components\ImageHelper::image($img, $settings, $settings2) ?>
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
