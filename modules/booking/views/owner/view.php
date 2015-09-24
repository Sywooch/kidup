<?php
use Carbon\Carbon;
use yii\helpers\Html;

/**
 * @var $booking \app\modules\booking\models\Booking
 * @var $item \app\modules\item\models\Item
 */
app\modules\booking\assets\BookingViewsAsset::register($this);
?>
<section id="booking">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
                <h2><?= Yii::t("booking", "Booking #{0}", [
                        $booking->id
                    ]) ?>
                </h2>

                <div class="card card-product" id="product">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-1">
                            <h4><?= Yii::t("booking", "Details") ?></h4>
                            <ul class="list-unstyled list-lines">
                                <li>
                                    <?= Yii::t("booking", "Item") ?>
                                    <b class="pull-right">
                                        <?= Html::a($item->name, '@web/item/' . $item->id) ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Dates") ?> <b class="pull-right">
                                        <?= Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                                        - <?= Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Renter") ?> <b class="pull-right">
                                        <?= Html::a($booking->renter->profile->first_name . ' ' . $booking->renter->profile->last_name,
                                            '@web/user/' . $booking->renter_id) ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Booking Status") ?> <b class="pull-right">
                                        <?= $booking->getStatusName() ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Pickup Address") ?> <b class="pull-right">
                                        <?= $item->location->street_name ?> <?= $item->location->street_number ?>
                                        , <?= $item->location->city ?>
                                    </b>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h4><?= Yii::t("booking", "Payment Information") ?></h4>
                            <ul class="list-unstyled list-lines">
                                <li>
                                    <?= Yii::t("booking", "Total to receive") ?>
                                    <b class="pull-right">
                                        <?= $booking->amount_payout ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "KidUp Service Fees") ?>
                                    <b class="pull-right">
                                        <?= $booking->amount_item - $booking->amount_payout ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Expected Payout Date") ?>
                                    <b class="pull-right">
                                        <?= Carbon::createFromTimestamp($booking->time_from + 24 * 60 * 60 * 7,
                                            \Yii::$app->params['serverTimeZone'])->toDateString() ?>
                                    </b>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-2">
                            <h4>
                                <?= Yii::t("booking", "Actions") ?>
                            </h4>
                            <?= Html::a(\Yii::t('booking', 'Contact Renter'),
                                '@web/inbox/' . $booking->getConversationId()) ?>
                            <?= Html::a(\Yii::t('booking', 'View Receipt'), '@web/booking/' . $booking->id . '/receipt',
                                ['target' => '_blank']) ?>
                            <?= Html::a(\Yii::t('booking', 'View Invoice'), '@web/booking/' . $booking->id . '/invoice',
                                ['target' => '_blank']) ?>
                        </div>
                    </div>
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
    </div>

</section>
