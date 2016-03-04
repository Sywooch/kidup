<?php

use booking\models\payout\BrainTree;
use booking\models\payin\Payin;
use images\components\ImageHelper;
use review\widgets\ReviewScore;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var \app\extended\web\View $this
 * @var \booking\models\booking\Booking $booking
 * @var \booking\forms\Confirm $model
 * @var \item\models\Item $item
 * @var \user\models\Profile $profile
 * @var array $tableData
 */

\booking\assets\ConfirmAsset::register($this);
\yii\web\JqueryAsset::register($this);

$this->title = \Yii::t('booking.confirm.page_title', 'Confirm Your Booking') . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::BOOKING;
$clientToken = (new BrainTree(new Payin()))->getClientToken();
?>
<section class="section" id="checkout">
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'enableAjaxValidation' => false,
        'enableClientScript' => false,
        'validateOnSubmit' => false,
        'validateOnChange' => false,
        'validateOnBlur' => false
    ]); ?>
    <div class="container">
        <h2>
            <?= Yii::t("booking.confirm.header", "Secure Booking - Pay in 1 Minute") ?>
        </h2>

        <div class="row">
            <div class="col-md-8">
                <!-- Mobile replacement for right panel -->
                <div class="card hidden-md hidden-lg">
                    <h3><?= Yii::t('booking.confirm.header_booking_info', 'Booking information') ?></h3>

                    <div class="row card card-minimal">
                        <div style="float: left;">
                            <?= ImageHelper::image($item->getImageName(0), ['w' => 70, 'h' => 70, 'fit' => 'crop']) ?>
                        </div>
                        <div style="float: left; margin-left: 15px;">
                            <b>
                                <?= $item->name ?>
                            </b>
                            <br>
                            <?= $item->location->city ?>
                        </div>
                    </div>

                    <i><?= Yii::t("booking.confirm.renting_from_label", "You are renting from") ?>:</i>

                    <div class="row card card-minimal">
                        <div style="float: left;">
                            <?= ImageHelper::image($profile->img, ['w' => 70, 'h' => 70, 'fit' => 'crop']) ?>
                        </div>
                        <div style="float: left; margin-left: 15px;">
                            <b>
                                <?= $profile->first_name ?>
                            </b>
                            <?= ReviewScore::widget(['user_id' => $profile->user_id]) ?>
                            <?= \Yii::t('booking.confirm.owner_member_since', 'Member since {0}', [
                                Carbon\Carbon::createFromTimestamp($profile->user->created_at)->formatLocalized('%b %y')
                            ]) ?>
                        </div>
                    </div>


                    <div class="row" style="margin-bottom: 20px;">
                        <div class="col-md-12">
                            <?= Carbon\Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                            -
                            <?= Carbon\Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                        </div>
                    </div>

                    <table class="table">
                        <tr>
                            <td>
                                <?= $tableData['price'][0] ?>
                            </td>
                            <td style="font-weight: 600;">
                                <?= $tableData['price'][1] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= $tableData['fee'][0] ?>
                            </td>
                            <td style="font-weight: 600;">
                                <?= $tableData['fee'][1] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= $tableData['total'][0] ?>
                            </td>
                            <td style="font-weight: 600;">
                                <?= $tableData['total'][1] ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <h3>
                        <?= Yii::t("booking.confirm.message_to_header", "Message to {0}", [
                            $profile->first_name
                        ]) ?>
                    </h3>

                    <div class="row">
                        <div class="col-md-7">
                            <?= $form->field($model, 'message')->textarea([
                                'class' => 'form-control',
                                'placeholder' => \Yii::t('booking.confirm.message_to_placeholder',
                                    'Your private message to {0}',
                                    [$profile->first_name])
                            ])->label(false) ?>
                        </div>
                        <div class="col-md-5">
                            <?= Yii::t('booking.confirm.message_information_text',
                                "Here you can introduce yourself to {owner} and ask for details on the product, or make suggestions for the exchange of the product. You can continue the conversation with {owner} through KidUp once you finish creating this booking.",
                                [
                                    'owner' => $profile->first_name
                                ]) ?>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h3>
                        <?= Yii::t("booking.confirm.header_payment_info", "Payment information") ?>
                    </h3>

                    <div class="row payment-methods">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <?= ImageHelper::img('kidup/booking/creditcards/dankort.png', ['q' => 90]) ?></div>
                                <div class="col-md-3">
                                    <?= ImageHelper::img('kidup/booking/creditcards/visa.png', ['q' => 90]) ?></div>
                                <div class="col-md-3">
                                    <?= ImageHelper::img('kidup/booking/creditcards/mastercard.png') ?></div>
                                <div class="col-md-3">
                                    <?= ImageHelper::img('kidup/booking/creditcards/amex.png', ['q' => 90]) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <br/>

                            <div id="payment-form"></div>
                            <?php
                            $this->registerJs('braintree.setup("' . $clientToken . '", "dropin", { container: "payment-form" });');
                            $this->registerJsFile('https://js.braintreegateway.com/v2/braintree.js');
                            // trick to show custom error message if nonce is empty
                            echo $form->field($model, 'nonce')->hiddenInput()->label(false); ?>
                        </div>
                        <div class="col-md-5">
                            <?= Yii::t("booking.confirm.payment_info_help",
                                "The total amount will be reserved on your creditcard when this booking is made. The card is only charged if the owner accepts your booking.") ?>
                            <?= Yii::t("booking.confirm.more_info_see_faq",
                                "For more information please see {link} our FAQ.{linkClose}",
                                [
                                    'link' => Html::beginTag('a',
                                        ['href' => \yii\helpers\Url::to('@web/p/faq'), 'target' => '_blank']),
                                    'linkClose' => Html::endTag('a')
                                ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right panel -->
            <div class="col-md-4 hidden-xs hidden-sm">
                <div class="row card card-minimal">

                    <div class="col-md-4">
                        <?= ImageHelper::image($item->getImageName(0), ['w' => 70, 'h' => 70, 'fit' => 'crop']) ?>
                    </div>
                    <div class="col-md-8">
                        <b>
                            <?= $item->name ?>
                        </b>
                        <br>
                        <?= $item->location->city ?>
                    </div>
                </div>

                <div class="row card card-minimal">

                    <div class="col-md-4">
                        <?= ImageHelper::image($profile->img, ['w' => 70, 'h' => 70, 'fit' => 'crop']) ?>
                    </div>
                    <div class="col-md-8">
                        <i><?= Yii::t("booking.confirm.renting_from_label", "You are renting from") ?>:</i>
                        <br>
                        <b>
                            <?= $profile->first_name ?>
                        </b>
                        <?= ReviewScore::widget(['user_id' => $profile->user_id]) ?>
                        <?= \Yii::t('booking.confirm.member_since', 'Member since {0}', [
                            Carbon\Carbon::createFromTimestamp($profile->user->created_at)->formatLocalized('%b %y')
                        ]) ?>
                    </div>
                </div>


                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <?= Carbon\Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                        -
                        <?= Carbon\Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                    </div>
                </div>

                <table class="table">
                    <tr>
                        <td>
                            <?= $tableData['price'][0] ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?= $tableData['price'][1] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= $tableData['fee'][0] ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?= $tableData['fee'][1] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= $tableData['total'][0] ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?= $tableData['total'][1] ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Booking button -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <h3><?= Yii::t("booking.confirm.review_and_book_header", "Review and book") ?></h3>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="well">
                                <?= $form->field($model, 'rules')->checkbox() ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <?= Yii::t("booking.create.after_creation_help",
                                "On creating this booking, the owner will get notified and can accept or reject your request. In the meantime you can use KidUp to communicate, for example on the details of the item exchange.") ?>
                            <?= Yii::t("booking.confirm.more_info_see_renting_guide",
                                "For more information please see {link} the renting on KidUp guide.{linkClose}",
                                [
                                    'link' => Html::beginTag('a',
                                        ['href' => \yii\helpers\Url::to('@web/p/how-to-rent'), 'target' => '_blank']),
                                    'linkClose' => Html::endTag('a')
                                ]) ?>
                        </div>
                    </div>
                    <?= Html::submitButton(\Yii::t('booking.create.book_now_button', 'Book now'),
                        ['class' => 'btn btn-lg btn-fill btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</section>