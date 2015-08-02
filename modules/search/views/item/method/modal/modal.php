<!-- Search modal -->
<div class="modal fade filter-modal" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- Modal header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t("item", "Filter") ?></h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <?php
                use yii\widgets\ActiveForm;

                $form = ActiveForm::begin([
                    'options' => [
                        'class' => 'form-vertical',
                        'name' => 'itemSearch'
                    ],
                    'action' => '',
                    'method' => 'get'
                ]);
                ?>

                <!-- Load the query filter -->
                <?= $this->render('../../filter/query/modal', [
                    'form' => $form,
                    'model' => $model
                ]) ?>

                <!-- Load the location filter -->
                <?= $this->render('../../filter/location/modal', [
                    'form' => $form,
                    'model' => $model
                ]) ?>

                <?php
                ActiveForm::end();
                ?>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button class="btn btn-primary btn-fill"
                    ng-click="applyFilter(ItemSearchFactory.filters[modal]); modal = ''"
                    ng-show="modal != ''"
                    data-dismiss="modal"
                >Apply</button>
                <button class="btn btn-default"
                    ng-click="modal = ''"
                    ng-show="modal != ''"
                >Back</button>
                <button type="button" class="btn btn-default" onclick="restoreFilterModal()" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
