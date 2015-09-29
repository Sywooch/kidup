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

<h4><?= Yii::t("item.create.photo.header", "Photos") ?>
    <br>
    <small>
        <?= Yii::t("item.create.photo.help_text_1", "Photos can help explaining the product functionality.") ?>
        <?= Yii::t("item.create.photo.help_text_2",  "Drag and drop images or click the box to start uploading.")?>
        <br>
        <b><?= Yii::t("item.create.photo.bold_first_photo_in_search", "The first photo appears in the search results!") ?></b>
    </small>
    <br>
</h4>
<div id="dropzone-form" class="dropzone upload-image-area"></div>
