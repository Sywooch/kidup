<?php
use yii\bootstrap\Modal;

/**
 * @var \yii\web\View $this
 * @var \app\modules\item\models\Item $model
 */
?>
<div class="col-sm-10 col-sm-offset-1">
    <h4><?= Yii::t("item", "Location") ?>
        <br>
        <small>
            <?= Yii::t("item", "Where can the item be picked up? This is private information.") ?>
        </small>
    </h4>

    <?php Modal::begin([
        'header' => '<h2>Hello world</h2>',
        'toggleButton' => ['label' => 'Add location'],
    ]);

    $form =  \kartik\form\ActiveForm::begin();
    ?>

    <input type="text" class="form-control">
    <input type="submit">

    <?php
    \kartik\form\ActiveForm::end();

    Modal::end(); ?>
</div>

