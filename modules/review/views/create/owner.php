<?php
use Carbon\Carbon;
use images\components\ImageHelper;
use kartik\rating\StarRating;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this \app\components\view\View
 * @var $booking \booking\models\booking\Booking
 * @var $item \item\models\item\Item
 */
$this->assetPackage = \app\assets\Package::REVIEW;

?>
<section class="section" id="create-review">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-md-offset-1">
                    <h2>
                        <?= Yii::t("review.create.header", "Rate & Review") ?>
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
                    <?= \user\widgets\UserImage::widget( [
                        'user_id' => $booking->renter_id,
                        'width' => '50px',
                    ]) ?>
                </div>
                <b><?= $item->name ?></b>
                <br/>
                <?= Yii::t("review.create.rented_by", "Rented by {0} between {1} and {2}", [
                    Html::a($booking->renter->profile->first_name, '@web/user/' . $booking->renter_id),
                    Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString(),
                    Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString()
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
                        <?= Yii::t("review.create.experience_is_public", "This review will be public.") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'public')->textarea(['rows' => 4])->label(false) ?>

                <h4>
                    <?= Yii::t("review.create.private_feedback_to_user", "Private feedback to {0}", [
                        $booking->item->owner->profile->first_name
                    ]) ?>
                    <br/>
                    <small>
                        <?= Yii::t("review.create.private_feedback_is_private", "This feedback will only be visible to the owner of the item.") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'private')->textarea()->label(false) ?>

                <h4>
                    <?= Yii::t("review.create.communication", "Communication") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review.create.communication.text", "How easy was it to communicate with the owner?") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'communication')->label(false)->widget(StarRating::className(), [
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
                <?= $form->field($model, 'exchange')->label(false)->widget(StarRating::className(), [
                    'pluginOptions' => [
                        'size' => 'lg',
                        'step' => 1,
                        'showClear' => false,
                        'showCaption' => false,
                    ]
                ]);
                ?>

                <h4>
                    <?= Yii::t("review.create.handling", "Handling") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review.create.handling_text",
                            "How did the renter treat the product, was it returned in the same state as it was rented out?") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'handling')->label(false)->widget(StarRating::className(), [
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

                <?= Html::submitButton(\Yii::t('review.create.submit_button', 'Submit Review'), [
                    'class' => 'btn btn-danger btn-lg btn-fill'
                ]) ?>
                <br/><br/>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</section>