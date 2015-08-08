<?php

namespace app\modules\home;

use app\components\Event;
use app\modules\item\models\Rent;
use app\modules\message\models\Conversation;
use app\modules\payment\models\Payin;
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
        if ($app->hasModule('home') && ($module = $app->getModule('home')) instanceof Module) {
            Yii::setAlias('@home', __DIR__);
        }
    }
}