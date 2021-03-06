<?php
namespace review\controllers;

use app\extended\web\Controller;
use booking\models\booking\Booking;
use item\models\item\Item;
use review\forms\OwnerReview;
use review\forms\RenterReview;
use review\models\Review;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class CreateController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        // only authenticated
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($bookingId)
    {
        /**
         * @var Booking $booking
         */
        $booking = Booking::findOne($bookingId);
        if ($booking === null) {
            throw new NotFoundHttpException("Booking not found");
        }
        if ($booking->status !== Booking::ACCEPTED || $booking->time_to > time()) {
            throw new ForbiddenHttpException("Review is available after the booking ended");
        }

        if ($booking->item->owner_id == \Yii::$app->user->id) {
            $model = new OwnerReview();
            $view = 'owner';
        } elseif ($booking->renter_id == \Yii::$app->user->id) {
            $model = new RenterReview();
            $view = 'renter';
        } else {
            throw new ForbiddenHttpException("Booking doesn't belong to you");
        }

        // see if it is already reviewed
        $review = Review::find()->where(['reviewer_id' => \Yii::$app->user->id, 'booking_id' => $bookingId])->count();
        if ($review > 1) {
            throw new ForbiddenHttpException("You've already reviewed this booking!");
        }

        $model->booking = $booking;

        $item = Item::findOne($booking->item_id);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                \Yii::$app->session->addFlash('success', \Yii::t('review.create.flash.thank_you', 'Thank you for reviewing!'));
                return $this->redirect('@web/home');
            }
        }

        return $this->render($view, [
            'booking' => $booking,
            'model' => $model,
            'item' => $item
        ]);
    }
}
