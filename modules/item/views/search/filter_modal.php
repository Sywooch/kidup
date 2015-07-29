<?php
use \yii\widgets\ActiveForm;
?>

<!-- Filter modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['/search']]); ?>
            <div class="modal-header">
                <button type="button" class="close" onclick="restoreFilterModal()" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?= Yii::t("item", "Choose a filter") ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="filterSelect">
                    <?php
                    foreach ($filters as $filter) {
                        echo '<a href="#" onclick="showFilter(\'' . $filter['view'] . '\')"
                                class="btnSelectFilter btnSelectFilter-' . $filter['view'] . ' btn btn-danger bottomMargin">' .
                                $filter['label'] . '</a> ';
                    }
                    ?>
                </div>
                <?php
                foreach ($filters as $filter) {
                    echo '<div class="filter filter-' . $filter['view'] . ' hidden">';
                    echo $this->render('filter/' . $filter['view'] . '.php', [
                        'searchModel' => $searchModel,
                        'form' => $form,
                        'categories' => $categories,
                        'collapsable' => false,
                        'mobile' => true
                    ]);
                    echo '</div>';
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="restoreFilterModal()" data-dismiss="modal"><?= Yii::t("item", "Close") ?></button>
                <button type="button" class="btnBack btn btn-default hidden" onclick="restoreFilterModal()"><?= Yii::t("item", "Back") ?></button>
                <button type="button" class="btnApply btn btn-danger hidden" onclick="applyFilter()"><?= Yii::t("item", "Apply filter") ?></button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>