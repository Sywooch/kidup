<?php

/**
 * @var \app\extended\web\View $this
 * @var \item\models\Item $item
 * @var \yii\data\ActiveDataProvider $provider
 */


$this->title = \Yii::t('booking.view_by_item.title', 'Your Current Bookings') . ' - ' . Yii::$app->name;
?>
<section class="section" id="rentals">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-7">
                    <h2><?= Yii::t("booking.view_by_item.header", "Bookings") ?><br>
                        <small><?= Yii::t("booking.view_by_item.subheader", "All the bookings from item {0}", [
                                $item->name
                            ]) ?></small>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 card">
                <?= $this->render('list_owner', [
                    'provider' => $provider
                ]); ?>
            </div>
        </div>
    </div>
</section>
