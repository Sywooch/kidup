<?php
// todo make this a configurable item widget
use app\components\WidgetRequest;
use app\modules\images\components\ImageHelper;
use yii\helpers\Url;

?>

<div class="item card-width col-xs-12 col-sm-6 col-md-4">
    <a href="<?= Url::to('@web/item/' . $model->id) ?>">
        <div class="card">
            <div class="image"
                 style="<?= ImageHelper::bgImg($model->getImageName(0),
                     ['q' => 90, 'w' => 600]) ?>; background-size: cover; background-position: 50% 50%;">
                <div class="price-badge"><span>kr.</span> <?= $model->price_week ?> <?= Yii::t("item", "/ week") ?></div>
            </div>
            <div class="content">
                <h4 class="category">
                    <?php
                    $categories = [];
                    foreach ($model->categories as $c) {
                        if ($c->type !== 'main') {
                            continue;
                        }
                        if (count($categories) >= 2) {
                            continue;
                        }
                        $categories[] = $c->name;
                    }
                    echo implode($categories, ' ,');
                    ?>
                </h4>

                <h3 class="title" style="height:20px;">
                    <?= $model->name ?>
                </h3>

                <div class="footer">
                    <div class="stats pull-right">
                        <i class="fa fa-map-marker"></i><?= (int)$model->distance ?> km
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
