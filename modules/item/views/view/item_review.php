<?php

use Carbon\Carbon;
use yii\helpers\Url;
use app\modules\user\widgets\UserImage;
use app\modules\review\widgets\ReviewScore;
use app\modules\review\models\Review;

/**
 * @var \app\components\extended\View $this
 * @var $model \app\modules\review\models\Review
 */

$this->assetPackage = \app\assets\Package::ITEM_VIEW;
?>
<div class="card card-minimal">
    <div class="row" style="margin:15px;padding:15px;background-color: white;">
        <div class="col-md-2 text-center">
            <a href="<?= Url::to('@web/user/' . $model->reviewer_id) ?>">
                <?= UserImage::widget([
                    'user_id' => $model->reviewer_id
                ]) ?>
                <br/>
                <?= $model->reviewer->profile->first_name ?>
            </a>
        </div>
        <div class="col-md-10">
            <?= $model->value ?>
            <div class="pull-right">
                <?php
                // getting the score, should be done nicer
                $r = Review::find()->where([
                    'reviewed_id' => $model->reviewed_id,
                    'type' => Review::TYPE_EXPERIENCE,
                    'booking_id' => $model->booking_id,
                    'reviewer_id' => $model->reviewer_id
                ])->andWhere('id > :id')->params([':id' => $model->id])->one();
                if ($r !== null) {
                    echo ReviewScore::widget([
                        'stars' => $r->value
                    ]);
                }
                ?>
            </div>
            <br/><br/>
            <small><?= Carbon::createFromTimestamp($model->created_at,
                    \Yii::$app->params['serverTimeZone'])->toFormattedDateString() ?></small>
        </div>
    </div>
</div>
