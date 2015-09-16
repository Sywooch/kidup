<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use \app\modules\item\widgets\GoogleAutoComplete;
use \kartik\typeahead\Typeahead;

/**
 * @var \app\modules\search\forms\Filter $model
 */
\app\assets\FullModalAsset::register($this);

\yii\bootstrap\Modal::begin([
    'id' => 'bookingModal',
    'options' => [
        'class' => 'modal modal-fullscreen force-fullscreen'
    ],
    'closeButton' => false,
    'header' => "<b>".\Yii::t('home', 'KidUp Booking')."</b>"
]);

?>

<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
    <i class="fa fa-close"></i>
</button>

<br /><br />

<?= $this->render('booking_widget', [
    'model' => $model
]); ?>

<?php
\yii\bootstrap\Modal::end();
?>
