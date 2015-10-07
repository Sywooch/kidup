<?php
use yii\helpers\Html;

$this->title = "Admin";
?>

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
            'width' => '800'
        ],
        'tooltip' => [
            'show' => true
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
                'max' => (int)$userData[0][count($userData[0]) - 1],
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
                'max' => (int)$itemData[0][count($itemData[0]) - 1],
                'padding' => ['top' => 10, 'bottom' => 0]
            ]
        ]
    ]
]); ?>
