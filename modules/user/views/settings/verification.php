<?php

/*
 * This file is part of the  project.
 *
 * (c)  project <http://github.com/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use app\helpers\SelectData;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use user\widgets\Connect;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \user\models\Profile $profile
 */

?>

<?php $form = ActiveForm::begin([
    'id' => 'profile-form',
    'type' => ActiveForm::TYPE_VERTICAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template' => "{label}{input} \n {error} {hint}",
        // 'labelOptions' => ['class' => 'control-label'],
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
]); ?>

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

            <div class="form-group field-profile-description">
                <label class="control-label" for="profile-description">
                    <?= Yii::t("user.settings.account.phone_number", "Phone number") ?>
                </label>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'phone_country')->widget(Select2::classname(), [
                            'data' => SelectData::phoneCountries(),
                            'options' => ['placeholder' => \Yii::t('user.settings.account.country_code_phone', 'Country code')],
                            'pluginOptions' => [
                                'allowClear' => false
                            ],
                        ])->label(false); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'phone_number')->label(false)->textInput([
                            'placeholder' => \Yii::t('user.settings.account.phone_number_placeholder', 'e.g. 26415315')
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= \yii\helpers\Html::submitButton(Yii::t('user.settings.verification.save_button', 'Save'),
    ['class' => 'btn btn-primary btn-fill btn-lg']) ?>
<br/><br/>

<?php ActiveForm::end(); ?>
