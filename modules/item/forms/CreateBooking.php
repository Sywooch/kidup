<?php

namespace app\modules\item\forms;

use app\models\base\Currency;
use app\modules\booking\models\Booking;
use app\modules\item\models\Item;
use app\modules\user\models\User;
use Carbon\Carbon;
use yii\base\Model;
use yii\helpers\Html;

class CreateBooking extends Model
{

    public $dateFrom;
    public $dateTo;
    private $from;
    private $to;
    private $itemBookings;
    public $currency;
    public $item;
    public $periods = [];
    public $tableData = false;

    public function __construct(Item $item, Currency $currency)
    {
        $this->currency = $currency;
        $this->item = $item;
        $this->itemBookings = Booking::find()->where('item_id = :itemId and status != :status and time_to > :time')->params([
            ':itemId' => $this->item->id,
            ':status' => Booking::ACCEPTED,
            ':time' => time(),
        ])->all();
        foreach ($this->itemBookings as $booking) {
            $this->periods[] = [$booking->time_from, $booking->time_to];
        }
        return parent::__construct();
    }

    public function formName()
    {
        return 'create-booking';
    }

    public function rules()
    {
        return [
            [['dateFrom', 'dateTo'], 'string'],
            [['dateFrom', 'dateTo'], 'required'],
        ];
    }

    /**
     * Computes the values for the pricing table in the booking widget
     * @return bool
     */
    public function calculateTableData()
    {
        if ($this->validateDates()) {
            $prices = $this->item->getPriceForPeriod($this->from, $this->to, $this->currency);
            $days = floor(($this->to - $this->from) / (60 * 60 * 24));
            if ($days <= 7) {
                $period = \Yii::t('item', '{n, plural, =1{1 day} other{# days}}', ['n' => $days]);
                $periodPrice = $this->item->price_day;
            } elseif ($days > 7 && $days <= 31) {
                $period = \Yii::t('item', '{n, plural, =1{1 week} other{# weeks}}', ['n' => round($days / 7)]);
                $periodPrice = $this->item->price_week;
            } else {
                $period = \Yii::t('item', '{n, plural, =1{1 month} other{# months}}', ['n' => round($days / 30)]);
                $periodPrice = $this->item->price_month;
            }
            $this->tableData = [
                'price' => [
                    $period . ' x ' . $this->currency->forex_name . ' ' . $periodPrice,
                    $this->currency->abbr . ' ' . $prices['price']
                ],
                'fee' => [\Yii::t('item', 'Service fee'), $this->currency->abbr . ' ' . $prices['fee']],
                'total' => [\Yii::t('item', 'Total'), $this->currency->abbr . ' ' . $prices['total']]
            ];
            return true;
        }
        return false;
    }

    private function validateDates()
    {
        if ($this->validate('dateFrom') && $this->validate('dateTo')) {
            $this->from = Carbon::createFromFormat('d-m-Y', $this->dateFrom)->timestamp;
            $this->to = Carbon::createFromFormat('d-m-Y', $this->dateTo)->timestamp;
            // see if it clashes with another booking
            foreach ($this->itemBookings as $booking) {
                // https://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap
                if ($this->from <= $booking->time_to and $this->to >= $booking->time_from) {
                    $this->addError('dateFrom', \Yii::t('item', 'A booking already exists in this period'));
                    return false;
                }
            }
            if ($this->to <= $this->from) {
                $this->addError('dateFrom', \Yii::t('item', 'The from date should be larger then the to date'));
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

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
        return false;
    }
}