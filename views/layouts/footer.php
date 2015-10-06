<?php
use yii\helpers\Html;
use yii\helpers\Url;
use images\components\ImageHelper;

\app\assets\FontAwesomeAsset::register($this);
?>

<!--Kid Up footer-->
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="info">
                        <div class="col-sm-3">
                            <p class="titel"><?= Yii::t("app.footer.company_header", "Company") ?></p>
                            <ul class="nav">
                                <li><p><?= Html::a(Yii::t('app.footer.about_us', 'About Us'), '@web/p/about-kidup')?></p></li>
                                <li><p><?= Html::a(Yii::t('app.footer.terms_and_conditions', 'Terms and Conditions'), '@web/p/terms-and-conditions')?></p></li>
                                <li><p><?= Html::a(Yii::t('app.footer.privacy', 'Privacy'), '@web/p/privacy')?></p></li>
                                <li><p><?= Html::a(Yii::t('app.footer.cookies', 'Cookies'), '@web/p/cookies')?></p></li>
                                <li><p><?= Html::a(Yii::t('app.footer.contact', 'Contact'), '@web/p/about-kidup')?></p></li>
                            </ul>
                        </div>
                        <div class="col-sm-3">
                            <p class="titel"><?= Yii::t("app.footer.understand_header", "Understand") ?></p>
                            <ul class="nav">
                                <li><p><?= Html::a(Yii::t('app.footer.why_use_kidup', 'Why use kidup?'), '@web/p/why-rent')?></p></li>
                                <li><p><?= Html::a(Yii::t('app.footer.how_rent', 'How to rent?'), '@web/p/how-to-rent')?></p></li>
                                <li><p><?= Html::a(Yii::t('app.footer.how_rent_out', 'How to rent out?'), '@web/p/how-to-rentout')?></p></li>
                            </ul>
                        </div>
                        <div class="col-sm-3">
                            <p class="titel"><?= Yii::t("app.footer.help", "Help") ?></p>
                            <ul class="nav">
                                <li><p><?= Html::a(Yii::t('app.footer.renting_guide', 'Renting guide'), '@web/p/guide')?></p></li>
                                <li><p><?= Html::a(Yii::t('app.footer.safety_insurance', 'Safety & Insurance'), '@web/p/safety')?></p></li>
                                <li><p><?= Html::a(Yii::t('app.footer.faq', 'FAQ'), '@web/p/faq')?></p></li>
                            </ul>
                        </div>
                    </div>
                    <div class="social">
                        <div class="col-sm-3 text-center">
                            <p class="titel"><?= Yii::t("app.footer.follow_us", "Follow us") ?></p>
                            <a href="https://www.facebook.com/kidup.social" target="_blank" title="Facebook">
                                <i id="facebook" class="fa fa-facebook fa-2x"></i>
                            </a>
                            <a href="https://instagram.com/kidup_social/" target="_blank" title="Instagram">
                                <i id="facebook" class="fa fa-instagram fa-2x"></i>
                            </a>
                            <a href="https://twitter.com/kidup_social" target="_blank" title="twitter">
                                <i id="facebook" class="fa fa-twitter fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 language text-center">
                <?= Html::a('DA', '@web/home/home/change-language?lang=da-DK') ?> /
                <?= Html::a('EN', '@web/home/home/change-language?lang=en-US') ?>
            </div>
        </div>
        <div class="row last">
            <div class="col-sm-12 text-center">
                <img src="<?= ImageHelper::url('kidup/logo/horizontal-white.png') ?>" width="80px">
                <h4>&#169;KidUp | 2015</h4>
            </div>
        </div>
    </div>
</footer>
