<?php

use app\helpers\ViewHelper;
use images\components\ImageHelper;
use item\widgets\ItemCard;
use yii\helpers\Url;

/**
 * @var \app\extended\web\View $this
 * @var \item\models\Category[] $categories
 * @property \item\models\Item[] $items
 */

$babyClothes = $categories['Baby Clothes']->getTranslatedName();
$baby = $categories['Baby Necessities']->getTranslatedName();
$onTheRoad = $categories['On the Road']->getTranslatedName();
$toys = $categories['Toys']->getTranslatedName();
$toysOutside = $categories['Toys Outside']->getTranslatedName();
$furniture = $categories["Children's Furniture"]->getTranslatedName();
?>

<!--Area for all categories-->
<Section id="categories">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2><?= Yii::t("home.grid.header", "Find Kids stuff") ?></h2>
            </div>
        </div>
        <!--new row with stuff-->
        <?php
        $category = function ($category, $image) { ?>
            <div class="col-md-2 col-xs-6 category" style="text-align: center">
                <a href="<?= Url::to('@web/search/' . $category) ?>" <?= ViewHelper::trackClick("home.click_category",
                    $image) ?>>
                    <?= ImageHelper::image('kidup/categories/icons/' . $image . '.png', ['f' => 'png', 'w' => 200],
                        ['style' => 'max-width:100%;']) ?>
                    <div class="text-center">
                        <h4><?= $category ?></h4>
                    </div>
                </a>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-1"></div>
            <?= $category($toysOutside, 'toys-outside') ?>
            <?= $category($onTheRoad, 'on-the-road') ?>
            <?= $category($furniture, 'furniture') ?>
            <?= $category($toys, 'toys') ?>
            <?= $category($baby, 'baby-stuff') ?>
        </div>
    </div>
</Section>
<!--Area for steps explanation-->