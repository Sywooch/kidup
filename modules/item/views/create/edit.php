<?php

use app\modules\images\components\ImageHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use \kartik\form\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \app\modules\item\models\Item $model
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
                <div class="card">
                    <div class="row hidden-xs ">
                        <div class="col-sm-12 text-center">
                            <h4>
                                <?= Yii::t("item", "Tips") ?>
                                <br>
                                <small>
                                    <?= Yii::t("item",
                                        "Some suggestions to make it more attractive for people to rent your item.") ?>
                                </small>
                            </h4>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-12">
                            <?= ImageHelper::img('kidup/graphics/pickup.png',
                                ['w' => 100, 'h' => 100, 'q' => 90],
                                ['width' => 'auto', 'height' => 'auto', 'class' => 'pull-left']) ?>
                            <br>
                            <?= Yii::t("item",
                                "Accurate, detailed information makes your item more trustworthy. Consider adding a personal note.") ?>
                        </div>
                    </div>
                    <hr style="margin: 0">
                    <div class="row">
                        <div class="col-md-12">
                            <?= ImageHelper::img('kidup/graphics/photograph.png',
                                ['w' => 100, 'h' => 100, 'q' => 90],
                                ['width' => 'auto', 'height' => 'auto', 'class' => 'pull-left']) ?>
                            <br>
                            <span>
                                <?= Yii::t("item", "Provide {b}high-quality images{bOut} of your product.",
                                    ['b' => "<b>", 'bOut' => "</b>"]) ?>
                            </span>
                        </div>
                    </div>
                    <hr style="margin: 0">
                    <div class=" row">
                        <div class="col-md-12">
                            <?= ImageHelper::img('kidup/graphics/meet.png',
                                ['w' => 100, 'h' => 100, 'q' => 90],
                                ['width' => 'auto', 'height' => 'auto', 'class' => 'pull-left']) ?>
                            <br>
                            <?= Yii::t("item", "Use our price suggestion tool to get optimal pricing.") ?>
                        </div>
                    </div>
                </div>

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
                                        <?= Yii::t("item",
                                            "Drag and drop images or click the box to start uploading. They can be sorted by dragging uploaded pictures, the first picture will be used as 'billboard' image on the product page.") ?>
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
                                    'description')->textarea(['class' => 'form-control', 'rows' => 6])->label(false) ?>
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
                                <h4>
                                    <?= Yii::t("item", "Condition") ?> <br>
                                </h4>
                                <?= $form->field($model, 'condition')->dropDownList(
                                    \app\modules\item\models\Item::getConditions()
                                )->label(false) ?>
                            </div>
                        </div>

                        <div class="row">
                            <?= $this->render('location', [
                                'form' => $form,
                                'model' => $model
                            ]); ?>
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
