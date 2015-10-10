<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use \yii2mod\c3\chart\Chart;

/**
 * @var \admin\forms\Query $model
 */
$this->title = "Admin";
?>

<?php
$form = ActiveForm::begin([
    'method' => 'get',
]);
?>
<div class="row well">
    <div class="col-md-2">
        <?= $form->field($model, 'entity')->widget(\kartik\select2\Select2::className(),
            ['data' => $model->entities]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'interval')->widget(\kartik\select2\Select2::className(),
            ['data' => $model->intervals]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'dateFrom')->widget(\yii\jui\DatePicker::className()) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'dateTo')->widget(\yii\jui\DatePicker::className()) ?>
    </div>
    <div class="col-md-2">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary 2']) ?>
    </div>

</div>


<?php
ActiveForm::end();
echo Chart::widget([
    'options' => [
        'id' => 'users_chart'
    ],
    'clientOptions' => [
        'data' => [
            'data' => 'data',
            'columns' => $model->results,
            'colors' => [
                'data' => '#4EB269',
            ],
        ],
        'size' => [
            'width' => '800'
        ],
        'tooltip' => [
            'show' => true
        ],
    ]
]); ?>
