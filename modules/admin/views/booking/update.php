<?php

/**
 * @var yii\web\View $this
 * @var booking\models\Booking $model
 */

$this->title = 'Update Booking: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="booking-update">

    <h1>Please be very fucking carefull here :)</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
