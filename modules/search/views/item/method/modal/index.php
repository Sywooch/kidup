<?php
use yii\helpers\Html;

echo $this->render('modal');
?>

<section class="section hidden-lg hidden-md show-sm show-xs" id="search-sidebar">
    <!-- Search results -->
    <div class="container-fluid">

        <!-- Filter button -->
        <div class="row bottomMargin">
            <div class="col-sm-12 col-xs-12">
                <?= Html::button(
                    '<i class="fa fa-filter"></i> ' . \Yii::t('app', "Filter"),
                    ['class' => 'btn btn-danger btn-fill']) ?>
            </div>
        </div>

        <!-- Search results -->
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <?= $this->render('../../results'); ?>
            </div>
        </div>

    </div>
</section>