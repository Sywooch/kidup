<?php
use app\helpers\ViewHelper;
use app\modules\images\components\ImageHelper;

/**
 * @var \app\extended\web\View $this
 * @var array $images
 * @var app\modules\item\models\Item $model
 * @var app\modules\item\models\Location $location
 * @var \app\modules\home\forms\Search $searchModel
 * @var bool $show_modal
 */
$this->assetPackage = \app\assets\Package::HOME;
$this->title = ViewHelper::getPageTitle(\Yii::t('title', 'Share Kid Stuff'));
\app\modules\home\assets\HomeAsset::register($this);
?>
<div id="home">
    <div class="cover-home"
         style="<?= ImageHelper::bgImg('kidup/home/header.png', ['q' => 70, 'w' => 2000]) ?>; "></div>
    <!--Area for background-image, tag-line and sign-up -->
    <div id="header-home">
        <div class="header-content">
            <div class="row ">
                <div class=" col-xs-12 col-sm-12 title text-center">
                    <h1>
                        <?= \Yii::t('home', 'Share'); ?>
                        <strong id="typist-element"
                                data-typist="<?= Yii::t("home", "a stroller,a toy,a bike") ?>"><?= \Yii::t('home',
                                'a trolley') ?></strong>
                        <br/>
                        <?= \Yii::t('home', 'With a family near you') ?>
                    </h1>
                    <h4>
                        <?= \Yii::t('home', 'KidUp is your online parent-to-parent marketplace.') ?>
                    </h4>

                    <div class="btn btn-default hidden-xs hidden-sm" id="how-it-works-btn">
                        <?= Yii::t("home", "How it Works") ?>
                    </div>
                    <?php $this->registerJs("$('#how-it-works-btn').click(function() {
                        $('html, body').animate({
                            scrollTop: $('#how-it-works').offset().top
                        }, 1000);
                    });") ?>
                    <div class="row mobile-search visible-xs visible-sm">
                        <div class="col-xs-8 col-xs-offset-1">
                            <input class="form-control"
                                   placeholder="<?= Yii::t("home", "What are you looking for?") ?>"
                                   data-toggle="modal"
                                   data-target="#searchModal">
                        </div>
                        <div class="col-xs-2">
                            <button type="submit" class="btn btn-danger btn-fill mobile-search-btn" data-toggle="modal"
                                    data-target="#searchModal">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
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
    <div id="content-home-steps">
        <div class="divider">
            <?= ImageHelper::img('kidup/logo/balloon.png', ['w' => 40, 'h' => 40]) ?>
        </div>

        <div class="container" id="how-it-works">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h2><?= \Yii::t('home', 'How to use KidUp?') ?></h2>
                </div>
                <div class="col-sm-4 text-center">
                    <?= ImageHelper::img('kidup/graphics/find.png', ['q' => 90, 'w' => 130],
                        ['class' => 'steps']) ?>
                    <h4><?= \Yii::t('home', 'Seek and Find') ?></h4>

                    <p><?= \Yii::t('home',
                        'With KidUp you can easily seek and find the products to help your family.') ?></p>
                </div>
                <div class="col-sm-4 text-center">
                    <?= ImageHelper::img('kidup/graphics/pickup.png', ['q' => 90, 'w' => 130],
                        ['class' => 'steps']) ?>
                    <h4><?= \Yii::t('home', 'Meet and share') ?></h4>

                    <p><?= \Yii::t('home',
                            'Meet other families and share experiences related to the rented product.') ?></p>
                </div>
                <div class="col-sm-4 text-center">
                    <?= ImageHelper::img('kidup/graphics/review.png', ['q' => 90, 'w' => 130],
                        ['class' => 'steps']) ?>
                    <h4><?= \Yii::t('home', 'Review and Help') ?></h4>

                    <p><?= \Yii::t('home',
                            'Review your experience, so other KidUppers can easily and swithfly find the right item.') ?></p>
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
    <section id="content-stories" class="hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-1 hidden-xs text-center">
                    <?= ImageHelper::img('kidup/home/mom-in-balloon.png', ['w' => 200, 'h' => 200]) ?>
                </div>
                <div class="col-xs-12 col-sm-6 col-sm-offset-1">

                    <h3>
                        <b>
                            <?= \Yii::t('home', 'We can help other families') ?>
                        </b>
                    </h3>
                    <h4>Sabine Clasen</h4>

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
</div>
