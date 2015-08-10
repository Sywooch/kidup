<?php
use yii\bootstrap\Modal;
use yii\helpers\Url;
Modal::begin([
    'header' => '<h3>'.\Yii::t('item', 'Yay! Your product is now online and ready to be rented out!').'</h3>',
    'id' => 'sharingModal'
]);
echo \Yii::t('item', 'Tip: There is a 83% bigger chance to rent out the product within the first 14 days if you share it on Facebook!');
echo "<div style='text-align:center;margin-top:20px;font-size:30px;'>";
echo \kartik\social\FacebookPlugin::widget([
    'type' => \kartik\social\FacebookPlugin::SHARE,
]);
echo "</div>";

Modal::end();

$this->registerJs("$(window).load(function(){
        $('#sharingModal').modal('show');
    });");

?>