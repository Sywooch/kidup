<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var app\models\base\Item $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'description' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['placeholder' => 'Enter Description...', 'rows' => 6]
            ],
            'price_day' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Price Day...']],
            'price_week' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Price Week...']],
            'price_month' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Price Month...']],
            'owner_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Owner ID...']],
            'condition' => [
                'type' => \kartik\builder\TabularForm::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Condition...']
            ],
            'currency_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Currency ID...']],
            'is_available' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Is Available...']],
            'location_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Location ID...']],
            'created_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Created At...']],
            'updated_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Updated At...']],
            'min_renting_days' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Min Renting Days...']
            ],
            'category_id' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Category ID...']],
            'name' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Name...', 'maxlength' => 140]],

        ]


    ]);
    echo Html::submitButton($model->isNewRecord ? 'Create' : '',
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
