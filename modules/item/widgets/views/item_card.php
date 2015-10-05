<?php
use \images\components\ImageHelper;
use yii\helpers\Url;
use \user\widgets\UserImage;
use \review\widgets\ReviewScore;
/**
 * @var \app\extended\web\View $this
 * @var \item\models\Item $model
 * @var string $rowClass
 */

\item\assets\ItemAsset::register($this);

?>

<div class="<?= $rowClass ?>">
    <a href="<?= Url::to('@web/item/' . $model->id) ?>" data-pjax="0">
        <div class="card">
            <div class="image"
                 style="<?= ImageHelper::bgImg($model->getImageName(0),
                     ['q' => 90, 'w' => 300]) ?>; background-size: cover; background-position: 50% 50%;">
                <div class="price-badge">
                    <span class="time">
                        <?= Yii::t("item.card.from", "from") ?>
                    </span>
                    <span class="currency">kr.</span>
                    <span class="price">
                        <?= $model->price_day !== null ? $model->price_day : round($model->price_week / 7) ?>
                    </span>
                </div>
                <div class="author">
                    <?= UserImage::widget([
                        'user_id' => $model->owner_id,
                        'width' => '50px'
                    ]) ?>
                </div>
            </div>
            <div class="content">
                <h3 class="title" style="height:20px;">
                    <?= $model->name ?>
                </h3>

                <div class="category">
                    <?= $model->category->getTranslatedName() ?>
                </div>

                <div class="footer-divs">
                    <div class="reviews">
                        <?= ReviewScore::widget([
                            'user_id' => $model->owner_id,
                            'reviewCount' => $widget->reviewCount
                        ]) ?>
                    </div>
                    <div class="location">
                        <i class="fa fa-map-marker"></i>
                        <?php
                        $distance = (int)$model->distance;
                        if ($model->location !== null) {
                            if ($distance == 0 || !$showDistance) {
                                echo $model->location->city;
                            } else {
                                echo $distance . ' km';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>