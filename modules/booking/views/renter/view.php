<?php
use Carbon\Carbon;
use yii\helpers\Html;
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
                    <h2><?= Yii::t("booking", "Booking #{0}", [
                            $booking->id
                        ]) ?> <br>
                    </h2>
                </div>
            </div>
            <br/><br/>

            <div class="card card-product" id="product">
                <div class="row">
                    <div class="col-md-4 col-md-offset-1">
                        <h4><?= Yii::t("booking", "Details") ?></h4>
                        <ul class="list-unstyled list-lines">
                            <li>
                                <?= Yii::t("booking", "Item") ?>
                                <b>
                                    <?= Html::a($item->name, '@web/item/' . $item->id) ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Dates") ?> <b>
                                    <?= Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                                    - <?= Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Owner") ?> <b>
                                    <?= Html::a($booking->item->owner->profile->first_name . ' '. $booking->item->owner->profile->last_name,
                                        '@web/user/' . $booking->item->owner_id) ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Booking Status") ?> <b>
                                    <?= $booking->getStatusName() ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Pickup Address") ?> <b>
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
                                <?= Yii::t("booking", "Rent for {0} days",[
                                    Carbon::createFromTimestamp($booking->time_from)->diffInDays(Carbon::createFromTimestamp($booking->time_to))
                                ]) ?>
                                <b>
                                    <?= $booking->amount_item ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "KidUp Service Fees (including VAT)") ?>
                                <b>
                                    <?= $booking->amount_payin - $booking->amount_item ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Total") ?>
                                <b>
                                    <?= $booking->amount_payin?> DKK
                                </b>
                            </li>
                            <br/><br/>
                            <li>
                                <?= Yii::t("booking", "Payment Received") ?>
                                <b>
                                    <?= $booking->payin->status == Payin::STATUS_ACCEPTED ? $booking->amount_payin : 0 ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Balance") ?>
                                <b>
                                    <?= $booking->payin->status == Payin::STATUS_ACCEPTED ? 0 : $booking->amount_payin ?> DKK
                                </b>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <h4>
                            <?= Yii::t("booking", "Actions") ?>
                        </h4>
                        <?= Html::a(\Yii::t('booking', 'Contact Owner'),
                            '@web/messages/' . $booking->conversations[0]->id) ?>
                        <br/>
                        <?= Html::a(\Yii::t('booking', 'View Receipt'), '@web/booking/' . $booking->id . '/receipt') ?>
                        <br/>
                        <?= Html::a(\Yii::t('booking', 'View Invoice'), '@web/booking/' . $booking->id . '/invoice') ?>
                    </div>
                </div>
                <br/><br/>
            </div>
            <span class="pull-left">
                <?= Yii::t("booking", "No rights can be derived from the contents of this page.") ?>
            </span>
            <?php if(!isset($_GET['pdf'])): ?>
                <a href="?pdf=true" target="_blank">
                    <div class="pull-right btn btn-primary">
                        <?= Yii::t("booking", "Print") ?>
                    </div>
                </a>
            <?php endif; ?>
            <br/><br/>
        </div>
    </div>

</section>
