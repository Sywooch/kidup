<?php
/**
 * @var \yii\web\View $this
 * @var \app\modules\item\models\Item $model
 */
?>
<div class="col-sm-10 col-sm-offset-1">
    <h4><?= Yii::t("item", "Price") ?>
        <br>
        <small>
            <?= Yii::t("item", "You can set a price to reflect the product you'll be renting.") ?>
        </small>
    </h4>

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
        <div class="col-md-6 col-md-offset-2" style="padding-left:0">
            <b><?= Yii::t("item", "Price suggestions") ?></b>
            <br>
            <?= Yii::t("item", "Get a price suggestion by providing the store price of your item:") ?>
            <br/><br/>
            <input type="text" class="form-control" id="new-price"
                   placeholder="<?= Yii::t("item", "New price in DKK") ?>"
                   width="100px"/>
            <br/>

            <div class="suggestion-daily"></div>
            <div class="suggestion-weekly"></div>
            <div class="suggestion-monthly"></div>
        </div>
    </div>
    <?= Yii::t("item",
        "You can offer discounts for longer or shorter rents by setting ") ?>
    <a role="button" data-toggle="collapse" href="#collapseExample"
       aria-expanded="false" aria-controls="collapseExample">
        <?= Yii::t("item", "daily and monthly prices") ?>
    </a>.
    <div class="row">
        <div class="col-md-4">
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
</div>