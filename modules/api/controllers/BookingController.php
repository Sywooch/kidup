<?php
namespace api\controllers;

use api\models\Booking;
use api\models\Currency;
use api\models\Item;
use api\models\Review;
use booking\forms\Confirm;
use booking\models\BrainTree;
use item\forms\CreateBooking;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BookingController extends Controller
{
    public function init()
    {
        $this->modelClass = Booking::className();
        parent::init();
    }

    public function accessControl()
    {
        return [
            'guest' => ['payment-token', 'costs', 'options', 'reviews '],
            'user' => ['create', 'view', 'index']
        ];
    }

    public function actions()
    {
        $actions = parent::actions();

        // overwrite the default create action
        unset($actions['create']);
        unset($actions['costs']);
        unset($actions['index']);
        unset($actions['view']);

        return $actions;
    }

    function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Booking::find()->where([
                'renter_id' => \Yii::$app->user->id
            ])
        ]);
    }

    function actionView($id){
        $b = Booking::find()->where(['id' => $id])->one();
        if($b === null) {
            throw new NotFoundHttpException;
        }
        /**
         * @var Booking $b
         */
        if($b->canBeAccessedByUser(\Yii::$app->user->identity)){
            return $b;
        }else{
            throw new ForbiddenHttpException;
        }
    }

    /**
     * @api {post} bookings
     * @apiGroup        Booking
     * @apiName         createBooking
     * @apiDescription  Create a booking.
     *
     * @apiParam {Number}       item_id             The item_id of the item to create the booking for.
     * @apiParam {Integer}      time_from           timestamp formatted date to start the booking on.
     * @apiParam {Integer}      time_to            timestamp formatted date to end the booking on.
     * @apiParam {String}       payment_nonce       a valid braintree payment nonce.
     * @apiParam {String}       message             message to the owner.
     * @apiSuccess {boolean}    success             Whether or not the booking was successfully created.
     * @apiSuccess {Number}     booking_id          The booking_id of the newly created booking (only if success === true).
     */
    public function actionCreate()
    {
        // fetch the parameters
        $params = \Yii::$app->request->post();

        // check the parameters
        if (!isset($params['item_id'])) {
            throw(new BadRequestHttpException("No item_id is set."));
        }
        if (!isset($params['time_from'])) {
            throw(new BadRequestHttpException("No time_from (timestamp) is set."));
        }
        if (!isset($params['time_to'])) {
            throw(new BadRequestHttpException("No time_to (timestamp) is set."));
        }
        if (!isset($params['payment_nonce'])) {
            throw(new BadRequestHttpException("No payment_method_nonce (string) is set."));
        }

        // load the parameters
        $item_id = $params['item_id'];
        $nonce = $params['payment_nonce'];
        $message = $params['message'];

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
        $booking->dateFrom = date("d-m-Y", round($params['time_from']));
        $booking->dateTo = date("d-m-Y", round($params['time_to']));

        // save the booking
        $bookingObject = null;
        if ($booking->validateDates()) {
            if ($booking->save(false)) {
                $bookingObject = $booking->booking;
            } else {
                throw new BadRequestHttpException('Booking object couldnt validate');
            }
        } else {
            throw new BadRequestHttpException('Dates are invalid');
        }
        if (is_object($bookingObject) && isset($bookingObject->id) && is_numeric($bookingObject->id) && $bookingObject->id > 0) {
            // do the actual payment
            $model = new Confirm($bookingObject);
            $model->message = $message;
            $model->nonce = $nonce;
            $model->rules = 1;
            if ($model->save()) {
                // booking is made and payed!
                return $bookingObject;
            } else {
                throw new BadRequestHttpException('Payment failed - no idea why');
            }
        }
        throw new BadRequestHttpException('Booking failed');
    }

    /**
     * @api {get} bookings/costs
     * @apiGroup        Bookingc
     * @apiName         costsBooking
     * @apiDescription  Check the costs of a booking.
     *
     * @apiParam {Number}       item_id             The item_id of the item to create the booking for.
     * @apiParam {Integer}      time_from           timestamp formatted date to start the booking on.
     * @apiParam {Integer}      time_to            timestamp formatted date to end the booking on.
     * @apiSuccess {Object[]}   tableData           An array containing the costs of the booking.
     */
    public function actionCosts($item_id, $time_from, $time_to)
    {
        // load the parameters
        // dates should be in d-m-Y format
        $time_from = date("d-m-Y", (int)$time_from);
        $time_to = date("d-m-Y", (int)$time_to);

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
        $booking->dateFrom = $time_from;
        $booking->dateTo = $time_to;

        // do not save, display the table data
        $booking->calculateTableData();

        return $booking->tableData;
    }

    /**
     * @api {get} bookings/payment-token
     * @apiGroup        Booking
     * @apiName         paymentTokenBooking
     * @apiDescription  Returns a client token to be used for braintree creditcard processing.
     *
     * @apiSuccess {Array}   token           The client token.
     */
    public function actionPaymentToken()
    {
        return [
            'token' => (new BrainTree())->getClientToken()
        ];
    }

    public function actionReviews($id){
        return new ActiveDataProvider([
            'query' => Review::find()->where(['reviewed_id' => $id, 'type' => Review::TYPE_USER_PUBLIC])
        ]);
    }

}