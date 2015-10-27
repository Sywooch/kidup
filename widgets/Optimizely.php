<?php

namespace app\widgets;

use app\components\Cache;
use yii\helpers\Url;
use Yii;

/**
 * Facebook tracker widget
 */
class Optimizely extends \yii\bootstrap\Widget
{

    public function run()
    {
        if (YII_ENV !== 'prod') {
            return '';
        }

        return '<script src="//cdn.optimizely.com/js/3252830293.js"></script>';
    }
}