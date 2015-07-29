<?php
use yii\helpers\Html;
use yii\helpers\Url;
\app\modules\splash\assets\SplashAsset::register($this);
$this->title = ucfirst(\Yii::t('title', 'Thank you for signing up')) . ' - '. Yii::$app->name;
?>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/da_DK/sdk.js#xfbml=1&version=v2.3&appId=1515825585365803";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

<div id="splash">
    <header id="header" style="margin-bottom: 0px;">
        <div class="cover" style="background-image: url('<?= Url::to('@splashAssets/img/header.png') ?>')"></div>
        <div class="container">
            <div class="row">
                <!--<div class=" col-xs-12 col-sm-9 col-md-7 col-lg-6 col-md-push-1">-->
                <div class=" col-xs-12 col-sm-12 col-md-10 col-md-push-1 text-center">
                    <a href="#"><img src="<?= Url::to('@assets/img/logo/horizontal.png') ?>" width="200px"></a>
                    <!--<h1></h1>-->
                </div>
            </div>

            <div class="row start">
                <div class=" col-xs-12 col-md-6 col-md-offset-3 text-center">
                    <h1>Tak fordi du tilmeldte dig!</h1>
                    <p class="intro" >Vi nærmer os med hastige skridt vores lancering og vi glæder os til at kontakte dig når vi er i luften. Imellemtiden er du meget velkommen til at følge os på sociale medier og ikke mindst fortælle dine venner og familie omkring KidUp!</p>
                    <div class="fb-share-button" data-href="http://www.kidup.dk" data-layout="button"></div>

                </div>
                <div class="col-sm-12 text-center">
                    <div class="social">
                        <a href="https://www.facebook.com/kidup.social" target="_blank" title="Facebook">
                            <i id="facebook" class="fa fa-facebook fa-2x"></i>
                        </a>
                        <a href="http://instagram.com/kidup_social/" target="_blank" title="Instagram">
                            <i id="instagram" class="fa fa-instagram fa-2x"></i>
                        </a>
                        <a href="https://twitter.com/kidup_social" target="_blank" title="twitter">
                            <i id="twitter" class="fa fa-twitter fa-2x"></i>
                        </a>
                        <!--<p class="titel">Følg os</p>-->
                    </div>
                </div>

                <div class=" col-xs-12 col-md-8 col-md-offset-2">
                    <form>
                        <div class="row">


                            <div class="col-sm-12 text-center">
                                <br>
                                <?= Html::a('<i class="fa fa-chevron-left"></i> Go back', ['./']) ?>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>
</div>
<?php echo $this->render('splashFooter'); ?>