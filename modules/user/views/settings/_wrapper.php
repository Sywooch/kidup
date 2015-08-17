<?php
$this->title = $title;
\app\modules\user\assets\SettingsAsset::register($this);
?>
<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<section class="section" id="settings">
    <div class=" site-area-header hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-sm-7 col-md-offset-1">
                    <h2><?= Yii::t("user", "Settings") ?><br>
                        <small><?= Yii::t("user", "Manage your KidUp settings") ?></small>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container site-area-content">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1">
                <!-- Nav tabs -->
                <div class="tim-title">
                </div>
                <div class="row">
                    <div class="col-sm-3 col-md-3  col-md-offset-1 text-left">
                        <?= $this->render('_menu') ?>
                    </div>
                    <div class="col-sm-9 col-md-7 card">
                        <?= $page ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>