<?php
use app\components\WidgetRequest;
use Carbon\Carbon;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/*
 * @var Conversation $conversation
 * @var yii\web\View $this
 */
\app\modules\message\assets\MessageAsset::register($this);
$this->title = ucfirst(\Yii::t('title', 'Chat')) . ' - ' . Yii::$app->name;
?>

<section class="section" id="conversation">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-md-6 col-md-offset-1 ">
                <h3 class="row">
                    <?= Yii::t("user", "Conversation with {username}", [
                        'username' => $conversation->otherUser->first_name
                    ]) ?>
                </h3>

                <div class="row mail-content card">

                    <div class="col-sm-12">
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


                        <?= $form1->field($form, 'message')->label(false)->textarea([
                            'class' => 'form-control',
                            'placeholder' => \Yii::t('message', 'Your personal message to {0}',
                                [$conversation->otherUser->first_name]),
                            'rows' => 3
                        ]); ?>
                        <?= Html::submitButton(\Yii::t('message', 'Send'),
                            ['class' => 'btn btn-danger btn-fill pull-right']); ?>
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
                                <div class="col-md-2 col-sm-3">
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
                                <div class="col-md-8 col-sm-6">
                                    <?= $message->message ?>
                                </div>
                                <div class="col-md-2 col-sm-3">
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
            <div class="col-md-4">
                <div class="card other-user">
                    <div class="row">
                        <div class="col-md-4">
                            <a class="author" href="<?= \yii\helpers\Url::to("@web/user/{$conversation->otherUser->user_id}") ?>">
                                <?=\app\modules\user\widgets\UserImage::widget([
                                    'user_id' => $conversation->otherUser->user_id,
                                    'width' => '80px',
                                ]) ?>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <h5>
                                <?= $conversation->otherUser->first_name ?>
                            </h5>
                            <div class="location">
                                <?= $conversation->booking->item->location->city ?>
                            </div>
                            <div class="member-since">
                                <?= Yii::t("message", "Member since {0}", [
                                    Carbon::createFromTimestamp($conversation->otherUser->user->created_at)->toDateString()
                                ]) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>