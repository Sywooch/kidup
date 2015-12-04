<?php
use kartik\typeahead\Typeahead;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsVariables([
    'location_not_given' => Yii::t("search.location.not_given",
        "We need a location in order to search. Please fill in the location field or allow us to get your location."),
], 'window.i18n_search')
?>

<?php $form = ActiveForm::begin([
    'action' => Url::to('@web/search', true),
    'method' => 'get',
    'options' => [
        'class' => 'form-inline hidden-xs',
        'data-pjax' => 0
    ],
    'enableClientValidation' => false,
    'enableClientScript' => false,
    'id' => 'menu-search-form'
]) ?>

<div class="form-group" style="margin-top:10px;">
</div>

<?= Html::submitButton(Yii::t("item.menu_search.search_button", "Search"),
    ['class' => "btn btn-fill btn-danger", 'onclick' => \app\helpers\ViewHelper::trackClick("menu.click_search")]) ?>

<?php ActiveForm::end() ?>

