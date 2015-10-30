<?php
use app\helpers\ViewHelper;
/**
 * @var \app\extended\web\View $this
 */
$this->assetPackage = \app\assets\Package::PAGES;

?>
<div style="background-color: rgba(235,235,235,0.8);margin-bottom: -90px;padding-bottom:80px;">
    <section class="container">
        <div class="row" style="margin-top: 60px">
            <?php if (\Yii::$app->user->isGuest): ?>
                <h4 style="text-align: center">
                    <?= Yii::t("pages.signup-trigger-line-1", "Thank you for your interest in KidUp!") ?>
                    <br>
                    <small>
                        <a href="#" style="color:#71baaf;" data-toggle="modal" data-target="#signup-conversion-modal" <?= ViewHelper::trackClick('pages.click_modal')?>>
                            <?= Yii::t("pages.signup-trigger-line-2", "Register here and get all the updates!") ?>
                        </a>
                    </small>
                </h4>
                <?php endif; ?>
            <br><br>

            <div class="col-md-8 col-md-offset-2 card">
                <br/>
                <?= $content ?>
                <br/>
            </div>
        </div>
        <?= \app\widgets\SignupModal::widget([
            'autoOpen' => false
        ]); ?>
    </section>
</div>
<!---->
<!--<br><br>-->
<!--<iframe src="http://pages.kidup.dk/2015/07/29/omkring-os/" frameborder="0" scrolling="no" height="500px" width="100%"-->
<!--        style="margin-top:30px;margin-bottom:-100px;overflow:hidden;"></iframe>-->
