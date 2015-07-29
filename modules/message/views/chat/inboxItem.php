<?php
use app\components\WidgetRequest;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 */
?>

<div class="panel panel-default <?= (isset($_GET['id']) && $_GET['id'] == $model->id) ? 'selected' : '' ?>">
    <div class="panel-heading">
        <div class="panel-title">
            <div class="author">
                <a href="<?= Url::to('@web/messages/'.$model->id) ?>">
                    <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, ['user_id' => $model->otherUser->user_id])?>
                    <?php
                        if($model->unreadMessages() > 0) echo ' <div class="badge"></div>';
                    ?>
                    <span>
                        <?= $model->otherUser->first_name ?: $model->title ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>