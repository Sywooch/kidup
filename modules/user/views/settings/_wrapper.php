<?php
/**
 * @var \app\extended\web\View $this
 */
\app\modules\user\assets\SettingsAsset::register($this);
$this->title = $title;
$this->assetPackage = \app\assets\Package::USER_SETTINGS;
?>
<section class="section" id="settings">
    <div class=" site-area-header hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-sm-7 col-md-offset-1">
                    <h2>
                        <?= Yii::t("user.settings.header", "Settings") ?><br>
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