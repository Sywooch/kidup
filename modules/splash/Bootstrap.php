<?php

namespace app\modules\splash;

use app\components\Event;
use app\models\Booking;
use app\modules\item\models\Item;
use app\modules\item\models\Rent;
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
        if ($app->hasModule('splash') && ($module = $app->getModule('splash')) instanceof Module) {
            Yii::setAlias('@splash', '@web/splash');
            Yii::setAlias('@splashAssets', '@web/assets_web/modules/splash');
        }
    }
}