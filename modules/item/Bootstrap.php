<?php

namespace app\modules\item;

use app\components\Event;
use app\models\Booking;
use app\modules\item\models\Rent;
use app\modules\payment\models\Payin;
use yii\base\BootstrapInterface;
use yii\base\Module;
use Yii;
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