<?php
use yii\helpers\Url;
/**
 * @var \yii\web\View $this
 * @var \app\modules\item\forms\Edit $model
 * @var string $preload
 * @var string $fileUrl
 */
$this->registerJs("
    window.uploadUrl = '" . Url::to(['/item/create/upload', 'item_id' => $model->item->id]) . "';
    window.deleteUrl = '" . Url::to(['/item/create/delete-upload', 'item_id' => $model->item->id]) . "';
    window.sortUrl = '" . Url::to(['/item/create/image-sort', 'item_id' => $model->item->id]) . "';
    window.preloadImages = " . $preload . ";
    window.fileUrl = '" . $fileUrl . "/';
", \yii\web\View::POS_HEAD);

\app\assets\DropZoneAsset::register($this);

?>

<h4><?= Yii::t("item", "Pictures") ?>
    <br>
    <small>
        <?= Yii::t("item", "Add some quality pictures of your product.") ?>
        <?= Yii::t("item",
            "Drag and drop images or click the box to start uploading. They can be sorted by dragging uploaded pictures, the first picture will be used as 'billboard' image on the product page.") ?>
    </small>
    <br>
    <small>
        <?= Yii::t("item", "Add some quality pictures of your product.") ?>
        <?= Yii::t("item",
            "Drag and drop images or click the box to start uploading. They can be sorted by dragging uploaded pictures, the first picture will be used as 'billboard' image on the product page.") ?>
    </small>
</h4>
<div id="dropzone-form" class="dropzone upload-image-area"></div>
