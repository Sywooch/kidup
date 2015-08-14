<?php
use yii\helpers\Url;

?>

<!--Kid Up footer-->
<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="row">
                    <div class="info">
                        <div class="col-md-6">
                            <p class="titel">Kidup IVS</p>
                            <ul class="nav">
                                <li>Ceres Alle 1</li>
                                <li>8000 Aarhus C</li>
                                <!--<li><p>CVR: ____</p></li>-->
                                <br>
                                <li><a href="mailto:info@kidup.dk?subject=Hello Kidup"><p>info@kidup.dk</p></a></li>
                            </ul>
                        </div>

                    </div>
                    <div class="social">
                        <div class="col-md-6 text-center">
                            <p class="titel">Følg os</p>
                            <a href="https://www.facebook.com/kidup.social" target="_blank" title="Facebook">
                                <i id="facebook" class="fa fa-facebook fa-2x"></i>
                            </a>
                            <a href="http://instagram.com/kidup_social/" target="_blank" title="Instagram">
                                <i id="instagram" class="fa fa-instagram fa-2x"></i>
                            </a>
                            <a href="https://twitter.com/kidup_social" target="_blank" title="twitter">
                                <i id="twitter" class="fa fa-twitter fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row last">
            <div class="col-sm-12 text-center">
                <img src="<?= Url::to('@web/img/logo/horizontal.png') ?>" width="80px">
                <h4>&#169;KidUp | 2015</h4>
            </div>
        </div>
    </div>
</footer>

