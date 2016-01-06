<?php \yii\widgets\Spaceless::begin(); ?>
    <script>
//        window.setTimeout(function(){
//            window.trackItemCardView($("[item-id={{objectID}}]"), {"item_id": ' {{objectID}} ', "page": "search"});
//        },250);
    </script>
    <div class="item-card card-width col-xs-12 col-sm-6 col-md-3 col-lg-3" id="item-template" style="padding:4px;" item-id="{{objectID}}">
        <a href="<?= \yii\helpers\Url::to('@web/item') ?>/{{objectID}}" data-pjax="0">
            <div class="card" style="margin-bottom:4px;">
                <div class="image"
                     style="background-image: url('{{img}}') ;background-size: cover; background-position: 50% 50%;">
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
                        <img class="avatar img-circle" src="{{owner.img}}" width="50" height="50" alt=""
                             style="width:50px;height:50px;"></div>
                </div>
                <div class="content">
                    <h3 class="title" style="height:20px;">
                        {{title}}
                    </h3>

                    <div class="category">
                        {{#helpers.subCategory}}{{/helpers.subCategory}}
                    </div>

                    <div class="footer-divs">
                        <div class="reviews">
                            {{#helpers.reviews}}{{/helpers.reviews}}
                        </div>
                        <div class="location">
                            <i class="fa fa-map-marker"></i>
                            {{#helpers.location}}{{/helpers.location}}
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
<?php \yii\widgets\Spaceless::end(); ?>