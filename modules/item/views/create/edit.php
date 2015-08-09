<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\images\components\ImageHelper;
/**
 * @var \yii\web\View $this
 * @var \app\modules\item\models\Item $model
 * @var string $preload
 * @var string $fileUrl
 */
$this->registerJs("
    window.uploadUrl = '" . Url::to(['/item/image-upload', 'item_id' => $model->item->id]) . "';
    window.deleteUrl = '" . Url::to(['/item/image-delete', 'item_id' => $model->item->id]) . "';
    window.sortUrl = '" . Url::to(['/item/image-sort', 'item_id' => $model->item->id]) . "';
    window.preloadImages = " . $preload . ";
    window.fileUrl = '" . $fileUrl . "/';
", \yii\web\View::POS_HEAD);

\app\modules\item\assets\CreateAsset::register($this);
\app\assets\DropZoneAsset::register($this);
?>

<section class="section" id="new-rental">
    <div class="container">
        <div class="row">
            <div class=" col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2">
                <div class="card header">
                    <div class="content ">
                        <h2 class="title">
                            <?= Yii::t("item", "Create a new item") ?>
                            <b>(<?= Yii::t("item", "step 2 out of 2") ?>)</b>
                        </h2>
                        <?= ImageHelper::img('kidup/graphics/rentout.png', ['w' => 120, 'h' => 120], ['class' => 'header-top-image']) ?>
                    </div>
                </div>
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
                                    <?= $form->field($model, 'name')->input(['class' => 'form-control'])->label(false) ?>
                                </div>
                            </div>
                        </div>
                        <div id="select-categories" class="row">
                            <div class="col-sm-10 col-sm-offset-1 categories">
                                <h4><?= Yii::t("item", "Categories") ?>
                                    <br>
                                    <small><?= Yii::t("item", "What categories describe your item best?") ?></small>
                                </h4>
                                <?php foreach ($categories['main'] as $category): ?>
                                    <div class="btn btn-default category-clickable-button"
                                         data-id="<?= $category->id ?>">
                                        <?= $category->name ?>
                                    </div>
                                    <?= Html::activeHiddenInput($model,
                                        'categories[main][' . $category->id . ']'); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="select-age" class="row">
                            <div class="col-sm-10 col-sm-offset-1 categories">
                                <h4><?= Yii::t("item", "Age") ?>
                                    <br>
                                    <small><?= Yii::t("item", "Which ages is this product ment for?") ?></small>
                                </h4>
                                <?php foreach ($categories['age'] as $category): ?>
                                    <div class="btn btn-default category-clickable-button"
                                         data-id="<?= $category->id ?>">
                                        <?= $category->name ?>
                                    </div>
                                    <?= Html::activeHiddenInput($model,
                                        'categories[age][' . $category->id . ']'); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="select-age" class="row">
                            <div class="col-sm-10 col-sm-offset-1 categories">
                                <h4><?= Yii::t("item", "Special") ?>
                                    <br>
                                    <small><?= Yii::t("item", "Anything special about this product?") ?></small>
                                </h4>
                                <?php foreach ($categories['special'] as $category): ?>
                                    <div class="btn btn-default category-clickable-button"
                                         data-id="<?= $category->id ?>">
                                        <?= $category->name ?>
                                    </div>
                                    <?= Html::activeHiddenInput($model,
                                        'categories[special][' . $category->id . ']'); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-sm-10 col-sm-offset-1">
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
                                        <?= Yii::t("item", "Drag and drop images or click the box to start uploading. They can be sorted by dragging uploaded pictures, the first picture will be used as 'billboard' image on the product page.") ?>
                                    </small>
                                </h4>
                                <div id="dropzone-form" class="dropzone upload-image-area"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <h4><?= Yii::t("item", "Description") ?>
                                    <br>
                                    <small>
                                        <?= Yii::t("item",
                                            "How much is the item used? What do you enjoy so much about it? What parent-child moments did you experience with this item?") ?>
                                    </small>
                                </h4>
                                <?= $form->field($model,
                                    'description')->textarea(['class' => 'form-control'])->label(false) ?>
                            </div>
                        </div>
                        <div class="row price">
                            <div class="col-sm-10 col-sm-offset-1">
                                <h4><?= Yii::t("item", "Price") ?>
                                    <br>
                                    <small>
                                        <?= Yii::t("item", "Only the weekly price is required.") ?>
                                    </small>
                                </h4>

                                <div class="row" style="margin:0">
                                    <div class="col-md-5" style="padding-left:0">
                                        <div class="row" style="margin:0">
                                            <div class="col-md-6" style="padding-left:0">
                                                <?= $form->field($model, 'price_day')->input('number', [
                                                    'class' => 'form-control',
                                                    'min' => 0
                                                ])->label(false)
                                                ?>
                                            </div>
                                            <div class="col-md-6" style="padding-top:10px">
                                                / <?= Yii::t("item", "day") ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" style="padding-left:0">
                                                <?= $form->field($model, 'price_week')->input('number', [
                                                    'class' => 'form-control',
                                                    'min' => 0
                                                ])->label(false)
                                                ?>
                                            </div>
                                            <div class="col-md-6" style="padding-top:10px">
                                                / <?= Yii::t("item", "week") ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" style="padding-left:0">
                                                <?= $form->field($model, 'price_month')->input('number', [
                                                    'class' => 'form-control',
                                                    'min' => 0
                                                ])->label(false)
                                                ?>
                                            </div>
                                            <div class="col-md-6" style="padding-top:10px">
                                                / <?= Yii::t("item", "month") ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <b><?= Yii::t("item", "Price suggestions") ?></b>
                                        <br/>
                                        <?= Yii::t("item",
                                            "Having trouble setting the right price for your product? We can suggest one if you know the new price of the item:") ?>
                                        <br/><br/>
                                        <input type="text" class="form-control" id="new-price"
                                               placeholder="<?= Yii::t("item", "New price in DKK") ?>"
                                               width="100px"/>
                                        <br/>

                                        <div class="suggestion-daily"></div>
                                        <div class="suggestion-weekly"></div>
                                        <div class="suggestion-monthly"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row price">
                            <div class="col-sm-10 col-sm-offset-1">
                                <h4>
                                    <?= Yii::t("item", "Condition") ?> <br>
                                </h4>
                                <?= $form->field($model, 'condition')->dropDownList(
                                    \app\modules\item\models\Item::getConditions()
                                )->label(false) ?>
                            </div>
                        </div>
                        <!-- leave id for scrolling -->

                        <div class="row " id="publishing">
                            <div class="col-md-11">
                                <?= Html::button(\Yii::t('app', 'Save'), [
                                    'class' => "btn btn-danger btn-fill btn-lg pull-right",
                                    'id' => 'submit-save'
                                ]) ?>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1 terms">
                                <h4>
                                    <?= Yii::t("item", "Preview & Publishing"); ?>
                                    <small>
                                        <br/>
                                        <?php
                                        if ($model->isPublishable() !== true) {
                                            echo \Yii::t('item', 'There are some things left to be fixed before you can preview or publish this item.');
                                            echo \Yii::t('item', 'Refresh the page if everything is fixed.');
                                        }
                                        ?>
                                    </small>
                                </h4>
                                <?php
                                if ($model->isPublishable() === true) {
                                    echo $form->field($model, 'rules')->checkbox([
                                        'data-toggle' => "checkbox"
                                    ]);
                                    echo Html::button(\Yii::t('app', 'Publish'), [
                                        'class' => "btn btn-danger pull-right",
                                        'id' => 'submit-publish'
                                    ]);
                                    echo Html::button(\Yii::t('app', 'Preview'), [
                                        'class' => "btn btn-primary",
                                        'id' => 'submit-preview',
                                    ]);

                                } else {
                                    foreach ($model->isPublishable() as $error) {
                                        echo $error . '<BR>';
                                    };
                                } ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
