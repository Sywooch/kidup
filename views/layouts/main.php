<?php
use app\assets\FontAwesomeAsset;
use app\components\Cache;
use app\modules\images\components\ImageHelper;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;
use \app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
BootstrapPluginAsset::register($this);

$url = @Yii::$app->request->getUrl();
$transparent = ($url == '/' || $url == '/home');

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
        <?php $this->renderDynamic('$this->head();'); ?>
        <link rel='shortcut icon' type='image/x-icon' href='<?= ImageHelper::url('kidup/logo/favicon.png') ?>'/>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--Facebook meta tags - Important for sharing links-->
        <meta property="og:image" content="<?= ImageHelper::url('kidup/facebook-kidupdk.jpg') ?>">
        <meta property="og:image:secure_url" content="<?= ImageHelper::url('kidup/facebook-kidupdk.jpg') ?>">
        <meta property="og:title" content="Kid Up | <?= Yii::t("title", "Online parent-to-parent marketplace") ?>"/>
        <meta property="og:site_name" content="Kid Up | <?= Yii::t("title", "Online parent-to-parent marketplace") ?>"/>
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
            'learnMore' => null,
            'link' => 'http://silktide.com/privacy-policy',
            'theme' => 'dark-bottom'
        ]);
    });

    if (YII_ENV == 'prod') {
        echo Cache::html('layout_ga', function () {
            return \kartik\social\GoogleAnalytics::widget([]);
        });
    }
    foreach (array_keys($this->assetBundles) as $bundle) {
        $this->registerAssetBundle($bundle);
    }
    $this->endBody();
    \yii\helpers\VarDumper::dump($this->jsFiles,10,true);
    ?>

    </body>
    </html>
<?php $this->endPage() ?>