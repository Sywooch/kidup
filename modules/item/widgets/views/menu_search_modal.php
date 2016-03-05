<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \app\extended\web\View $this
 * @var \search\forms\Filter $model
 */
\app\assets\FullModalAsset::register($this);
\yii\bootstrap\Modal::begin([
    'id' => 'searchModal',
    'options' => [
        'class' => 'modal modal-fullscreen force-fullscreen'
    ],
    'closeButton' => false,
    'header' => "<b>".\Yii::t("item.mobile_search.header_name", 'KidUp Search')."</b>"
]);

$emptyLocation = \Yii::t('home.search.empty_location', 'Location: Near Me');
$this->registerJsVariables([
    'emptyLocation' => $emptyLocation,
]);
?>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    <i class="fa fa-close"></i>
</button>

<?php $form = ActiveForm::begin([
    'id' => 'mobile-search',
    'fieldConfig' => [
        'template' => "{input}"
    ],
    'action' => Url::to('@web/search', true),
    'method' => 'get',
    'options' => [
        'style' => 'padding:15px;padding-top:80px;',
        'data-pjax' => 0
    ],
]) ?>

<input type="text" class="form-control" placeholder="<?= \Yii::t("item.mobile_search.placeholder", 'What do you like to get your child?') ?>" name="q">

<br><br>

<?= Html::submitButton(Yii::t("item.mobile_search.search_button", 'Search'), [
    'class' => 'btn btn-danger btn-fill btn-block',
    'id' => 'menu-search-submit-button'
]) ?>

<?php ActiveForm::end(); ?>

<?php
\yii\bootstrap\Modal::end();
?>
