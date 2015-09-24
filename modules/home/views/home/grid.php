<?php
/**
 * @property \app\modules\item\models\Item[] $items
 */
use app\modules\images\components\ImageHelper;
use app\modules\item\widgets\ItemCard;
use yii\helpers\Url;

$babyClothes = \Yii::t('categories_and_features', 'Baby Clothes');
$baby = \Yii::t('categories_and_features', 'Baby Necessities');
$onTheRoad = \Yii::t('categories_and_features', 'On the Road');
$toys = \Yii::t('categories_and_features', 'Toys');
$toysOutside = \Yii::t('categories_and_features', 'Toys Outside');
$furniture = \Yii::t('categories_and_features', "Children's Furniture");
?>

<!--Area for all categories-->
<Section id="content-home-categories">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2><?= Yii::t("home", "Find Kids stuff") ?></h2>
            </div>
        </div>
        <!--new row with stuff-->
        <div class="row">
            <div class="col-md-4">
                <a href="<?= Url::to('@web/search/'.$toysOutside) ?>">
                    <div class="card card-background">
                        <div class="image"
                             style="<?= ImageHelper::bgCoverImg('kidup/categories/bike2.png', ['q' => 90, 'w' => 500]) ?>">
                        </div>
                        <div class="content text-center">
                            <h2 class="category-name"><?= $toysOutside ?></h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="<?= Url::to('@web/search/'.$onTheRoad) ?>">
                    <div class="card  card-background">
                        <div class="image" style="<?= ImageHelper::bgCoverImg('kidup/categories/stroller2.png',
                            ['q' => 90, 'w' => 300]) ?>">
                        </div>
                        <div class="content text-center">
                            <h2 class="category-name"><?= $onTheRoad ?></h2>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-5">
                <a href="<?= Url::to('@web/search/'.$furniture) ?>">
                    <div class="card  card-background">
                        <div class="image"
                             style="<?= ImageHelper::bgCoverImg('kidup/categories/room2.png', ['q' => 90, 'w' => 500]) ?>">
                        </div>
                        <div class="content text-center">
                            <h2 class="category-name"><?= $furniture ?></h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!--new row with stuff-->
        <div class="row">
            <div class="col-md-3">

                <a href="<?= Url::to('@web/search/'.$toys) ?>">
                    <div class="card  card-background">
                        <div class="image"
                             style="<?= ImageHelper::bgCoverImg('kidup/categories/toy2.png', ['q' => 90, 'w' => 470]) ?>">
                        </div>
                        <div class="content text-center">
                            <h2 class="category-name"><?= $toys ?></h2>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-5">
                <a href="<?= Url::to('@web/search/'.$babyClothes) ?>">
                    <div class="card  card-background">
                        <div class="image" style="<?= ImageHelper::bgCoverImg('kidup/categories/playfull2.png',
                            ['q' => 90, 'w' => 600]) ?>; background-position: 50% 20%;">
                        </div>
                        <div class="content text-center">
                            <h2 class="category-name"><?= $babyClothes ?></h2>
                        </div>
                    </div>
                    <!-- end card  card-background -->
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?= Url::to('@web/search/'.$baby) ?>">
                    <div class="card  card-background">
                        <div class="image" style="<?= ImageHelper::bgCoverImg('kidup/categories/carseat2.png',
                            ['q' => 90, 'w' => 400]) ?>">
                        </div>
                        <div class="content text-center">
                            <h2 class="category-name"><?= $baby ?></h2>
                        </div>
                    </div>
                    <!-- end card  card-background -->
                </a>
            </div>
        </div>
        <div class="row">
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
        <div class="row hidden-xs" style="margin-bottom: 20px;">
            <div class="text-center" style="font-size: 22px;margin-bottom:20px;margin-top:20px;">
                <?= Yii::t("home", "We are honered to be featured on") ?>
            </div>
            <div class="col-md-2 col-md-offset-1">
                <?= ImageHelper::image('kidup/home/mentions/aarhus_stiftidende.png', ['w' => 200], [
                    'style' => 'opacity:0.5;'
                ]) ?>
            </div>
            <div class="col-md-2">
                <?= ImageHelper::image('kidup/home/mentions/dr.png', ['w' => 200], [
                    'style' => 'opacity:0.5;margin-top: 17px;'
                ]) ?>
            </div>
            <div class="col-md-2">
                <?= ImageHelper::image('kidup/home/mentions/radio24-7.png', ['w' => 200], [
                    'style' => 'opacity:0.5;'
                ]) ?>
            </div>
            <div class="col-md-2">
                <?= ImageHelper::image('kidup/home/mentions/startup-weekend.png', ['w' => 200], [
                    'style' => 'opacity:0.5; margin-top: 10px;'
                ]) ?>
            </div>
            <div class="col-md-2">
                <?= ImageHelper::image('kidup/home/mentions/trendsonline.png', ['w' => 200], [
                    'style' => 'opacity:0.5; margin-top: 15px;'
                ]) ?>
            </div>
        </div>
    </div>
</Section>
<!--Area for steps explanation-->