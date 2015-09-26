<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var \app\modules\admin\forms\Translate $model
 */
?>

<?php

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);
echo $model->source->message;
echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'translation' => [
            'type' => Form::INPUT_TEXTAREA,
            'options' => ['placeholder' => 'Enter Description...', 'rows' => 6]
        ],
    ]
]);
echo Html::submitButton("Save", ['class' => 'btn btn-primary']);
ActiveForm::end(); ?>