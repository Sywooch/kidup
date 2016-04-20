<?php
/**
 * @var \app\components\view\View $this
 */
$this->registerJsVariables([
    'daily_price' => Yii::t("item.create.pricing.suggestion.daily_price", "Daily Price"),
    'weekly_price' => Yii::t("item.create.pricing.suggestion.weekly_price", "Weekly Price"),
    'monthly_price' => Yii::t("item.create.pricing.suggestion.monthly_price", "Monthly Price"),
    'yearly_price' => Yii::t("item.create.pricing.suggestion.yearly_price", "Yearly Price")
], 'i18n')
?>

<div class="card">
    <div class="content">
        <h4><?= Yii::t("item.create.pricing.suggestion.header", "Price suggestions") ?></h4>
        <?= Yii::t("item.create.pricing.suggestion.help_text", "Get a price suggestion by providing the store price of your item:") ?>
        <br/><br/>
        <input type="text" class="form-control" id="new-price"
               placeholder="<?= Yii::t("item.create.pricing.suggestion.new_price", "New price in DKK") ?>"
               width="100px"/>
        <br/>

        <div class="suggestion-daily"></div>
        <div class="suggestion-weekly"></div>
        <div class="suggestion-monthly"></div>
        <div class="suggestion-yearly"></div>
    </div>
</div>