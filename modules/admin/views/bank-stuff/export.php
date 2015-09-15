<?php
use yii\bootstrap\Html;
?>

<div class="col-md-4">
    Export for danske bank. DONT CLICK THIS if you're not sure what it does.
    <br>
    <?= Html::a('Generate CSV export', '@web/admin/bank-stuff/generate-payout',
        [
            'class' => 'btn btn-danger btn-xl btn-fill',
            'data-confirm' => 'ATTENTION: You\'re sure, right?',
        ]
    ) ?>
</div>