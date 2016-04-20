<?php

namespace message;

use app\components\Event;
use booking\models\booking\Booking;
use booking\models\booking\BookingFactory;
use message\models\conversation\ConversationFactory;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Module;

class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var $module Module */
        if ($app->hasModule('message') && ($module = $app->getModule('message')) instanceof Module) {
            Yii::setAlias('@message', __DIR__);
        }

        Event::register(BookingFactory::className(), BookingFactory::EVENT_AFTER_BOOKING_CREATE, function ($event) {
            /**
             * @var BookingFactory $booking
             */
            $booking = $event->sender;
            $factory = new ConversationFactory();
            $res = $factory->createFromBooking($booking);
        });

    }
}