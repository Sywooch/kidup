<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var admin\models\search\Booking $searchModel
 */

$this->title = 'Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Booking', ['create'], ['class' => 'btn btn-success'])*/ ?>
    </p>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusName();
                }
            ],
            [
                'attribute' => 'item_id',
                'value' => function ($model) {
                    return Html::a($model->item->name,'@web/admin/item/view?id='.$model->item->id);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'renter_id',
                'value' => function ($model) {
                    return Html::a($model->renter->email,'@web/admin/user/view?id='.$model->renter->id);
                },
                'format' => 'raw'
            ],
            'time_from:datetime',
            'time_to:datetime',
//            'item_backup:ntext', 
            'created_at:datetime',
//            'payin_id', 
//            'payout_id', 
//            'amount_item', 
//            'amount_payin', 
//            'amount_payin_fee', 
//            'amount_payin_fee_tax', 
//            'amount_payin_costs', 
//            'amount_payout', 
//            'amount_payout_fee', 
//            'amount_payout_fee_tax', 
//            'request_expires_at', 
//            'promotion_code_id', 

            [
                'class' => 'yii\grid\ActionColumn',
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' => Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'],
                ['class' => 'btn btn-success']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'],
                ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]);
    Pjax::end(); ?>

</div>
