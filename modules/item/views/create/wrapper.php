<?php

use kartik\icons\Icon;
use kartik\sidenav\SideNav;

/**
 * @var \app\components\view\View $this
 * @var \item\forms\Edit $model
 * @var \item\models\item\Item $item
 * @var array $pageParams
 * @var array $rightColumnParams
 */
\item\assets\CreateAsset::register($this);
$this->assetPackage = \app\assets\Package::ITEM_CREATE;
?>

<section class="section" id="new-rental">
    <div class="card header">
        <div class="content ">
            <h2 class="title">
                <?= $item->name ? $item->name : \Yii::t('item.create.wrapper.new_product',
                    'New product: ' . $item->category->name) ?>
                <a href="<?= \yii\helpers\Url::to('@web/item/' . $item->id) ?>" class="pull-right" style="color:white;"
                   target="_blank">
                    <?= Yii::t("item.create.wrapper.preview", "Preview") ?>
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
                    'label' => ($model->isScenarioValid('basics') ? Icon::show('check') : '') .
                        \Yii::t('item.create.menu.basics', 'General'),
                    'active' => $page == 'basics/basics'
                ],
                [
                    'url' => '@web/item/create/edit-description?id=' . $item->id,
                    'label' => ($model->isScenarioValid('description') ? Icon::show('check') : '') .
                        \Yii::t('item.create.menu.description', 'Description'),
                    'active' => $page == 'description/description'
                ],
                [
                    'url' => '@web/item/create/edit-location?id=' . $item->id,
                    'label' => ($model->isScenarioValid('location') ? Icon::show('check') : '') .
                        \Yii::t('item.create.menu.location', 'Location'),
                    'active' => $page == 'location/location'
                ],
                [
                    'url' => '@web/item/create/edit-photos?id=' . $item->id,
                    'label' => ($model->isScenarioValid('photos') ? Icon::show('check') : '') .
                        \Yii::t('item.create.menu.photos', 'Photos'),
                    'active' => $page == 'photos/photos'
                ],
                [
                    'url' => '@web/item/create/edit-pricing?id=' . $item->id,
                    'label' => ($model->isScenarioValid('pricing') ? Icon::show('check') : '') .
                        \Yii::t('item.create.menu.pricing', 'Pricing'),
                    'active' => $page == 'pricing/pricing'
                ],
            ];

            if ($item->is_available == 1) {
                $items[] = [
                    'url' => '@web/item/create/unpublish?id=' . $item->id,
                    'label' => \Yii::t('item.create.menu.unpublish_item', 'Unpublish item'),
                    'active' => $page == 'unpublish'
                ];
            } elseif ($model->isScenarioValid('default')) {
                $items[] = [
                    'url' => '@web/item/create/edit-publish?id=' . $item->id,
                    'label' => \Yii::t('item.create.menu.publish_item', 'Publish item'),
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
                    echo \Yii::t('item.create.menu.complete_x_steps', 'Complete {n, plural, =1{1 step} other{# steps}} to publish your product.',
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
                                <?= \yii\helpers\Html::submitButton(\Yii::t('item.create.back', 'Back'), [
                                    'class' => "btn btn-link",
                                    'name' => 'btn-back'
                                ]); ?>
                            <?php endif; ?>
                            <?= \yii\helpers\Html::submitButton(\Yii::t('item.create.next', 'Next'), [
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
