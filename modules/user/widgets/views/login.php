<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \user\forms\LocationForm $model
 * @var string $action
 */

?>

<?php if (Yii::$app->user->isGuest): ?>

    <?php $form = ActiveForm::begin([
        'id' => 'login-widget-form',
        'fieldConfig' => [
            'template' => "{input}\n{error}"
        ],
        'action' => $action
    ]) ?>

    <?= $form->field($model, 'login')->textInput(['placeholder' => 'Login']) ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password']) ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <?= Html::submitButton(Yii::t('user.login.sign_in_button', 'Sign in'), ['class' => 'btn btn-primary btn-block']) ?>

    <?php ActiveForm::end(); ?>

<?php else: ?>

    <?= Html::a(Yii::t('user.login.logout_link', 'Logout'), ['/user/security/logout'],
        ['class' => 'btn btn-danger btn-block', 'data-method' => 'post']) ?>

<?php endif ?>