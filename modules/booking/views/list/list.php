<?php
use \booking\models\Booking;
use kartik\grid\GridView;
use yii\helpers\Html;

/**
 * @var \app\extended\web\View $this
 * @var \yii\data\ActiveDataProvider $provider
 */
$this->assetPackage = \app\assets\Package::BOOKING;

?>

<div class="row">
    <div class="col-sm-12">
        <?php
        echo GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                [
                    'attribute' => \Yii::t('booking.list.label_status', 'status'),
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->getStatusName($model->status);
                    },
                    'format' => 'raw'
                ],
                [
                    'label' => \Yii::t('booking.list.label_item_name', 'Item Name'),
                    'value' => function ($model, $key, $index, $widget) {
                        return Html::a($model->item->name, '@web/item/' . $model->item_id);
                    },
                    'format' => 'raw'
                ],
                [
                    'value' => function ($model, $key, $index, $widget) {
                        $name = $model->item->owner->profile->first_name . ' ' . $model->item->owner->profile->last_name;

                        return Html::a($name, '@web/user/' . $model->owner->id);
                    },
                    'label' => \Yii::t('booking.list.label_owner', 'Owner'),
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'time_from',
                    'label' => \Yii::t('booking.list.label_dates', 'Dates'),
                    'value' => function ($model, $key, $index, $widget) {
                        $date1 = \Carbon\Carbon::createFromTimestamp($model->time_from,
                            \Yii::$app->params['serverTimeZone'])->toFormattedDateString();
                        $date2 = \Carbon\Carbon::createFromTimestamp($model->time_to,
                            \Yii::$app->params['serverTimeZone'])->toFormattedDateString();

                        return $date1 . " - " . $date2;
                    },
                    'format' => 'raw'
                ],
                [
                    'label' => \Yii::t('booking.list.label_options', 'Options'),
                    'value' => function ($model, $key, $index, $widget) {
                        if ($model->status == \booking\models\Booking::AWAITING_PAYMENT) {
                            $links = [
                                Html::a(\Yii::t('booking.list.label_complete_payment_link', 'Complete payment'),
                                    ['/booking/' . $model->id . '/confirm']),
                            ];
                        } else {
                            /**
                             * @var \booking\models\Booking $model
                             */
                            $links = [];

                            if ($model->status !== Booking::DECLINED && $model->status !== Booking::CANCELLED) {
                                $links [] = Html::a(\Yii::t('booking.list.link_receipt', 'Receipt'),
                                    '@web/booking/' . $model->id . '/receipt');
                                $links [] = Html::a(\Yii::t('booking.list.link_invoice', 'Invoice'),
                                    '@web/booking/' . $model->id . '/invoice');
                            };

                            $links[] = Html::a(\Yii::t('booking.list.link_view_booking', 'View Booking'), '@web/booking/' . $model->id);
                            $links[] = Html::a(\Yii::t('booking.list.link_contact_owner', 'Contact {0}', [
                                $model->item->owner->profile->first_name
                            ]), ['/messages/' . $model->getConversationId()]);
                        }

                        return implode("<br/>", $links);
                    },
                    'format' => 'raw'
                ]
            ],
            'pjax' => true, // pjax is set to always true for this demo
            'export' => false,
            'bordered' => false,
            'hover' => false,
            'striped' => false,
            'condensed' => false,
            'responsive' => false,
        ]);
        ?>
    </div>
</div>
