<?php

namespace app\modules\pages;

use yii\base\Module;
use Yii;
use yii\base\BootstrapInterface;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
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