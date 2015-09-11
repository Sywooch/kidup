<?php
use app\components\WidgetRequest;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/*
 * @var \app\modules\message\models\Conversation $conversation
 * @var dataProvider $conversationDataProvider
 * @var $conversationDataProvider
 * @var yii\web\View $this
 */
\app\modules\message\assets\MessageAsset::register($this);
$this->title = ucfirst(\Yii::t('title', 'Chat')) . ' - ' . Yii::$app->name;
?>

<section class="section" id="inbox">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-4">
                                <?= \app\modules\images\components\ImageHelper::image('kidup/graphics/notification.png', ['w' => 55]) ?>
                                <h4 class="title">
                                    <?= Yii::t("message", "Inbox") ?>
                                </h4>
                            </div>

                            <div class="col-md-2 col-md-offset-6 booking">
                                <?= Yii::t("message", "Booking Status") ?>
                            </div>
                        </div>

                    </div>
                    <?php
                    // displaying the inbox items
                    echo ListView::widget([
                        'dataProvider' => $conversationDataProvider,
                        'layout' => '
                                <table class="table">
                                    {items}
                                </table>
                                <div class="row">
                                    {pager}
                                </div>',
                        'itemView' => 'inboxItem',
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>