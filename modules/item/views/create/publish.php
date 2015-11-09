<?php

use yii\helpers\Html;

/**
 * @var \app\extended\web\View $this
 * @var \item\forms\Create $model
 */
\item\assets\CreateAsset::register($this);

$this->assetPackage = \app\assets\Package::ITEM_CREATE;

?>

<h4>
    <?= Yii::t("item.create.publish.header", "Your product is ready to be published!") ?>
</h4>
<?= Yii::t("item.create.publish.explanation",
    "You can publlish your product now so it can be found and booked by other users. You can always edit the product afterwards.") ?>
<br><br>
<?= Html::a(Html::button(\Yii::t('item.create.publish.button', 'Publish'),
    ['class' => 'btn btn-danger btn-fill']
), '@web/item/create/edit-publish?id=' . $model->item->id . '&publish=true') ?>
<br>
<br>
<small>
    <?= Yii::t("item.create.publish.agree_to_terms", "By publishing the item you agree to our terms and conditions.") ?>
</small>

