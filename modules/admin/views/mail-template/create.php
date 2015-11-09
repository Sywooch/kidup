<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var mail\models\base\MailTemplate $model
 */

$this->title = 'Create Mail Template';
$this->params['breadcrumbs'][] = ['label' => 'Mail Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-template-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
