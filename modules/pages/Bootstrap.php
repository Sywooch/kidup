<?php

namespace pages;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('pages') && ($module = $app->getModule('pages')) instanceof Module) {
            Yii::setAlias('@pages', __DIR__);
        }
    }
}