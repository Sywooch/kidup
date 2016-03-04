<?php

use kartik\detail\DetailView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var booking\models\payin\Booking $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'status',
            'item_id',
            'renter_id',
            'currency_id',
            'refund_status',
            'time_from:datetime',
            'time_to:datetime',
            'item_backup:ntext',
            'updated_at',
            'created_at',
            'payin_id',
            'payout_id',
            'amount_item',
            'amount_payin',
            'amount_payin_fee',
            'amount_payin_fee_tax',
            'amount_payin_costs',
            'amount_payout',
            'amount_payout_fee',
            'amount_payout_fee_tax',
            'request_expires_at',
            'promotion_code_id',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
