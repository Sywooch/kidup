<?php

namespace booking\controllers;

use app\helpers\Event;
use booking\models\booking\Booking;
use booking\models\payin\BrainTree;
use booking\models\invoice\InvoiceFactory;
use booking\models\payin\Payin;
use booking\models\payout\Payout;
use Carbon\Carbon;
use review\models\Review;
use Yii;
use yii\base\Model;

class CronController extends Model
{
    public function minute()
    {
        // see if the booking expired (owner did not respond)
        $payins = Payin::find()->where(['status' => Payin::STATUS_PENDING])->all();
        foreach ($payins as $payin) {
            /**
             * @var $payin \booking\models\payin\Payin
             */
            if (!is_null($payin->booking)) {
                $payin->booking->updateStatus();
            }
        }

        $payins = Payin::findAll(['status' => Payin::STATUS_SETTLING]);
        foreach ($payins as $payin) {
            /**
             * @var $payin \booking\models\payin\Payin
             */
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
        $bookings = Booking::find()
            ->where(['status' => Booking::PENDING])
            ->andWhere('request_expires_at < :tstamp')
            ->params([':tstamp' => Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp])
            ->all();

        foreach ($bookings as $booking) {
            /**
             * @var Booking $booking
             */
            $booking->ownerFailsToRespond();
        }

        // que the payout if booking has been on for 24 hours

        $bookings = Booking::find()->where([
            'booking.status' => Booking::ACCEPTED
        ])
            ->andWhere('time_from < :time1', [
                ':time1' => Carbon::now(\Yii::$app->params['serverTimeZone'])->subDay()->timestamp,
            ])
            ->innerJoinWith('payout')
            ->andWhere(['payout.status' => Payout::STATUS_WAITING_FOR_BOOKING_START])
            ->all();

        foreach ($bookings as $booking) {
            if ($booking->payout->status == Payout::STATUS_WAITING_FOR_BOOKING_START) {
                $payout = $booking->payout;
                $payout->status = Payout::STATUS_TO_BE_PROCESSED;
                $payout->invoice_id = (new InvoiceFactory())->createForBooking($booking)->id;
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
                if ($c == 0) {
                    Event::trigger($booking, Booking::EVENT_REVIEW_REMINDER_OWNER);
                }

                $c = Review::find()->where([
                    'booking_id' => $booking->id,
                    'user_id' => $booking->renter_id
                ])->count();
                if ($c == 0) {
                    Event::trigger($booking, Booking::EVENT_REVIEW_REMINDER_RENTER);
                }
            }
        }
    }
}
