<?php
use yii\helpers\Url;
use app\components\WidgetRequest;
if($model->is_available == 0) return false;
?>

<div class="item card-width col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <a href="<?= Url::to('@web/item/'.$model->id) ?>">
        <div class="card">
            <div class="image"
                 style="background-image: url('<?= $model->getImageUrls()[0]['medium'] ?>'); background-size: cover; background-position: 50% 50%;">
                <div class="filter filter-primary"></div>
                <div class="price-badge"><span>dkk</span> <?= $model->price_week ?></div>
            </div>
            <div class="content">
                <h4 class="category">
                    <?php
                    $categories = [];
                    foreach ($model->categories as $c) {
                        if($c->type !== 'main') continue;
                        if(count($categories) > 2) continue;
                        $categories[] = $c->name;
                    }
                    echo implode($categories, ' , ');
                    ?>
                </h4>

                <h3 class="title">
                    <?= $model->name ?>
                </h3>

                <div class="footer">
                    <div class="author">
                        <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                            'user_id' => $model->owner_id,
                            'width' => '30px'
                        ])?>
                    </div>
                    <div class="stats pull-right">
                        <i class="fa fa-map-marker"></i><?= (int)$model->distance ?> km
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>