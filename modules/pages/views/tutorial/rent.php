<?php
$this->title = ucfirst(\Yii::t('title', 'How to rent from other parents')) . ' - ' . Yii::$app->name;

?>
<div id="how-to">
    <div class="intro">
        <div class="row">
            <div class="col-sm-12">
                <div class="header">
                    <div class="cover rent"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="hidden-xs hidden-sm col-sm-4 col-md-3 menu">
                    <h5><?= \yii\helpers\Html::a(\Yii::t('app', 'Why use kidup?'), "why") ?></h5>
                    <h5 class="active"><?= \yii\helpers\Html::a(\Yii::t('app', 'How to rent?'), "rent") ?></h5>
                    <h5><?= \yii\helpers\Html::a(\Yii::t('app', 'How to rent out?'), "out") ?></h5>
                </div>
                <div class="col-sm-10 col-sm-offset-1 col-md-8  info">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-center title">
                            <h3>Hvordan <b>lejer</b> jeg?</h3>
                            <h4>Hvordan du lejer ting fra andre familier</h4>
                        </div>
                    </div>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 col-md-push-7">
                            <img class="center-block" src="../../images/how-to-rent/Find-an-item-near-you.png">
                        </div>
                        <div class="col-sm-12 col-md-7 col-md-pull-5 ">
                            <h2>Find udstyr i nærheden</h2>

                            <p>Skal du være forældre for første gang? Skal du besøge familien? På tur i weekenden? Find
                                udstyr i nærheden, og slip for besværet ved at skulle købe nyt eller
                                transportere dit udstyr over lange distancer. KidUp finder udstyr indenfor dit nærområde
                                og du kan sælg vælge hvem du vil leje af.</p>
                        </div>
                    </section>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 ">
                            <img class="center-block" src="../../images/how-to-rent/Rent-the-item.png">
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <h2>Lej udstyret</h2>

                            <p>Du kan leje andre forældres børneudstyr, i lige præcis den periode hvor du skal bruge
                                det. Det værende en weekend, en uge eller et halvt år - det er op til dig og ejeren af
                                udstyret. Så smid en forespørgsel afsted og sammen kan i finde ud af hvad der passer
                                bedst.</p>
                        </div>
                    </section>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 col-md-push-7">
                            <img class="center-block" src="../../images/how-to-rent/Pickup-your-item-and-enjoy.png">
                        </div>
                        <div class="col-sm-12 col-md-7 col-md-pull-5">
                            <h2>Hent udstyret og kom godt igang</h2>

                            <p>Når du skal bruge udstyret, mødes du med ejeren for at hente det. Sammen kigger i
                                udstyret igennem, finder ud af hvordan det fungerer og giver hånd på at tingene er i
                                orden.
                                Skulle der opstå problemer i løbet af lejeperiode skal i endelige tage kontakt til os
                                igennem vores Hjælpecentral. </p>
                        </div>
                    </section>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 ">
                            <img class="center-block"
                                 src="../../images/how-to-rent/Return-the-item-and-write-a-review.png">
                        </div>
                        <div class="col-sm-12 col-md-7 ">
                            <h2>Aflever udstyret og skriv en anmeldelse</h2>

                            <p>Når du er færdig med udstyret, gør du det rent og afleverer det til ejeren som aftalt.
                                Efterfølgende kan du gå på KidUp og skrive en anmeldelse, så andre forældre får lyst til
                                at leje udstyret. Sammen kan vi således skabe en dejlig gennemsigtig portal, hvor man
                                nemt kan finde andre søde forældre. </p>
                        </div>
                    </section>
                    <!--                    <hr>-->
                    <!--                    <section class="row">-->
                    <!--                        <div class="col-sm-12 text-center">-->
                    <!--                            <h2>-->
                    <? //= Yii::t("app", "Are you ready to start renting?") ?><!--</h2>-->
                    <!--                            <br>-->
                    <!--                            <button type="submit" class="btn btn-main">-->
                    <? //= Yii::t("app", "Try it now") ?><!--</button>-->
                    <!--                        </div>-->
                    <!--                    </section>-->
                </div>
            </div>
        </div>

    </div>
</div>
