<?php
use app\modules\images\components\ImageHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

\yii\web\JqueryAsset::register($this);
\app\modules\item\assets\CreateAsset::register($this);
/**
 * @var \yii\web\View $this
 */
?>
<section class="section" id="new-rental">
    <div class="container">
        <div class="row">
            <div class=" col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2">
                <div class="card header">
                    <div class="content ">
                        <h2 class="title"><?= Yii::t("item", "Create a new item") ?> <b>(<?= Yii::t("item",
                                    "step 1 out of 2") ?>)</b></h2>
                        <?= ImageHelper::img('kidup/graphics/rentout.png', ['w' => 120, 'h' => 120],
                            ['class' => 'header-top-image']) ?>
                    </div>
                </div>
                <div class="card">
                    <div class="content">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <h4><?= Yii::t("item", "Title") ?><br>
                                    <small><?= Yii::t("item",
                                            "How can you describe your item, in one catchy title?") ?></small>
                                </h4>
                                <div class="form-group">
                                    <?= $form->field($model,
                                        'name')->textInput(['class' => 'form-control'])->label(false) ?>
                                </div>
                            </div>
                        </div>
                        <div id="select-categories" class="row">
                            <div class="col-sm-10 col-sm-offset-1 categories">
                                <h4>
                                    <?= Yii::t("item", "Categories") ?>
                                    <br>
                                    <small><?= Yii::t("item", "What categories describe your item best?") ?></small>
                                </h4>
                                <?php foreach ($categories as $category): ?>
                                    <div class="btn btn-default category-clickable-button"
                                         data-id="<?= $category->id ?>">
                                        <?= $category->name ?>
                                    </div>
                                    <?= Html::activeHiddenInput($model, 'categories[' . $category->id . ']',
                                        ['value' => 0]); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-sm-10 col-sm-offset-1">
                                <?= Html::submitButton(\Yii::t('app', 'Next step'), [
                                    'class' => "btn btn-danger btn-lg btn-fill pull-right",
                                    'id' => 'go-to-rental-2'
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
