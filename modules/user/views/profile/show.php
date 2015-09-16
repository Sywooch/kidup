<?php

use app\components\WidgetRequest;
use app\modules\item\widgets\ItemCard;
use Carbon\Carbon;
use app\modules\user\widgets\UserImage;
use app\modules\images\components\ImageHelper;

/**
 * @var \yii\web\View $this
 * @var \app\modules\user\models\Profile $profile
 * @var \yii\data\ActiveDataProvider $itemProvider
 * @var bool $fbVerified
 * @var bool $twVerified
 */
$this->title = ucfirst(\Yii::t('title', 'Kidup user {0}', [$profile->first_name])) . ' - ' . Yii::$app->name;

\app\modules\user\assets\ProfileAsset::register($this);
?>
<section class="section" id="public-profile">
    <div class="container site-area-content">
        <div class="row">
            <div class="col-sm-4 col-md-3 col-md-offset-1">
                <div class="card card-user">
                    <div class="content">
                        <div class="author">
                            <?= UserImage::widget([
                                'user_id' => $profile->user_id,
                                'width' => '150px'
                            ]) ?>

                        </div>
                        <h4><?= Yii::t("user", "Verification") ?></h4>

                        <?php $verified = function ($type, $icon = '<i class="fa fa-check"></i>') {
                            return
                                '<div class="row verifyEntity">
                            <div class="col-xs-2">
                                ' . $icon . '
                            </div>
                            <div class="col-xs-8 entity">
                                ' . $type . '
                            </div>
                        </div>';
                        } ?>
                        <?= $profile->user->created_at < Carbon::createFromDate(2015, 12, 30)->timestamp ? $verified(\Yii::t('user', 'KidUp Ambassador')):''?>
                        <?= $fbVerified ? $verified('Facebook') : '' ?>
                        <?= $twVerified ? $verified('Twitter') : '' ?>
                        <?= $profile->email_verified == 1 ? $verified(\Yii::t('user', 'Email')) : '' ?>
                        <?= $profile->phone_verified == 1 ? $verified(\Yii::t('user', 'Phone')) : '' ?>
                    </div>

                </div>

            </div>

            <div class="col-sm-8 col-md-7">
                <h2>
                    <div class="pull-right">
                        <?php
                        if ($profile->user->created_at < Carbon::createFromDate(2015, 12, 30)->timestamp) {
                            echo ImageHelper::image('kidup/user/ambassador.png', ['w' => 150])."<br>";
                        } ?>
                    </div>
                    <?= Yii::t("user", "Hi, I'm {0}!", [
                        $profile->first_name
                    ]) ?>

                    <br/>
                    <small>
                        <?= isset($profile->user->locations[0]->country0->name) ? $profile->user->locations[0]->country0->name : '' ?>
                        -
                        <?= Yii::t("user", "Member since {0}", [
                            Carbon::createFromTimestamp($profile->user->created_at)->toFormattedDateString()
                        ]) ?>
                    </small>
                </h2>

                <div class="description">
                    <?= $profile->description ?>
                </div>
                <hr>
                <h4>
                    <?= Yii::t("user", "Reviews from other families") ?>
                </h4>

                <div class="card-area-width">
                    <?php
                    // displaying the search results
                    echo \yii\widgets\ListView::widget([
                        'dataProvider' => $reviewProvider,
                        'itemView' => 'profile_review',
                        'itemOptions' => ['tag' => 'span'],
                    ]);
                    ?>
                </div>
            </div>

        </div>
        <div class="hidden-xs row">
            <div class="col-md-10 col-md-offset-1">
                <h3>
                    <?= Yii::t("user", "Products from {0}", [
                        $profile->first_name
                    ]) ?>
                </h3>

                <div class="row">
                    <?php
                    foreach ($items as $item) {
                        echo ItemCard::widget([
                            'model' => $item,
                            'showDistance' => false,
                            'numberOfCards' => 3,
                            'reviewCount' => true
                        ]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

