
<?php
use yii\helpers\Url;
/**
 * @var $model \app\modules\item\models\Item
 *
 */
?>
<a href="<?= Url::to('@web/item/'.$model->id) ?>">
    <div class="card-width" style="width:100%; padding: 0 15px;">
        <div class="card">
            <div class="image">
                <img src="<?= $model->getImageUrls()[0]['medium'] ?>" alt="...">
            </div>
            <div class="content">
                <p class="category">
                    <?= $model->name ?>
                </p>
            </div>
        </div>
    </div>
</a>
