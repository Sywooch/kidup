<?php
use app\modules\images\components\ImageHelper;
use yii\helpers\Url;
use app\modules\item\widgets\ItemCard;
/**
 * @var $model \app\modules\item\models\Item
 *
 */
?>
<?= ItemCard::widget([
    'model' => $model,
]); ?>
