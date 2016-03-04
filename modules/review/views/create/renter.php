<?php
use Carbon\Carbon;
use images\components\ImageHelper;
use kartik\rating\StarRating;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this \app\extended\web\View
 * @var $booking \booking\models\booking\Booking
 * @var $item \item\models\Item
 */
$this->assetPackage = \app\assets\Package::REVIEW;
?>
<section class="section" id="create-review">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-md-offset-1">
                    <h2>
                        <?= Yii::t("review.create.", "Rate & Review") ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-md-3 col-md-offset-1">
                <?= ImageHelper::img($item->getImageName(0), ['q' => 90, 'w' => 600]) ?>
                <div style="margin-top:-25px">
                    <?= \user\widgets\UserImage::widget([
                        'user_id' => $item->owner_id,
                        'width' => '50px',
                    ]) ?>
                </div>
                <b><?= $item->name ?></b>
                <br/>
                <?= Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString() ?> -
                <?= Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString() ?>
                <br/>
                <?= Yii::t("review.create.by_user", "By {user}", [
                    'user' => Html::a($item->owner->profile->first_name, '@web/user/' . $item->owner_id)
                ]) ?>
            </div>
            <div class="col-md-6 col-md-offset-1 card">
                <?php
                $form = ActiveForm::begin([
                    'enableClientValidation' => true,
                    'method' => 'post',
                ]); ?>
                <h4>
                    <?= Yii::t("review.create.describe_experience", "Describe your experience") ?> <br/>
                    <small>
                        <?= Yii::t("review.create.experience_in_public", "This review will be public.") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'public')->textarea(['rows' => 4])->label(false) ?>

                <h4>
                    <?= Yii::t("review.create.private_feedback", "Private feedback to {0}", [
                        $booking->item->owner->profile->first_name
                    ]) ?>
                    <br/>
                    <small>
                        <?= Yii::t("review.create.private_feedback_is_private_owner",
                            "This feedback will only be visible to the owner of the item.") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'private')->textarea()->label(false) ?>

                <h4>
                    <?= Yii::t("review.create.experience", "Overall Experience") ?>
                </h4>
                <?= $form->field($model, 'experience')->label(false)->widget(StarRating::classname(), [
                    'pluginOptions' => [
                        'size' => 'lg',
                        'step' => 1,
                        'showClear' => false,
                        'showCaption' => false,
                    ]
                ]);
                ?>

                <h4>
                    <?= Yii::t("review.create.communication", "Communication") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review.create.communication_text",
                            "How easy was it to communicate with the owner?") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'communication')->label(false)->widget(StarRating::classname(), [
                    'pluginOptions' => [
                        'size' => 'lg',
                        'step' => 1,
                        'showClear' => false,
                        'showCaption' => false,
                    ]
                ]);
                ?>

                <h4>
                    <?= Yii::t("review.create.exchange", "Exchange") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review.create.exchange_text", "How did the exchange of product go?") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'exchange')->label(false)->widget(StarRating::classname(), [
                    'pluginOptions' => [
                        'size' => 'lg',
                        'step' => 1,
                        'showClear' => false,
                        'showCaption' => false,
                    ]
                ]);
                ?>

                <h4>
                    <?= Yii::t("review.create.accuracy", "Accuracy") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review.create.accuracy_text",
                            "How well did the photo's and description describe the actual item?") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'adAccuracy')->label(false)->widget(StarRating::classname(), [
                    'pluginOptions' => [
                        'size' => 'lg',
                        'step' => 1,
                        'showClear' => false,
                        'showCaption' => false,
                    ]
                ]);
                ?>

                <h4>
                    <?= Yii::t("review.create.for_kidup", "For KidUp") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review.create.for_kidup_text",
                            "Is there anything you'd like to tell to KidUp regarding this experience?") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'kidupPrivate')->textarea(['rows' => 4])->label(false) ?>

                <?= Html::submitButton(\Yii::t('review.submit_button', 'Submit Review'), [
                    'class' => 'btn btn-danger btn-lg btn-fill'
                ]) ?>
                <br/><br/>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</section>