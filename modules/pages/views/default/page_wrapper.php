<?php
/**
 * @var \app\extended\web\View $this
 */
\app\modules\pages\assets\WpAsset::register($this);
$this->assetPackage = \app\assets\Package::PAGES;
?>
<div style="background-color: rgba(235,235,235,0.8);margin-bottom: -90px;padding-bottom:80px;">
    <section class="container">
        <div class="row" style="margin-top: 100px">
            <div class="col-md-8 col-md-offset-2 card">
                <br/>
                <?= $content ?>
                <br/>
            </div>
        </div>
    </section>
</div>
