<?php

/**
 * @var app\components\extended\View $this
 * @var \app\modules\item\models\Item $item
 * @var \yii\data\ActiveDataProvider $provider
 */


$this->title = \Yii::t('title', 'Your Current Bookings') . ' - ' . Yii::$app->name;
?>
<section class="section" id="rentals">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-7">
                    <h2><?= Yii::t("booking", "Bookings") ?><br>
                        <small><?= Yii::t("booking", "All the bookings from item {0}", [
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
