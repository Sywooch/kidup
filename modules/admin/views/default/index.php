<?php
use yii\helpers\Html;

$this->title = \Yii::t('title', 'Admin') . ' - ' . Yii::$app->name;
?>
<section class="section container">
    <br/><br/>
    <div class="row">
        <div class="">
            <div class="col-md-8 card">
                <h3>Admin Page</h3>

                <?php
                echo \yii2mod\c3\chart\Chart::widget([
                    'options' => [
                        'id' => 'users_chart'
                    ],
                    'clientOptions' => [
                        'data' => [
                            'users' => 'users',
                            'columns' => $userData,
                            'colors' => [
                                'users' => '#4EB269',
                            ],
                        ],
                        'size' => [
                            'width' => '600'
                        ],
                        'axis' => [
                            'x' => [
                                'label' => 'Days',
                                'type' => 'category'
                            ],
                            'y' => [
                                'label' => [
                                    'text' => 'users',
                                    'position' => 'outer-top'
                                ],
                                'min' => (int)$userData[0][1],
                                'max' => (int)$userData[0][count($userData[0])-1],
                                'padding' => ['top' => 10, 'bottom' => 0]
                            ]
                        ]
                    ]
                ]); ?>

                <?php echo \yii2mod\c3\chart\Chart::widget([
                    'options' => [
                        'id' => 'item_chart'
                    ],
                    'clientOptions' => [
                        'data' => [
                            'items' => 'items',
                            'columns' => $itemData,
                            'colors' => [
                                'items' => '#846FB1',
                            ],
                        ],
                        'size' => [
                            'width' => '600'
                        ],
                        'axis' => [
                            'x' => [
                                'label' => 'Days',
                                'type' => 'category'
                            ],
                            'y' => [
                                'label' => [
                                    'text' => 'items',
                                    'position' => 'outer-top'
                                ],
                                'min' => (int)$itemData[0][1],
                                'max' => (int)$itemData[0][count($itemData[0])-1],
                                'padding' => ['top' => 10, 'bottom' => 0]
                            ]
                        ]
                    ]
                ]); ?>

                <br/><br/>
            </div>
            <div class="col-md-4">
                Export for danske bank. DONT CLICK THIS if you're not sure what it does.
                <br>
                <?= Html::a('Generate CSV export', '@web/admin/payment/generate-payout',
                    [
                        'class' => 'btn btn-danger btn-xs btn-fill',
                        'data-confirm' => 'ATTENTION: You\'re sure, right?',
                    ]
                ) ?>
            </div>
        </div>
    </div>



</section>