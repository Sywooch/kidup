<?php

use yii\helpers\Html;
use app\modules\images\components\ImageHelper;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
$this->title = Yii::$app->name . ' - ' . \Yii::t('title', 'Error');
?>
<section class="section container">

    <div class="row" style="margin-top:40px;">
        <div class="col-md-4">
            <?= ImageHelper::img('kidup/error/error.png', ['w' => 300]) ?>
        </div>
        <div class="col-md-8">
            <h1 style="color:black">
                <?= Yii::t("app", "Oh no! An error happened") ?>
            </h1>

            <span style="font-size: 16px">
                 <?= Yii::t("app", "We'd like to describe it like '{0}'",
                     ["<b>" . nl2br(Html::encode($message)) . "</b>"]) ?>
                <br><br>
                <?= Yii::t("app",
                    "We're sorry you're experiencing this issue. We're aware of the error and try to get it fixed for you!") ?>
                <?= Yii::t("app", "In the meantime, perhaps you can:") ?>
                <br><br>
                <ul>
                    <li><?= Html::a(\Yii::t("app", 'Search for interesting products'), '@web/search?q=') ?></li>
                    <li><?= Html::a(\Yii::t("app", 'Upload one of your own'), '@web/item/create') ?></li>
                    <li><?= Html::a(\Yii::t("app", 'Visit the homepage'), '@web/home') ?></li>
                </ul>
            </span>

        </div>
    </div>

</section>
