<?php

namespace item;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('item') && ($module = $app->getModule('item')) instanceof Module) {
            Yii::setAlias('@item', __DIR__);
        }
    }
}