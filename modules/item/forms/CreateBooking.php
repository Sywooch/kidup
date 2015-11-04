<?php

namespace item\forms;

use booking\models\Booking;
use Carbon\Carbon;
use item\models\Item;
use user\models\base\Currency;
use yii\base\Model;
use yii\helpers\Json;
use yii\helpers\Url;

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
            $this->tableData = $this->item->getTableData($this->from, $this->to, $this->currency);
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
                $this->addError('dateFrom', \Yii::t('item.create_booking.error.already_booked',
                    'A booking already exists in this period'));
                return false;
            }
            if ($this->to <= $this->from) {
                $this->addError('dateFrom', \Yii::t('item.create_booking.error.start_date_larger_then_end_date',
                    'The start date should be larger then the end date'));
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
    public function attemptBooking($fakeBooking)
    {
        if ($this->validateDates()) {
            if (isset(\Yii::$app->request->get()['_book'])) {
                if ($this->save($fakeBooking)) {
                    $redirect = Url::to('@web/booking/confirm?item_id=' . $this->item->id . '&date_from=' . $this->dateFrom . '&date_to=' . $this->dateTo,
                        true);
                    if (YII_ENV === 'test') {
                        return \Yii::$app->controller->redirect($redirect);
                    }
                    return "<script>window.location = '{$redirect}';</script>";
                }
                return false;
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
    public function save($fakeBooking)
    {
        if (!$this->validate()) {
            return false;
        }

        if (\Yii::$app->user->isGuest) {
            $this->addError('dateFrom', \Yii::t('item.create_booking.error.should_be_logged_in',
                'You should be logged in to perform this action.'));
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
        if ($fakeBooking) {
            $this->booking = $booking;
            return true;
        }
        if ($booking->save()) {
            $this->booking = $booking;
            return true;
        }
        return false;
    }
}