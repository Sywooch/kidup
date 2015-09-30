<?php
use \images\components\ImageHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \app\extended\web\View $this
 */
\item\assets\ListAsset::register($this);

$item = \item\models\Item::findOne($model->item_id);
$this->assetPackage = \app\assets\Package::ITEM_VIEW;
?>

<tr>
    <td class="text-center ">
        <div class="product-image"
             style="<?= ImageHelper::bgImg($item->getImageName(0),
                 ['q' => 90, 'w' => 700]) ?>; background-size: cover; background-position: 50% 50%;">
        </div>
    </td>
    <td><?= $item->name ?></td>
    <td><?= Yii::t("item.view.request.requested_string", "Requested by {0} between {1} and {2}", [
            Html::a($model->renter->profile->first_name, '@web/user/' . $model->renter_id),
            \Carbon\Carbon::createFromTimestamp($model->time_from,
                \Yii::$app->params['serverTimeZone'])->toFormattedDateString(),
            \Carbon\Carbon::createFromTimestamp($model->time_to,
                \Yii::$app->params['serverTimeZone'])->toFormattedDateString(),
        ]) ?></td>
    <td class="td-actions text-right">
        <a href="<?= Url::to('@web/booking/' . $model->id . '/request') ?>">
            <button class="btn btn-primary btn-sm">
                <?= Yii::t("item.view.request.respond_button", "Respond") ?>
            </button>
        </a>
        <a href="<?= Url::to('@web/messages/' . $model->conversation->id) ?>">
            <button class="btn btn-primary btn-sm">
                <?= Yii::t("item.view.request.chat_button", "Chat") ?>
            </button>
        </a>
    </td>
</tr>
