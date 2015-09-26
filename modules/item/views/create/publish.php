<?php

use \kartik\sidenav\SideNav;
use kartik\icons\Icon;
use \yii\helpers\Html;

/**
 * @var \app\components\extended\View $this
 * @var \app\modules\item\models\Item $item
 */
\app\modules\item\assets\CreateAsset::register($this);

$this->assetPackage = \app\assets\Package::ITEM_CREATE;

?>
<section class="section" id="new-rental">
    <div style="height: 10vh;"></div>

    <div class="row" style="margin-right:0">
        <div class="col-md-4 card col-md-offset-4" style="text-align: center">
            <h4>
                <?= Yii::t("item", "Your product is ready to be published!") ?>
            </h4>
            <?= Yii::t("item",
                "You can pusblish your product now so it can be found and booked by other users. You can always edit the product afterwards.") ?>
            <br><br>
            <?= Html::a(Html::button(\Yii::t('item', 'Publish'),
                ['class' => 'btn btn-danger btn-fill']
            ), '@web/item/create/edit-publish?id=' . $item->id . '&publish=true') ?>
            <br>
            <br>
            <small>
                <?= Yii::t("item", "By publishing the item you agree to our terms and conditions.") ?>
            </small>
            <br>
            <br>
        </div>
    </div>

    <div style="height: 10vh;"></div>

</section>
