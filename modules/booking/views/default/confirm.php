<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\booking\models\BrainTree;
use \app\modules\booking\models\Payin;
use \app\modules\images\components\ImageHelper;
use app\modules\review\widgets\ReviewScore;

/*
 * @var yii\web\View $this
 * @var app\modules\booking\models\Booking $booking
 * @var \app\modules\booking\forms\Confirm $model
 * @var \app\modules\user\models\Profile $profile
 */

\app\modules\booking\assets\ConfirmAsset::register($this);
\yii\web\JqueryAsset::register($this);

$this->title = \Yii::t('title', 'Confirm Your Rent') . ' - ' . Yii::$app->name;
$clientToken = (new BrainTree(new Payin()))->getClientToken();
?>
<section class="section" id="checkout">
    <div class="container">
        <h2>
            <?= Yii::t("booking", "Secure Booking - Pay in 1 Minute") ?>
        </h2>

        <div class="row">
            <div class="col-md-8">
                <?php $form = ActiveForm::begin([
                    'enableClientValidation' => true,
                    'fieldClass' => 'justinvoelker\awesomebootstrapcheckbox\ActiveField',
                ]); ?>

                <div class="card">
                    <h3>
                        <?= Yii::t("booking", "Message to {0}",[
                            $profile->first_name
                        ]) ?>
                    </h3>
                    <?= Yii::t('booking',
                        "Please tell {owner} a little about why your booking this item, why you like the product so much and how you'd like to arrange a meeting to transfer the item.",
                        [
                            'owner' => $profile->first_name
                        ]) ?>
                    <br><br>
                    <?= $form->field($model, 'message')->textarea([
                        'class' => 'form-control',
                        'placeholder' => \Yii::t('booking', 'Your private message to {0}',
                            [$profile->first_name])
                    ])->label(false) ?>
                </div>

                <div class="card">
                    <h3>
                        <?= Yii::t("booking", "Payment information") ?>
                    </h3>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <?= ImageHelper::img('kidup/booking/creditcards/dankort.png', ['q' => 90]) ?></div>
                                <div class="col-md-3">
                                    <?= ImageHelper::img('kidup/booking/creditcards/visa.png', ['q' => 90]) ?></div>
                                <div class="col-md-3">
                                    <?= ImageHelper::img('kidup/booking/creditcards/mastercard.png') ?></div>
                                <div class="col-md-3">
                                    <?= ImageHelper::img('kidup/booking/creditcards/amex.png', ['q' => 90]) ?></div>
                            </div>
                        </div>
                    </div>
                    <div id="payment-form"></div>
                    <?php
                    $this->registerJs('braintree.setup("' . $clientToken . '", "dropin", { container: "payment-form" });');
                    $this->registerJsFile('https://js.braintreegateway.com/v2/braintree.js');
                    ?>
                </div>

                <div class="card">
                    <h3><?= Yii::t("booking", "Review and book") ?></h3>

                    <div class="well">
                        <?= $form->field($model, 'rules')->checkbox() ?>
                    </div>
                    <?= Html::submitButton('Book now', ['class' => 'btn btn-lg btn-fill btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
            <div class="col-md-4">
                <div class="row card card-minimal">

                    <div class="col-md-4">
                        <?= ImageHelper::image($item->getImageName(0), ['w' => 70, 'h' => 70, 'fit' => 'crop']) ?>
                    </div>
                    <div class="col-md-8">
                        <b>
                            <?= $item->name ?>
                        </b>
                        <br>
                        <?= $item->location->city ?>
                    </div>
                </div>

                <div class="row card card-minimal">

                    <div class="col-md-4">
                        <?= ImageHelper::image($profile->img, ['w' => 70, 'h' => 70, 'fit' => 'crop']) ?>
                    </div>
                    <div class="col-md-8">
                        <?= Yii::t("booking", "You are renting from") ?>
                        <br>
                        <b>
                            <?= $profile->first_name ?>
                        </b>
                        <?= ReviewScore::widget(['user_id' => $profile->user_id]) ?>
                        <?= \Yii::t('booking', 'Member since {0}',[
                            Carbon\Carbon::createFromTimestamp($profile->user->created_at)->formatLocalized('%b %y')
                        ])?>
                    </div>
                </div>


                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-12">
                        <?= Yii::t("booking", "Starting day") ?>:
                        <?= Carbon\Carbon::createFromTimestamp($booking->time_from)->formatLocalized('%d %B'); ?>
                        <br>
                        <?= Yii::t("booking", "Ending day") ?>:
                        <?= Carbon\Carbon::createFromTimestamp($booking->time_to)->formatLocalized('%d %B'); ?>
                    </div>
                </div>

                <table class="table">
                    <tr>
                        <td>
                            <?= $tableData['price'][0] ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?= $tableData['price'][1] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= $tableData['fee'][0] ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?= $tableData['fee'][1] ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?= $tableData['total'][0] ?>
                        </td>
                        <td style="font-weight: 600;">
                            <?= $tableData['total'][1] ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
