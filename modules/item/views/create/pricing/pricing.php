<?php
/**
 * @var \yii\web\View $this
 * @var \item\models\Item $model
 */
?>

<h4>
    <?= Yii::t("item.create.pricing.header", "Price") ?>
</h4>
<?= Yii::t("item.create.pricing.sub_header", "You can set a price to reflect the product you'll be renting.") ?>
<hr>
<div class="row">

    <div class="col-md-4" style="padding-left:0">
        <?= Yii::t("item.create.pricing.weekly_price_header", "Weekly price (in DKK)") ?><br>
        <?= $form->field($model, 'price_week')->input('number', [
            'class' => 'form-control',
            'options' => [
            ],
            'min' => 0
        ])->label(false) ?>
    </div>
</div>
<?= Yii::t("item.create.pricing.discounts_by_daily_prices",
    "You can offer discounts for longer or shorter rents by setting {link} daily and monthly prices{linkOut}.",[
        'link' => '<a role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">',
        'linkOut' => '</a>'
    ]) ?>
<div class="row">
    <div class="col-md-4" style="padding-left:0">
        <div class="collapse" id="collapseExample">
            <?= Yii::t("item.create.pricing.daily_price", "Daily price") ?><br>
            <?= $form->field($model, 'price_day')->input('number', [
                'class' => 'form-control',
                'min' => 0,
            ])->label(false)
            ?>
            <?= Yii::t("item.create.pricing.monthly_price", "Montly price") ?><br>
            <?= $form->field($model, 'price_month')->input('number', [
                'class' => 'form-control',
                'min' => 0
            ])->label(false)
            ?>
        </div>
    </div>
</div>