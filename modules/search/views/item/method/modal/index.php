<?php
use yii\helpers\Html;

// load the modal
echo $this->render('modal', [
    'model' => $model
]);
?>

<section class="section hidden-lg hidden-md show-sm show-xs search-modal">
    <!-- Search results -->
    <div class="container-fluid">

        <!-- Filter button -->
        <div class="row bottomMargin">
            <div class="col-sm-12 col-xs-12">
                <?= Html::button(
                    '<i class="fa fa-filter"></i> ' . \Yii::t('app', "Filter"),
                    [
                        'class' => 'btn btn-danger btn-fill',
                        'data-toggle' => 'modal',
                        'data-target' => '#filterModal',
                        'ng-click' => "modal = ''",
                        'name' => 'filter'
                    ]) ?>
            </div>
        </div>

        <!-- Search results -->
        <div class="row results-modal">
            <div class="col-sm-12 col-xs-12">
                <?= $this->render('../../filter/buttons', []); ?>

                <?= $this->render('../../results/index', [
                    'results' => $results
                ]); ?>
            </div>
        </div>

    </div>
</section>