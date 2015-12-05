<?php \yii\widgets\Spaceless::begin(); ?>
<div class="item-card card-width col-xs-12 col-sm-6 col-md-3 col-lg-3" id="item-template">
    <a href="<?= \yii\helpers\Url::to('@web/item') ?>/{{objectID}}" data-pjax="0">
        <div class="card">
            <div class="image" style="background-size: cover; background-position: 50% 50%;">
                <div class="price-badge">
                    <span class="time">
                        <?= Yii::t("item.card.from", "from") ?>
                    </span>
                    <span class="currency">kr.</span>
                    <span class="price">
                        {{price_week}}
                    </span>
                </div>
                <div class="author">
                    <img src="{{img}}" alt="">
                </div>
            </div>
            <div class="content">
                <h3 class="title" style="height:20px;">
                    {{title}}
                </h3>

                <div class="category">

                </div>

                <div class="footer-divs">
                    <div class="reviews">
                        {{score}}
                    </div>
                    <div class="location">
                        <i class="fa fa-map-marker"></i>
                        {{_raninkInfo.geoDistance}}y
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
<?php \yii\widgets\Spaceless::end(); ?>