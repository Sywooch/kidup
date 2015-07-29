<?php
$this->title = ucfirst(\Yii::t('title', 'Why use kidup')) . ' - '. Yii::$app->name;

?>
<div id="why">
    <div class="intro ">
        <div class="row">
            <div class="col-sm-12">
                <div class="header">
                    <div class="cover why"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <div class="row">
                <div class=" hidden-xs hidden-sm  col-sm-4 col-md-3 menu">
                    <h5 class="active"><?= \yii\helpers\Html::a(\Yii::t('app', 'Why use kidup?'), "why") ?></h5>
                    <h5><?= \yii\helpers\Html::a(\Yii::t('app', 'How to rent?'), "rent") ?></h5>
                    <h5><?= \yii\helpers\Html::a(\Yii::t('app', 'How to rent out?'), "out") ?></h5>
                </div>
                <div class="col-sm-10 col-sm-offset-1 col-md-8  info">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-center title">
                            <h3>Hvorfor <b>bruge</b> KidUp?</h3>
                            <h4>Hvorfor du skal bruge KidUp, både nu og i fremtiden</h4>
                        </div>
                    </div>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 ">
                            <img class="center-block" src="../../images/why-use/youdecide.png">
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <h2>Lær dit barn at dele verden</h2>

                            <p>Sammen kan vi lære vores børn, at dele verdenen. Vi kan lære dem at det er okay at bruge tingene mere end en gang, og at det man ikke bruger, kan glæde en anden familie. 
                            <p>                            
                            </p>
                        </div>
                    </section>
                    <section class="row hidden">
                        <div class="col-sm-12 col-md-5 col-md-push-7">
                            <img class="center-block" src="../../images/why-use/covered.png">
                        </div>
                        <div class="col-sm-12 col-md-7 col-md-pull-5">
                            <h2>Eksperternes verden</h2>
                            <p>Indenfor salgsverdenen kan ekspertise være svært at finde - hvilken barnevogn er egentlig bedst, og hvordan bruger jeg den her klapvogn? Hos KidUp står du ansigt til ansigt med andre forældrere, som kun har de bedste produkter, og ved lige præcis hvordan de skal bruges. </p>
                        </div>
                    </section>
                    <section class="row">
                        <div class="col-sm-12 col-md-5 ">
                            <img class="center-block" src="../../images/why-use/wearehere.png">
                        </div>
                        <div class="col-sm-12 col-md-7 ">
                            <h2>Skabe bæredygtigt forbrug</h2>

                          <p>Ved at bruge KidUp genbruger du ubrugte ressourcer, hvad enten du er lejer eller udlejer. Sammen kan vi få sat en stopper for det endeløse forbrug, og tænke os lidt mere om - inden vi køber nyt. Vi kan skabe en verden med måde. </p>
                            <p>&nbsp;</p>
                            <p>
                              <?//= Yii::t("app", "Start exploring products") ?>
                              <!--</h2>-->
                              <!--                            <br>-->
                              <!--                            <button type="submit" class="btn btn-main">--><?//= Yii::t("app", "Browse now") ?><!--</button>-->
                              <!--                        </div>-->
                              <!--                    </section>-->
                            </p>
                      </div></section></div>
            </div>
        </div>
    </div>
</div>
