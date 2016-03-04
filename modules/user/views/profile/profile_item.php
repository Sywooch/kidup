<?php
use item\widgets\ItemCard;

/**
 * @var $model \item\models\item\Item
 *
 */
?>
<?= ItemCard::widget([
    'model' => $model,
]); ?>
