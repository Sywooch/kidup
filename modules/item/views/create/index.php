<?php
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
                        <img class="header-top-image"
                             src="<?= \yii\helpers\Url::to('@web/img/graphics/rentout.png') ?>">
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
                        <div class="row hidden-xs">
                            <div class="rental-advice">
                                <div class="col-sm-10 col-sm-offset-1 text-left">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <img style="width: 100%"
                                                 src="<?= \yii\helpers\Url::to('@web/img/graphics/photograph.png') ?>">
                                            <small>
                                                At leje og udleje ting handler om, at vi virkelig kan se hvad vi lejer.
                                                På KidUp handler det altså om, at du tager nogle knaldgode billeder.
                                            </small>
                                        </div>
                                        <div class="col-sm-4">
                                            <img style="width: 100%"
                                                 src="<?= \yii\helpers\Url::to('@web/img/graphics/pickup.png') ?>">
                                            <small>
                                                KidUp handler også om tillid. Hvis man ser personlig beskrivelse af de
                                                forskellige ting, så får man ro i maven. Det giver troværdighed og
                                                tillid.
                                            </small>
                                        </div>
                                        <div class="col-sm-4">
                                            <img style="width: 100%"
                                                 src="<?= \yii\helpers\Url::to('@web/img/graphics/meet.png') ?>">
                                            <small>
                                                KidUp henvender sig til større ting, såsom barnevogne, vugger,
                                                alarmsystemer etc. Har du mindre ting, f.eks. tøj, kan du samle det i
                                                alderskasser.
                                            </small>
                                        </div>
                                    </div>
                                </div>
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
<?php $this->registerJs("
    $(document).ready(function() {
        /* Select Categories */
        $('.category-clickable-button').click(function () {
            $(this).toggleClass('btn-primary btn-fill');
            var id = $(this).attr('data-id');
            var hiddenEl = $('#create-item-categories-'+id);
            var currentVal = hiddenEl.val();
            hiddenEl.val(currentVal == 0 ? 1 : 0);
        });
    });"); ?>


