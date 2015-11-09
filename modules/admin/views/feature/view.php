<?php

use kartik\detail\DetailView;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var item\models\base\Feature $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Features', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feature-view">
    <?= DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'panel' => [
            'heading' => $this->title,
            'type' => DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'is_singular',
            'name',
            'description',
            'is_required',
        ],
        'deleteOptions' => [
            'url' => ['delete', 'id' => $model->id],
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ],
    ]) ?>

</div>
