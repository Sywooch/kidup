<?php

namespace app\widgets;

use app\assets\SmartBannerAsset;
use Yii;

class MobileAppBanner extends \yii\bootstrap\Widget
{
    public function run()
    {
        $this->view->registerAssetBundle(SmartBannerAsset::className());
        $this->view->registerJs("(function() { $.smartbanner() } )");
        $this->view->registerMetaTag([
            'name' => 'Author',
            'content' => "KidUp"
        ]);
        $this->view->registerMetaTag([
            'name' => 'apple-itunes-app',
            'content' => "app-id=544007664"
        ]);
        $this->view->registerMetaTag([
            'name' => 'google-play-app',
            'content' => "dk.kidup.app"
        ]);
    }
}

