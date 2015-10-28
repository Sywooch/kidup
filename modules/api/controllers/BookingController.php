<?php
namespace api\controllers;

use booking\models\Booking;
use api\models\Item;
use item\forms\CreateBooking;
use yii\base\Exception;
use yii\data\ActiveDataProvider;

class BookingController extends Controller
{
    public function init(){
        $this->modelClass = Booking::className();
        parent::init();
    }

    public function accessControl(){
        return [
            'guest' => [],
            'user' => ['index', 'create', 'costs']
        ];
    }

    public function actions(){
        $actions = parent::actions();

        // overwrite the default create action
        unset($actions['create']);
        unset($actions['costs']);

        return $actions;
    }

    /**
     * POST /api/v1/bookings
     *
     * Create a booking.
     *
     * @param itemID        the itemID to create to booking for
     * @param dateFrom      d-m-Y formatted date to start the booking on
     * @param dateEnd       d-m-Y formatted date to end the booking on
     * @return success      whether the booking was successfully created or not
     * @return bookingID    the ID of the newly created booking (only if success === true)
     * @throws Exception    when itemID is not set
     * @throws Exception    when date_from is not set
     * @throws Exception    when date_to is not set
     */
    public function actionCreate() {
        // fetch the parameters
        $params = \Yii::$app->request->post();

        // check the parameters
        if (!isset($params['itemID'])) {
            throw(new Exception("No itemID is set."));
        }
        if (!isset($params['date_from'])) {
            throw(new Exception("No date_from (timestamp) is set."));
        }
        if (!isset($params['date_to'])) {
            throw(new Exception("No date_to (timestamp) is set."));
        }

        // load the parameters
        $itemID = $params['itemID'];
        // dates should be in d-m-Y format
        $date_from = $params['date_from'];
        $date_to = $params['date_to'];

        // fetch the item
        $item = Item::find()->where([
            'id' => $itemID,
            'is_available' => 1
        ]);

        // check whether the item is found
        if ($item->count() != 1) {
            throw(new Exception("Item not found."));
        }

        // set the item as the found item
        $item = $item->one();

        // grab the currency of the user
        $currency = \Yii::$app->user->isGuest ? Currency::find()->one() : \Yii::$app->user->identity->profile->currency;

        // create a new booking
        $booking = new CreateBooking($item, $currency);
        $booking->dateFrom = $date_from;
        $booking->dateTo = $date_to;

        // create the result
        $result = [
            'success' => false
        ];

        // save the booking
        $bookingObject = $booking->attemptBooking();
        if ($bookingObject !== false) {
            $result['success'] = true;
            $result['bookingID'] = $bookingObject->id;
        }

        return $result;
    }

    /**
     * POST /api/v1/bookings/costs
     *
     * Calculate the costs for a booking.
     *
     * @param itemID        the itemID to create to booking for
     * @param dateFrom      d-m-Y formatted date to start the booking on
     * @param dateEnd       d-m-Y formatted date to end the booking on
     * @return tableData    an array containing the costs of the booking
     * @throws Exception    when itemID is not set
     * @throws Exception    when date_from is not set
     * @throws Exception    when date_to is not set
     */
    public function actionCosts() {
        // fetch the parameters
        $params = \Yii::$app->request->post();

        // check the parameters
        if (!isset($params['itemID'])) {
            throw(new Exception("No itemID is set."));
        }
        if (!isset($params['date_from'])) {
            throw(new Exception("No date_from (timestamp) is set."));
        }
        if (!isset($params['date_to'])) {
            throw(new Exception("No date_to (timestamp) is set."));
        }

        // load the parameters
        $itemID = $params['itemID'];
        // dates should be in d-m-Y format
        $date_from = $params['date_from'];
        $date_to = $params['date_to'];

        // fetch the item
        $item = Item::find()->where([
            'id' => $itemID,
            'is_available' => 1
        ]);

        // check whether the item is found
        if ($item->count() != 1) {
            throw(new Exception("Item not found."));
        }

        // set the item as the found item
        $item = $item->one();

        // grab the currency of the user
        $currency = \Yii::$app->user->isGuest ? Currency::find()->one() : \Yii::$app->user->identity->profile->currency;

        // create a new booking
        $booking = new CreateBooking($item, $currency);
        $booking->dateFrom = $date_from;
        $booking->dateTo = $date_to;

        // do not save, display the table data
        $booking->calculateTableData();

        return [
            'tableData' => $booking->tableData
        ];
    }

}