<?php

namespace app\modules\booking\controllers;

use app\components\Event;
use app\modules\booking\models\Booking;
use app\modules\booking\models\BrainTree;
use app\modules\booking\models\Invoice;
use app\modules\booking\models\Payin;
use app\modules\booking\models\Payout;
use app\modules\review\models\Review;
use Carbon\Carbon;
use Yii;
use yii\base\Model;

class CronController extends Model
{
    public function minute()
    {
        // see if the booking expired (owner did not respond)
        $payins = Payin::findAll(['status' => Payin::STATUS_PENDING]);
        foreach ($payins as $payin) {
            $payin->booking->updateStatus();
        }

        $payins = Payin::findAll(['status' => Payin::STATUS_SETTLING]);
        foreach ($payins as $payin) {
            if (\Yii::$app->keyStore->get('braintree_type') == 'sandbox') {
                require(Yii::$aliases['@vendor'] . '/braintree/braintree_php/tests/TestHelper.php');
                $b = new BrainTree($payin);
                $b->sandboxSettlementAccept();
            }
            $payin->booking->updateStatus();
        }
    }

    public function hour()
    {
        // see if the booking expired (owner did not respond)
        $bookings = Booking::findAll(['status' => Booking::PENDING]);

        foreach ($bookings as $booking) {
            if ($booking->request_expires_at < Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp) {
                $booking->ownerFailsToRespond();
            }

            if ($booking->time_from < Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp) {
                $booking->ownerFailsToRespond();
            }
        }

        // que the payout if booking has been on for 24 hours
        $bookings = Booking::find()->where([
            'status' => Booking::ACCEPTED
        ])->andWhere('time_from < :time1 && time_from >= :time2', [
            ':time1' => Carbon::now(\Yii::$app->params['serverTimeZone'])->subDay()->timestamp,
            ':time2' => Carbon::now(\Yii::$app->params['serverTimeZone'])->subDay()->subHour()->timestamp,
        ])->all();
        foreach ($bookings as $booking) {
            if ($booking->payout->status == Payout::STATUS_WAITING_FOR_BOOKING_START) {
                $invoice = new Invoice();
                $invoice = $invoice->create($booking);
                $payout = $booking->payout;
                $payout->status = Payout::STATUS_TO_BE_PROCESSED;
                $payout->invoice_id = $invoice->id;
                $payout->save();
                Event::trigger($booking, Booking::EVENT_OWNER_INVOICE_READY);
            }
        }
    }

    public function day()
    {
        // resend booking reminder if not accepted yet

        $bookings = Booking::find()
            ->where(['status' => Booking::PENDING])
            ->andWhere('request_expires_at < :time and request_expires_at >= :time2', [
                ':time' => Carbon::now(\Yii::$app->params['serverTimeZone'])->addDay()->timestamp,
                ':time2' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp
            ])->all();
        foreach ($bookings as $booking) {
            Event::trigger($booking, Booking::EVENT_OWNER_CONFIRMATION_REMINDER);
        }

        $bookings = Booking::find()
            ->where(['status' => Booking::PENDING])
            ->andWhere('request_expires_at < :time', [
                ':time' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp
            ])->all();
        foreach ($bookings as $booking) {
            /**
             * @var Booking $booking
             */
            if ($booking->request_expires_at < Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp) {
                $booking->ownerFailsToRespond();
            }

            if ($booking->time_from < Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp) {
                $booking->ownerFailsToRespond();
            }
        }
        // bookings that are almost starting
        $bookings = Booking::find()->where('status = :status and time_from < :timeFrom and time_from > :timeTo', [
            ':status' => Booking::ACCEPTED,
            ':timeTo' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp + 24 * 60 * 60,
            ':timeFrom' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp + 48 * 60 * 60
        ])->all();
        foreach ($bookings as $booking) {
            Event::trigger($booking, Booking::EVENT_BOOKING_ALMOST_START);
        }

        // bookings that are almost ending
        $bookings = Booking::find()->where('status = :status and time_to < :timeFrom and time_to > :timeTo', [
            ':status' => Booking::ACCEPTED,
            ':timeTo' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp + 24 * 60 * 60,
            ':timeFrom' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp + 48 * 60 * 60
        ])->all();

        foreach ($bookings as $booking) {
            Event::trigger($booking, Booking::EVENT_BOOKING_ALMOST_ENDS);
        }

        // bookings that ended
        $bookings = Booking::find()->where('status = :status and time_to < :timeFrom and time_to > :timeTo', [
            ':status' => Booking::ACCEPTED,
            ':timeTo' => Carbon::now(\Yii::$app->params['serverTimeZone'])->subDay()->timestamp,
            ':timeFrom' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp
        ])->all();

        foreach ($bookings as $booking) {
            Event::trigger($booking, Booking::EVENT_BOOKING_ENDED);
        }

        // reviews

        // reminder
        $bookings1 = Booking::find()->where('status = :status and time_to < :timeFrom and time_to > :timeTo', [
            ':status' => Booking::ACCEPTED,
            ':timeTo' => Carbon::now(\Yii::$app->params['serverTimeZone'])->addDays(7)->timestamp,
            ':timeFrom' => Carbon::now(\Yii::$app->params['serverTimeZone'])->addDays(8)->timestamp
        ])->all();
        $bookings2 = Booking::find()->where('status = :status and time_to < :timeFrom and time_to > :timeTo', [
            ':status' => Booking::ACCEPTED,
            ':timeTo' => Carbon::now(\Yii::$app->params['serverTimeZone'])->addDays(2)->timestamp,
            ':timeFrom' => Carbon::now(\Yii::$app->params['serverTimeZone'])->addDays(3)->timestamp
        ])->all();
        foreach ([$bookings1, $bookings2] as $bookings) {
            foreach ($bookings as $booking) {
                $c = Review::find()->where([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->item->owner_id
                ])->count();
                if($c == 0){
                    Event::trigger($booking, Booking::EVENT_REVIEW_REMINDER_OWNER);
                }

                $c = Review::find()->where([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->renter_id
                ])->count();
                if($c == 0){
                    Event::trigger($booking, Booking::EVENT_REVIEW_REMINDER_RENTER);
                }
            }
        }
    }
}
