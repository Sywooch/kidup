<?php
use yii\helpers\Html;

$this->title = \Yii::t('booking.list.title_previous_bookings', 'Your Previous Bookings') . ' - ' . Yii::$app->name;
/**
 * @var \app\components\view\View $this
 * @var \yii\data\ActiveDataProvider $provider
 */
$this->assetPackage = \app\assets\Package::BOOKING;
?>
<section class="section" id="rentals">
    <div class=" site-area-header hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-sm-7">
                    <h2><?= Yii::t("booking.list.header_previous", "Bookings") ?><br>
                        <small><?= Yii::t("booking.list.subheader_previous", "All the items other families shared with you") ?></small>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-md-2">
                <?= Html::a(Yii::t("booking.list.menu_current_bookings", "Current Bookings"), ['/booking/current']); ?>
                <b><?= Yii::t("booking.list.menu_previous_bookings", "Previous Bookings") ?></b>
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
