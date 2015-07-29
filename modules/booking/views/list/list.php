<?php
use kartik\grid\GridView;
use yii\helpers\Html;

?>

<div class="row">
    <div class="col-sm-12">
        <?php
        echo GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                [
                    'attribute' => \Yii::t('booking', 'status'),
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->getStatusName($model->status);
                    },
                    'format' => 'raw'
                ],
                [
                    'label' => \Yii::t('booking', 'Item Name'),
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
                    'label' => \Yii::t('booking', 'Owner'),
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'time_from',
                    'label' => \Yii::t('booking', 'Dates'),
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
                    'label' => \Yii::t('booking', 'Options'),
                    'value' => function ($model, $key, $index, $widget) {
                        if ($model->status == \app\modules\booking\models\Booking::AWAITING_PAYMENT) {
                            $links = [
                                Html::a(\Yii::t('booking', 'Complete payment'),
                                    ['/booking/' . $model->id . '/confirm']),
                            ];
                        } else {
                            $links = [
                                Html::a(\Yii::t('booking', 'Receipt'), ['/booking/' . $model->id . '/receipt']),
                                Html::a(\Yii::t('booking', 'Invoice'), ['/booking/' . $model->id . '/invoice']),
                                Html::a(\Yii::t('booking', 'View Booking'), ['/booking/' . $model->id]),
                                Html::a(\Yii::t('booking', 'Contact {0}', [
                                    $model->item->owner->profile->first_name
                                ]), '@web/messages/' . $model->conversations[0]->id),
                            ];
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
