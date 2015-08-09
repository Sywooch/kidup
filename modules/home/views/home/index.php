<?php
use yii\helpers\Url;
use app\components\ViewHelper;
use app\modules\images\components\ImageHelper;

\app\modules\home\assets\HomeAssets::register($this);
\app\assets\AngularAsset::register($this);
$this->title = ViewHelper::getPageTitle(\Yii::t('title', 'Share Kid Stuff'));
?>

<div id="home">
    <!--Area for background-image, tag-line and sign-up -->
    <header id="header-home">
        <div class="cover-home"
             style="<?= ImageHelper::bgImg('kidup/home/header.png', ['q' => 70, 'w' => 2000]) ?>"></div>
        <div class="header-content">
            <div class="row ">
                <div class=" col-xs-12 col-sm-12 col-md-10 col-md-offset-1 text-center ">
                </div>
                <div class=" col-xs-12 col-sm-12 title text-center">
                    <h1>
                        <?= \Yii::t('home', 'Share'); ?>
                        <?= \Yii::t('home', 'a trolley') ?>
                        <br/>
                        <?= \Yii::t('home', 'With a family near you') ?>
                    </h1>
                    <h4><?= \Yii::t('home', 'KidUp is your online parent-to-parent marketplace.') ?></h4>

                </div>
            </div>
        </div>
    </header>

    <?= $searchWidget ?>

    <?= $gridWidget ?>

    <!-- Steps explanation area-->
    <div id="content-home-steps" class=" hidden-xs">
        <div class="divider">
            <?= ImageHelper::img('kidup/logo/balloon.png') ?>
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

                    <div class="row step-description">
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
    <div id="owl-kidup" class="owl-carousel">
        <div class="item">
            <div id="content-owl" class="expert"
                 style="<?= ImageHelper::bgImg('kidup/home/slider/approved-expert.png', ['q' => 70, 'w' => 1600]) ?>">
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
                        <div class="col-sm-8 col-sm-offset-2 text-center">
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
    <Section id="content-stories">
        <div class="container">
            <div class="row ">
                <div class="col-xs-4 col-sm-4 col-sm-offset-1 text-center">
                    <?= ImageHelper::img('kidup/home/mom-in-balloon.png', ['w' => 400]) ?>
                </div>
                <div class="col-sm-6 col-sm-offset-1 ">
                    <h4>Sabine Clasen</h4>

                    <h3><?= \Yii::t('home', 'We can help other families') ?></h3>
                    <br>
                    <div style="font-size: 17px">
                        <?= \Yii::t('home',
                            'I am completely in love with the sharing-aspect and think it makes perfect sence to share things you aren\'t raelly using.') ?>
                        <br><br>
                        <?= \Yii::t('home',
                            'With the money we make on renting Vilhelm\'s equipment out, we can take a summertrip to Legoland with the entire family.') ?>
                    </div>
                </div>
            </div>
        </div>
    </Section>
    <section id="signup">
        <div class="row ">
            <div class="col-sm-12 text-center">
                <h4><?= \Yii::t('home', 'Become a part of the KidUp familiy') ?>
                    <a href="<?= Url::to('@web/user/register') ?>">
                        <button class="btn btn-danger btn-lg btn-fill"><?= \Yii::t('home', 'Sign Up Now') ?></button>
                    </a>
                </h4>
            </div>
        </div>
    </Section>
</div>