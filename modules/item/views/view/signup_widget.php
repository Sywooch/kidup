<?php

/**
 * @var \app\extended\web\View $this
 * @var \user\models\User $owner
 */

$this->registerJs(<<<JS
$(".signup-widget > .header").click(function(){
    $(".signup-widget > .signup").slideToggle(500);
});
JS
);
?>

<div class="signup-widget">
    <div class="header">
        <?= Yii::t("item.view.signup_widget.header", "Do like {owner} & earn some money!",[
            'owner' => $owner->profile->first_name
        ]) ?>
        <br>
        <i class="fa fa-angle-down"></i>
    </div>
    <div class="signup">
        <?= Yii::t("item.view.signup_widget.explanation", "Follow other KidUp users: rent out your unused children equipment and earn a little extra!",[
            'owner' => $owner->profile->first_name
        ]) ?>
        <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
            'baseAuthUrl' => ['/user/security/auth'],
            'options' => ['style' => 'overflow:none'] // needed to counter some yii stuff
        ]) ?>
        <br>
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
    </div>
</div>