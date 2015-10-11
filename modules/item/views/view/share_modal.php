<?php
use yii\bootstrap\Modal;
use \kartik\social\FacebookPlugin;

/**
 * @var \app\extended\web\View $this
 * @var  \item\models\Item $model
 */

$this->assetPackage = \app\assets\Package::ITEM_VIEW;

Modal::begin([
    'header' => '<h3>' . \Yii::t('item.view.share_modal.text',
            'Yay! Your product is now online and ready to be rented out!') . '</h3>',
    'id' => 'sharingModal'
]);
echo \images\components\ImageHelper::image('kidup/graphics/celebration.png', ['w' => 400]);
echo "<br>";
echo \Yii::t('item.view.share_modal.tip',
    'Tip: There is a 83% bigger chance to rent out the product within the first 14 days if you share it on Facebook!');
echo "<div style='text-align:center;margin-top:20px;font-size:30px;'>";

$title = urlencode(\Yii::t('facebook_share.new_window_title', 'Share KidUp on Facebook'));
$url = urlencode(\yii\helpers\Url::to('@web/item/' . $model->id));
$image = urlencode(\images\components\ImageHelper::url($model->getImageName(0), ['w' => 400]));

?>
    <a onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[url]=<?php echo $url; ?>&amp;p[images][0]=<?php echo $image; ?>','sharer','toolbar=0,status=0,width=548,height=325');"
       href="javascript: void(0)">
        <div class="btn btn-lg btn-danger">
            <?= Yii::t("facebook_share.button.text", "Share your item on facebook!") ?>
        </div>
    </a>
<?php
echo "</div>";

Modal::end();

$js = <<<JS
$(window).load(function(){
    $('#sharingModal').modal('show');
});
$('.btnShare').click(function(){
            elem = $(this);
            postToFeed(elem.data('title'), elem.data('desc'), elem.prop('href'), elem.data('image'));

            return false;
        });
JS;
$this->registerJs($js);
?>