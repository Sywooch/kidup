<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\modules\user\widgets\Connect;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */

?>


<div class="row">
    <div class="col-md-12">
        <h4><?= Yii::t("user", "Trust and verification") ?>
            <br>
            <small><?= Yii::t("user", "Verify in multiple ways to built trust on KidUp!") ?></small>
        </h4>
        <div class="form-group">

            <label><?= Yii::t("user", "Email") ?></label>

            <div class="row">
                <div class="col-md-6">
                    <?= Yii::t("user",
                        "Your email is never shared, and only used by KidUp for secure communication.") ?>
                </div>

                <div class="col-md-6">
                    <?php echo $profile->email_verified == 1 ? '
                                        <button class="btn btn-primary btn-fill" disabled><i class="fa fa-check"></i>
                                            ' . $profile->user->email . '
                                        </button>'
                        : Html::a(Html::button(\Yii::t('user', 'Resend confirmation email'),
                            ['class' => 'btn btn-primary']),
                            ['/user/settings/verification', 'confirm_email' => true]); ?>
                </div>
            </div>
        </div>
        <br/>
        <?php $auth = Connect::begin([
            'baseAuthUrl' => ['/user/settings/connect'],
            'accounts' => $user->accounts,
            'autoRender' => false,
            'popupMode' => false
        ]) ?>
        <?php foreach ($auth->getClients() as $client): ?>
            <label><?= $client->getTitle() ?></label>

            <div class="row">
                <div class="col-md-6">
                    <?= Yii::t("user",
                        "Adding {0} as social network account enables us and others to verify you.",
                        [$client->getTitle()]) ?>
                </div>
                <div class="col-md-6">
                    <?= $auth->isConnected($client) ?
                        '<button class="btn btn-social btn-' . strtolower($client->getTitle())
                        . '" disabled><i class="fa fa-' . strtolower($client->getTitle()) . '"></i>
                                                Approved
                                            </button>' :
                        Html::a(Html::button('<i class="fa fa-' . strtolower($client->getTitle()) . '"></i>' .
                            Yii::t('user', 'Connect with ' . $client->getTitle()),
                            ['class' => 'btn btn-social btn-' . strtolower($client->getTitle())]),
                            $auth->createClientUrl($client)
                        )
                    ?>
                    <br/>
                </div>
            </div>
        <?php endforeach; ?>
        <?php Connect::end() ?>

        <!--Phone vericifation-->
        <div class="form-group">
            <label><?= Yii::t("user", "Phone") ?></label>

            <div id="phone-verification-area" class="row">
                <div class="col-md-6">
                    <?= Yii::t("user",
                        "Rest assured, your number is only shared with another KidUp user once you have a confirmed booking.") ?>
                </div>
                <div class="col-md-6">
                    <?php
                    if (!$profile->getPhoneNumber()) {
                        echo Yii::t('user', 'Please set a phone number');
                    } elseif (!$profile->isValidPhoneNumber()) {
                        echo Yii::t('user', 'Your phone number appears to be invalid');
                    } else {
                        if (!$profile->phone_verified) {
                            echo $profile->getPhoneNumber();
                        }
                    } ?>
                    <br/>
                    <?php
                    if ($profile->getPhoneNumber() && $profile->isValidPhoneNumber()) {
                        if ($profile->phone_verified) {
                            echo '<button class="btn btn-primary btn-fill" disabled><i class="fa fa-check"></i>
                                                        ' . $profile->getPhoneNumber() . '
                                                    </button>';
                        } else {
                            echo Html::a(Html::button(Yii::t('user', 'Confirm'),
                                ['class' => 'btn btn-primary btn-fill btn-lg']),
                                ['/user/settings/verification', 'confirm_phone' => 1]);

                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
