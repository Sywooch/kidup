<?php

use app\modules\images\components\ImageHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use \kartik\form\ActiveForm;
use \kartik\sidenav\SideNav;
use kartik\icons\Icon;
Icon::map($this, Icon::FA);
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

\app\modules\item\assets\CreateAsset::register($this);
\app\assets\DropZoneAsset::register($this);
?>

    <section class="section" id="new-rental">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card header">
                        <div class="content ">
                            <h2 class="title">
                                <?= Yii::t("item", "Edit your product") ?>
                                <b>(<?= Yii::t("item", "step 2 out of 2") ?>)</b>
                            </h2>
                            <?= ImageHelper::img('kidup/graphics/rentout.png', ['w' => 120, 'h' => 120],
                                ['class' => 'header-top-image']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 ">
                    <?= SideNav::widget([
                        'type' => SideNav::TYPE_PRIMARY,
                        'heading' => false,
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'url' => '#',
                                'label' => 'Basics',
                            ],
                            [
                                'url' => '#',
                                'label' => 'Description',
                            ],
                            [
                                'url' => '#',
                                'label' => 'Location',
                            ],
                            [
                                'url' => '#',
                                'label' => 'Photos',
                            ],
                            [
                                'label' => Icon::show('check'). 'Pricing',
                            ],
                        ],
                    ]);?>
                    You can publish in 2 more steps.

                </div>
                <div class="col-sm-10 col-md-8">

                    <div class="card">
                        <div class="content">
                            <?php $form = ActiveForm::begin([
                                'enableClientValidation' => false,
                                'enableAjaxValidation' => false,
                            ]); ?>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <h4><?= Yii::t("item", "Title") ?>
                                        <br>
                                        <small><?= Yii::t("item",
                                                "How can you describe your item, in one catchy title?") ?></small>
                                    </h4>
                                    <div class="form-group">
                                        <?= $form->field($model,
                                            'name')->input(['class' => 'form-control'])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <div id="select-categories" class="row">
                                <div class="col-sm-10 col-sm-offset-1 categories">

                                </div>
                            </div>
                            <div id="select-age" class="row">
                                <div class="col-sm-10 col-sm-offset-1 categories">

                                </div>
                            </div>
                            <div id="select-age" class="row">
                                <div class="col-sm-10 col-sm-offset-1 categories">

                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-sm-10 col-sm-offset-1">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">

                                </div>
                            </div>
                            <div class="row price">
                                <?= $this->render('pricing', [
                                    'form' => $form,
                                    'model' => $model
                                ]); ?>
                            </div>

                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">

                                </div>
                            </div>
                            <!-- leave id for scrolling -->
                            <div class="row" id="publishing">
                                <div class="col-md-11">
                                    <?= Html::button(\Yii::t('app', 'Save'), [
                                        'class' => "btn btn-danger btn-fill btn-lg pull-right",
                                        'id' => 'submit-save'
                                    ]) ?>
                                </div>
                            </div>
                            <a name="publishing"></a>

                            <hr/>
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1 terms">
                                    <h4>
                                        <?= Yii::t("item", "Preview & Publishing"); ?>
                                        <?php
                                        if ($model->isPublishable() !== true) {
                                            ?><br/>
                                            <small><?php
                                            echo \Yii::t('item',
                                                'There are some things left to be fixed before you can preview or publish this item.');
                                            echo \Yii::t('item', 'Refresh the page if everything is fixed.');
                                            ?></small><?php
                                        }
                                        ?>
                                    </h4>
                                    <?php
                                    if ($model->isPublishable() === true && $model->is_available == 0) {
                                        echo $form->field($model, 'rules')->checkbox([
                                            'data-toggle' => "checkbox"
                                        ]);
                                        echo Html::submitButton(\Yii::t('item', 'Publish'), [
                                            'class' => "btn btn-danger pull-right btn-fill",
                                            'name' => 'button',
                                            'value' => 'submit-publish',
                                            'id' => 'submit-publish'
                                        ]);
                                        echo Html::submitButton(\Yii::t('item', 'Preview'), [
                                            'class' => "btn btn-primary",
                                            'name' => 'button',
                                            'value' => 'submit-preview',
                                            'id' => 'submit-preview'
                                        ]);
                                    } elseif ($model->isPublishable() !== true) {
                                        foreach ($model->isPublishable() as $error) {
                                            echo $error . '<BR>';
                                        };
                                    }
                                    if ($model->item->is_available === 1) {
                                        echo \Yii::t('item',
                                            'The item is now publicly available. If you like to make it unavailable, please {0}',
                                            [
                                                Html::a(\Yii::t('item', 'click here.'),
                                                    '@web/item/' . $model->item->id . '/unpublish')
                                            ]);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
