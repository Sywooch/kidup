<?php
use app\helpers\ViewHelper;
use images\components\ImageHelper;

/**
 * @var \app\extended\web\View $this
 * @var array $images
 * @var \item\models\Item $model
 * @var \item\models\Category $categories
 * @var \item\models\Location $location
 * @var \home\forms\Search $searchModel
 * @var bool $show_modal
 * @var string $rotatingImage
 */
$this->assetPackage = \app\assets\Package::HOME;
$this->title = ViewHelper::getPageTitle(\Yii::t('home.title', 'Share Kid Stuff'));
\home\assets\HomeAsset::register($this);
?>
<div id="home">
    <div class="cover-home"
         style="<?= ImageHelper::bgImg($rotatingImage, ['q' => 70, 'w' => 2000]) ?>; "></div>
    <!--Area for background-image, tag-line and sign-up -->
    <div id="header-home">
        <div class="header-content">
            <div class="row ">
                <div class=" col-xs-12 col-sm-12 title text-center">
                    <h1>
                        <?= \Yii::t("home.share", 'Share'); ?>
<!--                        <strong id="typist-element"-->
<!--                                data-typist="--><?//= Yii::t("home.scrolling_header_share_items",
//                                    "a stroller,a toy,a bike") ?><!--">--><?//= \Yii::t("home.scrolling_header_share_default_item",
//                                'a trolley') ?><!--</strong>-->
                        <strong><?= \Yii::t("home.scrolling_header_share_default_item", 'a trolley') ?></strong>
                        <br/>
                        <?= \Yii::t("home.header_with_a_family", 'With a family near you') ?>
                    </h1>
                    <br><br><br>
                    <?php if (\Yii::$app->user->isGuest): ?>
                        <div class="btn btn-fill btn-primary hidden-xs hidden-sm signup-button"
                             data-toggle="modal"
                             data-target="#signup-conversion-modal"
                            <?= ViewHelper::trackClick('home.click_signup') ?>
                        >
                            <?= Yii::t("home.signup_call_to_action",
                                "Sign up for free and win a family trip to legoland!") ?>
                            &nbsp;<i class="fa fa-angle-right"></i>
                        </div>
                        <?= \app\widgets\SignupModal::widget([
                            'autoOpen' => false
                        ]) ?>
                        <?= \home\widgets\ReferralModal::widget() ?>
                    <?php else: ?>
                        <a href="<?= \yii\helpers\Url::to("@web/item/create") ?>">
                            <div class="btn btn-fill btn-primary hidden-xs hidden-sm signup-button">
                                <?= Yii::t("home.create_item_call_to_action", "Upload a product and earn money!") ?>
                                &nbsp;<i class="fa fa-angle-right"></i>
                            </div>
                        </a>
                    <?php endif; ?>

                    <br>

                    <div class="btn btn-default hidden-xs hidden-sm" id="how-it-works-btn" style="margin-top:10px;">
                        <?= Yii::t("home.subheader_how_it_works", "How it Works") ?>
                    </div>

                    <?php $this->registerJs("$('#how-it-works-btn').click(function() {
                        $('html, body').animate({
                            scrollTop: $('#how-it-works').offset().top
                        }, 1000);
                    });") ?>
                    <div class="row mobile-search visible-xs visible-sm">
                        <div class="col-xs-8 col-xs-offset-1">
                            <input class="form-control"
                                   placeholder="<?= Yii::t("home.mobile_what_looking_for",
                                       "What are you looking for?") ?>"
                                   data-toggle="modal"
                                   data-target="#searchModal">
                        </div>
                        <div class="col-xs-2">
                            <button type="submit" class="btn btn-danger btn-fill mobile-search-btn" data-toggle="modal"
                                    data-target="#searchModal">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>

                        <br>

                        <div class="btn btn-fill btn-primary signup-button" data-toggle="modal"
                             data-target="#signup-conversion-modal"
                            <?= ViewHelper::trackClick('home.click_signup') ?>>
                            <?= Yii::t("home.signup_call_to_action_mobile", "Sign up for free") ?>
                            &nbsp;<i class="fa fa-angle-right"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?= $this->render('search', [
        'model' => $searchModel,
        'defaultCategory' => $categories['Baby Toys']
    ]); ?>

    <?= $this->render('items', [
        'items' => $items,
    ]);
    ?>

    <?= $this->render('grid', [
        'categories' => $categories
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
                    <h2><?= \Yii::t("home.how_use_kidup", 'How to use KidUp?') ?></h2>
                </div>
                <div class="col-sm-4 text-center">
                    <?= ImageHelper::img('kidup/graphics/find.png',
                        ['q' => 90, 'w' => 130],
                        ['class' => 'steps']) ?>
                    <h4><?= \Yii::t("home.seek_and_find", 'Seek and Find') ?></h4>

                    <p><?= \Yii::t("home.seek_and_find_text",
                            'With KidUp you can easily seek and find the products to help your family.') ?></p>
                </div>
                <div class="col-sm-4 text-center">
                    <?= ImageHelper::img('kidup/graphics/pickup.png', ['q' => 90, 'w' => 130],
                        ['class' => 'steps']) ?>
                    <h4><?= \Yii::t("home.meet_and_share", 'Meet and share') ?></h4>

                    <p><?= \Yii::t("home.meet_and_share_text",
                            'Meet other families and share experiences related to the rented product.') ?></p>
                </div>
                <div class="col-sm-4 text-center">
                    <?= ImageHelper::img('kidup/graphics/review.png', ['q' => 90, 'w' => 130],
                        ['class' => 'steps']) ?>
                    <h4><?= \Yii::t("home.review_and_help", 'Review and Help') ?></h4>

                    <p><?= \Yii::t("home.review_and_help_text",
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
                            <h1><?= \Yii::t("home.slider.approved_experts", 'Approved by Experts') ?></h1>
                            <h4><?= \Yii::t("home.slider.approved_subheader",
                                    'When renting from other parents, you are sure to get the right expert help.') ?>
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
                            <h1><?= \Yii::t("home.slider.cannot_have_everything", 'You cannot have everything') ?></h1>
                            <h4><?= \Yii::t("home.slider.rent_it_at_kidup",
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
                            <h1><?= Yii::t("home.slider.share_the_world", "Share the world") ?></h1>
                            <h4><?= \Yii::t("home.slider.share_the_world_text",
                                    'Together we can teach our children to share and reuse.') ?>
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
                            <?= \Yii::t("home.stories.header", 'We can help other families') ?>
                        </b>
                    </h3>
                    <h4>Sabine Clasen</h4>

                    <br>

                    <div style="font-size: 17px">
                        <?= \Yii::t("home.stories.first_alinea",
                            'I am completely in love with the sharing-aspect and think it makes perfect sense to share things you aren\'t using at the moment.') ?>
                        <br><br>
                        <?= \Yii::t("home.stories.second_alinea",
                            'With the money we made on Kidup with Vilhelm\'s unused equipment, we can take a summertrip to Legoland with the entire family.') ?>
                    </div>
                </div>
            </div>
            <div class="row hidden-sm hidden-xs" style="margin-bottom: 20px;">
                <div class="text-center" style="font-size: 22px;margin-bottom:20px;margin-top:80px;">
                    <?= Yii::t("home.grid.featured_on_header", "We are honered to be featured on") ?>
                </div>
                <div class="col-md-2 text-center">
                    <?= ImageHelper::image('kidup/home/mentions/aarhus_stiftidende.png', ['w' => 180], [
                        'style' => 'opacity:0.5;'
                    ]) ?>
                </div>
                <div class="col-md-2 text-center">
                    <?= ImageHelper::image('kidup/home/mentions/politiken.png', ['w' => 180], [
                        'style' => 'opacity:0.5;margin-top: 4px;'
                    ]) ?>
                </div>
                <div class="col-md-2 text-center">
                    <?= ImageHelper::image('kidup/home/mentions/dr.png', ['w' => 180], [
                        'style' => 'opacity:0.5;margin-top: 17px;'
                    ]) ?>
                </div>
                <div class="col-md-2 text-center">
                    <?= ImageHelper::image('kidup/home/mentions/radio24-7.png', ['w' => 180], [
                        'style' => 'opacity:0.5;'
                    ]) ?>
                </div>
                <div class="col-md-2 text-center">
                    <?= ImageHelper::image('kidup/home/mentions/startup-weekend.png', ['w' => 180], [
                        'style' => 'opacity:0.5; margin-top: 10px;'
                    ]) ?>
                </div>
                <div class="col-md-2 text-center">
                    <?= ImageHelper::image('kidup/home/mentions/trendsonline.png', ['w' => 180], [
                        'style' => 'opacity:0.5; margin-top: 15px;'
                    ]) ?>
                </div>
            </div>
        </div>
    </section>
</div>
