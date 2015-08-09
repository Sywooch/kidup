
<?php
use yii\helpers\Url;
use app\modules\images\components\ImageHelper;
/**
 * @var $model \app\modules\item\models\Item
 *
 */
?>
<a href="<?= Url::to('@web/item/'.$model->id) ?>">
    <div class="card-width" style="width:100%; padding: 0 15px;">
        <div class="card">
            <div class="image" style="<?= ImageHelper::bgImg($model->getImageUrls()[0], ['q' => 90, 'w' => 300]) ?>">
            </div>
            <div class="content">
                <p class="category">
                    <?= $model->name ?>
                </p>
            </div>
        </div>
    </div>
</a>
