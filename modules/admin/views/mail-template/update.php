<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var mail\models\base\MailTemplate $model
 */

$this->title = 'Update Mail Template: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mail Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mail-template-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
