<?php
use Carbon\Carbon;
use yii\helpers\Html;

/**
 * @var app\components\extended\View $this
 * @var app\modules\booking\models\Booking $booking
 * @var app\modules\item\models\Item $item
 * @var app\modules\user\models\Profile $profile
 * @var string $declineLink
 * @var string $acceptLink
 * @var string $timeLeft
 */
$this->title = \Yii::t('title', 'Respond to booking request') . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::BOOKING;
?>

<section class="section" id="checkout">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
                    <div class="checkout-header">
                        <h2>
                            <?= Yii::t("booking", "Booking Request") ?><br>
                            <small><?= Yii::t("booking", "Respond to {0}'s booking request",
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
                    <?= \Yii::t('booking', 'User {0} would like to rent your item {1}', [
                        Html::a($profile->first_name, '@web/user/' . $profile->user_id, ['target' => '_blank']),
                        Html::a($item->name, '@web/item/' . $item->id, ['target' => '_blank']),
                    ]) ?>
                </h4>

                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled list-lines">
                            <li>
                                <i class="fa fa-info-circle"></i><?= Yii::t("booking", "item") ?>
                                <b><?= $item->name ?></b>
                            </li>
                            <li>
                                <i class="fa fa-calendar"></i><?= Yii::t("booking", "Dates") ?> <b>
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
                                x <?= ($booking->time_to - $booking->time_from) / (24 * 60 * 60) ?> <?= Yii::t("booking",
                                    "days") ?>
                                <b>DKK <?= $booking->amount_item ?></b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Service Fee (inc. VAT)") ?>
                                <b>DKK <?= $booking->amount_payout - $booking->amount_item ?></b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Total payout") ?>
                                <b>DKK <?= $booking->amount_payout ?></b>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-12">
                        <?= Yii::t("booking", "You've got {time} left to respond to this booking request.", [
                            'time' => $timeLeft
                        ]) ?>
                        <?= Yii::t("booking",
                            "Please be aware that the booking lapses if you have not responded within this timeperiod.") ?>
                        <br/><br/>
                        <b>
                            <?= Yii::t("booking", "By clicking one of the underlying buttons, you agree with our ") ?>
                            <?= Html::a(\Yii::t('booking', 'terms and conditions'),
                                '@web/p/terms-and-conditions'
                                , ['target' => '_blank']) ?>.
                        </b>
                        <br/>
                        <br/>
                        <?= Html::a(Html::button(Yii::t("booking", "Decline booking"), [
                            'class' => 'btn btn-default'
                        ]), $declineLink) ?>
                        <?= Html::a(Html::button(Yii::t("booking", "Accept booking"), [
                            'class' => 'btn btn-primary btn-fill'
                        ]), $acceptLink) ?>
                        <br/><br/>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
