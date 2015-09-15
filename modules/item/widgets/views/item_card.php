<?php
use app\components\WidgetRequest;
use app\modules\images\components\ImageHelper;
use yii\helpers\Url;

/**
 * @var \app\modules\item\models\Item $model
 */

// profile page, item page, search, homepage
\app\modules\item\assets\ItemAsset::register($this);


?>

<div class="<?= $rowClass ?>">
    <a href="<?= Url::to('@web/item/' . $model->id) ?>" data-pjax="0">
        <div class="card">
            <div class="image"
                 style="<?= ImageHelper::bgImg($model->getImageName(0),
                     ['q' => 90, 'w' => 300]) ?>; background-size: cover; background-position: 50% 50%;">
                <div class="price-badge">
                    <span class="currency">kr.</span>
                    <span class="price">
                        <?= $model->price_week ?>
                    </span>
                    <span class="time">
                        <?= Yii::t("item", "/ week") ?>
                    </span>
                </div>
                <div class="author">
                    <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
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
                    <?php
                    echo $model->category->name;
                    ?>
                </div>

                <div class="footer">
                    <div class="reviews">
                        <?= \app\modules\review\widgets\ReviewScore::widget([
                            'user_id' => $model->owner_id
                        ]) ?>
                    </div>
                    <div class="location">
                        <i class="fa fa-map-marker"></i>
                        <?php
                        $distance = (int)$model->distance;
                        if ($distance == 0 || !$showDistance) {
                            echo $model->location->city;
                        } else {
                            echo $distance . ' km';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>