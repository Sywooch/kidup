<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var item\models\base\Feature $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="feature-form">

    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);
    echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [
            'name' => ['type' => Form::INPUT_TEXT, 'options' => ['placeholder' => 'Enter Name...', 'maxlength' => 45]],
            'is_required' => ['type' => Form::INPUT_CHECKBOX, 'options' => ['placeholder' => 'Enter Is Required...']],
            'is_singular' => ['type' => Form::INPUT_CHECKBOX, 'options' => ['placeholder' => 'Enter Is Singular...']],
            'description' => [
                'type' => Form::INPUT_TEXT,
                'options' => ['placeholder' => 'Enter Description...', 'maxlength' => 256]
            ],
        ]

    ]);
    $cats = \item\models\Category::find()->where('parent_id IS NOT NULL')->indexBy('id')->asArray()->select('name, id')->all();
    foreach ($cats as &$c) {
        $c = $c['name'];
    }
    echo $form->field($model, 'categories')->widget(\kartik\select2\Select2::className(), [
        'data' => $cats,
        'options' => ['placeholder' => \Yii::t('admin', 'Select admins for this group'), 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    if (!$model->is_singular) {
        echo Html::tag('h2', 'Feature Values');
        foreach ($model->featureValues as $val) { ?>
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($val, "name")->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= Html::a(Html::button('delete', [
                        'class' => 'btn btn-danger',

                    ]), '@web/admin/feature/delete-feature-value?id=' . $val->id, ['data-confirm' => 'Are you sure you want to delete selected items?']) ?>
                </div>
            </div>
        <?php }
        echo Html::a(Html::button('add feature value', ['class' => 'btn btn-success pull-right']), '@web/admin/feature/add-feature-value?id='.$model->id);
    }
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
