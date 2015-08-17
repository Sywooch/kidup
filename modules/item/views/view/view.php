<?php

use app\widgets\Map;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var array $images
 * @var app\modules\item\models\Item $model
 * @var app\modules\item\models\Location $location
 * @var bool $show_modal
 */

$this->title = ucfirst(\Yii::t('title', '{0}', [$model->name])) . ' - ' . Yii::$app->name;

\app\assets\FontAwesomeAsset::register($this);
\yii\jui\JuiAsset::register($this);
\dosamigos\gallery\GalleryAsset::register($this);
\app\modules\item\assets\ViewAsset::register($this);
?>
<?= $show_modal ? $this->render('share_modal', ['model' => $model]) : '' ?>

<div id="product">
    <div class="parallax filter-black"
         style="background-image: url('<?= isset($images[0]) ? $images[0]['url'] : '' ?>')">
    </div>
    <!--book-line-fixed -->
    <div id="booking-navbar" class="book-line booking">
        <div class="container">
            <div class="row">
                <?= $bookingForm ?>
            </div>
        </div>
    </div>
    <section id="content" class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-lg-7 col-md-offset-1">
                    <br/><br/>

                    <div class="card">
                        <div class="content product-content">
                            <h2 class="title"><?= $model->name ?></h2>
                            <h4 class="category"><?php
                                $names = [];
                                foreach ($model->categories as $cat) {
                                    if ($cat->type == \app\modules\item\models\Category::TYPE_MAIN) {
                                        $names[] = $cat->name;
                                    }
                                }
                                echo implode(", ", $names);
                                ?></h4>

                            <p class="description">
                                <?= nl2br($model->description) ?>
                            </p>
                        </div>
                    </div>

                    <!--This part is hidden for now. Then it's easier to publish a new product-->
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
                                            <b><?= \app\modules\item\models\Item::getConditions()[$model->condition] ?></b>
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
                                            <i class="fa fa-map-marker"></i><?= Yii::t("item", "Special") ?> <b>
                                                <?php
                                                $names = [];
                                                foreach ($model->categories as $cat) {
                                                    if ($cat->type == \app\modules\item\models\Category::TYPE_SPECIAL) {
                                                        $names[] = $cat->name;
                                                    }
                                                }
                                                echo implode("<br>", $names);
                                                ?></b>
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
                                    foreach ($model->categories as $cat) {
                                        if ($cat->type == \app\modules\item\models\Category::TYPE_AGE) {
                                            $names[] = "<div class='label label-primary'>" . $cat->name . "</div>";
                                        }
                                    }
                                    echo implode("<br>", $names);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row item-images">
                        <?= \dosamigos\gallery\Gallery::widget([
                            'items' => $images,
                        ]); ?>
                    </div>

                    <?php
                    if (count($related_items) > 0) {
                        ?>
                        <div class="card">
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
                        </div>
                        <?php
                    }
                    ?>

                </div>
                <div class="col-xs-12 col-sm-4 col-md-3" style="text-align: center; margin-top: 20px;">
                    <?= \app\components\WidgetRequest::request(\app\components\WidgetRequest::USER_PROFILE_IMAGE,
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
                </div>
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

