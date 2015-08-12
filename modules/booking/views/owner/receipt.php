<?php
use yii\helpers\Html;
use Carbon\Carbon;
use yii\helpers\Url;
use app\modules\booking\models\Payout;
use app\modules\images\components\ImageHelper;
/**
 * @var $booking \app\modules\booking\models\Booking
 * @var $item \app\modules\item\models\Item
 */
?>
<br/><br/>
<section class="section container" id="checkout">
    <div class="row" >
        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
            <div class="site-area-header">
                <div class="checkout-header">
                    <h2><?= Yii::t("booking", "Receipt #RO-{0}", [
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
                            <b class="pull-right">KidUp</b><br/>
                            <?= \Yii::$app->params['kidupAddressLine1'] ?> <br/>
                            <?= \Yii::$app->params['kidupAddressLine2'] ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div style="text-align: right">
                            <br/>
                            <?= ImageHelper::img('kidup/logo/horizontal.png', ['w' => 100]) ?>
                            <br/>
                            <b class="pull-right"><?= Yii::t("booking", "Generated on {0}", [
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
                                <b class="pull-right">
                                    <?= \Yii::$app->user->identity->profile->first_name . ' ' . \Yii::$app->user->identity->profile->last_name ?>
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
                                    <?=  $booking->renter->profile->first_name . ' ' .$booking->renter->profile->last_name ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Pickup Address") ?> <b class="pull-right">
                                    <?= $item->location->street_name ?> <?= $item->location->street_number ?>, <?= $item->location->city ?>, <?= $item->location->country0->name ?>
                                </b>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-md-offset-2">
                        <ul class="list-unstyled list-lines">
                            <li>
                                <?= Yii::t("booking", "Item rent for {0} days",[
                                    Carbon::createFromTimestamp($booking->time_from)->diffInDays(Carbon::createFromTimestamp($booking->time_to))
                                ]) ?>
                                <b class="pull-right">
                                    <?= $booking->amount_item ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "KidUp Service Fees") ?>
                                <b class="pull-right">
                                    <?= $booking->amount_item - $booking->amount_payout ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Total") ?>
                                <b class="pull-right">
                                    <?= $booking->amount_payout ?> DKK
                                </b>
                            </li>
                            <br/><br/>
                            <li>
                                <?= Yii::t("booking", "Total received") ?>
                                <b class="pull-right">
                                    <?= isset($booking->payout) && $booking->payout->status == Payout::STATUS_PROCESSED ? $booking->payout->amount : 0 ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Balance") ?>
                                <b class="pull-right">
                                    <?= isset($booking->payout) && $booking->payout->status == Payout::STATUS_PROCESSED ? 0 : $booking->amount_payout  ?> DKK
                                </b>
                            </li>
                        </ul>
                    </div>
                </div>
                <br/><br/><br/>
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
        </div>
    </div>
</section>

