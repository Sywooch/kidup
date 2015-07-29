<?php

use app\components\WidgetRequest;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/*
 * @var yii\web\View $this
 * @var \app\modules\booking\models\Booking $booking
 * @var \app\modules\booking\forms\Confirm $model
 */
\app\modules\booking\assets\ConfirmAsset::register($this);
$this->title = \Yii::t('title', 'Confirm Your Rent') . ' - ' . Yii::$app->name;
?>
<section class="section" id="checkout">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-10 col-md-offset-1 text-center ">
                    <div class="checkout-header">
                        <h2>
                            <?= Yii::t("booking", "Booking") ?><br>
                            <small><?= Yii::t("booking", "Confirm your KidUp booking") ?></small>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-sm-4 col-md-3 col-md-offset-1">
                <div class="card card-image">
                    <div class="image img-rounded">
                        <img src="<?= $itemImage ?>">
                    </div>
                </div>
                <div class="card card-user card-plain">
                    <div class="content">
                        <div class="author">
                            <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                                'user_id' => $profile->user_id,
                                'width' => '200px'
                            ]) ?>

                            <h4 class="title">
                                <?= $profile->first_name ?>
                            </h4>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 col-md-7">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>
                                    <?= Yii::t("booking", "Summary") ?>
                                </h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled list-lines">
                                            <li>
                                                <i class="fa fa-info-circle"></i><?= Yii::t("booking", "item") ?>
                                                <b><?= $item->name ?></b>
                                            </li>
                                            <li>
                                                <i class="fa fa-calendar"></i><?= Yii::t("booking", "Dates") ?> <b>
                                                    <?= Carbon\Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                                                    - <?= Carbon\Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                                                </b>
                                            </li>
                                            <li>
                                                <i class="fa fa-map-marker"></i><?= Yii::t("booking", "Location") ?>
                                                <b><?= $item->location->city ?></b>
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
                                                <b>DKK <?= $booking->amount_payin - $booking->amount_item ?></b>
                                            </li>
                                            <li>
                                                <?= Yii::t("booking", "Total") ?>
                                                <b>DKK <?= $booking->amount_payin ?></b>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>
                                    <?= Yii::t("booking", "Payment") ?>
                                </h5>
                                <?php
                                    $ccForm = $model->renderCreditCardForm();
                                    echo $ccForm;
                                ?>
                                <?php if ($model->hasErrors('nounce') == true): ?>
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-2">
                                            <?= \Yii::t('app', 'Please add a payment method') ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <br/>
                        <?php $form = ActiveForm::begin([
                            'enableClientValidation' => false,
                            'fieldClass' => 'justinvoelker\awesomebootstrapcheckbox\ActiveField',
                        ]); ?>
                        <div class="row <?= strlen($ccForm) > 100 ? 'greyed-out' : '' ?>">
                            <div class="col-sm-12">
                                <h5><?= Yii::t("booking", "Message") ?>
                                    <br>
                                    <small><?= Yii::t('booking',
                                            "Please tell {owner} a little about why your booking this item, why you like the product so much and how you'd like to arrange a meeting to transfer the item.",
                                            [
                                                'owner' => $profile->first_name
                                            ]) ?></small>
                                </h5>
                                <?= $form->field($model, 'message')->textarea([
                                    'class' => 'form-control',
                                    'placeholder' => \Yii::t('booking', 'Your private message to {0}',
                                        [$profile->first_name])
                                ])->label(false) ?>
                            </div>
                        </div>

                        <div class="row <?= strlen($ccForm) > 100 ? 'greyed-out' : '' ?>">
                            <div class="col-md-12">
                                <?= $form->field($model, 'rules')->checkbox()?>

                                <?= Html::submitButton('Book now', ['class' => 'btn btn-lg btn-fill btn-danger']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
