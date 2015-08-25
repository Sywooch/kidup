<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\item\models\Item $model
 */
?>

<h4>
    <?= Yii::t("item", "Price") ?>
</h4>
<?= Yii::t("item", "You can set a price to reflect the product you'll be renting.") ?>
<hr>
<div class="row">

    <div class="col-md-4" style="padding-left:0">
        <?= Yii::t("item", "Weekly price") ?><br>
        <?= $form->field($model, 'price_week')->input('number', [
            'class' => 'form-control',
            'options' => [
            ],
            'min' => 0
        ])->label(false) ?>
    </div>
</div>
<?= Yii::t("item",
    "You can offer discounts for longer or shorter rents by setting ") ?>
<a role="button" data-toggle="collapse" href="#collapseExample"
   aria-expanded="false" aria-controls="collapseExample">
    <?= Yii::t("item", "daily and monthly prices") ?>
</a>.
<div class="row">
    <div class="col-md-4" style="padding-left:0">
        <div class="collapse" id="collapseExample">
            <?= Yii::t("item", "Daily price") ?><br>
            <?= $form->field($model, 'price_day')->input('number', [
                'class' => 'form-control',
                'min' => 0,
            ])->label(false)
            ?>
            <?= Yii::t("item", "Montly price") ?><br>
            <?= $form->field($model, 'price_month')->input('number', [
                'class' => 'form-control',
                'min' => 0
            ])->label(false)
            ?>
        </div>
    </div>
</div>