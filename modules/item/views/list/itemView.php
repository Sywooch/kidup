<?php
use yii\helpers\Url;

\app\modules\item\assets\ListAsset::register($this);

?>

<tr>
    <td class="text-center ">
        <div class="product-image"
             style="background-image: url('<?= $model->getImageUrls()[0]['thumb'] ?>'); background-size: cover; background-position: 50% 50%;" >
        </div>
    </td>
    <td><?= $model->name ?></td>
    <td class="td-actions text-right">
        <a href="<?= Url::to('@web/item/' . $model->id) ?>">
            <button class="btn btn-primary btn-sm">
                <?= Yii::t("item", "View") ?>
            </button>
        </a>
        <a href="<?= Url::to('@web/item/' . $model->id . '/edit') ?>">
            <button class="btn btn-primary btn-sm">
                <?= Yii::t("item", "Edit") ?>
            </button>
        </a>
        <a href="<?= Url::to('@web/booking/by-item/' . $model->id ) ?>">
            <button class="btn btn-primary btn-sm">
                <?= Yii::t("item", "Bookings") ?>
            </button>
        </a>
        <?php if ($model->is_available == 1): ?>
            <a href="<?= Url::to('@web/item/' . $model->id . '/unpublish') ?>">
                <button class="btn btn-danger btn-sm">
                    <?= Yii::t("item", "Unpublish") ?>
                </button>
            </a>
        <?php endif; ?>

        <?php if ($model->is_available == 0): ?>
            <a href="<?= Url::to('@web/item/' . $model->id . '/edit#publishing') ?>">
                <button class="btn btn-danger btn-sm">
                    <?= Yii::t("item", "Publish") ?>
                </button>
            </a>
        <?php endif; ?>
    </td>
</tr>
