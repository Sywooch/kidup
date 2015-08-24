<?php

namespace app\modules\booking\forms;

use app\components\Error;
use app\modules\booking\models\Booking;
use app\modules\item\models\Item;
use app\modules\user\models\User;
use Carbon\Carbon;
use yii\base\Model;
use yii\helpers\Html;

class Create extends Model
{

    public $dateFrom;
    public $dateTo;
    public $itemId;
    public $bookingId;
    public $currencyId;
    public $prices;

    public function __construct()
    {

    }

    public function formName()
    {
        return 'create-booking';
    }

    public function rules()
    {
        return [
            [['dateFrom', 'dateTo'], 'string'],
            [['itemId', 'currencyId'], 'number'],
            [['prices', 'dateFrom', 'dateTo'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $from = Carbon::createFromFormat('d-m-Y', $this->dateFrom)->timestamp;
        $to = Carbon::createFromFormat('d-m-Y', $this->dateTo)->timestamp;
        // http://stackoverflow.com/questions/2545947/mysql-range-date-overlap-check
        $booking = Booking::find()->where(':from < time_from and :to > time_to and item_id = :item_id',
            [':from' => $from, ':to' => $to, ':item_id' => $this->itemId])->one();
        if (count($booking) > 0) {
            \Yii::$app->session->setFlash("error", \Yii::t('booking', "Item is already booked between these dates"));
            return false;
        }

        if (\Yii::$app->user->isGuest) {

            \Yii::$app->session->setFlash("error",
                \Yii::t('booking', "You should be logged in to perform this action"));
            \Yii::$app->controller->redirect('@web/user/login');
            return false;
        }

        /**
         * @var $user \app\modules\user\models\User
         */
        $user = User::findOne(\Yii::$app->user->id);
        if ($user->canMakeBooking() !== true) {
            \Yii::$app->session->setFlash("info",
                \Yii::t('booking', "Please finish your {0} before making a booking.", [
                    Html::a(\Yii::t('booking', 'profile settings'), '@web/user/settings/profile', [
                        'target' => '_blank'
                    ])
                ]));
            return false;
        }
        $item = Item::findOne($this->itemId);
        $rentingDays = Carbon::createFromFormat('d-m-Y', $this->dateFrom)->diffInDays(Carbon::createFromFormat('d-m-Y',
            $this->dateTo));
        if ($rentingDays < $item->min_renting_days) {
            \Yii::$app->session->setFlash("error",
                \Yii::t('booking', "This item requires at least {0} days per booking.", [$item->min_renting_days]));
            return false;
        }
        $booking = new Booking();
        $booking->setScenario('init');
        $booking->time_from = $from;
        $booking->time_to = $to;
        $booking->item_id = $this->itemId;
        $booking->renter_id = \Yii::$app->user->id;
        $booking->currency_id = $this->currencyId;
        $booking->status = Booking::AWAITING_PAYMENT;
        $booking->setPayinPrices($from, $to, $this->prices);
        if ($booking->save()) {
            $this->bookingId = $booking->id;
            return true;
        } else {
        }
    }
}