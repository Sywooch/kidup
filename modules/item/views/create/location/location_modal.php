<?php
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use app\helpers\SelectData;
use \item\widgets\GoogleAutoComplete;

/**
 * @var \yii\web\View $this
 * @var \item\forms\LocationForm $model
 * @var integer $itemId
 */
?>

<?php Modal::begin([
    'header' => '<h2>'.Yii::t('item.create.location_modal.enter_address', 'Enter Address').'</h2>'.
        Yii::t('item.create.location_modal.address_help_text', 'Where can the product be picked up?'),
    'id' => 'location-modal', // important for the opening button caller
    'class' => 'greyed-modal'
]);

$form = \yii\bootstrap\ActiveForm::begin([
    'action' => '@web/item/create/add-location',
    'method' => 'post',
    'enableAjaxValidation' => true,
    'validationUrl' => '@web/item/create/add-location',
]);
?>
<?= $form->field($model, 'item_id')->hiddenInput(['value' => $itemId])->label(false) ?>
<?= $form->field($model, 'country')->widget(Select2::classname(), [
    'data' => SelectData::nationality(),
    'options' => ['placeholder' => \Yii::t('item.create.location_modal.select_country', 'Select a country')],
    'pluginOptions' => [
        'allowClear' => false
    ],
]); ?>

<div class="row">
    <div class="col-xs-7">
        <?= $form->field($model, 'street')->widget(\item\widgets\GoogleAutoComplete::className(), [
            'options' => [
                'class' => 'location-input form-control',
                'autocompleteName' => 'item-create'
            ],
            'autocompleteOptions' => [
                'types' => ['geocode']
            ]
        ])->label(\Yii::t('itemcreate.location_modal.street_and_number', 'Street and Streetnumber')); ?>
    </div>
    <div class="col-xs-5">
        <?= $form->field($model, 'street_suffix')->label(\Yii::t('item.create.location_modal.address_suffix', 'Apt, Suite, Building (optional)')) ?>
    </div>
</div>

<?= $form->field($model, 'zip_code') ?>
<?= $form->field($model, 'city') ?>

<?= \yii\bootstrap\Html::submitButton(\Yii::t('item.create.location_modal.button', 'Submit'), [
    'class' => "btn btn-danger btn-fill",
]); ?>

<?php
\yii\bootstrap\ActiveForm::end();

Modal::end(); ?>

