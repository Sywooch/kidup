<?php
use yii\widgets\ListView;

/**
 * @var yii\data\ActiveDataProvider $conversationDataProvider
 * @var app\components\extended\View $this
 */

\app\modules\message\assets\MessageAsset::register($this);

$this->title = ucfirst(\Yii::t('title', 'Inbox')) . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::MESSAGE;
?>

<section class="section" id="inbox">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="card">
                    <div class="header">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="hidden-xs">
                                    <?= app\modules\images\widgets\Image::widget([
                                        'name' => 'kidup/graphics/notification.png',
                                        'imageOptions' =>  ['w' => 55]]) ?>
                                </div>
                                <h4 class="title">
                                    <?= Yii::t("message", "Inbox") ?>
                                </h4>
                            </div>

                            <div class="col-md-2 col-md-offset-6 booking hidden-xs">
                                <?= $conversationDataProvider->count > 0 ? Yii::t("message", "Booking Status") : '' ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    //                     displaying the inbox items
                    if ($conversationDataProvider->count > 0) {
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
                        ]);
                    } else { ?>
                        <div class="text-center empty-inbox">
                            <h4>
                                <?= Yii::t("message", "Your inbox is empty at the moment.") ?>
                            </h4>
                            <?= Yii::t("message",
                                "How about searching for a {0} or a {1} and getting some action here?", [
                                    \yii\helpers\Html::a(\Yii::t('message', 'stroller'), '@web/search/'.\Yii::t('categories_and_features', 'Stroller')),
                                    \yii\helpers\Html::a(\Yii::t('message', 'bike'), '@web/search/'.\Yii::t('categories_and_features', 'Bikes')),
                                ]) ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>