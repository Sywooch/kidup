<?php
use Carbon\Carbon;
use yii\helpers\Html;

/**
 * @var \app\extended\web\View $this
 * @var $booking \booking\models\Booking
 * @var $item \item\models\Item
 */
\booking\assets\BookingViewsAsset::register($this);
$this->assetPackage = \app\assets\Package::BOOKING;
?>
<section id="booking">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
                <h2><?= Yii::t("booking.view.header", "Booking #{0}", [
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
                                    <?= Yii::t("booking.view.renter", "Renter") ?> <b class="pull-right">
                                        <?= Html::a($booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
                                            '@web/user/' . $booking->renter_id) ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.booking_status", "Booking Status") ?> <b class="pull-right">
                                        <?= $booking->getStatusName() ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.pickup_address", "Pickup Address") ?> <b class="pull-right">
                                        <?= $item->location->street_name ?> <?= $item->location->street_number ?>
                                        , <?= $item->location->city ?>
                                    </b>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h4><?= Yii::t("booking.view.payment_info", "Payment Information") ?></h4>
                            <ul class="list-unstyled list-lines">
                                <li>
                                    <?= Yii::t("booking.view.to_receive_total", "Total to receive") ?>
                                    <b class="pull-right">
                                        <?= $booking->amount_payout ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.kidup_service_fee", "KidUp Service Fees") ?>
                                    <b class="pull-right">
                                        <?= $booking->amount_item - $booking->amount_payout ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.view.expected_payout_date", "Expected Payout Date") ?>
                                    <b class="pull-right">
                                        <?= Carbon::createFromTimestamp($booking->time_from + 24 * 60 * 60 * 7,
                                            \Yii::$app->params['serverTimeZone'])->toDateString() ?>
                                    </b>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2">
                            <h4>
                                <?= Yii::t("booking.view.actions", "Actions") ?>
                            </h4>
                            <?= Html::a(Yii::t("booking.view.link_contact_renter", 'Contact Renter'),
                                '@web/inbox/' . $booking->getConversationId()) ?>
                            <?= Html::a(Yii::t("booking.view.link_view_receipt", 'View Receipt'), '@web/booking/' . $booking->id . '/receipt',
                                ['target' => '_blank']) ?>
                            <?= Html::a(Yii::t("booking.view.link_view_invoice", 'View Invoice'), '@web/booking/' . $booking->id . '/invoice',
                                ['target' => '_blank']) ?>
                        </div>
                    </div>
                </div>
            <span class="pull-left">
                <?= Yii::t("booking.view.no_rights_from_page", "No rights can be derived from the contents of this page.") ?>
            </span>
                <?php if (!isset($_GET['pdf'])): ?>
                    <a href="?pdf=true" target="_blank">
                        <div class="pull-right btn btn-primary">
                            <?= Yii::t("booking.view.print_booking_button", "Print") ?>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</section>
