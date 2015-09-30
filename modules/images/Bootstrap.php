<?php

namespace images;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('images') && ($module = $app->getModule('images')) instanceof Module) {
            Yii::setAlias('@images', __DIR__);
        }
    }
}