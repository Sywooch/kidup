<?php

namespace booking\controllers;

use api\models\Currency;
use app\extended\web\Controller;
use app\jobs\SlackJob;
use \booking\forms\Confirm;
use \booking\models\Booking;
use \booking\models\Payin;
use \images\components\ImageHelper;
use item\forms\CreateBooking;
use \item\models\Item;
use \user\models\PayoutMethod;
use Carbon\Carbon;
use Yii;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['confirm', 'request'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public $enableCsrfValidation = false;

    public function actionConfirm()
    {
        $session = Yii::$app->session;
        $currentBooking = $session->get('currentBooking');

        // check whether the current booking is correct
        if (!is_array($currentBooking)) {
            throw new ForbiddenHttpException(Yii::t('error.booking.invalid', "Invalid booking"));
        }
        $keys = ['itemID', 'currencyID', 'dateFrom', 'dateTo'];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $currentBooking)) {
                throw new ForbiddenHttpException(Yii::t('error.booking.invalid', "Invalid booking"));
            }
        }

        $itemID = $currentBooking['itemID'];
        $currencyID = $currentBooking['currencyID'];

        // find the corresponding item
        $items = Item::find()->where(['id' => $itemID, 'is_available' => 1]);
        if ($items->count() != 1) {
            throw new ForbiddenHttpException(Yii::t('error.booking.item.invalid', "Invalid item for booking"));
        }
        $item = $items->one();

        // find the corresponding currency
        $currencies = Currency::find()->where(['id' => $currencyID]);
        if ($currencies->count() != 1) {
            throw new ForbiddenHttpException(Yii::t('error.booking.currency.invalid', "Invalid currency for booking"));
        }
        $currency = $currencies->one();

        // now check if the booking is valid
        $createBooking = new CreateBooking($item, $currency);
        $createBooking->dateFrom = $currentBooking['dateFrom'];
        $createBooking->dateTo = $currentBooking['dateTo'];
        if (!$createBooking->isValid()) {
            throw new ForbiddenHttpException(Yii::t('error.booking.daterange.invalid', "Invalid dates for booking"));
        }
        
        $model = new Confirm($createBooking);
        $booking = \Yii::createObject([
            'status' => Booking::AWAITING_PAYMENT,
            'item_id' => $item->id,
            'currency_id' => $currency->id,

        ]);

        if ($model->load(\Yii::$app->request->post())) {
            if(isset(\Yii::$app->request->post()['payment_method_nonce'])){
                $model->nonce = \Yii::$app->request->post()['payment_method_nonce'];
            }
            if(\Yii::$app->request->isAjax){
                return \yii\helpers\Json::encode($model->validate());
            }
            if ($model->save()) {
                // booking is confirmed
                $booking = $model->booking;
                if (YII_ENV == 'prod') {
                    new SlackJob([
                        'message' => "New booking payin has been made, id: " . $booking->id
                    ]);
                }
                return $this->redirect(['/booking/' . $booking->id]);
            }
        }

        return $this->render('confirm', [
            'booking' => $booking,
            'model' => $model,
            'item' => $item,
            'itemImage' => ImageHelper::url($item->getImageName(0)),
            'profile' => $item->owner->profile,
            'tableData' => $item->getTableData($booking->time_from, $booking->time_to, $booking->currency)
        ]);
    }

    public function actionRequest($id, $response = null)
    {
        /**
         * @var Booking $booking
         */
        $booking = $this->find($id);

        if ($booking->item->owner_id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("Not permitted");
        }

        if ($booking->status !== Booking::PENDING) {
            \Yii::$app->session->addFlash('info', \Yii::t('booking.flash.booking_already_confirmed', "Booking already seems to be confirmed"));
            return $this->redirect('@web/booking/' . $id);
        }

        $payoutMethod = PayoutMethod::find()->where(['user_id' => \Yii::$app->user->id])->count();
        if ($payoutMethod == 0) {
            $link = Url::to('@web/user/settings/payout-preference');
            \Yii::$app->session->addFlash('info',
                \Yii::t('booking.flash.add_payment_method', "Please add a {href}payment method{endHref} so we know where we can pay you.", [
                    'href' => "<a href='{$link}'>",
                    'endHref' => "</a>",
                ]));
            return $this->redirect('@web/item/list');
        }

        if ($booking->payin->status !== Payin::STATUS_AUTHORIZED) {
            if ($booking->payin->status == Payin::STATUS_PENDING) {
                \Yii::$app->session->addFlash('info', \Yii::t('booking.flash.booking_still_unconfirmed',
                    'The payment has to be confirmed before the booking can be accepted. Please try again in a couple of minutes'));
                return $this->redirect('@web/item/list');
            }
            throw new ServerErrorHttpException("Please contact support with error message: Payin status should be authorized, booking id {$booking->id}");
        }

        if ($booking->request_expires_at < time()) {
            \Yii::$app->session->addFlash('info',
                \Yii::t('booking.request_no_respondse_removed_flash', 'This booking had no response for more then 48 hours, so is removed.'));
            return $this->goHome();
        }

        // todo make this POST (safer)
        if ($response == 'accept') {
            $res = $booking->ownerAccepts();
            \Yii::$app->session->addFlash('info', \Yii::t('booking.flash.booking_accepted', 'Booking has been successfully accepted'));
            return $this->redirect('@web/booking/' . $id);
        }

        if ($response == 'decline') {
            $res = $booking->ownerDeclines();
            \Yii::$app->session->addFlash('info', \Yii::t('booking.flash.booking_declined', 'Booking has been successfully declined'));
            return $this->redirect('@web/booking/by-item/' . $booking->item_id);
        }

        return $this->render('owner_response', [
            'acceptLink' => Url::to('@web/booking/' . $id . '/request?response=accept'),
            'declineLink' => Url::to('@web/booking/' . $id . '/request?response=decline'),
            'item' => $booking->item,
            'booking' => $booking,
            'profile' => $booking->renter->profile,
            'timeLeft' => Carbon::createFromTimestamp($booking->payin->created_at)->diffForHumans(
                Carbon::createFromTimestamp($booking->payin->created_at)->addDay(1)
                , true)
        ]);
    }

    public function actionConversation($id)
    {
        $booking = $this->find($id);
        if (isset($booking->conversations[0])) {
            return $this->redirect(['@web/messages/' . $booking->conversations[0]->id]);
        }
    }

    private function find($id)
    {
        $booking = Booking::find()->where(['id' => $id])->one();
        if ($booking == null) {
            throw new NotFoundHttpException("Booking doesn't exist or does not belong to you.");
        }
        return $booking;
    }
}
