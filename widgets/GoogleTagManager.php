<?php

namespace app\widgets;

use app\components\Cache;
use Yii;

class GoogleTagManager extends \yii\bootstrap\Widget
{
    public function run()
    {
        if (!Yii::$app->keyStore->enable_analytics) {
            return false;
        }

        return Cache::build('widget_google_tag_manager-view-render')
            ->duration(60 * 60)
            ->data(function () {
                return $this->render('tag_manager');
            });
    }
}

