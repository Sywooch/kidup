<?php
/**
 * @var $model \app\modules\review\models\Review
 *
 */
use app\components\WidgetRequest;
use yii\helpers\Url;
use Carbon\Carbon;
?>
<div class="card-width" style="width: 100%; padding:0 20px">
    <div class="card" style="padding:20px">
        <div class="row">
            <div class="col-md-2">
                <a href="<?= Url::to('@web/user/'.$model->reviewer_id) ?>">
                    <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                        'user_id' => $model->reviewer_id
                    ]) ?>
                    <br/>
                    <?= $model->reviewer->profile->first_name ?>
                </a>
            </div>
            <div class="col-md-10">
                <?= $model->value ?>
                <br/><br/>
                <small><?= Carbon::createFromTimestamp($model->created_at, \Yii::$app->params['serverTimeZone'])->toFormattedDateString() ?></small>
            </div>
        </div>
    </div>
</div>
