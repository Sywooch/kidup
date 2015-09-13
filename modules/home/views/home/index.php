<?php
use app\components\ViewHelper;
use app\modules\images\components\ImageHelper;
use yii\helpers\Url;

\app\modules\home\assets\HomeAsset::register($this);
\app\assets\AngularAsset::register($this);
$this->title = ViewHelper::getPageTitle(\Yii::t('title', 'Share Kid Stuff'));

/**
 * @var yii\web\View $this
 * @var array $images
 * @var app\modules\item\models\Item $model
 * @var app\modules\item\models\Location $location
 * @var \app\modules\home\forms\Search $searchModel
 * @var bool $show_modal
 */
?>
<div id="home">
    <!--Area for background-image, tag-line and sign-up -->
    <div id="header-home">
        <div class="header-content">
            <div class="row ">
                <div class=" col-xs-12 col-sm-12 title text-center">
                    <h1>
                        <?= \Yii::t('home', 'Share'); ?>
                        <!-- VERY important to not put any space/enters in between the strong tags, it will crash the shit out of your browser-->
                        <strong
                            id="typist-element"
                            data-typist="<?= Yii::t("home", "a stroller,a toy,a bike") ?>"><?= \Yii::t('home',
                                'a trolley') ?></strong>
                        <br/>
                        <?= \Yii::t('home', 'With a family near you') ?>
                    </h1>
                    <h4 class="hidden-xs hidden-sm">
                        <?= \Yii::t('home', 'KidUp is your online parent-to-parent marketplace.') ?>
                    </h4>

                    <div class="btn btn-default" id="how-it-works-btn">
                        <?= Yii::t("home", "How it Works") ?>
                    </div>
                    <?php $this->registerJs("$('#how-it-works-btn').click(function() {
                        $('html, body').animate({
                            scrollTop: $('#how-it-works').offset().top - 400
                        }, 1000);
                    });") ?>

                </div>
            </div>
        </div>
    </div>

    <?= $this->render('search', [
        'model' => $searchModel
    ]); ?>

    <?= $this->render('grid', [
        'categories' => $categories,
        'items' => $items,
    ]);
    ?>

    <!-- Steps explanation area-->
    <div id="content-home-steps" class="hidden-xs">
        <div class="divider">
            <?= ImageHelper::img('kidup/logo/balloon.png', ['w' => 40, 'h' => 40]) ?>
        </div>

        <div class="container ">
            <div class="row ">
                <div class="col-sm-12 text-center">
                    <h2><?= \Yii::t('home', 'How to use kidup?') ?></h2>
                    <h4><?= \Yii::t('home', 'Understand how simple it is to use KidUp?') ?></h4>
                </div>
                <div class="col-sm-12 text-center step-area">
                    <div class="row">
                        <div id="step-item-1" class="col-sm-3 step-item active">
                            <?= ImageHelper::img('kidup/graphics/find.png', ['q' => 90, 'w' => 130],
                                ['class' => 'steps']) ?>

                            <div class="number">1</div>
                            <div class="row step-lines">
                                <div class="one-v"></div>
                                <div class="one-h"></div>
                            </div>
                        </div>
                        <div id="step-item-2" class="col-sm-3 step-item">
                            <?= ImageHelper::img('kidup/graphics/photograph.png', ['q' => 90, 'w' => 130],
                                ['class' => 'steps']) ?>


                            <div class="number">2</div>
                            <div class="row step-lines">
                                <div class="two-v"></div>
                            </div>
                        </div>
                        <div id="step-item-3" class="col-sm-3 step-item">
                            <?= ImageHelper::img('kidup/graphics/pickup.png', ['q' => 90, 'w' => 130],
                                ['class' => 'steps']) ?>


                            <div class="number">3</div>
                            <div class="row step-lines">
                                <div class="three-v"></div>
                            </div>
                        </div>
                        <div id="step-item-4" class="col-sm-3 step-item">
                            <?= ImageHelper::img('kidup/graphics/review.png', ['q' => 90, 'w' => 130],
                                ['class' => 'steps']) ?>


                            <div class="number">4</div>
                            <div class="row step-lines">
                                <div class="four-v"></div>
                                <div class="four-h"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row step-description" id="how-it-works">
                        <div class="col-sm-6 col-sm-offset-3 sm">
                            <div class="step-d-1 active">
                                <h4><?= \Yii::t('home', 'Seek and Find') ?></h4>

                                <p><?= \Yii::t('home',
                                        'With KidUp you can easily seek and find the products to help your family.') ?></p>
                            </div>
                            <div class="step-d-2">
                                <h4><?= \Yii::t('home', 'Choose and rent') ?></h4>

                                <p><?= \Yii::t('home',
                                        'You can quickly explore, see prices, see pictures and rent products through KidUp.') ?></p>
                            </div>
                            <div class="step-d-3">
                                <h4><?= \Yii::t('home', 'Meet and share') ?></h4>

                                <p><?= \Yii::t('home',
                                        'Meet other families and share experiences related to the rented product.') ?></p>
                            </div>
                            <div class="step-d-4">
                                <h4><?= \Yii::t('home', 'Review and Help') ?></h4>

                                <p><?= \Yii::t('home',
                                        'Review your experience, so other KidUppers can easily and swithfly find the right item.') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Owl area-->
    <div id="owl-kidup" class="owl-carousel hidden-xs">
        <div class="item">
            <div id="content-owl" class="expert"
                 style="<?= ImageHelper::bgImg('kidup/home/slider/approved-expert.png',
                     ['q' => 70, 'w' => 1600]) ?>">
                <div class="container ">
                    <div class="row values">
                        <div class="col-sm-8 col-sm-offset-2 text-center">
                            <h1><?= \Yii::t('home', 'Approved by Experts') ?></h1>

                            <h4><?= \Yii::t('home',
                                    'By renting products from other parents, you\'re sure to get picture perfect experthelp.<br>So you can focus on being a good parent.') ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div id="content-owl" class="sad"
                 style="<?= ImageHelper::bgImg('kidup/home/slider/wishes.png', ['q' => 70, 'w' => 1600]) ?>">
                <div class="container ">
                    <div class="row values">
                        <div class="col-sm-8 col-sm-offset-2 text-center">
                            <h1><?= \Yii::t('home', 'You cannot have everything') ?></h1>
                            <h4><?= \Yii::t('home',
                                    'But at KidUp you can rent it. Then together you can find out, if it\'s really truly something.') ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div id="content-owl" class="share"
                 style="<?= ImageHelper::bgImg('kidup/home/slider/share.png', ['q' => 70, 'w' => 1600]) ?>">
                <div class="container ">
                    <div class="row values">
                        <div class="col-xs-8 col-xs-offset-2 text-center">
                            <h1><?= Yii::t("home", "Share the world") ?></h1>
                            <h4><?= \Yii::t('home', 'Together we can teach our children to share and reuse.') ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Area for story-->
    <section id="content-stories">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1 hidden-xs text-center">
                    <?= ImageHelper::img('kidup/home/mom-in-balloon.png', ['w' => 324, 'h' => 366]) ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-sm-offset-1">
                    <h4>Sabine Clasen</h4>

                    <h3><?= \Yii::t('home', 'We can help other families') ?></h3>
                    <br>

                    <div style="font-size: 17px">
                        <?= \Yii::t('home',
                            'I am completely in love with the sharing-aspect and think it makes perfect sense to share things you aren\'t using at the moment.') ?>
                        <br><br>
                        <?= \Yii::t('home',
                            'With the money we made on Kidup with Vilhelm\'s unused equipment, we can take a summertrip to Legoland with the entire family.') ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--    <section id="signup">-->
    <!--        <div class="container">-->
    <!--            <div class="row">-->
    <!--                <div class="col-sm-12 col-xs-12 text-center">-->
    <!--                    <h4>-->
    <!--                        --><? //= \Yii::t('home', 'Featured on') ?>
    <!--                    </h4>-->
    <!---->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </section>-->
</div>
