<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var item\models\base\Feature $model
 */

$this->title = 'Update Feature: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Features', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="feature-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
