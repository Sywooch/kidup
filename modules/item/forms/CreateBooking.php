<?php

namespace app\modules\item\forms;

use app\models\base\Currency;
use app\modules\booking\models\Booking;
use app\modules\item\models\Item;
use app\modules\user\models\User;
use Carbon\Carbon;
use yii\base\Model;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class CreateBooking extends Model
{

    public $dateFrom;
    public $dateTo;
    public $from;
    public $to;
    public $booking; // holds the final booking, if created
    private $itemBookings;
    public $currency;
    public $item;
    public $periods = [];
    public $tableData = false;

    public function __construct(Item $item, Currency $currency)
    {
        $this->currency = $currency;
        $this->item = $item;
        $this->itemBookings = Booking::find()->where('item_id = :itemId and status = :status and time_to > :time')->params([
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

    public function validateDates()
    {
        if ($this->validate('dateFrom') && $this->validate('dateTo')) {
            $this->from = Carbon::createFromFormat('d-m-Y g:i:s', $this->dateFrom . ' 12:00:00')->timestamp;
            $this->to = Carbon::createFromFormat('d-m-Y g:i:s', $this->dateTo . ' 12:00:00')->timestamp;
            // see if it clashes with another booking
            // https://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap
            $overlapping = Booking::find()->where(':from < time_to and :to > time_from and item_id = :item_id and status = :status',
                [
                    ':from' => $this->from,
                    ':to' => $this->to,
                    ':item_id' => $this->item->id,
                    ':status' => Booking::ACCEPTED
                ])->count();
            if ($overlapping > 0) {
                $this->addError('dateFrom', \Yii::t('item', 'A booking already exists in this period'));
                return false;
            }
            if ($this->to <= $this->from) {
                $this->addError('dateFrom', \Yii::t('item', 'The start date should be larger then the end date'));
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Attempt to make a booking based on session data. Returns false or a redirect url
     * @return bool|string
     */
    public function attemptBooking()
    {
        if ($this->validateDates()) {
            if (\Yii::$app->session->has('ready_to_book') || YII_ENV == 'test') {
                $session = Json::decode(\Yii::$app->session->get('ready_to_book'));
                if (($session['time_from'] == $this->from && $session['time_to'] == $this->to && $this->item->id == $session['item_id'])
                    || YII_ENV == 'test'
                ) {

                    if ($this->save()) {
                        $redirect = Url::to('@web/booking/' . $this->booking->id . '/confirm', true);
                        return "<script>window.location.replace('{$redirect}');</script>";
                    }
                }
            }
        }

        if ($this->calculateTableData()) {
            \Yii::$app->session->set('ready_to_book', Json::encode([
                'item_id' => $this->item->id,
                'time_from' => $this->from,
                'time_to' => $this->to,
                'currency_id' => $this->currency->id
            ]));
        } else {
            \Yii::$app->session->remove('ready_to_book');
        }
        return false;
    }

    /**
     * Tries to save an actual booking
     * @return bool
     */
    private function save()
    {
        if (!$this->validate()) {
            return false;
        }

        if (\Yii::$app->user->isGuest) {
            $this->addError('dateFrom', \Yii::t('item', 'You should be logged in to perform this action.'));
            return false;
        }

        $booking = new Booking();
        $booking->setScenario('init');
        $booking->time_from = $this->from;
        $booking->time_to = $this->to;
        $booking->item_id = $this->item->id;
        $booking->renter_id = \Yii::$app->user->id;
        $booking->currency_id = $this->currency->id;
        $booking->status = Booking::AWAITING_PAYMENT;
        $booking->setPayinPrices();
        if ($booking->save()) {
            $this->booking = $booking;
            return true;
        }
        return false;
    }
}