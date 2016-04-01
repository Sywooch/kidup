<?php

use Carbon\Carbon;
use images\components\ImageHelper;
use item\widgets\ItemCard;
use user\widgets\UserImage;

/**
 * @var \yii\web\View $this
 * @var \user\models\profile\Profile $profile
 * @var \yii\data\ActiveDataProvider $itemProvider
 * @var bool $fbVerified
 * @var bool $twVerified
 */
$this->title = ucfirst(\Yii::t('user.view.title', 'Kidup user {0}', [$profile->first_name])) . ' - ' . Yii::$app->name;

\user\assets\ProfileAsset::register($this);
$this->assetPackage = \app\assets\Package::USER;
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
                        <h4><?= Yii::t("user.view.verification_header", "Verification") ?></h4>

                        <?php
                        $emailVerified = ImageHelper::image('kidup/user/verification/email.png', ['w' => 45]);
                        $fbVerified = ImageHelper::image('kidup/user/verification/facebook.png', ['w' => 45]);
                        $phoneVerified = ImageHelper::image('kidup/user/verification/phone.png', ['w' => 45]);
                        $verified = function ($type, $icon) {
                            return
                                '<div class="col-md-4 verifyEntity">
                                ' . $icon . '
                            <div class="entity">
                                ' . $type . '
                            </div>
                        </div>';
                        } ?>
                        <?php if ($profile->user->created_at < Carbon::createFromDate(2015, 12, 30)->timestamp): ?>
                                <?= \Yii::t('user.view.kidup_ambassador', 'KidUp Ambassador') ?>
                        <?php endif; ?>
                        <div class="row">
                            <?= $fbVerified ? $verified('Facebook', $fbVerified) : '' ?>
                            <?= $profile->email_verified == 1 ? $verified(\Yii::t('user.view.email', 'Email'),
                                $emailVerified) : '' ?>
                            <?= $profile->phone_verified == 1 ? $verified(\Yii::t('user.view.phone', 'Phone'),
                                $phoneVerified) : '' ?>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-sm-8 col-md-6">
                <h2>
                    <div class="pull-right">
                        <?php
                        if ($profile->user->created_at < Carbon::createFromDate(2015, 12, 30)->timestamp) {
                            echo ImageHelper::image('kidup/user/ambassador.png', ['w' => 150]) . "<br>";
                        } ?>
                    </div>
                    <?= Yii::t("user.view.header_hi_i_am", "Hi, I'm {0}!", [
                        $profile->first_name ? $profile->first_name : \Yii::t('user.view.deafult_name_on_empty_profile_name',
                            'a KidUpper')
                    ]) ?>

                    <br/>
                    <small>
                        <?= isset($profile->user->locations[0]->country0->name) ? $profile->user->locations[0]->country0->name : '' ?>
                        -
                        <?= Yii::t("user.view.member_since", "Member since {0}", [
                            Carbon::createFromTimestamp($profile->user->created_at)->formatLocalized("%B %Y")
                        ]) ?>
                    </small>
                </h2>

                <div class="description">
                    <?= $profile->description ?>
                </div>
                <hr>
                <h4>
                    <?= Yii::t("user.view.reviews_from_families_header", "Reviews from other families") ?>
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
                    <?= count($items) > 0 ? Yii::t("user.view.products_from_user", "Products from {0}", [
                        $profile->first_name
                    ]) : '' ?>
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

