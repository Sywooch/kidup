<?php
/**
 * @property \app\modules\item\models\Item[] $items
 */
use app\modules\images\components\ImageHelper;
use app\modules\item\widgets\ItemCard;
use yii\helpers\Url;

?>

<!--Area for all categories-->
<Section id="content-home-categories">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2><?= Yii::t("home", "Find Kids stuff") ?></h2>
                <h4><?= Yii::t("home", "Find the things which fits your family") ?></h4>
            </div>
        </div>
        <!--new row with stuff-->
        <div class="row">
            <div class="col-md-4">
                <a href="<?= Url::to('@web/search?q=categories|20') ?>">
                    <div class="card card-background">
                        <div class="image"
                             style="<?= ImageHelper::bgImg('kidup/categories/bike2.png', ['q' => 90, 'w' => 500]) ?>">
                        </div>
                        <div class="content text-center">
                            <br>
                            <br>

                            <h2 class="category-name"><?= Yii::t("categories", "Bikes") ?></h2>
                        </div>
                    </div>
                    <!-- end card -->
                </a>
            </div>

            <div class="col-md-3">
                <a href="<?= Url::to('@web/search?q=categories|13') ?>">
                    <div class="card  card-background">
                        <div class="image" style="<?= ImageHelper::bgImg('kidup/categories/stroller2.png',
                            ['q' => 90, 'w' => 300]) ?>">
                        </div>
                        <div class="content text-center">
                            <br>
                            <br>

                            <h2 class="category-name"><?= Yii::t("categories", "Stroller") ?></h2>
                        </div>
                    </div>
                    <!-- end card  card-background -->
                </a>
            </div>
            <div class="col-md-5">
                <a href="<?= Url::to('@web/search?q=categories|12') ?>">
                    <div class="card  card-background">
                        <div class="image"
                             style="<?= ImageHelper::bgImg('kidup/categories/toy2.png', ['q' => 90, 'w' => 470]) ?>">
                        </div>
                        <div class="content text-center">
                            <br>
                            <br>

                            <h2 class="category-name"><?= Yii::t("categories", "Toys") ?></h2>
                        </div>
                    </div>
                    <!-- end card  card-background -->
                </a>
            </div>
        </div>
        <!--new row with stuff-->
        <div class="row">

            <div class="col-md-3">
                <a href="<?= Url::to('@web/search?q=categories|16,19') ?>">
                    <div class="card  card-background">
                        <div class="image"
                             style="<?= ImageHelper::bgImg('kidup/categories/room2.png', ['q' => 90, 'w' => 300]) ?>">
                        </div>
                        <div class="content text-center">
                            <br>
                            <br>

                            <h2 class="category-name"><?= Yii::t("categories", "Room") ?></h2>

                        </div>
                    </div>
                    <!-- end card  card-background -->
                </a>
            </div>
            <div class="col-md-5">
                <a href="<?= Url::to('@web/search?q=categories|22') ?>">
                    <div class="card  card-background">
                        <div class="image" style="<?= ImageHelper::bgImg('kidup/categories/playfull2.png',
                            ['q' => 90, 'w' => 600]) ?>; background-position: 50% 20%;">
                        </div>
                        <div class="content text-center">
                            <br>
                            <br>

                            <h2 class="category-name"><?= Yii::t("categories", "Outdoor") ?></h2>
                        </div>
                    </div>
                    <!-- end card  card-background -->
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?= Url::to('@web/search?q=categories|11') ?>">
                    <div class="card  card-background">
                        <div class="image" style="<?= ImageHelper::bgImg('kidup/categories/carseat2.png',
                            ['q' => 90, 'w' => 400]) ?>">
                        </div>
                        <div class="content text-center">
                            <br>
                            <br>

                            <h2 class="category-name"><?= Yii::t("categories", "Carseat") ?></h2>
                        </div>
                    </div>
                    <!-- end card  card-background -->
                </a>
            </div>
        </div>
        <div class="row">
            <?php
            foreach ($items as $i => $item) {
                if ($i > 6) break;
                echo ItemCard::widget([
                    'model' => $item,
                    'showDistance' => true
                ]);
            }
            ?>
        </div>
    </div>
</Section>
<!--Area for steps explanation-->