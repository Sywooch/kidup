<?php

namespace app\widgets;

use yii\helpers\Url;
use Yii;

/**
 * Facebook tracker widget
 */
class FacebookTracker extends \yii\bootstrap\Widget
{

    public function init()
    {
        parent::init();

//        if (YII_ENV !== 'prod') {
//            return false;
//        }

        $patterns = [
            'search/search/index' =>                    '6027473304499',
            'user/registration/post-registration' =>    '6027473275699',
            'item/create/index' =>                      '6027473382499',
            'item/create/edit-publish' =>               '6027473357499',
        ];

        if(isset($patterns[\Yii::$app->controller->getRoute()])){
            $trackerId = $patterns[\Yii::$app->controller->getRoute()];
            return $this->render('fb_tracker', ['id' => $trackerId]);
        }
        return false;
    }
}

