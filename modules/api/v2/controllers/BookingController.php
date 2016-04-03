<?php
namespace api\v2\controllers;

use api\v2\models\Booking;
use api\v2\models\Currency;
use api\v2\models\Item;
use api\v2\models\Review;
use booking\models\booking\BookingException;
use booking\models\booking\BookingFactory;
use booking\models\payin\BrainTree;
use item\forms\CreateBooking;
use message\models\message\MessageFactory;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;

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
            'guest' => ['payment-token', 'costs', 'options', 'reviews'],
            'user' => ['create', 'view', 'index', 'as-owner', 'accept', 'decline']
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
            ])->orderBy('updated_at DESC')
        ]);
    }

    function actionAsOwner()
    {
        return new ActiveDataProvider([
            'query' => Booking::find()->where([
                'item.owner_id' => \Yii::$app->user->id
            ])->innerJoinWith("item")
                ->orderBy('updated_at DESC')
        ]);
    }

    function actionView($id)
    {
        return Booking::findOneOr404($id);
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

        $userMessage = isset($params['message']) ? $params['message'] : null;

        try {
            $booking = new BookingFactory();
            $booking->payment_nonce = $params['payment_nonce'];
            $booking = $booking->createForApi($params, $this->modelClass);
            if($booking->hasErrors()){
                return $booking;
            }
            $booking = Booking::findOne($booking->id);
            $message = new MessageFactory();
            $message->createInitialBookingMessage($userMessage, $booking->conversation, $booking);
            return $booking;
        } catch (BookingException $e) {
            \Yii::error($e);
            throw new BadRequestHttpException($e->getMessage());
        }
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
        $item = Item::findOneOr404([
            'id' => $item_id,
            'is_available' => 1
        ]);

        // grab the currency of the user
        $currency = Currency::getUserOrDefault(\Yii::$app->user->identity);

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

    public function actionReviews($id)
    {
        return new ActiveDataProvider([
            'query' => Review::find()->where(['reviewed_id' => $id, 'type' => Review::TYPE_USER_PUBLIC])
        ]);
    }

    public function actionDecline($id)
    {
        $booking = Booking::findOneOr404(['id' => $id]);

        try {
            $booking->ownerDeclines();
        } catch (BookingException $e) {
            \Yii::error($e);
            throw new BadRequestHttpException($e->getMessage());
        }

        return $booking;
    }

    public function actionAccept($id)
    {
        $booking = Booking::findOneOr404(['id' => $id]);

        try {
            $booking->ownerAccepts();
        } catch (BookingException $e) {
            \Yii::error($e);
            throw new BadRequestHttpException($e->getMessage());
        }

        return $booking;
    }
}