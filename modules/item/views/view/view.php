<?php

use app\components\WidgetRequest;
use app\modules\images\components\ImageHelper;
use app\modules\item\models\Category;
use app\modules\item\models\Item;
use app\modules\item\widgets\ItemCard;
use app\widgets\Map;
use dosamigos\gallery\Gallery;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var array $images
 * @var string $bookingForm
 * @var app\modules\item\models\Item $model
 * @var app\modules\item\models\Location $location
 * @var bool $show_modal
 */

$this->title = ucfirst(\Yii::t('title', '{0}', [$model->name])) . ' - ' . Yii::$app->name;

\app\assets\FontAwesomeAsset::register($this);
\yii\jui\JuiAsset::register($this);
\dosamigos\gallery\GalleryAsset::register($this);
\app\modules\item\assets\ViewAsset::register($this);
\app\assets\LodashAsset::register($this);
\yii\widgets\PjaxAsset::register($this);
?>
<?= $show_modal ? $this->render('share_modal', ['model' => $model]) : '' ?>

<div id="item">
    <section id="content" class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-7 col-md-offset-1">
                    <div class="row main-info">
                        <div class="col-md-2">
                            <a href="<?= Url::to('@web/user/' . $model->owner_id) ?>">
                                <?= \app\modules\user\widgets\UserImage::widget(
                                    [
                                        'user_id' => $model->owner_id,
                                        'width' => '80px'
                                    ])
                                ?>
                            </a>
                            <a href="<?= Url::to('@web/user/' . $model->owner_id) ?>">
                                <div class="owner-name">
                                    <?= $model->owner->profile->first_name ?>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-10">
                            <div class="item-title"><?= $model->name ?></div>
                            <div class="item-location">
                                <?= $model->location->city . ", " . $model->location->country0->name ?>
                            </div>
                            <div class="row icon-row">
                                <div class="col-md-3">
                                    <div class="icon-text">
                                        <i class="fa fa-child"> </i>
                                        <br>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="icon-text">
                                        <i class="fa fa-bolt"> </i>
                                        <br>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="icon-text">
                                        <i class="fa fa-check"> </i>
                                        <br>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="icon-text">
                                        <i class="fa fa-check"> </i>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= Gallery::widget([
                        'items' => $images,
                        'id' => 'gallery',
                        'clientOptions' => [
                            'gallery' => true,
                            'stretchImages' => true,
                        ]
                    ]) ?>

                    <div class="card">
                        <div class="content product-content">
                            <h4>
                                <?= Yii::t("item", "About this product") ?>
                            </h4>

                            <p class="description">
                                <?= nl2br($model->description) ?>
                            </p>
                        </div>
                    </div>

                    <div class="card card-product">
                        <div class="content">
                            <h4>
                                <?= Yii::t("item", "Product info") ?>
                            </h4>

                            <div id="product-info" class="row">
                                <div class="col-sm-6">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <?= Yii::t("item", "Publish date") ?>
                                            </td>
                                            <td>
                                                <b>
                                                    <?= \Carbon\Carbon::createFromTimestamp($model->created_at)->formatLocalized('%d %B %Y'); ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?= Yii::t("item", "Condition") ?>
                                            </td>
                                            <td>
                                                <b>
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?= Yii::t("item", "Location") ?>
                                            </td>
                                            <td>
                                                <b>
                                                    <?= $model->location->city . ", " . $model->location->country0->name ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?= Yii::t("item", "Pricing") ?>
                                            </td>
                                            <td>
                                                <b>
                                                    <?php
                                                    if (is_int($model->price_day)) {
                                                        echo $model->price_day . ' ' . Yii::t("item",
                                                                "per day") . "<br>";
                                                    }
                                                    echo $model->price_week . ' ' . Yii::t("item", "per week");
                                                    if (is_int($model->price_month)) {
                                                        echo "<br>" . $model->price_month . ' ' . Yii::t("item",
                                                                "per month") . "<br>";
                                                    } ?>
                                                </b>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <?= Yii::t("item", "Ages") ?>
                                            </td>
                                            <td>
                                                <b>

                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?= Yii::t("item", "Special") ?>
                                            </td>
                                            <td>
                                                <b>
                                                </b>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div style="margin-left:-25px;margin-right:-25px;">
                                    <div class="map1">
                                        <div class="google1">
                                            <?= Map::widget([
                                                'longitude' => $location->longitude,
                                                'latitude' => $location->latitude,
                                                'addRandCircle' => true,
                                                'options' => [
                                                    'height' => "400px"
                                                ]
                                            ]); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (count($related_items) > 0): ?>
                        <h4><b><?= Yii::t('item', 'Related products') ?></b></h4>

                        <div class="related">
                            <div class="row">
                                <?php foreach ($related_items as $item) {
                                    echo ItemCard::widget([
                                        'model' => $item,
                                        'showDistance' => false,
                                        'numberOfCards' => 2,
                                        'titleCutoff' => 30
                                    ]);
                                } ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php echo $this->render('booking_widget', [
                    'model' => $bookingForm,
                ]) ?>
            </div>
        </div>
    </section>
</div>

