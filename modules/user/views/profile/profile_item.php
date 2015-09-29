<?php
use \images\components\ImageHelper;
use yii\helpers\Url;
use \item\widgets\ItemCard;
/**
 * @var $model \item\models\Item
 *
 */
?>
<?= ItemCard::widget([
    'model' => $model,
]); ?>
