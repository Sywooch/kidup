<?php

namespace app\widgets;

use app\components\Cache;
use Yii;

class GoogleTagManager extends \yii\bootstrap\Widget
{
    public function run()
    {
        if (YII_ENV !== 'prod') {
            return false;
        }

        return Cache::data('widget_google_tag_manager-view-render', function () {
            return $this->render('tag_manager');
        }, 60 * 60);
    }
}

