<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\widgets\Alert;
use app\assets\FontAwesomeAsset;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
FontAwesomeAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <link rel='shortcut icon' type='image/x-icon' href='<?= \yii\helpers\Url::to('@assets/img/logo/favicon.png') ?>'/>

        <!--Facebook meta tags - Important for sharing links-->
        <meta property="og:image" content="<?= Url::to('@assets/img/facebook-kidupdk.jpg', true) ?>">
        <meta property="og:image:secure_url" content="<?= Url::to('@assets/img/facebook-kidupdk.jpg', true) ?>">
        <meta property="og:title" content="Kid Up | Online parent-to-parent marketplace"/>
        <meta property="og:site_name" content="Kid Up | Online parent-to-parent marketplace"/>
        <meta property="og:url" content="http://kidup.dk"/>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= Alert::widget() ?>
    <?= $content ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>