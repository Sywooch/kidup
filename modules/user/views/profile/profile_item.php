<?php
use item\widgets\ItemCard;

/**
 * @var $model \item\models\Item
 *
 */
?>
<?= ItemCard::widget([
    'model' => $model,
]); ?>
