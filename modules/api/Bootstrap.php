<?php

namespace api;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('api') && ($module = $app->getModule('api')) instanceof Module) {
            Yii::setAlias('@api', __DIR__);
        }
    }
}