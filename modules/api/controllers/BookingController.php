<?php
namespace api\controllers;

use api\models\Currency;
use api\models\Item;
use booking\models\Booking;
use booking\models\BrainTree;
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
            'guest' => ['payment-token', 'index', 'create', 'view'],
            'user' => ['costs']
        ];
    }

    public function actions(){
        $actions = parent::actions();

        // overwrite the default create action
        unset($actions['create']);
        unset($actions['costs']);

        return $actions;
    }

    function actionIndex(){
        return new ActiveDataProvider([
            'query' => Booking::find()->where([
                'renter_id' => \Yii::$app->user->id
            ])
        ]);
    }

    /**
     * @api {post} bookings
     * @apiGroup        Booking
     * @apiName         createBooking
     * @apiDescription  Create a booking.
     *
     * @apiParam {Number}       item_id             The item_id of the item to create the booking for.
     * @apiParam {String}       date_from           d-m-Y formatted date to start the booking on.
     * @apiParam {String}       date_end            d-m-Y formatted date to end the booking on.
     * @apiSuccess {boolean}    success             Whether or not the booking was successfully created.
     * @apiSuccess {Number}     booking_id          The booking_id of the newly created booking (only if success === true).
     */
    public function actionCreate() {
        // fetch the parameters
        $params = \Yii::$app->request->post();

        // check the parameters
        if (!isset($params['item_id'])) {
            throw(new Exception("No item_id is set."));
        }
        if (!isset($params['date_from'])) {
            throw(new Exception("No date_from (timestamp) is set."));
        }
        if (!isset($params['date_to'])) {
            throw(new Exception("No date_to (timestamp) is set."));
        }

        // load the parameters
        $item_id = $params['item_id'];
        // dates should be in d-m-Y format
        $date_from = $params['date_from'];
        $date_to = $params['date_to'];

        /**
         * @var $item Item
         */
        $item = Item::find()->where([
            'id' => $item_id,
            'is_available' => 1
        ])->one();

        // check whether the item is found
        if ($item === null) {
            throw(new Exception("Item not found."));
        }

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
        $bookingObject = null;
        if ($booking->validateDates()) {
            $booking->save(false);
            $bookingObject = $booking->booking;
        }
        if (is_object($bookingObject) && isset($bookingObject->id) && is_numeric($bookingObject->id) && $bookingObject->id > 0) {
            $result['success'] = true;
            $result['booking_id'] = $bookingObject->id;
        }

        return $result;
    }

    /**
     * @api {post} bookings/costs
     * @apiGroup        Booking
     * @apiName         costsBooking
     * @apiDescription  Check the costs of a booking.
     *
     * @apiParam {Number}       item_id             The item_id of the item to create the booking for.
     * @apiParam {String}       date_from           d-m-Y formatted date to start the booking on.
     * @apiParam {String}       date_end            d-m-Y formatted date to end the booking on.
     * @apiSuccess {Object[]}   tableData           An array containing the costs of the booking.
     */
    public function actionCosts() {
        // fetch the parameters
        $params = \Yii::$app->request->post();

        // check the parameters
        if (!isset($params['item_id'])) {
            throw(new Exception("No item_id is set."));
        }
        if (!isset($params['date_from'])) {
            throw(new Exception("No date_from (timestamp) is set."));
        }
        if (!isset($params['date_to'])) {
            throw(new Exception("No date_to (timestamp) is set."));
        }

        // load the parameters
        $item_id = $params['item_id'];
        // dates should be in d-m-Y format
        $date_from = $params['date_from'];
        $date_to = $params['date_to'];

        // fetch the item
        $item = Item::find()->where([
            'id' => $item_id,
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

    /**
     * @api {get} bookings/payment-token
     * @apiGroup        Booking
     * @apiName         paymentTokenBooking
     * @apiDescription  Returns a client token to be used for braintree creditcard processing.
     *
     * @apiSuccess {Array}   token           The client token.
     */
    public function actionPaymentToken(){
        return [
            'token' => (new BrainTree())->getClientToken()
        ];
    }

}