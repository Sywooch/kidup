<?php
use app\modules\item\models\Search;
use app\assets\FontAwesomeAsset;
use kartik\growl\Growl;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

\app\assets\AppAsset::register($this);
FontAwesomeAsset::register($this);
BootstrapPluginAsset::register($this);
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
        <link rel='shortcut icon' type='image/x-icon' href='<?= Url::to('@assets/img/logo/favicon.png') ?>'/>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--Facebook meta tags - Important for sharing links-->
        <meta property="og:image" content="<?= Url::to('@assets/img/facebook-kidupdk.jpg', true) ?>">
        <meta property="og:image:secure_url" content="<?= Url::to('@assets/img/facebook-kidupdk.jpg', true) ?>">
        <meta property="og:title" content="Kid Up | <?= Yii::t("title", "Online parent-to-parent marketplace") ?>"/>
        <meta property="og:site_name" content="Kid Up | <?= Yii::t("title", "Online parent-to-parent marketplace") ?>"/>
        <meta property="og:url" content="http://kidup.dk"/>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?= $this->render('menu'); ?>

    <div id="wrapper" style="">
        <?php
        // this is the notification plugin, showing all errors
        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {

            echo Growl::widget([
                'type' => $key,
                'body' => $message,
                'showSeparator' => true,
                'delay' => 500,
                'pluginOptions' => [
                    'placement' => [
                        'from' => 'bottom',
                        'align' => 'right',
                    ],
                    'timer' => 10000,
                ]
            ]);
        } ?>
        <?= $content ?>
    </div>

    <!-- Load modals -->
    <?php
    //echo $this->render('../../modules/item/widgets/views/menu_search_modal.php', [
    //    'model' => new Search(\Yii::$app->request->get())
    //]);
    ?>

    <?php
    if (!isset($this->context->noFooter) || $this->context->noFooter !== true) {
        // whether to render a footer or not
        echo $this->render('footer');
    } ?>
    <?= \cinghie\cookieconsent\widgets\CookieWidget::widget([
        'message' => \Yii::t('app', 'This website uses cookies to ensure you get the best possible KidUp experience.'),
        'dismiss' => \Yii::t('app', 'Accept'),
        'learnMore' => null,
        'link' => 'http://silktide.com/privacy-policy',
        'theme' => 'dark-bottom'
    ]); ?>
    <?php echo \kartik\social\GoogleAnalytics::widget([]); ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>