<?php

namespace app\modules\images;

use yii\base\BootstrapInterface;
use yii\base\Module;
use Yii;
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
        if ($app->hasModule('images') && ($module = $app->getModule('images')) instanceof Module) {
            Yii::setAlias('@images', __DIR__);
        }
    }
}