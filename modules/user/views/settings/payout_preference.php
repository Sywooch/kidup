<?php
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use app\modules\user\helpers\SelectData;
use yii\helpers\Html;
?>


<div class="row">
    <div class="col-md-12">
        <h3>
            <?= Yii::t("user", "Prefered payout method") ?>
        </h3>
        <?= Yii::t("user", "Kidup currently only offers payouts to danish bank accounts, identified with a konto number and bank code.") ?>
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
        <?= $form->field($model, 'identifier_1') ?>
        <?= $form->field($model, 'identifier_2') ?>
        <hr/>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-9">
                <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn pull-right btn-primary btn-fill']) ?><br>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
