<?php
use app\components\WidgetRequest;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/*
 * @var \app\modules\message\models\Conversation $conversation
 * @var $conversationDataProvider
 * @var yii\web\View $this
 */

$this->title = ucfirst(\Yii::t('title', 'Chat')) . ' - ' . Yii::$app->name;
?>

<section class="section" id="inbox" style="margin-top: 70px">
    <div class="site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-md-offset-1">
                    <h2><?= Yii::t("message", "Inbox") ?><br>
                        <small><?= Yii::t("message", "All your parent interactions, securely through KidUp") ?></small>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-sm-4 col-md-3 col-md-offset-1">
                <div class="card card-refine card-mail">
                    <div class="header">
                        <h4 class="title"><?= Yii::t("message", "Inbox") ?>
                            <button class="btn btn-default btn-xs btn pull-right btn-simple">
                                <i class="fa fa-envelope-o"></i>
                            </button>
                        </h4>
                    </div>
                    <div class="content">
                        <?php
                        // displaying the inbox items
                        echo ListView::widget([
                            'dataProvider' => $conversationDataProvider,
                            'layout' => '<div class="row">
                                    {items}
                                </div>
                                <div class="row">
                                    {pager}
                                </div>',
                            'itemView' => 'inboxItem',
                            'itemOptions' => ['tag' => 'span'],
                            //                    'pager' => ['class' => \kop\y2sp\ScrollPager::className()]
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 col-md-7 ">
                <div class="row mail-content card">
                    <div class="row col-sm-12">
                        <h3 class="text-center">
                            <?= Yii::t("user", "Conversation with {username}", [
                                'username' => $conversation->otherUser->first_name
                            ]) ?>
                        </h3>

                        <div class="text-center">
                            <?= $conversation->title ?>
                            <br/>
                            <?= isset($booking->id) ? Html::a(\Yii::t('Booking', 'Booking'),
                                '@web/booking/' . $booking->id) : '' ?>
                        </div>
                        <?php
                        if (isset($booking) && isset($booking->item)) {
                            if (\Yii::$app->user->id == $booking->item->owner_id
                                && $booking->status == \app\modules\booking\models\Booking::PENDING
                            ) {
                                echo \yii\bootstrap\Alert::widget([
                                    'options' => [
                                        'class' => 'alert-info',
                                    ],
                                    'body' => \Yii::t('booking',
                                        'This booking is still waiting for your response, {0} to accept or decline it. You have {1} the booking will void.',
                                        [
                                            Html::a(\Yii::t('booking', 'click here'),
                                                '@web/booking/' . $booking->id . '/request'),
                                            Carbon::now()->diffForHumans(Carbon::createFromTimestamp($booking->request_expires_at))
                                        ]),
                                ]);
                            }
                        }
                        ?>
                        <?php $form1 = ActiveForm::begin([
                            'enableClientValidation' => true,
                            'method' => 'post',
                        ]); ?>
                        <div class="media media-post">
                            <form class="form">
                                <a class="pull-left author" href="#">
                                    <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                                        'user_id' => $conversation->otherUser->user_id
                                    ]) ?>
                                </a>

                                <div class="media-body">
                                    <?= $form1->field($form, 'message')->label(false)->textarea([
                                        'class' => 'form-control',
                                        'placeholder' => \Yii::t('message', 'Your personal message to {0}',
                                            [$conversation->otherUser->first_name]),
                                        'rows' => 3
                                    ]); ?>
                                    <div class="media-footer">
                                        <?= Html::submitButton(\Yii::t('message', 'Send'),
                                            ['class' => 'btn btn-danger btn-fill pull-right']); ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 message card">
                        <?php if (isset($booking->id)) {
                            echo \Yii::t('booking', 'This chat refers to {0}', [
                                Html::a(\Yii::t('booking', 'booking #{0}', [$booking->id]),
                                    '@web/booking/' . $booking->id)
                            ]);
                        } ?>
                        <?= Yii::t("booking",
                            "Please keep all your communication through KidUp's secure system. This way KidUp can easily provide support when necessary.") ?>
                    </div>
                </div>
                <?php foreach ($messages as $message): ?>
                <div class="row">
                    <div class="col-xs-12 message card">
                        <div class="row">
                            <div class="col-md-2">
                                <?php if ($message->sender_user_id === \Yii::$app->user->id): ?>
                                    <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                                        'user_id' => $message->sender_user_id
                                    ]) ?>
                                    <h4 style="margin:0" class="">
                                        <?= \Yii::$app->user->identity->profile->first_name ?>
                                    </h4>
                                    <small><i>
                                            <?= Carbon::createFromTimestamp($message->created_at)->diffForHumans(); ?>
                                        </i></small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <?= $message->message ?>
                            </div>
                            <div class="col-md-2">
                                <?php if ($message->sender_user_id !== \Yii::$app->user->id): ?>
                                    <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                                        'user_id' => $message->sender_user_id
                                    ]) ?>
                                    <h3 style="margin:0" class="">
                                        <?= $message->senderUser->profile->first_name ?>
                                    </h3>
                                    <small><i>
                                            <?= Carbon::createFromTimestamp($message->created_at)->diffForHumans(); ?>
                                        </i></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>