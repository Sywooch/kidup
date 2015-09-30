<?php
use yii\helpers\Html;

/**
 * @var \app\extended\web\View $this
 * @var \yii\data\ActiveDataProvider $provider
 */

$this->assetPackage = \app\assets\Package::BOOKING;

$this->title = \Yii::t('booking.list.title_current_bookings', 'Your Current Bookings') . ' - ' . Yii::$app->name;
?>
<section class="section" id="rentals">
    <div class=" site-area-header hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-sm-7">
                    <h2><?= \Yii::t('booking.list.header', "Bookings") ?><br>
                        <small><?= \Yii::t('booking.list.sub_header', "All the items other families shared with you") ?></small>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-md-2">
                <b><?= Yii::t("booking.list.menu_current_bookings", "Current Bookings") ?></b>
                <?= Html::a(Yii::t("booking.list.menu_previous_bookings", "Previous Bookings"), ['/booking/previous']); ?>
            </div>
            <div class="col-md-10 card">
                <br/>
                <?= $this->render('list', [
                    'provider' => $provider
                ]); ?>
            </div>
        </div>
    </div>
</section>
