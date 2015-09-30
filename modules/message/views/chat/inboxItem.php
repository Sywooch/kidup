<?php
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $model \message\models\Conversation
 */
?>
<tr>
    <td>
        <div class="row">
            <div class="col-md-2">
                <a href="<?= Url::to("@web/user/{$model->id}") ?>">
                    <?= \user\widgets\UserImage::widget(['user_id' => $model->otherUser->user_id]) ?>
                </a>
            </div>
            <div class="col-md-2">
                <?= $model->otherUser->first_name ?: $model->title ?>
                <br>
                <?= \Carbon\Carbon::createFromTimestamp($model->lastMessage->created_at)->format("d-M") ?>
            </div>
            <div class="col-md-6">
                <a href="<?= Url::to("@web/inbox/{$model->id}") ?>">
                    <?php
                    if ($model->unreadMessages() > 0) {
                        echo ' <div class="badge"></div>';
                    }
                    ?>
                    <?= \yii\helpers\StringHelper::truncate($model->lastMessage->message, 120) ?>
                </a>
            </div>
            <div class="col-md-2">
                <?= $model->booking->getStatusName() ?>
            </div>
        </div>
    </td>
</tr>