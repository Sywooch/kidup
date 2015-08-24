<?php

use \kartik\sidenav\SideNav;
use kartik\icons\Icon;
use \yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \app\modules\item\models\Item $item
 */
\app\modules\item\assets\CreateAsset::register($this);

?>

<section class="section" id="new-rental">
    <div class="card header">
        <div class="content ">
            <h2 class="title">
                <?= $item->name ?>
            </h2>

            <div class="pull-right btn btn-link btn-sm" style="margin-top:-35px">
                Preview
            </div>
        </div>
    </div>
    <div class="row" style="margin-right:0">
        <div class="col-md-4 card col-md-offset-4" style="text-align: center">
            <h2 style="text-align: center">
                <?= \Yii::t('item', 'Publishing') ?>
            </h2>
            <h4>
                <?= Yii::t("item", "Congratulations! Your product is ready.") ?>
            </h4>
            <?= Yii::t("item",
                "You can pusblish it now to be found and booked by other users. You can always edit the product afterwards.") ?>
            <br><br>
            <?= Html::a(Html::button(\Yii::t('item', 'Publish'),
                ['class' => 'btn btn-danger btn-fill']
            ), '@web/item/create/publish?id=' . $item->id . '&publish=true') ?>
            <br>
            <br>
            <small>
                <?= Yii::t("item", "By publishing the item you agree to our terms and conditions.") ?>
            </small>
            <br>
            <br>
        </div>

    </div>
</section>
