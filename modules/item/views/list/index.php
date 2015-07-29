<?php
use yii\helpers\Url;

$this->title = ucfirst(\Yii::t('title', 'Your Items')) . ' - ' . Yii::$app->name;

\app\modules\item\assets\ListAsset::register($this);
?>

<section class="section" id="rentals">
    <div class=" site-area-header">
        <div class="container">
            <div class="row">
                <div class="col-sm-7 col-md-offset-1">
                    <h2><?= Yii::t("item", "Listings") ?><br>
                        <small><?= Yii::t("item", "Manage the item you'd like to share with other families") ?></small>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-sm-9 col-md-8 col-md-offset-2 card">
                <!-- Tab panes -->
                <div class="row">
                    <div class="col-sm-12">
                        <h4>
                            <?= Yii::t("item", "Listings") ?>
                            <br>
                            <small><?= Yii::t("item",
                                    "This is a list of your published and unpublished listings") ?></small>
                        </h4>
                        <a href="<?= Url::to('@web/item/create') ?>">
                            <button id="create-new-add" class="btn btn-danger btn-fill pull-right hidden-xs">
                                <?= Yii::t("item", "Create Listing") ?>
                            </button>
                        </a>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php if($requestProvider->count > 0): ?>
                        <div class="form-group">
                            <label><?= Yii::t("item", "Pending Requests") ?></label>

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
                        <?php endif; ?>
                        <div class="form-group">
                            <label><i class="fa fa-check"></i> <?= Yii::t("item", "Published") ?></label>

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

                        <div class="form-group">
                            <label><i class="fa fa-times"></i> <?= Yii::t("item", "Unpublished") ?></label>

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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
