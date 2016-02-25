<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \app\extended\web\View */
/* @var $content string */
\admin\assets\AdminThemeAsset::register($this);
\yii\web\JqueryAsset::register($this);
$this->assetPackage = \app\assets\Package::ADMIN;
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
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="skin-blue-light sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?= \yii\helpers\Url::to('@web/home') ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Kid</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>KidUp</b>admin</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel">
                <div class="pull-left image">
                    <?= \user\widgets\UserImage::widget([
                        'user_id' => \Yii::$app->user->id,
                        'width' => '40px'
                    ]) ?>
                </div>
                <div class="pull-left info">
                    <p><?= \Yii::$app->user->identity->profile->first_name ?></p>
                    <i>
                        <?php
                        $texts = [
                            "You're awesome!",
                            "Going strong!",
                            "You make KidUp rock",
                            "Awesome work",
                            "Nice having you"
                        ];
                        echo $texts[rand(0, 4)];
                        ?>
                    </i>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header">Admin Actions</li>
                <!-- Optionally, you can add icons to the links -->
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin') ?>">
                        <i class="fa fa-link"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin/bank-stuff') ?>">
                        <i class="fa fa-money"></i> <span>Export Payouts</span>
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin/item/index') ?>">
                        <span>Items</span>
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin/user/index') ?>">
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin/booking/index') ?>">
                        <span>Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin/translation/index') ?>">
                        <span>Translations</span>
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin/mail/index') ?>">
                        <span>E-mail templates</span>
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin/category/index') ?>">
                        <span>Categories</span>
                    </a>
                </li>
                <li>
                    <a href="<?= \yii\helpers\Url::to('@web/admin/feature/index') ?>">
                        <span>Features</span>
                    </a>
                </li>
            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 497px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= $this->title ?>
            </h1>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <?= $content ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Nothing to see here yet</h3>
                But seriously thank you for pressing that awesome button.
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg" style="position: fixed; height: auto;"></div>
</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
