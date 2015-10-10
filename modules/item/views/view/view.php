<?php

use \item\widgets\ItemCard;
use app\widgets\Map;
use yii\helpers\Url;
use \item\widgets\Gallery;
use \review\widgets\ReviewScore;
use \user\widgets\UserImage;
use \yii\widgets\ListView;
use Carbon\Carbon;

/**
 * @var yii\web\View $this
 * @var array $images
 * @var string $bookingForm
 * @var \item\models\Item $model
 * @var \item\models\Location $location
 * @var bool $show_modal
 * @var \yii\data\ActiveDataProvider $reviewDataProvider
 * @var \item\models\Item[] $related_items
 */


\app\assets\FontAwesomeAsset::register($this);
\yii\jui\JuiAsset::register($this);
\dosamigos\gallery\GalleryAsset::register($this);
\item\assets\ViewAsset::register($this);
\app\assets\LodashAsset::register($this);
\yii\widgets\PjaxAsset::register($this);

$this->title = ucfirst($model->name) . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::ITEM_VIEW;
?>
<?= $show_modal ? $this->render('share_modal', ['model' => $model]) : '' ?>

<div id="item">
    <section id="content" class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-offset-1" id="pageInfo">
                    <div class="row main-info">
                        <div class="col-md-2 owner">
                            <a href="<?= Url::to('@web/user/' . $model->owner_id) ?>">
                                <?= UserImage::widget(
                                    [
                                        'user_id' => $model->owner_id,
                                        'width' => '80px',
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
                                <?= $model->location ? $model->location->city . ", " . $model->location->country0->name : '' ?>
                            </div>
                            <div class="item-reviews">
                                <?= ReviewScore::widget(['user_id' => $model->owner_id]); ?>
                            </div>
                            <div class="item-category">
                                <?= $model->category->parent->getTranslatedName() . " - " .
                                $model->category->getTranslatedName() ?>
                            </div>
                        </div>
                    </div>

                    <div id="galleryPhotoViewer">
                        <?= Gallery::widget([
                            'items' => $images,
                            'id' => 'galleryPhotoViewer',
                            'clientOptions' => [
                                'gallery' => true,
                                'stretchImages' => true,
                            ],
                            'options' => [
                                'class' => 'smallImages'
                            ]
                        ]) ?>
                    </div>


                    <div class="card">
                        <div class="content product-content">
                            <h4>
                                <?= Yii::t("item.view.header_about_product", "About this product") ?>
                            </h4>

                            <p class="description">
                                <?= nl2br($model->description) ?>
                            </p>
                        </div>
                    </div>

                    <div class="card card-product">
                        <div class="content">
                            <h4>
                                <?= Yii::t("item.view.header.product_info", "Product info") ?>
                            </h4>

                            <div id="product-info" class="row">
                                <div class="col-sm-6">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <?= Yii::t("item.view.publish_date", "Publish date") ?>
                                            </td>
                                            <td>
                                                <b>
                                                    <?= Carbon::createFromTimestamp($model->created_at)->formatLocalized('%d %B %Y'); ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?= Yii::t("item.view.location", "Location") ?>
                                            </td>
                                            <td>
                                                <b>
                                                    <?= $model->location ? $model->location->city . ", " . $model->location->country0->name : '' ?>
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?= Yii::t("item.view.pricing", "Pricing") ?>
                                            </td>
                                            <td>
                                                <b>
                                                    <?php
                                                    if (is_int($model->price_day)) {
                                                        echo $model->price_day . ' ' .
                                                            Yii::t("item.view.per_day", "per day") . "<br>";
                                                    }
                                                    echo $model->price_week . ' ' . Yii::t("item.view.per_week",
                                                            "per week");
                                                    if (is_int($model->price_month)) {
                                                        echo "<br>" . $model->price_month . ' ' . Yii::t("item.view.per_month",
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
                                                <?= Yii::t("item.view.features", "Features") ?>
                                            </td>
                                            <td>
                                                <?php
                                                foreach ($model->singularFeatures as $feature) {
                                                    echo $feature->getTranslatedName() . "<br>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php foreach ($model->itemHasFeatures as $ihf): ?>
                                            <tr>
                                                <td>
                                                    <?= $ihf->feature->getTranslatedName() ?>
                                                </td>
                                                <td>
                                                    <b>
                                                        <?= $ihf->featureValue->getTranslatedName() ?>
                                                    </b>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <div style="margin-left:-25px;margin-right:-25px;">
                                    <div class="map1">
                                        <div class="google1">
                                            <?= $location ? Map::widget([
                                                'longitude' => $location->longitude,
                                                'latitude' => $location->latitude,
                                                'addRandCircle' => true,
                                                'options' => [
                                                    'height' => "400px"
                                                ]
                                            ]) : ''; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4><b><?= Yii::t('item.view.reviews_header', 'Reviews') ?></b></h4>

                    <?= ListView::widget([
                        'dataProvider' => $reviewDataProvider,
                        'itemView' => 'item_review',
                        'itemOptions' => ['tag' => 'span'],
                    ]) ?>

                    <?php if (count($related_items) > 0): ?>
                        <h4><b><?= Yii::t('item.view.related_products_header', 'Related products') ?></b></h4>

                        <div class="related">
                            <div class="row">
                                <?php foreach ($related_items as $item) {
                                    echo ItemCard::widget([
                                        'model' => $item,
                                        'showDistance' => false,
                                        'numberOfCards' => 2,
                                        'titleCutoff' => 30,
                                        'reviewCount' => true
                                    ]);
                                } ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div id="bookingWidget">
                    <div class="visible-xs visible-sm visible-md">
                        <div style="display:none !important;" id="mobileCloseBookingRequest">
                            <i class="fa fa-close"></i>
                        </div>
                    </div>
                    <?php echo $this->render('booking_widget', [
                        'model' => $bookingForm,
                    ]) ?>
                </div>
                <div id="booking-is-being-created-message" class="col-md-3 card" style="display: none;margin-top:30px;">
                    <div style="padding:15px;">
                        <?= Yii::t("item.view.booking_widget.booking_is_being_created",
                            "You're booking is being made, one second please.") ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="buttonContainer" style="z-index:10;">
            <button class="btn btn-fill btn-danger mobileBookingRequestButton visible-sm visible-xs">
                <?= \Yii::t('item.view.booking_widget.request_button', 'Request to Book') ?>
            </button>
        </div>
    </section>
    <?= \Yii::$app->user->isGuest ? $this->render('signup_widget', [
        'owner' => $model->owner
    ]) : '' ?>
</div>
