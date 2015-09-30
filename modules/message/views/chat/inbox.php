<?php
use yii\widgets\ListView;

/**
 * @var yii\data\ActiveDataProvider $conversationDataProvider
 * @var \app\extended\web\View $this
 */

\message\assets\MessageAsset::register($this);

$this->title = ucfirst(\Yii::t('message.inbox.title', 'Inbox')) . ' - ' . Yii::$app->name;
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
                                <h4 class="title">
                                    <?= Yii::t("message.inbox.header", "Inbox") ?>
                                </h4>
                            </div>

                            <div class="col-md-2 col-md-offset-6 booking hidden-xs">
                                <?= $conversationDataProvider->count > 0 ? Yii::t("message.inbox.booking_status", "Booking Status") : '' ?>
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
                                <?= Yii::t("message.inbox.inbox_is_empty", "Your inbox is empty at the moment.") ?>
                            </h4>
                            <?php
                            $categories = \item\models\Category::find()->limit(2)->all();
                            $name1 = $categories[0]->getTranslatedName();
                            $name2 = $categories[1]->getTranslatedName();
                            ?>
                            <?= Yii::t("message.inbox.empty_inbox_alternative_actions",
                                "How about searching for a {0} or a {1} and getting some action here?", [
                                    \yii\helpers\Html::a($name1, '@web/search/'.$name1),
                                    \yii\helpers\Html::a($name2, '@web/search/'.$name2),
                                ]) ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>