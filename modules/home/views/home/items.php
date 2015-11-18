<?php

use app\helpers\ViewHelper;
use item\widgets\ItemCard;

?>
<Section id="categories">
    <div class="container">
        <div class="col-sm-12 text-center">
            <h2><?= Yii::t("home.grid.items.header", "Rent this now") ?></h2>
        </div>
        <div class="row" <?= ViewHelper::trackClick("home.click_item") ?>>
            <?php
            foreach ($items as $i => $item) {
                if ($i > 6) {
                    break;
                }
                echo ItemCard::widget([
                    'model' => $item,
                    'showDistance' => true
                ]);
            }
            ?>
        </div>
    </div>
</Section>