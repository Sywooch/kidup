<?php
use app\components\WidgetRequest;
use app\modules\images\components\ImageHelper;
use app\modules\message\models\Message;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 */
// create the navbar
$url = @Yii::$app->request->getUrl();
$transparent = ($url == '/' || $url == '/home');
$class = 'navbar navbar-default ';
$class .= $transparent ? 'navbar-product' : 'navbar-navbar-product';

$logoUrl = Url::to('@web/img/logo/horizontal.png');

?>
    <nav
        class="navbar navbar-default <?= $transparent ? 'navbar-transparant' : 'navbar-fixed-top' ?>">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <!-- Search engine -->
                <button id="search-btn-mobile" class="btn btn-danger pull-right visible-xs" data-toggle="modal"
                        data-target="#searchModal"><i class="fa fa-search"></i></button>
                <button type="button" class="navbar-toggle pull-left" data-toggle="collapse"
                        data-target="#navigation-default2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= Url::to(['/home']) ?>">
                    <?= $transparent ? ImageHelper::img('kidup/logo/horizontal-white.png',
                        ['h' => 46], ['style' => 'padding-top:5px;'])
                        : ImageHelper::img('kidup/logo/horizontal.png', ['h' => 46]) ?>
                </a>
            </div>

            <div class="collapse navbar-collapse">
                <!--menu for larger then mobile-->
                <ul class="nav navbar-nav navbar-left ">
                    <?= \app\modules\item\widgets\MenuSearch::widget() ?>
                </ul>
                <ul class="nav navbar-nav navbar-right ">
                    <?php if (\Yii::$app->user->isGuest): ?>
                        <!--Not logged in-->
                        <li class="hidden-xs">
                            <button class="btn btn-simple" data-toggle="modal" data-target="#loginModal" id="login">
                                <?= Yii::t("app", "Login") ?>
                            </button>
                        </li>
                        <li class="hidden-xs">
                            <button class="btn btn-simple" data-toggle="modal" data-target="#registerModal">
                                <?= Yii::t("app", "Register") ?>
                            </button>
                        </li>
                    <?php endif;
                    if (!\Yii::$app->user->isGuest): ?>
                        <!--Logged in-->
                        <li class="message hidden-xs">
                            <a href="<?= Url::to('@web/inbox') ?>"><i class="fa fa-envelope-o"></i></a>

                            <?php
                            $count = Message::find()->where([
                                'receiver_user_id' => \Yii::$app->user->id,
                                'read_by_receiver' => 0
                            ])->count();
                            if ($count > 0) {
                                ?>
                                <div class="badge">
                                    <?php
                                    // this is ugly
                                    echo($count > 0 ? $count : '');
                                    ?></div>
                                <?php
                            }
                            ?>
                        </li>
                        <li class="dropdown profile hidden-xs">
                            <a href="#" class="dropdown-toggle profile-image" data-toggle="dropdown"
                               aria-expanded="true">
                                <?= WidgetRequest::request(WidgetRequest::USER_PROFILE_IMAGE, [
                                    'user_id' => \Yii::$app->user->id,
                                    'width' => '40px'
                                ])
                                ?>
                                <?= \Yii::$app->user->identity->profile->first_name ?>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?= Url::to('@web/user/' . \Yii::$app->user->id) ?>">
                                        <i class="fa fa-user menu-icon"></i>
                                        <?= Yii::t("app", "View Profile") ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?= Url::to('@web/booking/current') ?>">
                                        <i class="fa fa-list-ul menu-icon"></i>
                                        <?= Yii::t("app", "Your Bookings") ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to('@web/item/list') ?>">
                                        <?= ImageHelper::img('kidup/logo/balloon.png', ['w' => 30, 'h' => 30],
                                            ['class' => "menu-icon-kidup"]) ?>
                                        <?= Yii::t("app", "Your Products") ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?= Url::to('@web/user/settings/profile') ?>">
                                        <i class="fa fa-gears menu-icon"></i>
                                        <?= Yii::t("app", "Settings") ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <?php if (\Yii::$app->user->identity->isAdmin()): ?>
                                    <li>
                                        <a href="<?= Url::to('@web/admin') ?>">
                                            <i class="fa fa-gears menu-icon"></i>
                                            <?= Yii::t("app", "Admin") ?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?= Url::to('@web/user/logout') ?>" class="text-danger">
                                        <i class="pe-7s-close-circle"></i>
                                        <?= Yii::t("app", "Log Out") ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <!--Always shown on desktop-md+ -->
                    <li>
                        <a href="<?= Url::to('@web/item/create') ?>"
                           class="btn btn-primary hidden-xs <?= $transparent ? 'btn-fill' : '' ?>">
                            <?= Yii::t("app", "Rent Out") ?>
                        </a>

                    </li>
                </ul>

                <!--Mobile menu!!-->
                <ul class="nav navbar-nav navbar-mobile visible-xs">
                    <?php if (\Yii::$app->user->isGuest): ?>
                        <!--Not logged in2-->
                        <li>
                            <a href="<?= Url::to('@web/user/login') ?>">
                                <button class="btn btn-simple">
                                    <?= Yii::t("app", "Login") ?>
                                </button>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?= Url::to('@web/user/register') ?>">
                                <button class="btn btn-simple">
                                    <?= Yii::t("app", "Register") ?>
                                </button>
                            </a>
                        </li>
                    <?php endif;
                    if (!\Yii::$app->user->isGuest): ?>
                    <li>
                        <?= \app\components\Cache::html('user_widget', function () {
                            return \app\modules\user\widgets\UserImage::widget([
                                'user_id' => \Yii::$app->user->id,
                                'width' => '40px'
                            ]);
                        }, ['variations' => [\Yii::$app->user->id]])
                        ?>
                    </li>

                    <li>
                        <a href="<?= Url::to("@web/home") ?>">
                            <?= Yii::t("app", "Home") ?>
                        </a>
                    </li>

                    <li>
                        <a href="<?= Url::to('@web/user/' . \Yii::$app->user->id) ?>">
                            <?= Yii::t("app", "View Profile") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to('@web/inbox') ?>">
                            <?= Yii::t("app", "Inbox") ?>
                            <div class="badge"><?=
                                // todo make this more pretty
                                \app\models\base\Message::find()->where([
                                    'receiver_user_id' => \Yii::$app->user->id,
                                    'read_by_receiver' => 0
                                ])->count(); ?></div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to('@web/booking/current') ?>">
                            <?= Yii::t("app", "Your Bookings") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to('@web/item/list') ?>">
                            <?= Yii::t("app", "Your Products") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to('@web/user/settings/profile') ?>">
                            <?= Yii::t("app", "Settings") ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to('@web/user/logout') ?>" class="text-danger">
                            <?= Yii::t("app", "Log Out") ?>
                        </a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
<?php if ($transparent): ?>
    <!--    this is ugly as well -->

<?php endif; ?>
<?php
// add the login / register model if user is guest
if (\Yii::$app->user->isGuest) {
    if ($this->beginCache('layout.menu.widgets')) {
        echo \app\components\Cache::html('widget_user_login_modal', function () {
            return \app\modules\user\widgets\Login::widget();
        });
        echo \app\components\Cache::html('widget_user_register_modal', function () {
            return \app\modules\user\widgets\Register::widget();
        });
        $this->endCache();
    }
}

// this is the notification plugin, showing all errors
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
    echo \kartik\growl\Growl::widget([
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