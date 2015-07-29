<?php
use yii\helpers\Html;
use yii\helpers\Url;
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
                            <p class="titel"><?= Yii::t("app", "Company") ?></p>
                            <ul class="nav">
                                <li><p><?= Html::a(Yii::t('app', 'About us'), '@web/p/company/about')?></p></li>
                                <li><p><?= Html::a(Yii::t('app', 'Terms and Conditions'), '@web/p/help/terms')?></p></li>
                                <li><p><?= Html::a(Yii::t('app', 'Privacy'), '@web/p/help/privacy')?></p></li>
                                <li><p><?= Html::a(Yii::t('app', 'Contact'), '@web/site/contact')?></p></li>
                            </ul>
                        </div>
                        <div class="col-sm-3">
                            <p class="titel"><?= Yii::t("app", "Understand") ?></p>
                            <ul class="nav">
                                <li><p><?= Html::a(Yii::t('app', 'Why use kidup?'), '@web/p/tutorial/why')?></p></li>
                                <li><p><?= Html::a(Yii::t('app', 'How to rent?'), '@web/p/tutorial/rent')?></p></li>
                                <li><p><?= Html::a(Yii::t('app', 'How to rent out?'), '@web/p/tutorial/out')?></p></li>
                            </ul>
                        </div>
                        <div class="col-sm-3">
                            <p class="titel"><?= Yii::t("app", "Help") ?></p>
                            <ul class="nav">
                                <li><p><?= Html::a(Yii::t('app', 'Renting guide'), '@web/p/help/guides')?></p></li>
                                <li><p><?= Html::a(Yii::t('app', 'Safety & Insurance'), '@web/p/help/safety')?></p></li>
                                <li><p><?= Html::a(Yii::t('app', 'FAQ'), '@web/p/help/faq')?></p></li>
                            </ul>
                        </div>
                    </div>
                    <div class="social">
                        <div class="col-sm-3 text-center">
                            <p class="titel"><?= Yii::t("app", "Follow us") ?></p>
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
                <?= Html::a('DA', '@web/site/change-language?lang=da-DK') ?> /
                <?= Html::a('EN', '@web/site/change-language?lang=en-US') ?>
            </div>
        </div>
        <div class="row last">
            <div class="col-sm-12 text-center">
                <img src="<?= Url::to('@web/img/logo/horizontal-white.png') ?>" width="80px">
                <h4>&#169;KidUp | 2015</h4>
            </div>
        </div>
    </div>
</footer>