<?php
use app\modules\images\components\ImageHelper;
use yii\bootstrap\Modal;

/*
 * @var yii\web\View $this
 * @var string $clientToken
 */
?>
<?= Yii::t("booking", "Please add a payment method for this booking.") ?><br>
<?php
Modal::begin([
    'header' => '<h2>' . Yii::t("booking", "Add Credit Card") . '</h2>',
    'toggleButton' => ['label' => Yii::t("booking", "Add creditcard"), 'class' => 'btn btn-danger btn-fill'],
    'id' => 'creditCardModal'
]);
?>
    <div class="row">
        <div class="col-md-2">
            <i class="fa fa-lock" style=""></i>
            <br>
        </div>
        <div class="col-md-10">
            <?= Yii::t("booking",
                "Please add a payment method below. Nothing is processed until you confirm the booking.") ?>
            <div class="row" style="margin-top:15px;">
                <div class="col-md-3"><?= ImageHelper::img('kidup/booking/creditcards/dankort.png',
                        ['q' => 90]) ?></div>
                <div class="col-md-3"><?= ImageHelper::img('kidup/booking/creditcards/visa.png', ['q' => 90]) ?></div>
                <div class="col-md-3"><?= ImageHelper::img('kidup/booking/creditcards/mastercard.png') ?></div>
                <div class="col-md-3"><?= ImageHelper::img('kidup/booking/creditcards/amex.png', ['q' => 90]) ?></div>
            </div>
        </div>
    </div>

    <form id="checkout" method="post" action="">
        <div id="payment-form"></div>
        <input type="submit" value="<?= Yii::t("booking", "Add your card") ?>"
               class="btn btn-primary btn-fill pull-right">
        <br><br>
    </form>

    <script src="https://js.braintreegateway.com/v2/braintree.js">
        <?php
        $this->registerJs(' braintree.setup("'.  $clientToken .'", "dropin", {
                container: "payment-form"
            });')
            ?>


<?php
Modal::end();
?>