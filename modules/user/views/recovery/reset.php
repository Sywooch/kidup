<?php

/*
 * This file is part of the app\modules project.
 *
 * (c) app\modules project <http://github.com/app\modules>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\modules\user\models\RecoveryForm $model
 */

$this->title = ucfirst(\Yii::t('title', 'reset your password')) . ' - ' . Yii::$app->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="section container">

    <div class="row" id="login">
        <br/><br/>

        <div class="col-md-4 col-md-offset-4 card">
            <h4><?= Yii::t("user", "Reset your KidUp password") ?></h4>
            <?php $form = ActiveForm::begin([
                'id' => 'password-recovery-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => false
            ]); ?>

            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => \Yii::t('user', 'New Password')
            ])->label(false) ?>
            <br/><br/>

            <?= Html::submitButton(Yii::t('user', 'Finish'), ['class' => 'btn btn-danger btn-block']) ?><br>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>
