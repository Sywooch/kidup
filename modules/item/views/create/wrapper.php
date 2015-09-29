<?php

use app\modules\images\components\ImageHelper;
use \kartik\sidenav\SideNav;
use kartik\icons\Icon;
use \yii\helpers\Html;

/**
 * @var \app\extended\web\View $this
 * @var \app\modules\item\forms\Edit $model
 * @var \app\modules\item\models\Item $item
 * @var array $pageParams
 * @var array $rightColumnParams
 */
\app\modules\item\assets\CreateAsset::register($this);
$this->assetPackage = \app\assets\Package::ITEM_CREATE;
?>

<section class="section" id="new-rental">
    <div class="card header">
        <div class="content ">
            <h2 class="title">
                <?= $item->name ? $item->name : \Yii::t('item', 'New product: ' . $item->category->name) ?>
                <a href="<?= \yii\helpers\Url::to('@web/item/' . $item->id) ?>" class="pull-right" style="color:white;"
                   target="_blank">
                    <?= Yii::t("item", "Preview") ?>
                </a>
            </h2>
        </div>
    </div>
    <div class="row" style="margin-right:0">
        <div class="col-md-3">
            <?php
            $items = [
                [
                    'url' => '@web/item/create/edit-basics?id=' . $item->id,
                    'label' => ($model->isScenarioValid('basics') ? Icon::show('check') : '') . \Yii::t('item',
                            'General'),
                    'active' => $page == 'basics/basics'
                ],
                [
                    'url' => '@web/item/create/edit-description?id=' . $item->id,
                    'label' => ($model->isScenarioValid('description') ? Icon::show('check') : '') . \Yii::t('item',
                            'Description'),
                    'active' => $page == 'description/description'
                ],
                [
                    'url' => '@web/item/create/edit-location?id=' . $item->id,
                    'label' => ($model->isScenarioValid('location') ? Icon::show('check') : '') . \Yii::t('item',
                            'Location'),
                    'active' => $page == 'location/location'
                ],
                [
                    'url' => '@web/item/create/edit-photos?id=' . $item->id,
                    'label' => ($model->isScenarioValid('photos') ? Icon::show('check') : '') . \Yii::t('item',
                            'Photos'),
                    'active' => $page == 'photos/photos'
                ],
                [
                    'url' => '@web/item/create/edit-pricing?id=' . $item->id,
                    'label' => ($model->isScenarioValid('pricing') ? Icon::show('check') : '') . \Yii::t('item',
                            'Pricing'),
                    'active' => $page == 'pricing/pricing'
                ],
            ];

            if ($item->is_available == 1) {
                $items[] = [
                    'url' => '@web/item/create/edit-publish?id=' . $item->id,
                    'label' => \Yii::t('item', 'Unpublish item'),
                    'active' => $page == 'unpublish'
                ];
            } elseif ($model->isScenarioValid('default')) {
                $items[] = [
                    'url' => '@web/item/create/edit-publish?id=' . $item->id,
                    'label' => \Yii::t('item', 'Publish item'),
                    'active' => $page == 'publish'
                ];
            }

            echo SideNav::widget([
                'type' => SideNav::TYPE_PRIMARY,
                'heading' => false,
                'encodeLabels' => false,
                'items' => $items,
            ]); ?>
            <div style="text-align: center">
                <?php if ($item->is_available !== 1 && !$model->isScenarioValid('default')) {
                    echo \Yii::t('item', 'Complete {n, plural, =1{1 step} other{# steps}} to publish your product.',
                        [
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
                    <?php if ($page !== 'publish'): ?>
                        <hr>
                        <div class="row">
                            <?php if ($page !== 'basics/basics'): ?>
                                <?= \yii\helpers\Html::submitButton(\Yii::t('item', 'Back'), [
                                    'class' => "btn btn-link",
                                    'name' => 'btn-back'
                                ]); ?>
                            <?php endif; ?>
                            <?= \yii\helpers\Html::submitButton(\Yii::t('item', 'Next'), [
                                'class' => "btn btn-danger pull-right btn-fill",
                                'name' => 'btn-next'
                            ]); ?>
                        </div>
                    <?php endif; ?>

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
