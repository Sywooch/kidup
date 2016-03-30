<?php

use app\helpers\ViewHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \user\models\user\User $model
 * @var \user\models\socialAccount\SocialAccount $account
 */
$this->title = ViewHelper::getPageTitle(\Yii::t('user.connect.title', 'Connect your account to {0}', $account->provider));
$this->assetPackage = \app\assets\Package::USER;
?>
<section style="padding-top:80px" class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h3>
                <center><?= Html::encode($this->title) ?></center>
            </h3>
            <div class="panel panel-default">

                <div class="panel-body">
                    <div class="alert alert-info">
                        <p>
                            <?= Yii::t('user.connect.first_time_signin',
                                'Looks like this is first time you are using your {0} account to sign in to {1}',
                                [$account->provider, Yii::$app->name]) ?>.
                            <?= Yii::t('user.connect.first_time_signin_use_email_to_connect',
                                'Connect your email address below, you will never have to use this form again.') ?>.
                        </p>
                    </div>
                    <?php $form = ActiveForm::begin([
                        'id' => 'connect-account-form',
                    ]); ?>


                    <?= $form->field($model, 'email') ?>

                    <?= Html::submitButton(Yii::t('user.connect.finish_button', 'Finish'),
                        ['class' => 'btn btn-primary btn-wide btn-fill']) ?>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <p class="text-center">
                <?= Html::a(Yii::t('user.connect.already_on_kidup_question',
                    'Already on kidup? Login and connect this account on your verification page'),
                    ['/user/security/login']) ?>.
            </p>
        </div>
    </div>
</section>

