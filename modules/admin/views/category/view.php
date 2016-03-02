<?php

use kartik\detail\DetailView;

/**
 * @var yii\web\View $this
 * @var item\models\Category $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => Yii::$app->request->get('edit') == 't' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'name',
            'parent_id',
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->id],
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ],
        'enableEditMode' => false,
    ]) ?>

</div>
