<?php
use Carbon\Carbon;
use yii\helpers\Html;

/**
 * @var \app\components\view\View $this
 * @var \booking\models\booking\Booking $booking
 * @var \item\models\item\Item $item
 * @var \user\models\profile\Profile $profile
 * @var string $declineLink
 * @var string $acceptLink
 * @var string $timeLeft
 */
$this->title = \Yii::t('booking.owner_response.title', 'Respond to booking request') . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::BOOKING;
?>

<section class="section" id="checkout">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
                    <div class="checkout-header">
                        <h2>
                            <?= Yii::t("booking.owner_response.header", "Booking Request") ?><br>
                            <small><?= Yii::t("booking.owner_response.sub_header", "Respond to {0}'s booking request",
                                    [$profile->first_name]) ?></small>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 card">
                <h4>
                    <?= \Yii::t('booking.owner_response.user_wants_to_book_item',
                        'User {0} would like to book your item {1}', [
                            Html::a($profile->first_name, '@web/user/' . $profile->user_id, ['target' => '_blank']),
                            Html::a($item->name, '@web/item/' . $item->id, ['target' => '_blank']),
                        ]) ?>
                </h4>

                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled list-lines">
                            <li>
                                <i class="fa fa-info-circle"></i>
                                <?= Yii::t("booking.owner_response.item", "item") ?>
                                <b><?= $item->name ?></b>
                            </li>
                            <li>
                                <i class="fa fa-calendar"></i><?= Yii::t("booking.owner_response.dates", "Dates") ?> <b>
                                    <?= Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                                    - <?= Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                                </b>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled list-lines">
                            <li>
                                DKK <?= $item->price_day ?>
                                x <?= ($booking->time_to - $booking->time_from) / (24 * 60 * 60) ?>
                                <?= Yii::t("booking.owner_response.price_table_day", "days") ?>
                                <b>DKK <?= $booking->amount_item ?></b>
                            </li>
                            <li>
                                <?= Yii::t("booking.owner_response.price_table_service_fee",
                                    "Service Fee (inc. VAT)") ?>
                                <b>DKK <?= $booking->amount_payout - $booking->amount_item ?></b>
                            </li>
                            <li>
                                <?= Yii::t("booking.owner_response.price_table_total_payout", "Total payout") ?>
                                <b>DKK <?= $booking->amount_payout ?></b>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12">
                        <?= Yii::t("booking.owner_response.time_left_to_respond",
                            "You've got {time} left to respond to this booking request.", [
                                'time' => $timeLeft
                            ]) ?>
                        <?= Yii::t("booking.owner_response.booking_lapses_if_fail_to_respond",
                            "Please be aware that the booking lapses if you have not responded within this timeperiod.") ?>
                        <br/><br/>
                        <b>
                            <?= Yii::t("booking.owner_response.agree_terms_conditions",
                                "By clicking one of the underlying buttons, you agree with our {link} terms and conditions.{linkOut}",
                                [
                                    'link' => Html::beginTag('a',
                                        [
                                            'href' => \yii\helpers\Url::to('@web/p/terms-and-conditions'),
                                            'target' => '_blank'
                                        ]),
                                    'linkOut' => Html::endTag('a')
                                ]) ?>
                        </b>
                        <br/>
                        <br/>
                        <?= Html::a(Html::button(Yii::t("booking.owner_response.decline_button", "Decline booking"), [
                            'class' => 'btn btn-default'
                        ]), $declineLink) ?>
                        <?= Html::a(Html::button(Yii::t("booking.owner_response.accept_button", "Accept booking"), [
                            'class' => 'btn btn-primary btn-fill'
                        ]), $acceptLink) ?>
                        <br/><br/>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
