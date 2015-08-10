<?php

namespace app\modules\booking\controllers;

use app\controllers\Controller;
use app\modules\booking\forms\Confirm;
use app\modules\booking\models\Booking;
use app\modules\booking\models\Payin;
use app\modules\images\components\ImageHelper;
use Carbon\Carbon;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
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

    public function actionConfirm($id)
    {
        $booking = $this->find($id);

        if($booking->renter_id !== \Yii::$app->user->id){
            throw new ForbiddenHttpException();
        }

        if ($booking->status !== Booking::AWAITING_PAYMENT) {
            \Yii::$app->session->addFlash('info', "Booking is already confirmed.");
            return $this->redirect(['/booking/'.$id]);
        }

        $model = new Confirm($booking);

        if ($model->load(\Yii::$app->request->post()) ){
            if($model->save()) {
                // booking is confirmed
                if(YII_ENV == 'prod'){
                    \Yii::$app->slack->send("New booking payin has been made (id) ".$id);
                }
                return $this->redirect(['/booking/' . $id]);
            }else{
                return $this->refresh();
            }
        }

        return $this->render('confirm', [
            'booking' => $booking,
            'model' => $model,
            'item' => $booking->item,
            'itemImage' => ImageHelper::url($booking->item->media[0]->file_name),
            'profile' => $booking->item->owner->profile
        ]);
    }

    public function actionRequest($id, $response = null){
        /**
         * @var Booking $booking
         */
        $booking = $this->find($id);

        if($booking->item->owner_id !== \Yii::$app->user->id){
            throw new ForbiddenHttpException("Not permitted");
        }

        if($booking->status !== Booking::PENDING){
            throw new BadRequestHttpException("Booking already seems to be confirmed");
        }

        if($booking->payin->status !== Payin::STATUS_AUTHORIZED){
            if($booking->payin->status == Payin::STATUS_PENDING){
                \Yii::$app->session->addFlash('info', \Yii::t('booking', 'The payment has to be confirmed before the booking can be accepted. Please try again in a couple of minutes'));
                return $this->redirect('@web/item/list');
            }
            throw new ServerErrorHttpException("Please contact support with error message: Payin status should be authorized, booking id {$booking->id}");
        }

        if($booking->request_expires_at < time()){
            \Yii::$app->session->addFlash('info', \Yii::t('booking',  'This booking had no response for more then 48 hours, so is removed.'));
            return $this->goHome();
        }

        // todo make this POST (safer)
        if($response == 'accept'){
            $res = $booking->ownerAccepts();
            \Yii::$app->session->addFlash('info', \Yii::t('booking', 'Booking has been successfully accepted'));
            return $this->redirect('@web/booking/'.$id);
        }

        if($response == 'decline'){
            $res = $booking->ownerDeclines();
            \Yii::$app->session->addFlash('info', \Yii::t('booking', 'Booking has been successfully declined'));
            return $this->redirect('@web/home');
        }

        return $this->render('owner_response', [
            'acceptLink' => Url::to('@web/booking/'.$id.'/request?response=accept'),
            'declineLink' => Url::to('@web/booking/'.$id.'/request?response=decline'),
            'item' => $booking->item,
            'booking' => $booking,
            'profile' => $booking->renter->profile,
            'timeLeft' => Carbon::createFromTimestamp($booking->payin->created_at)->diffForHumans(
                Carbon::createFromTimestamp($booking->payin->created_at)->addDay(1)
            ,true)
        ]);
    }

    public function actionConversation($id){
        $booking = $this->find($id);
        if(isset($booking->conversations[0])){
            return $this->redirect(['@web/messages/'.$booking->conversations[0]->id]);
        }
    }

    private function find($id){
        $booking = Booking::find()->where(['id' => $id])->one();
        if ($booking == null) {
            throw new NotFoundHttpException("Booking doesn't exist or does not belong to you.");
        }
        return $booking;
    }
}
