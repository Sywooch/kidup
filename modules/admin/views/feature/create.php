<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var item\models\base\Feature $model
 */

$this->title = 'Create Feature';
$this->params['breadcrumbs'][] = ['label' => 'Features', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feature-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
