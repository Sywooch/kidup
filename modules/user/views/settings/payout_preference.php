<?php
use kartik\form\ActiveForm;
use yii\helpers\Html;

/*
 * @var app\modules\user\forms\PayoutPreferences $model
 */
?>


<div class="row">
    <div class="col-md-12">
        <h3>
            <?= Yii::t("user", "Prefered payout method") ?>
        </h3>
        <?= Yii::t("user",
            "Kidup currently only offers payouts to danish bank accounts, identified with a konto number and bank code.") ?>
        <br/>

        <br/>
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                'labelOptions' => ['class' => 'col-lg-3 control-label'],
            ],
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
        ]); ?>

        <?= $form->field($model, 'payee_name') ?>
        <?php if ($model->identifier_1 !== null): ?>
            <?= Yii::t("user", "Your current payout method is stored securely in our database.") ?> <br>
            <?= \Yii::t('user', 'Konto Number') . ': ' . $model->identifier_1 ?> <br>
            <?= \Yii::t('user', 'Bank Number') . ': ' . $model->identifier_2 ?> <br>
            <?= Yii::t("user", "If you'd like to change them, please re-enter them below.") ?><br><br>
        <?php endif; ?>
        <?= $form->field($model, 'identifier_1_encrypted') ?>
        <?= $form->field($model, 'identifier_2_encrypted') ?>
        <hr/>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn pull-right btn-primary btn-fill']) ?>
                <br>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
