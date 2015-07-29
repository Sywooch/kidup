<?php
use yii\helpers\Url;
use app\components\ViewHelper;

\app\modules\home\assets\HomeAssets::register($this);

$this->title = ViewHelper::getPageTitle(\Yii::t('title', 'Share Kid Stuff'));
?>

<div id="home">
    <!--Area for background-image, tag-line and sign-up -->
    <header id="header-home">
        <div class="cover-home" style="background-image: url('<?= Url::to('@web/img/home/header.png') ?>')"></div>
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
        <div class="divider"><img src="<?= Url::to('@web/img/logo/balloon.png') ?>"></div>
        <div class="container ">
            <div class="row ">
                <div class="col-sm-12 text-center">
                    <h2><?= \Yii::t('home', 'How to use kidup?') ?></h2>
                    <h4>Forstå hvor simpelt det er at bruge KidUp</h4>
                </div>
                <div class="col-sm-12 text-center step-area">
                    <div class="row">
                        <div id="step-item-1" class="col-sm-3 step-item active">
                            <img class="steps" src="<?= Url::to('@web/img/graphics/find.png') ?>"
                                 height="130px">

                            <div class="number">1</div>
                            <div class="row step-lines">
                                <div class="one-v"></div>
                                <div class="one-h"></div>
                            </div>
                        </div>
                        <div id="step-item-2" class="col-sm-3 step-item">
                            <img class="steps" src="<?= Url::to('@web/img/graphics/photograph.png') ?>"
                                 height="130px">

                            <div class="number">2</div>
                            <div class="row step-lines">
                                <div class="two-v"></div>
                            </div>
                        </div>
                        <div id="step-item-3" class="col-sm-3 step-item">
                            <img class="steps"
                                 src="<?= Url::to('@web/img/graphics/pickup.png') ?>"
                                 height="130px">

                            <div class="number">3</div>
                            <div class="row step-lines">
                                <div class="three-v"></div>
                            </div>
                        </div>
                        <div id="step-item-4" class="col-sm-3 step-item">
                            <img class="steps"
                                 src="<?= Url::to('@web/img/graphics/review.png') ?>"
                                 height="130px">

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
                                <h4>Søg og find </h4>

                                <p>Med KidUp kan du nemt søge efter de produkter som kan hjælpe din familie.</p>
                            </div>
                            <div class="step-d-2">
                                <h4>Vælg og lej</h4>

                                <p>Du kan hurtigt udforske, se priser, se billeder og leje dine produkter gennem
                                    KidUp.</p>
                            </div>
                            <div class="step-d-3">
                                <h4>Mød og del</h4>

                                <p>Mød andre familier og del de erfaringer, som hører sig til det lejede produkt.</p>
                            </div>
                            <div class="step-d-4">
                                <h4>Anmeld og hjælp</h4>

                                <p>Anmeld din oplevelse, så andre KidUp'er nemmere kan danne sig et indtryk af en
                                    bruger.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Section id="content-blocks " class="hidden">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h2>Hør om andre</h2>
                    <h4>Oplev hvordan andre familier har anvendt KidUp til at hjælpe dem.</h4>
                </div>
            </div>
            <div class="row ">
                <a>
                    <div class="col-sm-12 col-md-6">
                        <div class=" block-box">
                            <div class="block-half"
                                 style="background-image: url('<?= Url::to('@web/img/categories/room2.png') ?>')"></div>
                            <h3 class="one">Blog 1</h3>

                            <p>Tekst om blog 1</p>
                        </div>
                    </div>
                </a>
                <a>
                    <div class="col-sm-6 col-md-3">
                        <div class=" block-box">
                            <div class="block-full"
                                 style="background-image: url('<?= Url::to('@web/img/categories/playfull2.png') ?>')">
                                <div class="full-text">
                                    <h4>Tips fra KidUp</h4>

                                    <p>Forklaring omkring hvad dette går ud på.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <a>
                    <div class="col-sm-6 col-md-3">
                        <div class=" block-box">
                            <div class="block-half"
                                 style="background-image: url('<?= Url::to('@web/img/categories/outdoor2.png') ?>')"></div>
                            <h4 class="two">Få større indsigt</h4>

                            <p>Nogensinde været frusteret over hvordan og hvorledes at du kan gøre sån her og derefter
                                sån her?</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </Section>

    <!--Owl area-->
    <div id="owl-kidup" class="owl-carousel">
        <div class="item">
            <div id="content-owl" class="expert"
                 style="background: url(<?= Url::to('@web/img/home/slider/approved-expert.png') ?>);">
                <div class="container ">
                    <div class="row values">
                        <div class="col-sm-8 col-sm-offset-2 text-center">
                            <h1>Godkendt af eksperter</h1>

                            <h4>Ved at leje produkter af andre forældre, sikrer du dig at få skræddersyet
                                eksperthjælp.<br>
                                Så du kan koncentrere dig om at være en god forældre.
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div id="content-owl" class="sad"
                 style="background: url(<?= Url::to('@web/img/home/slider/wishes.png') ?>) no-repeat center right;">
                <div class="container ">
                    <div class="row values">
                        <div class="col-sm-8 col-sm-offset-2 text-center">
                            <h1>Man kan ikke få alt</h1>
                            <h4>Men hos KidUp kan du leje det.
                                Og så kan i sammen finde ud af,
                                om det virkelig er noget ved.
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div id="content-owl" class="share"
                 style="background: url(<?= Url::to('@web/img/home/slider/share.png') ?>);">
                <div class="container ">
                    <div class="row values">
                        <div class="col-sm-8 col-sm-offset-2 text-center">
                            <h1><?= Yii::t("home", "Share the world") ?></h1>
                            <h4>Sammen kan vi lære vores børn, at deles og genbruge.
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
                    <img class="" src="<?= Url::to('@web/img/home/mom-in-balloon.png') ?>">
                </div>
                <div class="col-sm-6 col-sm-offset-1 ">
                    <h4>Sabine Clasen</h4>

                    <h3>Vi kan hjælpe andre familier</h3>

                    <p>Jeg er helt tosset med hele deleaspektet og synes det giver så god mening at man deler de ting
                        man alligevel ikke bruger.</p>

                    <p>De penge vi kan tjene på at leje Mikkels barnevogn ud, kan vi spare sammen til en tur til Disney
                        Land med hele familien.</p>

                </div>
            </div>
        </div>
    </Section>
    <section id="signup">
        <div class="row ">
            <div class="col-sm-12 text-center">
                <h4>Bliv en del af KidUp fælleskabet
                    <a href="<?= Url::to('@web/user/register') ?>">
                        <button class="btn btn-danger btn-lg btn-fill">Tilmeld dig nu</button>
                    </a>
                </h4>
            </div>
        </div>
    </Section>

    <!--Area for testimonial-->
    <Section id="content-testimonial" class="hide">
        <div class="container">
            <div class="row ">
                <div class="col-sm-12 text-center">
                    <h4>Andre taler også om KidUp</h4>
                </div>
            </div>
            <div class="row logos">
                <div class="col-sm-12 text-center">
                </div>
            </div>
        </div>
    </Section>
</div>