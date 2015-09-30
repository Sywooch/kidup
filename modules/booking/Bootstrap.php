<?php

namespace booking;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('booking') && ($module = $app->getModule('booking')) instanceof Module) {
            Yii::setAlias('@booking', __DIR__);
        }
    }
}