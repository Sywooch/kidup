<?php
use app\modules\images\components\ImageHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

\yii\web\JqueryAsset::register($this);
\app\modules\item\assets\CreateAsset::register($this);
/**
 * @var \yii\web\View $this
 */
?>
<section class="section" id="new-rental">
    <div class="card header" style="text-align: center;">
        <div class="content ">
            <h2>
                <?= Yii::t("item", "Upload your product") ?>
            </h2>

            <h3 style="color:white;">
                <?= Yii::t("item", "KidUp lets you make money from renting out children stuff.") ?>
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
                                    <?= Yii::t("item", "Intended product age") ?>
                                </h5>
                                <?php foreach ($ages as $age): ?>
                                    <div class="btn btn-default category-clickable-button"
                                         data-id="<?= $age->id ?>">
                                        <?= $age->name ?>
                                    </div>
                                    <?= Html::activeHiddenInput($model, 'categories[' . $age->id . ']',
                                        ['value' => 0]); ?>
                                <?php endforeach; ?>
                                <br>
                                <h5>
                                    <?= Yii::t("item", "Product type") ?>
                                </h5>
                                <?php foreach ($categories as $category): ?>
                                    <div class="btn btn-default category-clickable-button"
                                         data-id="<?= $category->id ?>">
                                        <?= $category->name ?>
                                    </div>
                                    <?= Html::activeHiddenInput($model, 'categories[' . $category->id . ']',
                                        ['value' => 0]); ?>
                                <?php endforeach; ?>


                                <div class="row" style="margin: 0">
                                    <?php if (!\Yii::$app->user->identity->profile->validate('first_name')): ?>
                                        <h5>
                                            <?= Yii::t("item", "Profile") ?>
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

                                <?= Html::submitButton(\Yii::t('item', 'Continue'), [
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
