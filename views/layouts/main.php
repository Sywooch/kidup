<?php
use app\assets\FontAwesomeAsset;
use app\components\Cache;
use app\modules\images\components\ImageHelper;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;
use \app\assets\AppAsset;

/* @var $this \app\components\extended\View */
/* @var $content string */

$url = @Yii::$app->request->getUrl();
$transparent = ($url == '/' || $url == '/home');
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <?= $this->renderDynamic('echo yii\helpers\Html::csrfMetaTags();'); ?>
        <title>
            <?= Html::encode($this->title) ?>
        </title>
        <?php $this->head(); ?>
        <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/packages/common/common.css') ?>">
        <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/packages/'.$this->assetPackage.'/'.$this->assetPackage.'.css') ?>">

        <link rel='shortcut icon' type='image/x-icon' href='<?= ImageHelper::url('kidup/logo/favicon.png') ?>'/>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--Facebook meta tags - Important for sharing links-->
        <meta property="og:image" content="<?= ImageHelper::url('kidup/facebook-kidupdk.jpg') ?>">
        <meta property="og:image:secure_url" content="<?= ImageHelper::url('kidup/facebook-kidupdk.jpg') ?>">
        <meta property="og:title" content="KidUp | Din online forældre-til-forældre markedsplads for børneudstyr."/>
        <meta property="og:site_name" content="KidUp | Din online forældre-til-forældre markedsplads for børneudstyr."/>
        <meta property="og:url" content="http://kidup.dk"/>
    </head>
    <body>
    <?php $this->beginBody(); ?>
    <?= \app\widgets\FacebookTracker::widget() ?>
    <?= \app\widgets\GoogleTagManager::widget() ?>

    <?= $this->renderDynamic('return \Yii::$app->view->render("@app/views/layouts/menu");'); ?>

    <div id="wrapper" <?= $transparent ? 'class="wrapper-home"' : '' ?>>
        <?= $content ?>
    </div>

    <!-- Load modals -->
    <?php
    echo Cache::html('layout_mobile-search-modal', function () {
        return \app\modules\item\widgets\MenuSearchModal::widget();
    });

    echo Cache::html('layout_footer', function () {
        return $this->render('footer.php');
    }, ['variations' => [$this->context->noFooter]]);

    echo Cache::html('layout_cookie-widget', function () {
        return \cinghie\cookieconsent\widgets\CookieWidget::widget([
            'message' => \Yii::t('app',
                'This website uses cookies to ensure you get the best possible KidUp experience.'),
            'dismiss' => \Yii::t('app', 'Accept'),
            'learnMore' => \Yii::t('app', 'More info'),
            'link' => 'http://kidup.dk/p/privacy',
            'theme' => 'dark-bottom'
        ]);
    });

    if (YII_ENV == 'prod') {
        echo Cache::html('layout_ga', function () {
            return \kartik\social\GoogleAnalytics::widget([]);
        });
    }
    ?>
    <script type="text/javascript" src="<?= \yii\helpers\Url::to('@web/packages/common/common.js') ?>" charset="utf-8"></script>
    <script type="text/javascript" src="<?= \yii\helpers\Url::to('@web/packages/'.$this->assetPackage.'/'.$this->assetPackage.'.js') ?>" charset="utf-8"></script>
    <?php
    $this->endBody();
    ?>

    </body>
    </html>

<?php $this->endPage() ?>