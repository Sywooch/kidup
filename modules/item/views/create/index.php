<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \app\components\view\View $this
 * @var \item\forms\Create $model
 */

\yii\web\JqueryAsset::register($this);
\item\assets\CreateAsset::register($this);

$this->assetPackage = \app\assets\Package::ITEM_CREATE;
?>
<section class="section" id="new-rental">
    <div class="card header" style="text-align: center;">
        <div class="content ">
            <h2>
                <?= Yii::t("item.create.header", "Upload your product") ?>
            </h2>

            <h3 style="color:white;">
                <?= Yii::t("item.create.sub_header", "KidUp lets you make money from renting out children stuff.") ?>
            </h3>
            <br>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class=" col-sm-10 col-md-10 col-sm-offset-1 col-md-offset-1">
                <div class="card">
                    <div class="content">
                        <?php $form = ActiveForm::begin(); ?>

                        <div id="select-categories" class="row">
                            <div class="col-sm-10 col-sm-offset-1 categories">

                                <h5>
                                    <?= Yii::t("item.create.product_category_header", "Product category") ?>
                                </h5>
                                <?= Yii::t("item.create.pick_correct_category",
                                    "Start uploading an item by selecting the category where it fits best.") ?>
                                <?= $form->field($model, 'category')->widget(\kartik\select2\Select2::className(), [
                                    'data' => $model->categoryData,
                                    'options' => ['placeholder' => \Yii::t('item.create.find_category_placeholder', 'Find a category')],
                                ])->label(false) ?>

                                <div class="row" style="margin: 0">
                                    <?php if (!\Yii::$app->user->identity->profile->validate('first_name')): ?>
                                        <h5>
                                            <?= Yii::t("item.create.profile", "Profile") ?>
                                        </h5>
                                        <div class="col-md-6" style="padding-left:0">
                                            <?= $form->field($model, 'first_name'); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!\Yii::$app->user->identity->profile->validate('last_name')): ?>
                                        <div class="col-md-6" style="padding-right:0">
                                            <?= $form->field($model, 'last_name'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?= Html::submitButton(\Yii::t('item.create.continue_button', 'Continue'), [
                                    'class' => "btn btn-danger btn-lg btn-fill",
                                ]) ?>

                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
