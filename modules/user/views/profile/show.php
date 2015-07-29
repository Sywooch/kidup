<?php

use app\components\WidgetRequest;
use Carbon\Carbon;

/**
 * @var \yii\web\View $this
 * @var \app\modules\user\models\Profile $profile
 */
$this->title = ucfirst(\Yii::t('title', 'Kidup user {0}', [$profile->first_name])) . ' - ' . Yii::$app->name;
?>
<section class="section" id="public-profile">
    <div class="site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <h2>
                        <?= Yii::t("user", "Hi, I'm {0}!", [
                            $profile->first_name
                        ]) ?>
                        <br/>
                        <small>
                            <?= $profile->user->locations[0]->country0->name ?>
                            -
                            <?= Yii::t("user", "Member since {0}", [
                                Carbon::createFromTimestamp($profile->user->created_at)->toFormattedDateString()
                            ]) ?>
                        </small>
                    </h2>
                </div>
            </div>
        </div>
    </div>
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

                        <p class="description">
                            <?= $profile->description ?>
                        </p>
                    </div>
                </div>
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
                    //                    'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
                ]);
                ?>
            </div>

            <div class="col-sm-8 col-md-8">
                <div class="row">
                    <h4><?= Yii::t("user", "Reviews ") ?>
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
                            //                    'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
                        ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

