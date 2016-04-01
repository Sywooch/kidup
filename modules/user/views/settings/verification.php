<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use user\widgets\Connect;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \user\models\profile\Profile $profile
 */

?>


<div class="row">
    <div class="col-md-12">
        <h4><?= Yii::t("user.settings.trust.header", "Trust and verification") ?>
            <br>
            <small><?= Yii::t("user.settings.trust.sub_header",
                    "Verify in multiple ways to built trust on KidUp!") ?></small>
        </h4>
        <div class="form-group">

            <label><?= Yii::t("user.settings.trust.email", "Email") ?></label>

            <div class="row">
                <div class="col-md-6">
                    <?= Yii::t("user.settings.trust.email_info",
                        "Your email is never shared, and only used by KidUp for secure communication.") ?>
                </div>

                <div class="col-md-6">
                    <?php echo $profile->email_verified == 1 ? '
                                        <button class="btn btn-primary btn-fill" disabled><i class="fa fa-check"></i>
                                            ' . $profile->user->email . '
                                        </button>'
                        : Html::a(Html::button(\Yii::t('user.settings.trust.resend_confirmation_button',
                            'Resend confirmation email'),
                            ['class' => 'btn btn-primary']),
                            ['/user/settings/verification', 'confirm_email' => true]); ?>
                </div>
            </div>
        </div>
        <hr/>
        <?php $auth = Connect::begin([
            'baseAuthUrl' => ['/user/settings/connect'],
            'accounts' => $user->accounts,
            'autoRender' => false,
            'popupMode' => false
        ]) ?>
        <?php foreach ($auth->getClients() as $client): ?>
            <label><?= $client->getTitle() ?></label>

            <div class="row" style="padding-bottom: 5px;">
                <div class="col-md-6">
                    <?= Yii::t("user.settings.trust.add_social_enables_verification",
                        "Adding {0} as social network account enables us and others to verify you.",
                        [$client->getTitle()]) ?>
                </div>
                <div class="col-md-6">
                    <?= $auth->isConnected($client) ?
                        '<button class="btn btn-social btn-' . strtolower($client->getTitle())
                        . '" disabled><i class="fa fa-' . strtolower($client->getTitle()) . '"></i>
                                                ' . \Yii::t("user.settings.trust.approved", "Approved ") . '
                                            </button>' :
                        Html::a(Html::button('<i class="fa fa-' . strtolower($client->getTitle()) . '"></i>' .
                            Yii::t('user.settings.trust.connect_with', 'Connect with ') . $client->getTitle(),
                            ['class' => 'btn btn-social btn-' . strtolower($client->getTitle())]),
                            $auth->createClientUrl($client)
                        )
                    ?>
                    <br/>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
        <?php Connect::end() ?>

        <div class="form-group">
            <label><?= Yii::t("user.settings.trust.phone", "Phone") ?></label>

            <div id="phone-verification-area" class="row">
                <div class="col-md-6">
                    <?= Yii::t("user.settings.trust.phone_text",
                        "Rest assured, your number is only shared with another KidUp user once you have a confirmed booking.") ?>
                </div>
                <div class="col-md-6">
                    <?php
                    if (!$profile->getPhoneNumber()) {
                        echo Yii::t('user.settings.trust.please_set_phone', 'Please set a phone number');
                        echo Html::a(\Yii::t('user.settings.trust.change_phone_number', ' change phone number.'), '@web/user/settings/account');
                    } elseif (!$profile->isValidPhoneNumber()) {
                        echo Yii::t('user.settings.trust.phone_appears_invalid',
                            'Your phone number appears to be invalid');
                        echo Html::a(\Yii::t('user.settings.trust.change_phone_number', ' change phone number.'), '@web/user/settings/account');
                    } elseif (!$profile->phone_verified) {
                        echo $profile->getPhoneNumber();
                    } ?>
                    <br/>
                    <?php
                    if ($profile->getPhoneNumber() && $profile->isValidPhoneNumber()) {
                        if ($profile->phone_verified) {
                            echo '<button class="btn btn-primary btn-fill" disabled><i class="fa fa-check"></i>
                                                        ' . $profile->getPhoneNumber() . '
                                                    </button>';
                        } else {
                            echo Html::a(Html::button(Yii::t('user.settings.trust.confirm_button', 'Confirm'),
                                ['class' => 'btn btn-primary btn-fill btn-lg']),
                                ['/user/settings/verification', 'confirm_phone' => 1]);

                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
