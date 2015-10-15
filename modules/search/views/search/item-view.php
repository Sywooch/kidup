<?php
// a simple view that renders an item
use item\widgets\ItemCard;

echo ItemCard::widget([
    'model' => $model,
    'showDistance' => true,
    'numberOfCards' => 1
]);
?>