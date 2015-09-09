<?php

use app\components\WidgetRequest;
use app\modules\images\components\ImageHelper;
use app\modules\item\models\Category;
use app\modules\item\models\Item;
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

<div id="product">
    <div class="parallax filter-black"
         style="<?= ImageHelper::bgImg($model->getImageName(0)) ?>">
    </div>

    <section id="content" class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-7 col-md-offset-1">
                    <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE,
                        [
                            'user_id' => $model->owner_id,
                            'width' => '200px'
                        ])
                    ?>

                    <h4 class="title">
                        <?= $model->owner->profile->first_name ?>
                    </h4>
                    <a href="<?= Url::to('@web/user/' . $model->owner_id) ?>" class="btn btn-primary">
                        <?= Yii::t("user", "See profile") ?>
                    </a>

                    <div class="card">
                        <div class="content product-content">
                            <h2 class="title"><?= $model->name ?></h2>
                            <h4 class="category">
                                <?= implode(", ", $model->getCategoriesByType(Category::TYPE_MAIN)); ?>
                            </h4>

                            <p class="description">
                                <?= nl2br($model->description) ?>
                            </p>
                        </div>
                    </div>

                    <div class="card card-product">
                        <div class="content">
                            <h4 class="category"><b>
                                    <?= Yii::t("item", "Product info") ?>
                                </b>
                            </h4>

                            <div id="product-info" class="row">
                                <div class="col-sm-6">
                                    <ul class="list-unstyled list-lines">
                                        <li>
                                            <i class="fa fa-magic"></i><?= Yii::t("item", "Publish date") ?>
                                            <b>
                                                <?= \Carbon\Carbon::createFromTimestamp($model->created_at)->formatLocalized('%d %B %Y'); ?>
                                            </b>
                                        </li>

                                        <li>
                                            <i class="fa fa-magic"></i><?= Yii::t("item", "Condition") ?>
                                            <b><?= Item::getConditions()[$model->condition] ?></b>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled list-lines">
                                        <li>
                                            <i class="fa fa-map-marker"></i><?= Yii::t("item", "Location") ?> <b>
                                                <?= $model->location->country0->name . ", " . $model->location->city ?></b>
                                        </li>

                                        <li>
                                            <i class="fa fa-map-marker"></i><?= Yii::t("item", "Special") ?>
                                            <b>
                                                <?= implode("<br>",
                                                    $model->getCategoriesByType(Category::TYPE_SPECIAL)); ?>
                                            </b>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="category"><b>
                                            <?= Yii::t("item", "Pricing info") ?>
                                        </b></h4>

                                    <ul class="list-unstyled list-lines">
                                        <?php if (is_int($model->price_day)): ?>
                                            <li>
                                                <?= Yii::t("item", "Per day") ?>
                                                <b>
                                                    <?= $model->price_day; ?>
                                                </b>
                                            </li>
                                        <?php endif; ?>
                                        <li>
                                            <?= Yii::t("item", "Per week") ?>
                                            <b>
                                                <?= $model->price_week; ?>
                                            </b>
                                        </li>
                                        <?php if (is_int($model->price_day)): ?>
                                            <li>
                                                <?= Yii::t("item", "Per month") ?>
                                                <b>
                                                    <?= $model->price_month; ?>
                                                </b>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="col-sm-6">
                                    <h4 class="category"><b><?= Yii::t("item", "Ages") ?></b></h4>

                                    <?php
                                    $names = [];
                                    foreach ($model->getCategoriesByType(Category::TYPE_AGE) as $cat) {
                                        $names[] = "<div class='label label-primary'>" . $cat . "</div>";
                                    }
                                    echo implode("<br>", $names);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row item-images">
                        <?= Gallery::widget([
                            'items' => $images,
                        ]); ?>
                    </div>

                    <?php if (count($related_items) > 0): ?>
                        <div class="content">
                            <h4 class="category"><b><?= Yii::t('item', 'Related products') ?></b></h4>

                            <div class="related">
                                <div class="row">
                                    <?php foreach ($related_items as $item) {
                                        echo $this->render('item', [
                                            'model' => $item
                                        ]);
                                    } ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <?php echo $this->render('booking_widget', [
                    'model' => $bookingForm,
                ]) ?>
            </div>
        </div>


        <div class="container-fluid" style="margin-bottom:-90px;">
            <div class="row">
                <div class="map1">
                    <div class="google1">
                        <?= Map::widget([
                            'longitude' => $location->longitude,
                            'latitude' => $location->latitude,
                            'addRandCircle' => true
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

