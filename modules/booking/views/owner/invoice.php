<?php
use yii\helpers\Html;
use Carbon\Carbon;
use yii\helpers\Url;
use app\modules\booking\models\Payout;
/**
 * @var $booking \app\modules\booking\models\Booking
 * @var $item \app\modules\item\models\Item
 */
?>
<br/><br/>
<section class="section" id="checkout">
    <div class="row">
        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
            <div class="site-area-header">
                <div class="checkout-header">
                    <h2><?= Yii::t("booking", "Invoice #IO-{0}", [
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
                            <img src="<?=Url::to('@assets/img/logo/horizontal.png', true) ?>" width="100px"/>
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
                                <?= Yii::t("booking", "Address") ?>
                                <b class="pull-right">
                                    <?php  $l = \Yii::$app->user->identity->locations[0];
                                    $loc = [];
                                    $loc[] = $l->street_name . ' ' . $l->street_number;
                                    $loc[] = $l->zip_code;
                                    $loc[] = $l->country0->name;
                                    echo implode(',', $loc);
                                    ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Date of Service Rendered") ?> <b class="pull-right">
                                    <?= Carbon::createFromTimestamp($booking->time_from, \Yii::$app->params['serverTimeZone'])->toFormattedDateString() ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Item") ?> <b class="pull-right">
                                    <?= $item->name ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "VAT Country") ?> <b class="pull-right">
                                    <?= \Yii::$app->user->identity->locations[0]->country0->name ?>
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "VAT Rate") ?> <b class="pull-right">
                                    <?= \Yii::$app->user->identity->locations[0]->country0->vat ?>%
                                </b>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-md-offset-2">
                        <h3><?= Yii::t("booking", "KidUp Service Fee for use of the platform") ?></h3>
                        <ul class="list-unstyled list-lines">
                            <li>
                                <?= Yii::t("booking", "KidUp Service Fee") ?>
                                <b class="pull-right">
                                    <?= round($booking->amount_payout_fee,2) ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "TAX") ?>
                                <b class="pull-right">
                                    <?= round($booking->amount_payout_fee_tax,2) ?> DKK
                                </b>
                            </li>
                            <li>
                                <?= Yii::t("booking", "Total") ?>
                                <b class="pull-right">
                                    <?= round($booking->amount_payout_fee + $booking->amount_payout_fee_tax,2) ?> DKK
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

