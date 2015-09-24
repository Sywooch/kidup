<?php
use app\modules\images\components\ImageHelper;
use Carbon\Carbon;

/**
 * @var array $invoice
 */
app\modules\booking\assets\BookingViewsAsset::register($this);
?>
<section id="booking">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
                <h2><?= Yii::t("booking", "Invoice #{0}", [
                        $invoice['invoice_number']
                    ]) ?>
                </h2>

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
                                <?= ImageHelper::img('kidup/logo/horizontal.png', ['w' => 100]) ?>
                                <br/>
                                <b><?= Yii::t("booking", "Generated on {0}", [
                                        Carbon::createFromTimestamp($invoice['created_at'],
                                            \Yii::$app->params['serverTimeZone'])->toFormattedDateString()
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
                                        <?= $invoice['data']['renterProfile']['first_name'] ?> <?= $invoice['data']['renterProfile']['last_name'] ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Address") ?>
                                    <b class="pull-right">
                                        <?= $invoice['data']['renterAddress'] ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Date of Service Rendered") ?> <b class="pull-right">
                                        <?= Carbon::createFromTimestamp($invoice['data']['booking']['time_from'],
                                            \Yii::$app->params['serverTimeZone'])->toFormattedDateString() ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Item") ?> <b class="pull-right">
                                        <?= $invoice['data']['item']['name'] ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "VAT Country") ?> <b class="pull-right">
                                        <?= $invoice['data']['renterCountry']['name'] ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "VAT Rate") ?> <b class="pull-right">
                                        <?= $invoice['data']['renterCountry']['vat'] ?>%
                                    </b>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3 col-md-offset-2">
                            <h3><?= Yii::t("booking", "KidUp Service Fee for use of the platform") ?></h3>
                            <ul class="list-unstyled list-lines">
                                <li>
                                    <?= Yii::t("booking", "KidUp Service Fees") ?>
                                    <b class="pull-right">
                                        <?= round($invoice['data']['booking']['amount_payin_fee'], 2) ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "VAT") ?>
                                    <b class="pull-right">
                                        <?= round($invoice['data']['booking']['amount_payin_fee_tax'], 2) ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking", "Total") ?>
                                    <b class="pull-right">
                                        <?= round($invoice['data']['booking']['amount_payin_fee'] + $invoice['data']['booking']['amount_payin_fee_tax'],
                                            2) ?> DKK
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

