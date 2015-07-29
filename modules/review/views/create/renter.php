<?php
use kartik\rating\StarRating;
use yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use app\components\WidgetRequest;
use Carbon\Carbon;
/**
 * @var $this \yii\web\View
 * @var $booking \app\modules\booking\models\Booking
 * @var $item \app\modules\item\models\Item
 */
\app\assets\AppAsset::register($this);
?>
<section class="section" id="create-review">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-10 col-md-offset-1">
                    <h2>
                        <?= Yii::t("review", "Rate & Review") ?>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-md-3 col-md-offset-1">
                <img src="<?= $item->getImageUrls()[0]['medium'] ?>"  width="100%"/>
                <div style="margin-top:-25px">
                    <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                        'user_id' => $item->owner_id,
                        'width' => '50px',
                    ]) ?>
                </div>
                <b><?= $item->name ?></b>
                <br/>
                <?= Carbon::createFromTimestamp($booking->time_from)->toFormattedDateString() ?> -
                <?= Carbon::createFromTimestamp($booking->time_to)->toFormattedDateString() ?>
                <br/>
                <?= Yii::t("review", "By {0}", [
                    Html::a($item->owner->profile->first_name, '@web/user/'.$item->owner_id)
                ]) ?>
            </div>
            <div class="col-md-6 col-md-offset-1 card">
                <?php
                $form = ActiveForm::begin([
                    'enableClientValidation' => true,
                    'method' => 'post',
                ]); ?>
                <h4>
                    <?= Yii::t("review", "Describe your experience") ?> <br/>
                    <small>
                        <?= Yii::t("review", "This review will be public.") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'public')->textarea(['rows' => 4])->label(false) ?>

                <h4>
                    <?= Yii::t("review", "Private feedback to {0}", [
                        $booking->item->owner->profile->first_name
                    ]) ?>
                    <br/>
                    <small>
                        <?= Yii::t("review", "This feedback will only be visible to the owner of the item.") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'private')->textarea()->label(false) ?>

                <h4>
                    <?= Yii::t("review", "Overall Experience") ?>
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
                    <?= Yii::t("review", "Communication") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review", "How easy was it to communicate with the owner?") ?>
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
                    <?= Yii::t("review", "Exchange") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review", "How did the exchange of product go?") ?>
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
                    <?= Yii::t("review", "Accuracy") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review", "How well did the photo's and description describe the actual item?") ?>
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
                    <?= Yii::t("review", "For KidUp") ?>
                    <br/>
                    <small>
                        <?= Yii::t("review", "Is there anything you'd like to tell to KidUp regarding this experience?") ?>
                    </small>
                </h4>
                <?= $form->field($model, 'kidupPrivate')->textarea(['rows' => 4])->label(false) ?>

                <?= Html::submitButton(\Yii::t('review', 'Submit Review'), [
                    'class' => 'btn btn-danger btn-lg btn-fill'
                ])?>
                <br/><br/>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</section>