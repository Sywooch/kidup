<?php
use item\widgets\ItemCard;
use yii\helpers\Url;

/**
 * @var $model \item\models\Item
 *
 */
?>
<?= ItemCard::widget([
    'model' => $model,
]); ?>
