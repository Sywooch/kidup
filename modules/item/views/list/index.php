<?php
use yii\helpers\Url;

/**
 * @var \yii\data\ActiveDataProvider $unpublishedProvider
 * @var \yii\data\ActiveDataProvider $publishedProvider
 * @var \yii\data\ActiveDataProvider $requestProvider
 * @var \app\extended\web\View $this
 */

\item\assets\ListAsset::register($this);

$this->title = ucfirst(\Yii::t('item.list.title', 'Your Items')) . ' - ' . Yii::$app->name;
$this->assetPackage = \app\assets\Package::ITEM_VIEW;
?>
<section class="section" id="rentals">
    <div class=" site-area-header">
        <div class="container"></div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-sm-9 col-md-10 col-md-offset-1 card">
                <!-- Tab panes -->
                <div class="row">
                    <div class="col-sm-8">
                        <h3>
                            <?= Yii::t("item.list.header", "Products") ?><br>
                            <small><?= Yii::t("item.list.sub_header",
                                    "Manage the products you'd like to share with other families") ?></small>
                        </h3>
                    </div>
                    <div class="col-sm-4">
                        <a href="<?= Url::to('@web/item/create') ?>">
                            <button id="create-new-add" class="btn btn-danger btn-fill pull-right hidden-xs"
                                    style="margin-top:20px">
                                <?= Yii::t("item.list.button_create_new", "Create New") ?>
                            </button>
                        </a>
                    </div>
                </div>

                <!-- display a warning for mobile visitors -->
                <div class="row visible-xs">
                    <div class="col-xs-12">
                        <div class="alert alert-info" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <?= Yii::t("item.create.mobile_warning", "It is not possible to create a product on mobile devices right now, but it is possible on desktop
                            computers and notebooks. Currently we are working on a mobile app where it will be possible
                            to create a product on mobile devices.") ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php $this->beginBlock('pending'); ?>
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <?= \yii\widgets\ListView::widget([
                                    'dataProvider' => $requestProvider,
                                    'itemView' => 'requestView',
                                ]) ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php $this->endBlock(); ?>

                    <?php $this->beginBlock('published'); ?>
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <?= \yii\widgets\ListView::widget([
                                    'dataProvider' => $publishedProvider,
                                    'itemView' => 'itemView',
                                ]) ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php $this->endBlock(); ?>
                    <?php $this->beginBlock('unpublished'); ?>
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                <?= \yii\widgets\ListView::widget([
                                    'dataProvider' => $unpublishedProvider,
                                    'itemView' => 'itemView',
                                ]) ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php $this->endBlock(); ?>
                    <?= \yii\bootstrap\Tabs::widget(
                        [
                            'id' => 'relation-tabs',
                            'encodeLabels' => false,
                            'items' => [
                                [
                                    'label' => Yii::t("item.list.pending_request", "Pending Requests") .
                                        ' <span class="badge badge-default">' . $requestProvider->count . '</span>',
                                    'content' => $this->blocks['pending'],
                                    'active' => true,
                                ],
                                [
                                    'content' => $this->blocks['published'],
                                    'label' => Yii::t("item.list.published", "Published") .
                                        ' <span class="badge badge-default">' . $publishedProvider->count . '</span>',
                                    'active' => false,
                                ],
                                [
                                    'content' => $this->blocks['unpublished'],
                                    'label' => Yii::t("item.list.unpublished", "Unpublished") .
                                        ' <span class="badge badge-default">' . $unpublishedProvider->count . '</span>',
                                    'active' => false,
                                ],
                            ]
                        ]
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
