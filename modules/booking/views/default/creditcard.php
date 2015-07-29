<?php
use yii\bootstrap\Modal;

?>

<?php
Modal::begin([
    'header' => '<h2>'.Yii::t("booking", "Add Payment Method").'</h2>',
    'toggleButton' => ['label' => Yii::t("booking", "Add payment method"), 'class' => 'btn btn-danger btn-fill'],
]);
?>
    <?= Yii::t("booking", "Please add a payment method below. Nothing is processed until you confirm the booking.") ?>
    <?= Yii::t("booking", "KidUp currently only supports credit cards as payment method.") ?>
    <form id="checkout" method="post" action="">
        <div id="payment-form"></div>
        <input type="submit" value="<?= Yii::t("booking", "Add payment method") ?>" class="btn btn-danger btn-fill">
    </form>

    <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
    <script>
        // We generated a client token for you so you can copy and paste this code
        // and try it out right away. See the section below to generate your
        // own client token.
        var clientToken = "<?= $clientToken ?>";

        braintree.setup(clientToken, "dropin", {
            container: "payment-form"
        });
    </script>

<?php
Modal::end();
?>