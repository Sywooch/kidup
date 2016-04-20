<?php
use images\components\ImageHelper;
use review\widgets\ReviewScore;
use user\widgets\UserImage;
use yii\helpers\Url;

/**
 * @var \app\components\view\View $this
 * @var \item\models\item\Item $model
 * @var string $rowClass
 */
try{
    if(\Yii::$app->request->isConsoleRequest){
        $page = '';
    }else{
        $page = @\Yii::$app->request->getUrl();
    }
}catch(\ErrorException $e){
    $page = '';
}
\item\assets\ItemAsset::register($this);
$this->registerJs('window.trackItemCardView($("#item-id-' . $model->id . '"), {"item_id":' . $model->id . ',"page":"' . $page . '"});');
?>
<div class="<?= $rowClass ?>" id="item-id-<?= $model->id ?>">
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
