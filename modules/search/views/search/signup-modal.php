<?php
use yii\bootstrap\Modal;

if (\Yii::$app->session->has('signup-attempts')) {
    $attempts = Yii::$app->session->get('signup-attempts') + 1;
} else {
    $attempts = 1;
}
Yii::$app->session->set('signup-attempts', $attempts);
if ($attempts > 6) {
    Yii::$app->session->set('stop-attempting-signup', $attempts);
}
/**
 * @var \app\extended\web\View $this
 */

Modal::begin([
    'header' => '<h2>' . \Yii::t('search.signup-modal.title', "Sign Up, Get Involved, Win!") . '</h2>',
    'toggleButton' => false,
    'id' => 'signup-search-modal',
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
    <br>
<?= Yii::t("search.signup-modal.already_a_member", "Already on KidUp? {link}Log in here!{linkOut}", [
    'link' => \yii\helpers\Html::beginTag('a', ['href' => \yii\helpers\Url::to('@web/user/login')]),
    'linkOut' => "</a>",
]) ?>
    <br>
<?= Yii::t("search.signup-modal.signup_with_email", "Or {link}signup with your email{linkOut}.", [
    'link' => \yii\helpers\Html::beginTag('a', ['href' => \yii\helpers\Url::to('@web/user/register')]),
    'linkOut' => "</a>",
]) ?>
    <br>

<?php

Modal::end();

$this->registerJs("$('#signup-search-modal').modal();");
