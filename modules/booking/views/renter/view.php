<?php
use booking\models\payin\Payin;
use Carbon\Carbon;
use yii\helpers\Html;

/**
 * @var \app\components\view\View $this
 * @var $booking \booking\models\booking\Booking
 * @var $item \item\models\item\Item
 */
\booking\assets\BookingViewsAsset::register($this);
$this->assetPackage = \app\assets\Package::BOOKING;
?>
<br/><br/>
<section id="booking">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
                <h2>
                    <?= Yii::t("booking.view.header", "Booking #{0}", [
                        $booking->id
                    ]) ?>
                </h2>

                <div class="card card-product" id="product">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <h4><?= Yii::t("booking.view.details", "Details") ?></h4>
                            <ul class="list-unstyled list-lines">
                                <li>
                                    <?= Yii::t("booking.view.item", "Item") ?>
                                    <b class="pull-right">
                                        <?= Html::a($item->name, '@web/item/' . $item->id) ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.dates", "Dates") ?> <b class="pull-right">
                                        <?= Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                                        - <?= Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.owner", "Owner") ?> <b class="pull-right">
                                        <?= Html::a($booking->item->owner->profile->first_name . ' ' . $booking->item->owner->profile->last_name,
                                            '@web/user/' . $booking->item->owner_id) ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.booking_status", "Booking Status") ?> <b
                                        class="pull-right">
                                        <?= $booking->getStatusName() ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.pickup_address", "Pickup Address") ?> <b
                                        class="pull-right">
                                        <?php if ($booking->status !== \booking\models\booking\Booking::PENDING): ?>
                                            <?= $item->location->street_name ?> <?= $item->location->street_number ?>
                                            , <?= $item->location->city ?>
                                        <?php else: ?>
                                            <?= Yii::t("booking.view.address_available_after_accepted",
                                                "Location is available after the owner accepted the booking.") ?>
                                        <?php endif; ?>

                                    </b>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h4><?= Yii::t("booking.view.payment_information", "Payment Information") ?></h4>
                            <ul class="list-unstyled list-lines">
                                <li>
                                    <?= Yii::t("booking.view.rent_for_x_days", "Rent for {0} days", [
                                        Carbon::createFromTimestamp($booking->time_from)
                                            ->diffInDays(Carbon::createFromTimestamp($booking->time_to))
                                    ]) ?>
                                    <b class="pull-right">
                                        <?= $booking->amount_item ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.service_fee_incl_vat",
                                        "KidUp Service Fees (including VAT)") ?>
                                    <b class="pull-right">
                                        <?= $booking->amount_payin - $booking->amount_item ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.total", "Total") ?>
                                    <b class="pull-right">
                                        <?= $booking->amount_payin ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <hr>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.payment_received", "Payment Received") ?>
                                    <b class="pull-right">
                                        <?= $booking->payin->status == Payin::STATUS_ACCEPTED ? $booking->amount_payin : 0 ?>
                                        DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.balance", "Balance") ?>
                                    <b class="pull-right">
                                        <?= $booking->payin->status == Payin::STATUS_ACCEPTED ? 0 : $booking->amount_payin ?>
                                        DKK
                                    </b>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2">
                            <h4>
                                <?= Yii::t("booking.view.actions", "Actions") ?>
                            </h4>
                            <?= Html::a(\Yii::t("booking.view.link_contact_owner", 'Contact Owner'),
                                '@web/inbox/' . $booking->conversation->id) ?>
                            <br/>
                            <?= Html::a(\Yii::t("booking.view.link_view_receipt", 'View Receipt'),
                                '@web/booking/' . $booking->id . '/receipt',
                                ['target' => '_blank']) ?>
                            <br/>
                            <?= Html::a(\Yii::t("booking.view.link_view_invoice", 'View Invoice'),
                                '@web/booking/' . $booking->id . '/invoice',
                                ['target' => '_blank']) ?>
                        </div>
                    </div>
                    <br/><br/>
                </div>

                <?php if (!isset($_GET['pdf'])): ?>
                    <a href="?pdf=true" target="_blank">
                        <div class="pull-right btn btn-primary">
                            <?= Yii::t("booking.view.print_booking_button", "Print") ?>
                        </div>
                    </a>
                <?php endif; ?>
                <br/><br/>
            </div>
        </div>
    </div>

</section>
