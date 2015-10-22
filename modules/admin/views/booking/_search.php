<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var admin\models\search\Booking $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="booking-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'renter_id') ?>

    <?= $form->field($model, 'currency_id') ?>

    <?php // echo $form->field($model, 'refund_status') ?>

    <?php // echo $form->field($model, 'time_from') ?>

    <?php // echo $form->field($model, 'time_to') ?>

    <?php // echo $form->field($model, 'item_backup') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'payin_id') ?>

    <?php // echo $form->field($model, 'payout_id') ?>

    <?php // echo $form->field($model, 'amount_item') ?>

    <?php // echo $form->field($model, 'amount_payin') ?>

    <?php // echo $form->field($model, 'amount_payin_fee') ?>

    <?php // echo $form->field($model, 'amount_payin_fee_tax') ?>

    <?php // echo $form->field($model, 'amount_payin_costs') ?>

    <?php // echo $form->field($model, 'amount_payout') ?>

    <?php // echo $form->field($model, 'amount_payout_fee') ?>

    <?php // echo $form->field($model, 'amount_payout_fee_tax') ?>

    <?php // echo $form->field($model, 'request_expires_at') ?>

    <?php // echo $form->field($model, 'promotion_code_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
