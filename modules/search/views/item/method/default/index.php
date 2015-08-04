<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<section class="section show-lg show-md hidden-sm hidden-xs search-default">
    <div class="container-fluid">

        <!-- Search filters -->
        <div class="row">

            <?php
            $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form-vertical',
                    'name' => 'itemSearch'
                ],
                'action' => '',
                'method' => 'get'
            ]);
            ?>

            <div class="col-md-3 col-lg-3 hidden-sm hidden-xs">
                <div class="card card-refine">
                    <div class="header">

                        <!-- Button to clear all filters -->
                        <?= Html::button(\Yii::t('item', "Clear"),
                            [
                                'class' => 'btn btn-danger btn-xs pull-right',
                                'name' => 'clear'
                            ]) ?>

                        <!-- Title -->
                        <h4 class="title"><?= Yii::t("item", "Filter") ?></h4>

                    </div>
                    <div class="content">
                        <!-- Load the query filter -->
                        <?= $this->render('../../filter/query/default', [
                            'form' => $form,
                            'model' => $model
                        ]) ?>

                        <!-- Load the location filter -->
                        <?= $this->render('../../filter/location/default', [
                            'form' => $form,
                            'model' => $model
                        ]) ?>

                        <!--category filter -->
                        <?= $this->render('../../filter/category/default', [
                            'form' => $form,
                            'model' => $model
                        ]) ?>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <div class="col-lg-9 col-md-9 results-default">
                <?= $this->render('../../filter/buttons', []); ?>

                <?= $this->render('../../results/index', [
                    'results' => $results
                ]); ?>
            </div>

        </div>
    </div>
</section>