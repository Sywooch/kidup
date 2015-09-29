<?php
use yii\bootstrap\Modal;
use \kartik\social\FacebookPlugin;

/**
 * @var \app\extended\web\View $this
 * @var $model \app\modules\review\models\Review
 */

$this->assetPackage = \app\assets\Package::ITEM_VIEW;

Modal::begin([
    'header' => '<h3>' . \Yii::t('item', 'Yay! Your product is now online and ready to be rented out!') . '</h3>',
    'id' => 'sharingModal'
]);
echo \Yii::t('item',
    'Tip: There is a 83% bigger chance to rent out the product within the first 14 days if you share it on Facebook!');
echo "<div style='text-align:center;margin-top:20px;font-size:30px;'>";
echo FacebookPlugin::widget([
    'type' => FacebookPlugin::SHARE,
]);
echo "</div>";

Modal::end();

$js = <<<JS
$(window).load(function(){
    $('#sharingModal').modal('show');
});
JS;
$this->registerJs($js);
?>