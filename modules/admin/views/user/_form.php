<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var \user\models\base\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'email' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Email...', 'maxlength' => 255]
            ],
            'password_hash' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Password Hash...', 'maxlength' => 60]
            ],

            'status' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Status...']],
            'role' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Role...']],
            'created_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Created At...']],
            'updated_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Updated At...']],
            'confirmed_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Confirmed At...']],
            'blocked_at' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Blocked At...']],
            'flags' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Flags...']],
            'unconfirmed_email' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Unconfirmed Email...', 'maxlength' => 255]
            ],
            'registration_ip' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Registration Ip...', 'maxlength' => 45]
            ],

        ]


    ]);
    echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
