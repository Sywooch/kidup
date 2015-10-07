<?php
use app\assets\FontAwesomeAsset;
use app\components\Cache;
use images\components\ImageHelper;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;
use \app\assets\AppAsset;

/* @var $this \app\extended\web\View */
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

        <link rel='shortcut icon' type='image/x-icon' href='<?= ImageHelper::url('kidup/logo/favicon.png') ?>'/>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--Facebook meta tags - Important for sharing links-->
        <?= \app\widgets\FacebookPreviewImage::widget() ?>

        <meta property="og:title" content="KidUp | Din online forældre-til-forældre markedsplads for børneudstyr."/>
        <meta property="og:site_name" content="KidUp | Din online forældre-til-forældre markedsplads for børneudstyr."/>
        <meta property="og:url" content="http://kidup.dk"/>

    </head>
    <body>
    <?php
    $this->beginBody();

    echo \app\widgets\FacebookTracker::widget();
    echo \app\widgets\GoogleTagManager::widget();

    echo $this->renderDynamic('return \Yii::$app->view->render("@app/views/layouts/menu");');
    ?>

    <div id="wrapper" <?= $transparent ? 'class="wrapper-home"' : '' ?>>
        <?= $content ?>
    </div>

    <!-- Load modals -->
    <?php
    echo Cache::html('layout_mobile-search-modal', function () {
        return item\widgets\MenuSearchModal::widget();
    });

    echo Cache::html('layout_footer', function () {
        return $this->render('footer.php');
    }, ['variations' => [$this->context->noFooter]]);

    echo \cinghie\cookieconsent\widgets\CookieWidget::widget([
        'message' => \Yii::t('app.cookie_consent.website_uses_cookies_for_experience',
            'This website uses cookies to ensure you get the best possible KidUp experience.'),
        'dismiss' => \Yii::t('app.cookie_consent.accept_cookies', 'Accept'),
        'learnMore' => \Yii::t('app.cookie_consent.get_more_info_button', 'More info'),
        'link' => \yii\helpers\Url::to('@web/p/cookies'),
        'theme' => false // we load the css ourselves
    ]);

    if (YII_ENV == 'prod') {
        echo Cache::html('layout_ga', function () {
            return \kartik\social\GoogleAnalytics::widget([]);
        });
    }

    $this->endBody();
    ?>
    </body>
    </html>

<?php $this->endPage() ?>