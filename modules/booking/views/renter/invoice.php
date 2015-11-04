<?php
use Carbon\Carbon;
use images\components\ImageHelper;

/**
 * @var \app\extended\web\View $this
 * @var array $invoice
 */
\booking\assets\BookingViewsAsset::register($this);
$this->assetPackage = \app\assets\Package::BOOKING;
?>
<section id="booking">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
                <h2><?= Yii::t("booking.invoice.header", "Invoice #{0}", [
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
                                <b><?= Yii::t("booking.invoice.generated_at", "Generated on {0}", [
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
                                    <?= Yii::t("booking.invoice.name", "Name") ?>
                                    <b class="pull-right">
                                        <?= $invoice['data']['renterProfile']['first_name'] ?> <?= $invoice['data']['renterProfile']['last_name'] ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.invoice.address", "Address") ?>
                                    <b class="pull-right">
                                        <?= $invoice['data']['renterAddress'] ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.invoice.date_of_invoice_render", "Date of Service Rendered") ?> <b class="pull-right">
                                        <?= Carbon::createFromTimestamp($invoice['data']['booking']['time_from'],
                                            \Yii::$app->params['serverTimeZone'])->toFormattedDateString() ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.invoice.item", "Item") ?> <b class="pull-right">
                                        <?= $invoice['data']['item']['name'] ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.invoice.vat_country", "VAT Country") ?> <b class="pull-right">
                                        <?= $invoice['data']['renterCountry']['name'] ?>
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.invoice.vat_rate", "VAT Rate") ?> <b class="pull-right">
                                        <?= $invoice['data']['renterCountry']['vat'] ?>%
                                    </b>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3 col-md-offset-2">
                            <h3><?= Yii::t("booking.invoice.service_fee_header", "KidUp Service Fee for use of the platform") ?></h3>
                            <ul class="list-unstyled list-lines">
                                <li>
                                    <?= Yii::t("booking.invoice.service_fee", "KidUp Service Fee") ?>
                                    <b class="pull-right">
                                        <?= round($invoice['data']['booking']['amount_payin_fee'], 2) ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.invoice.vat", "VAT") ?>
                                    <b class="pull-right">
                                        <?= round($invoice['data']['booking']['amount_payin_fee_tax'], 2) ?> DKK
                                    </b>
                                </li>
                                <li>
                                    <?= Yii::t("booking.invoice.total", "Total") ?>
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
                <?= Yii::t("booking.invoice.no_right_from_this_page", "No rights can be derived from the contents of this page.") ?>
            </span>
                <?php if (!isset($_GET['pdf'])): ?>
                    <a href="?pdf=true" target="_blank">
                        <div class="pull-right btn btn-primary">
                            <?= Yii::t("booking.invoice.print_invoice_button", "Print") ?>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</section>

