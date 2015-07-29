<?php
use Carbon\Carbon;
use yii\helpers\Url;
use app\modules\booking\models\Payin;
/**
 * @var $booking \app\modules\booking\models\Booking
 * @var $item \app\modules\item\models\Item
 */
?>
<br/><br/>
<section class="section" id="checkout">
    <div class="row" style="max-width: 98%">
        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
            <div class="site-area-header">
                <div class="checkout-header">
                    <h2><?= Yii::t("booking", "Receipt #RR-{0}", [
                            $booking->id
                        ]) ?>
                    </h2>
                </div>
            </div>
            <br/><br/>

            <div class="card card-product" id="product">
                <div class="row">
                    <div class="col-md-4 col-md-offset-1">
                        <div style="text-align: left">
                            <br/>
                            <b>KidUp</b><br/>
                            <?= \Yii::$app->params['kidupAddressLine1'] ?> <br/>
                            <?= \Yii::$app->params['kidupAddressLine2'] ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="text-align: right">
                            <br/>
                            <img src="<?= Url::to('@assets/img/logo/horizontal.png') ?>" width="100px"/>
                            <br/>
                            <b><?= Yii::t("booking", "Generated on {0}", [
                                    Carbon::now(\Yii::$app->params['serverTimeZone'])->toFormattedDateString()
                                ]) ?></b>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-5 col-md-offset-1">
                        <ul class="list-unstyled list-lines">
                            <li>
                                <?= Yii::t("booking", "Name") ?>
                                <b>
                                    <?= \Yii::$app->user->identity->profile->first_name . ' ' . \Yii::$app->user->identity->profile->last_name ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Dates") ?> <b>
                                    <?= Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                                    - <?= Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Item Owner") ?> <b>
                                    <?= $booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Pickup Address") ?> <b>
                                    <?= $item->location->street_name ?> <?= $item->location->street_number ?>
                                    , <?= $item->location->city ?>, <?= $item->location->country0->name ?>
                                </b>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-md-offset-2">
                        <ul class="list-unstyled list-lines">
                            <li>
                                <?= Yii::t("booking", "Rent for {0} days", [
                                    Carbon::createFromTimestamp($booking->time_from)->diffInDays(Carbon::createFromTimestamp($booking->time_to))
                                ]) ?>
                                <b>
                                    <?= $booking->amount_item ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "KidUp Service Fees ") ?>
                                <br/>
                                <?= Yii::t("booking", "(including VAT)") ?>
                                <b>
                                    <?= $booking->amount_payin - $booking->amount_item ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Total") ?>
                                <b>
                                    <?= $booking->amount_payin ?> DKK
                                </b>
                            </li>
                            <br/><br/>
                            <li>
                                <?= Yii::t("booking", "Payment Received") ?>
                                <?= $booking->payin->status == Payin::STATUS_ACCEPTED ?: '*' ?>
                                <b>
                                    <?= $booking->payin->status == Payin::STATUS_ACCEPTED ? $booking->amount_payin : 0 ?>
                                    DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Balance") ?>
                                <?= $booking->payin->status == Payin::STATUS_ACCEPTED ?: '*' ?>
                                <b>
                                    <?= $booking->payin->status == Payin::STATUS_ACCEPTED ? 0 : $booking->amount_payin ?>
                                    DKK
                                </b>
                            </li>
                            <br/>
                            <?= $booking->payin->status == Payin::STATUS_ACCEPTED ?: '*'. \Yii::t('booking', "Payment will be processed automatically when the owner accepts this booking.") ?>
                        </ul>
                    </div>
                </div>
                <br/><br/><br/>
            </div>
            <span class="pull-left">
                <?= Yii::t("booking", "No rights can be derived from the contents of this page.") ?>
            </span>
            <?php if (!isset($_GET['pdf'])): ?>
                <a href="?pdf=true" target="_blank">
                    <div class="pull-right btn btn-primary">
                        <?= Yii::t("booking", "Print") ?>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

