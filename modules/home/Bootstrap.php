<?php

namespace home;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('home') && ($module = $app->getModule('home')) instanceof Module) {
            Yii::setAlias('@home', __DIR__);
        }
    }
}