<div id="main" style="margin-bottom:-90px;">
    <div class="container">
        <div class="row">
            <div class="main__image col-md-6">
                <img src="<?= \images\components\ImageHelper::url("kidup/home/fake-home/iphone.png") ?>" alt="iPhone" width="80%">
            </div>
            <div class="main__text col-md-4 col-md-offset-1">
                <h2><b><?= Yii::t("home.fake-home.title", "We are KidUp,") ?></b></h2>
                <h4><?= Yii::t("home.fake-home.text", "Your parent community for sharing local children’s equipment. Find equipment near you and rent it
                    from local parents.") ?></h4>
                <br>
                <h2><?= Yii::t("home.fake-home.download", "Download now.") ?></h2>
                <a href="https://geo.itunes.apple.com/dk/app/kidup/id1066928173?mt=8">
                    <img class="applestore" src="<?= \images\components\ImageHelper::url("kidup/home/fake-home/appstore.png") ?>" width="40%" alt="Apple Store">
                </a>
<!--                <a href="#">-->
                <!--                    <img class="googlestore" src="-->
                <? //= \images\components\ImageHelper::url("kidup/home/fake-home/google.png") ?><!--" width="40%" alt="Apple Store">-->
                <!--                </a>-->
            </div>
        </div>
    </div>
    <div class="main__illustration"></div>
</div>
<?php

$this->registerCss("#main {
    height: 100%;
    width: 100%;
    background-color: #8CE9FC;
    background-image: linear-gradient(90deg, #8CE9FC 25%, #59D0E8 100%);
    background-image: -moz-linear-gradient(left, #8CE9FC 25%, #59D0E8 100%);
    background-image: -webkit-linear-gradient(left, #8CE9FC 25%, #59D0E8 100%);
    background-image: -o-linear-gradient(left, #8CE9FC 25%, #59D0E8 100%);
    background-image: -ms-linear-gradient(left, #8CE9FC 25%, #59D0E8 100%);
    position: relative;
    color:white;
}
.main__text {
    margin-top: 10%;
    z-index: 1;
}
.main__image {
    text-align: center;
    margin: 20px 0 330px 0;
}
.main__illustration {
    position: absolute;
    bottom: 0;
    right: 0;
    height: 431px;
    width: 664px;
    background-image: url('".\images\components\ImageHelper::url("kidup/home/fake-home/illustration.png")."');
    background-size: cover;
}
#footer {
    min-height: 230px;
    padding-top: 30px;
    background-color: #153B42;
    background-image: url('".\images\components\ImageHelper::url("kidup/home/fake-home/pattern.png")."');
    background-size: 15%;
    text-align: center;
}
#footer a {
    color: #fff;
    line-height: 24px;
}
/* Custom, iPhone Retina */
   @media only screen and (min-width : 320px) {

   }

   /* Extra Small Devices, Phones */
   @media only screen and (min-width : 480px) {

   }

   /* Small Devices, Tablets */
   @media only screen and (min-width : 768px) {

   }

   /* Medium Devices, Desktops */
   @media only screen and (min-width : 992px) {

   }

   /* Large Devices, Wide Screens */
   @media only screen and (min-width : 1200px) {

   }



   /*==========  Non-Mobile First Method  ==========*/

   /* Large Devices, Wide Screens */
   @media only screen and (max-width : 1200px) {

   }

   /* Medium Devices, Desktops */
   @media only screen and (max-width : 992px) {
       #main {
           min-height: 800px;
       }
       .main__image {
           display: none;
           padding-bottom: 100px;
       }
       .logo {
           width: 30%;
            margin-bottom: 40px;
       }
   }

   /* Small Devices, Tablets */
   @media only screen and (max-width : 768px) {
       #main {
           min-height: 800px;
       }
       .main__image {
           display: none;
       }
       .logo {
           width: 40%;
           margin-bottom: 40px;
       }
   }

   /* Extra Small Devices, Phones */
   @media only screen and (max-width : 480px) {

   }

   /* Custom, iPhone Retina */
   @media only screen and (max-width : 320px) {

   }

"); ?>