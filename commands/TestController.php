<?php

namespace app\commands;

use app\components\Event;
use app\models\base\Item;
use app\modules\booking\models\Booking;
use app\modules\item\models\ItemSimilarity;
use app\modules\mail\models\MailMessage;
use app\modules\review\models\Review;
use Yii;
use yii\console\Controller;

class TestController extends Controller
{

    public function actionReview(){

        $booking = Booking::findOne(17);

        Review::create(Review::TYPE_USER_PUBLIC, "Alex is awesome", $booking, false);
        Review::create(Review::TYPE_EXPERIENCE, 4, $booking, false);
        Review::create(Review::TYPE_USER_COMMUNICATION, 5, $booking, false);
        Review::create(Review::TYPE_USER_EXCHANGE, 3, $booking, false);
        Review::create(Review::TYPE_AD_ACCURACY, 5, $booking, false);

        Review::create(Review::TYPE_USER_PUBLIC, "Alex is awesome2", $booking, false);
        Review::create(Review::TYPE_EXPERIENCE, 5, $booking, false);
        Review::create(Review::TYPE_USER_COMMUNICATION, 3, $booking, false);
        Review::create(Review::TYPE_USER_EXCHANGE, 5, $booking, false);
        Review::create(Review::TYPE_AD_ACCURACY, 4, $booking, false);

        Review::create(Review::TYPE_USER_PUBLIC, "Alex is awesome3", $booking, false);
        Review::create(Review::TYPE_EXPERIENCE, 4, $booking, false);
        Review::create(Review::TYPE_USER_COMMUNICATION, 5, $booking, false);
        Review::create(Review::TYPE_USER_EXCHANGE, 5, $booking, false);
        Review::create(Review::TYPE_AD_ACCURACY, 5, $booking, false);

        Review::create(Review::TYPE_USER_PUBLIC, "Alex is awesome4", $booking, false);
        Review::create(Review::TYPE_EXPERIENCE, 4, $booking, false);
        Review::create(Review::TYPE_USER_COMMUNICATION, 3, $booking, false);
        Review::create(Review::TYPE_USER_EXCHANGE, 4, $booking, false);
        Review::create(Review::TYPE_AD_ACCURACY, 5, $booking, false);

        Review::updateAll(['is_public' => 1], ['booking_id' => $booking->id]);

    }

    public function actionTest(){
        $item = \app\modules\item\models\Item::find()->one();
        (new ItemSimilarity())->compute($item);
    }
}
