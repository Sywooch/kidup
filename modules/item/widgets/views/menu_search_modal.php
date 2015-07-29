<?php
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;
?>

<!-- Search modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['/search']]); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Search</h4>
                </div>
                <div class="modal-body">
                    <?= $form->field($model, 'query')->input('text', [
                        'class' => 'form-control',
                        'placeholder' => Yii::t('app', 'What are you looking for?')
                    ])->label(false) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <?= Html::submitButton(\Yii::t('app', "Search"),
                        ['class' => 'btn btn-danger btn-fill btn-wide']) ?>
                </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>