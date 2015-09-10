<?php
use app\assets\FontAwesomeAsset;
use app\components\Cache;
use app\modules\images\components\ImageHelper;
use app\modules\item\models\Search;
use app\modules\message\models\Message;
use kartik\growl\Growl;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

\app\assets\AppAsset::register($this);
FontAwesomeAsset::register($this);
BootstrapPluginAsset::register($this);
try{
    // todo this should be improved drastically
    $transparent = \yii\helpers\Url::current() == '/home/home/index';
}catch (yii\base\Exception $e){
    $transparent = true;
}

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
        <?= Html::csrfMetaTags() ?>
        <title>
            <?= Html::encode($this->title) ?>
        </title>
        <?php $this->head() ?>
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

    <?= $this->renderDynamic('return \Yii::$app->view->render("@app/views/layouts/menu");'); ?>

    <div id="wrapper <?= $transparent ? 'wrapper-home' : '' ?>" <?= $transparent ? 'style="padding-top:1px"' : '' ?>>
        <?= $content ?>
    </div>

    <!-- Load modals -->
    <?php
    echo Cache::html('layout_mobile-search-modal', function () {
        return $this->render('../../modules/item/widgets/views/menu_search_modal.php');
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

    \app\widgets\FacebookTracker::widget();

    if(YII_ENV == 'prod'){
        echo Cache::html('layout_ga', function () {
            return \kartik\social\GoogleAnalytics::widget([]);
        });
    }
    ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>