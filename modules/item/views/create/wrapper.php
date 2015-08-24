<?php

use app\modules\images\components\ImageHelper;
use \kartik\sidenav\SideNav;
use kartik\icons\Icon;
use \yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \app\modules\item\forms\Edit $model
 * @var array $pageParams
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
        <div class="col-md-3">
            <?= SideNav::widget([
                'type' => SideNav::TYPE_PRIMARY,
                'heading' => false,
                'encodeLabels' => false,
                'items' => [
                    [
                        'url' => '@web/item/create/edit-basics?id=' . $item->id,
                        'label' => ($model->isScenarioValid('basics') ? Icon::show('check') : '') . 'Basics',
                        'active' => $page == 'basics/basics'
                    ],
                    [
                        'url' => '@web/item/create/edit-description?id=' . $item->id,
                        'label' => ($model->isScenarioValid('description') ? Icon::show('check') : '') . 'Description',
                        'active' => $page == 'description/description'
                    ],
                    [
                        'url' => '@web/item/create/edit-location?id=' . $item->id,
                        'label' => ($model->isScenarioValid('location') ? Icon::show('check') : '') . 'Location',
                        'active' => $page == 'location/location'
                    ],
                    [
                        'url' => '@web/item/create/edit-photos?id=' . $item->id,
                        'label' => ($model->isScenarioValid('photos') ? Icon::show('check') : '') . 'Photos',
                        'active' => $page == 'photos/photos'
                    ],
                    [
                        'url' => '@web/item/create/edit-pricing?id=' . $item->id,
                        'label' => ($model->isScenarioValid('pricing') ? Icon::show('check') : '') . 'Pricing',
                        'active' => $page == 'pricing/pricing'
                    ],
                ],
            ]); ?>
            <div style="text-align: center">
                <?php if ($model->isScenarioValid('default')) {
                    echo Html::a(Html::button(\Yii::t('item', 'Publish'), [
                        'class' => 'btn btn-danger btn-fill'
                    ]), '@web/item/create/publish?id=' . $item->id);
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
        <?php if(isset($rightColumn)): ?>
        <div class="col-md-3">
            <?= $this->render($rightColumn, array_merge($rightColumnParams, ['form' => $form, 'model' => $model])) ?>
        </div>
        <?php endif; ?>
    </div>
</section>
