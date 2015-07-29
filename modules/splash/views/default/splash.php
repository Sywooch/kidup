<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/**
 * @var \yii\web\View $this
 */
$this->title = 'KidUp - '.ucfirst(\Yii::t('title', 'Launching soon'));
\app\modules\splash\assets\SplashAsset::register($this);
?>

<div id="splash">
    <!--Area for background-image, tag-line and sign-up -->
    <header id="header">
        <div class="cover" style="background-image: url('<?= Url::to('@web/img/home/header.png') ?>')"></div>
        <div class="container ">
            <div class="row">
                <!--<div class=" col-xs-12 col-sm-9 col-md-7 col-lg-6 col-md-push-1">-->
                <div class=" col-xs-12 col-sm-12 col-md-10 col-md-push-1 text-center slideDown">
                    <a href="#"><img src="<?= \yii\helpers\Url::to('@web/img/logo/horizontal.png') ?>"
                                     width="200px"></a>
                    <!--<h1></h1>-->
                </div>
            </div>

            <div class="row start hatch">
                <div class=" col-xs-12 col-md-8 col-md-offset-2 text-center">
                    <h1>Få råd til at have børn</h1>

                    <p class="intro"> KidUp er en online <b>forældre-til-forældre markedsplads</b>, hvor du kan leje og
                        udleje hvad dig og dine børn ønsker. Vi går I luften <span class="green"><b>primo
                                2015,</b></span> så tilmeld jer nedenfor, så lover vi at give lyd når vi er online.</p>

                </div>
                <div class=" col-xs-12 col-md-8 col-md-offset-2">
                    <?php $form = ActiveForm::begin([
                        'id' => 'splash-signup-form'
                    ]); ?>
                    <div class="row">
                        <div class="col-sm-8 col-md-8">
                            <?= $form->field($model, 'email', [
                                'inputOptions' => [
                                    'class' => 'required email form-control',
                                    'placeholder' => 'Email her..',
                                    'required' => 'required',
                                ],
                                'labelOptions' => ['class' => 'hidden'],
                                'enableAjaxValidation' => false,
                            ])->textInput() ?>
                        </div>
                        <div class="col-sm-4 col-md-4" >
                            <?= \yii\helpers\Html::submitButton('Giv mig besked', ['class' =>'btn btn-primary', 'style' => "color:white"]) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <!--<div class="row info">-->
            <!--<div class=" col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-push-1 col-md-push-2 col-lg-push-3 fadeIn">-->
            <!--</div>-->
            <!--</div>-->
            <div class="row hidden-xs arrow-down">
                <div class="col-sm-12 text-center">
                    <div class="arrow floating">
                        <a href="#content">
                            <p>Læs mere</p>
                            <i class="fa fa-chevron-down fa-2x "></i>

                        </a>
                    </div>
                </div>
            </div>
            <!--<div class="row">-->
            <!--<div class="col-md-7 col-lg-6 col-md-push-1">-->
            <!--<p>(Like us on facebook)</p>-->
            <!--</div>-->
            <!--</div>-->
        </div>
    </header>

    <!-- Info container with 3 bullets -->
    <Section id="content">
        <div class="container shadow">
            <div class="row video">
                <div class="col-sm-12 text-center">
                    <h3>Hvorfor tilmelde sig KidUp?</h3>

                    <p style="text-align: center;">Med Kidup kan vi hjælpe dine børns evigt skiftende behov og
                        ønsker. </p>

                    <div class="video-area">
                        <iframe
                            src="//player.vimeo.com/video/117175317?portrait=0&badge=0&title=0&byline=0&color=71baaf"
                            frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                        <!--<div class="overlay" style="display: block;">-->
                        <!--<a href="#" class="play">play</a>-->
                        <!--<a href="#" class="pause"></a>-->
                        <!--</div>-->
                    </div>
                    <!--<p>-->
                    <!--Der er mange måder at være  en god forældre på. Alle forældre er forskellige og heldigvis er vores børn ligeså. Dine og børnenes ønsker og behov er evigt foranderligt. De ønsker dit barn har de første 5 år kan meget vel være det samme de efterfølgende 5 år, men I en større og oftest mere avanceret udgave. Udskiftsningsraten er høj. Med Kid Up kan I spare penge på at leje udstyr fra andre eksperter (forældre) I den periode som I har brug for det.-->
                    <!--</p>-->
                </div>
            </div>
            <div class="row featured">
                <div class="col-sm-12 text-center">
                    <h4 class="text-center">Vi er omtalt her:</h4>
                    <!-- <a >-->
                    <img class="stiften" src="<?= Url::to('@web/img/home/mentions/stiften.png')?>">
                    <!--/a>-->
                    <a target="_blank"
                       href="https://www.facebook.com/photo.php?fbid=10152462932040911&set=a.10150876127025911.407860.700025910&type=1&theater">
                        <img src="<?= Url::to('@web/img/home/mentions/startup-weekend.png')?>">
                    </a>
                </div>
            </div>
            <div class="row bullets">
                <div class="col-sm-4 one">
                    <h4 class="text-center">Leje</h4>
                    <img id="rent1" src="<?= Url::to('@web/img/graphics/find.png')?>" width="100%">

                    <p>Med KidUp kan du tjene penge mens du hjælper andre forældre med at få deres drømme udstyr.
                        Samtidig kan du <b>spare penge</b> ved at dit leje udstyr gennem KidUp.</p>
                </div>
                <div class="col-sm-4 two">
                    <h4 class="text-center">Eksperter</h4>
                    <img id="relationship1" src="<?= Url::to('@web/img/graphics/pickup.png')?>" width="100%">

                    <p>KidUp er baseret på <b>loyalitet og ekspertviden</b> omkring det at have børn, og som lejer og
                        udlejer har i muligheden for at dele erfaringer omkring jeres ustyr.
                    </p>
                </div>
                <div class="col-sm-4 three">
                    <h4 class="text-center">Sikkerhed</h4>
                    <img id="trust1" src="<?= Url::to('@web/img/graphics/rentout.png')?>" width="100%">

                    <p>KidUp’s fundament er baseret på <b>tillid mellem forældre</b> og at konceptet om at dele din
                        families udstyr på en sikker, nem og overskuelig måde.</p>
                </div>
            </div>
        </div>
    </Section>
    <Section id="go-to-top">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a href="#header">
                        <h4><i class="fa fa-chevron-circle-up fa-lg"></i></h4>

                        <p>Gå til toppen</p>
                    </a>
                </div>
            </div>
        </div>
    </Section>
</div>
<?= $this->render('splashFooter') ?>