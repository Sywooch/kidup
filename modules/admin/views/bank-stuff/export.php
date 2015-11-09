<?php
use yii\bootstrap\Html;
use kartik\form\ActiveForm;
?>

<div class="col-md-4">
    Export for danske bank. DONT CLICK THIS if you're not sure what it does.
    <br>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'keyFile')->fileInput() ?>

    <button>Submit</button>

    <?php ActiveForm::end() ?>
</div>