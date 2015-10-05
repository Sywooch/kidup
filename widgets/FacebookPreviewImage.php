<?php

namespace app\widgets;

use app\components\Cache;
use images\components\ImageHelper;
use yii\helpers\Url;
use Yii;
use item\models\Item;
/**
 * Registers Meta tags that are used by fb for preview images
 */
class FacebookPreviewImage extends \yii\bootstrap\Widget
{

    public function run()
    {
        $route = @\Yii::$app->controller->getRoute();
        $image =  ImageHelper::url('kidup/facebook-kidupdk.jpg', ['w' => 600]);
        if ($route == 'item/view/index') {
            $id = @\Yii::$app->controller->actionParams['id'];
            if((int)$id == $id){
                // image for product
                /**
                 * @var $item Item
                 */
                $item = Item::findOne($id);
                if($item !== null && count($item->itemHasMedia) > 0){
                    $image = ImageHelper::url($item->itemHasMedia[0]->media->file_name, ['w' => 600]);
                }
            }
        }
        $this->view->registerMetaTag([
            'property' => "og:image",
            'content' => $image
        ]);
        $this->view->registerMetaTag([
            'property' => "og:image:secure_url",
            'content' => $image
        ]);
    }
}