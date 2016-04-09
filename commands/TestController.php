<?php

namespace app\commands;

use booking\models\booking\Booking;
use booking\models\payout\PayoutFactory;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionPayout()
    {
        $booking = Booking::findOneOr404(191);
        $res = (new PayoutFactory())->createFromBooking($booking);
    }
}
