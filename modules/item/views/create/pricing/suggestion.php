<div class="card">
    <div class="content">
        <h4><?= Yii::t("item", "Price suggestions") ?></h4>
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