<?php

namespace admin;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('admin') && ($module = $app->getModule('admin')) instanceof Module) {
            Yii::setAlias('@admin', __DIR__);
        }
    }
}