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
        $name = "KidUp | Din online forældre-til-forældre markedsplads for børneudstyr.";
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
        if(\Yii::$app->request->get("ref") !== null || $route == 'user/referral/index'){
            $image = ImageHelper::url("kidup/facebook-referral.jpg", ['w' => 600]);
            $name = "KidUp | Vind en tur for hele familien til Lalandia!";
        }

        $this->view->registerMetaTag([
            'property' => "og:title",
            'content' => $name
        ]);
        $this->view->registerMetaTag([
            'property' => "og:site_name",
            'content' => $name
        ]);
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