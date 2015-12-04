<?php

namespace search;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('search') && ($module = $app->getModule('search')) instanceof Module) {
            Yii::setAlias('@search', __DIR__);
        }
    }

}