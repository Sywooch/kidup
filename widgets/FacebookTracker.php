<?php

namespace app\widgets;

use app\components\Cache;
use yii\helpers\Url;
use Yii;

/**
 * Facebook tracker widget
 */
class FacebookTracker extends \yii\bootstrap\Widget
{

    public function run()
    {
        if (YII_ENV !== 'prod') {
            return false;
        }

        $patterns = [
            'search/search/index' =>                    'Search',
            'user/registration/post-registration' =>    'CompleteRegistration',
            'item/create/index' =>                      'Lead',
            'item/view/index' =>                        'ViewContent',
        ];

        $route = @\Yii::$app->controller->getRoute();

        if (isset($patterns[$route])) {
            $trackerId = $patterns[$route];
            return Cache::build('facebook_tracker_widget-view-render')
                ->variations($trackerId)
                ->duration(60 * 60)
                ->data(function () use ($trackerId) {
                    return $this->render('fb_tracker', ['id' => $trackerId]);
                });
        }
        return '';
    }
}