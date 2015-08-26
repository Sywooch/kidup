<?php

use app\components\WidgetRequest;
use Carbon\Carbon;

/**
 * @var \yii\web\View $this
 * @var \app\modules\user\models\Profile $profile
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
                            <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                                'user_id' => $profile->user_id,
                                'width' => '150px'
                            ]) ?>

                        </div>
                        <h4><?= Yii::t("user", "Verification") ?></h4>

                        <?php $verified = function ($type) {
                            return
                                '<div class="row verifyEntity">
                            <div class="col-xs-1">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="col-xs-8 entity">
                                ' . $type . '
                            </div>
                        </div>';
                        } ?>

                        <?= $fbVerified ? $verified('Facebook') : '' ?>
                        <?= $twVerified ? $verified('Twitter') : '' ?>
                        <?= $profile->email_verified == 1 ? $verified(\Yii::t('user', 'Email')) : '' ?>
                        <?= $profile->phone_verified == 1 ? $verified(\Yii::t('user', 'Phone')) : '' ?>
                    </div>

                </div>
                <div class="hidden-xs">
                    <h3><?= Yii::t("user", "Items") ?></h3>
                    <?php
                    // displaying the search results
                    echo \yii\widgets\ListView::widget([
                        'dataProvider' => $itemProvider,
                        'itemView' => 'profile_item',
                        'layout' => '<div class="row">
                                    {items}
                                </div>
                                <div class="row">
                                    {pager}
                                </div>',
                        'itemOptions' => ['tag' => 'span'],
                    ]);
                    ?>
                </div>

            </div>

            <div class="col-sm-8 col-md-8">
                <h2>
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
                <h4><?= Yii::t("user", "Reviews ") ?><br>
                    <small><?= Yii::t("user", "Reviews from other families") ?></small>
                </h4>
                <div class="card-area-width">
                    <?php
                    // displaying the search results
                    echo \yii\widgets\ListView::widget([
                        'dataProvider' => $reviewProvider,
                        'itemView' => 'profile_review',
                        'layout' => '<div class="row">
                                    {items}
                                </div>
                                <div class="row">
                                    {pager}
                                </div>',
                        'itemOptions' => ['tag' => 'span'],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

