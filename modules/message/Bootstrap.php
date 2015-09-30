<?php

namespace message;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('message') && ($module = $app->getModule('message')) instanceof Module) {
            Yii::setAlias('@message', __DIR__);
        }
    }
}