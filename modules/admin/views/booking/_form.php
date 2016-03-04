<?php

use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var booking\models\booking\Booking $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="booking-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'status' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Status...', 'maxlength' => 50]
            ],
            'item_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Item...']],
            'time_from' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Time From...']],
            'time_to' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Time To...']],
            'updated_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Updated At...']],
            'created_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Created At...']],
            'request_expires_at' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Request Expires At...']
            ],
            'renter_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Renter...']],
            'currency_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Currency...']],
            'payin_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Payin...']],
            'payout_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Payout...']],
            'item_backup' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['placeholder' => 'Enter Item Backup...', 'rows' => 6]
            ],
            'amount_item' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Amount Item...']],
            'amount_payin' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Amount Payin...']],
            'amount_payin_fee' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Amount Payin Fee...']
            ],
            'amount_payin_fee_tax' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Amount Payin Fee Tax...']
            ],
            'amount_payin_costs' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Amount Payin Costs...']
            ],
            'amount_payout' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Amount Payout...']],
            'amount_payout_fee' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Amount Payout Fee...']
            ],
            'amount_payout_fee_tax' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Amount Payout Fee Tax...']
            ],
            'refund_status' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Refund Status...', 'maxlength' => 20]
            ],
            'promotion_code_id' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Promotion Code...', 'maxlength' => 255]
            ],

        ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
