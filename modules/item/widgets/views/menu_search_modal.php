<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var \app\modules\search\forms\Filter $model
 */
\app\assets\FullModalAsset::register($this);

\yii\bootstrap\Modal::begin([
    'id' => 'searchModal',
    'options' => [
        'class' => 'modal modal-fullscreen force-fullscreen'
    ]
]);

?>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<?php $form = ActiveForm::begin([
        'id' => 'mobile-search',
        'fieldConfig' => [
            'template' => "{input}\n{error}"
        ],
        'action' => Url::to('@web/search'),
        'method' => 'get',
        'options' => [
            'style' => 'padding:15px;padding-top:30px;'
        ]
    ]) ?>

    <?= $form->field($model, 'query')->textInput(['placeholder' => 'What are you looking for?']) ?>

    <?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-danger btn-fill btn-block']) ?>
    <?php ActiveForm::end(); ?>

<?php
\yii\bootstrap\Modal::end();
?>
