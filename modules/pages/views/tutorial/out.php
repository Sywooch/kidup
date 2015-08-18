<?php
$this->title = ucfirst(\Yii::t('title', 'How to rent to other parents')) . ' - ' . Yii::$app->name;

?>
<div id="how-to">
    <div class="intro">
        <div class="row">
            <div class="col-sm-12">
                <div class="header">
                    <div class="cover rent-out"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <div class="row">
                <div class="hidden-xs hidden-sm col-sm-4 col-md-3 menu">
                    <h5><?= \yii\helpers\Html::a(\Yii::t('app', 'Why use kidup?'), "why") ?></h5>
                    <h5><?= \yii\helpers\Html::a(\Yii::t('app', 'How to rent?'), "rent") ?></h5>
                    <h5 class="active"><?= \yii\helpers\Html::a(\Yii::t('app', 'How to rent out?'), "out") ?></h5>
                </div>
                <div class="col-sm-10 col-sm-offset-1 col-md-8 info">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-center title">
                            <h3>Hvordan <b>udlejer</b> jeg?</h3>
                            <h4>Lær hvordan du udlejer dine ting til andre familier</h4>
                        </div>
                    </div>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 col-md-push-7">
                            <img class="center-block"
                                 src="../../images/how-to-rent-out/Put-up-your-kids-stuf-for-free.png">
                        </div>
                        <div class="col-sm-12 col-md-7 col-md-pull-5 ">
                            <h2>Læg dit barns udstyr op gratis</h2>

                            <p>Indtast en kort beskrivelse af dit barns udstyr og fastsæt en pris. Upload
                                nogle gode billeder af udstyret - du vælger selv hvor mange. Nu er du klar til at tjene
                                penge på at leje dit udstyr ud.
                            </p>
                        </div>
                    </section>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 ">
                            <img class="center-block" src="../../images/how-to-rent-out/Receive-reservations.png">
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <h2>Modtag reservationer</h2>

                            <p>Når en bruger anmoder om at leje dit udstyr, vil du modtage en sms eller e-mail. Du
                                beslutter, hvem du vil leje dit udstyr ud til blot ved at acceptere eller afslå
                                reservationen.</p>
                        </div>
                    </section>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 col-md-push-7">
                            <img class="center-block"
                                 src="../../images/how-to-rent-out/Meet-the-renter-and-make-money.png">
                        </div>
                        <div class="col-sm-12 col-md-7 col-md-pull-5">
                            <h2>Mød lejeren og tjen penge</h2>

                            <p>Når du møder lejeren skal du huske at gennemgå udstyret for hvordan det bruges og standen
                                af udstyret. Send lejeren afsted og slap af, mens du hjælper en anden familie og tjener
                                lidt penge samtidigt.</p>
                        </div>
                    </section>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 ">
                            <img class="center-block" src="../../images/how-to-rent-out/Write-a-review.png">
                        </div>
                        <div class="col-sm-12 col-md-7 ">
                            <h2>Skriv en anmeldelse</h2>

                            <p>Hjælp andre udlejere med at vælge den rigtige lejer ved at skrive en anmeldelse, når du
                                får udstyret tilbage. Sammen kan vi med anmeldelser gøre KidUp til en fantastisk
                                oplevelse.</p>
                        </div>
                    </section>
                    <!--                    <hr>-->
                    <!--                    <section class="row">-->
                    <!--                        <div class="col-sm-12 text-center">-->
                    <!--                            <h2>-->
                    <? //= Yii::t("app", "Are you ready to rent out your stuff?") ?><!--</h2>-->
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
