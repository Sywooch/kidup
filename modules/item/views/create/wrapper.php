<?php

use app\modules\images\components\ImageHelper;
use \kartik\sidenav\SideNav;
use kartik\icons\Icon;
use \yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \app\modules\item\forms\Edit $model
 * @var \app\modules\item\models\Item $item
 * @var array $pageParams
 */
\app\modules\item\assets\CreateAsset::register($this);

?>

<section class="section" id="new-rental">
    <div class="card header">
        <div class="content ">
            <h2 class="title">
                <?= $item->name ? $item->name : \Yii::t('item', 'New product') ?>
                <a href="<?= \yii\helpers\Url::to('@web/item/' . $item->id) ?>" class="pull-right" style="color:white;"
                   target="_blank">
                    <?= Yii::t("item", "Preview") ?>
                </a>
            </h2>
        </div>
    </div>
    <div class="row" style="margin-right:0">
        <div class="col-md-3">
            <?= SideNav::widget([
                'type' => SideNav::TYPE_PRIMARY,
                'heading' => false,
                'encodeLabels' => false,
                'items' => [
                    [
                        'url' => ['/item/create/edit-basics?id=' . $item->id],
                        'label' => ($model->isScenarioValid('basics') ? Icon::show('check') : '') . 'Basics',
                    ],
                    [
                        'url' => ['/item/create/edit-description?id=' . $item->id],
                        'label' => ($model->isScenarioValid('description') ? Icon::show('check') : '') . 'Description',
                    ],
                    [
                        'url' => ['/item/create/edit-location?id=' . $item->id],
                        'label' => ($model->isScenarioValid('location') ? Icon::show('check') : '') . 'Location',
                    ],
                    [
                        'url' => ['/item/create/edit-photos?id=' . $item->id],
                        'label' => ($model->isScenarioValid('photos') ? Icon::show('check') : '') . 'Photos',
                    ],
                    [
                        'url' => ['/item/create/edit-pricing?id=' . $item->id],
                        'label' => ($model->isScenarioValid('pricing') ? Icon::show('check') : '') . 'Pricing',
                    ],
                ],
            ]); ?>
            <div style="text-align: center">
                <?php if ($item->is_available == 1) {
                    echo \Yii::t('item', 'Product is published. Click {a}here{aout} to unpublish.', [
                        'a' => "<a href='".\yii\helpers\Url::to('@web/item/create/unpublish?id='.$item->id)."'>",
                        'aout' => '</a>'
                    ]);
                }else if($model->isScenarioValid('default')) {
                    echo Html::a(Html::button(\Yii::t('item', 'Publish'), [
                        'class' => 'btn btn-danger btn-fill'
                    ]), '@web/item/create/edit-publish?id=' . $item->id);
                } else {
                    echo \Yii::t('item', 'Complete {n, plural, =1{1 step} other{# steps}} to publish your product.', [
                        'n' => $model->getStepsToCompleteCount()
                    ]);
                } ?>
            </div>
        </div>
        <div class="col-sm-10 col-md-4">
            <?php $form = \kartik\form\ActiveForm::begin() ?>
            <div class="card">
                <div class="content">
                    <?= $this->render($page, array_merge($pageParams, ['form' => $form, 'model' => $model])) ?>
                    <hr>
                    <div class="row">
                        <?= \yii\helpers\Html::submitButton(\Yii::t('item', 'Back'), [
                            'class' => "btn btn-link",
                            'name' => 'btn-back'
                        ]); ?>
                        <?= \yii\helpers\Html::submitButton(\Yii::t('item', 'Next'), [
                            'class' => "btn btn-danger pull-right btn-fill",
                            'name' => 'btn-next'
                        ]); ?>
                    </div>

                </div>
            </div>
            <?php \kartik\form\ActiveForm::end(); ?>
        </div>
        <?php if (isset($rightColumn)): ?>
            <div class="col-md-3">
                <?= $this->render($rightColumn,
                    array_merge(['form' => $form, 'model' => $model], $rightColumnParams)) ?>
            </div>
        <?php endif; ?>
    </div>
</section>
