<?php

namespace app\modules\booking\controllers;

use app\components\Error;
use app\extended\web\Controller;
use app\modules\booking\models\Booking;
use app\modules\item\models\Item;
use Carbon\Carbon;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ListController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['current', 'previous', 'by-item'],
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

    public function actionCurrent()
    {
        $provider = new ActiveDataProvider([
            'query' => Booking::find()->where(['renter_id' => \Yii::$app->user->id])
                ->andWhere('time_to >= :time', [':time' => Carbon::now(\Yii::$app->params['serverTimeZone'])])
                ->andWhere('(status != :status or created_at > :twoDaysAgo)',
                    [':status' => Booking::AWAITING_PAYMENT, ':twoDaysAgo' => time() - (2 * 24 * 60 * 60)]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('current', [
            'provider' => $provider,
        ]);
    }

    public function actionPrevious()
    {
        $provider = new ActiveDataProvider([
            'query' => Booking::find()->where(['renter_id' => \Yii::$app->user->id])
                ->andWhere('time_to <= :time', [':time' => Carbon::now(\Yii::$app->params['serverTimeZone'])])
                ->andWhere('status != :status or created_at > :twoDaysAgo',
                    [':status' => Booking::AWAITING_PAYMENT, ':twoDaysAgo' => time() - (2 * 24 * 60 * 60)]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('previous', [
            'provider' => $provider,
        ]);
    }

    public function actionByItem($id)
    {
        $item = Item::findOne($id);
        if ($item == null) {
            throw new NotFoundHttpException("Item not found");
        }
        if ($item->owner_id !== \Yii::$app->user->id) {
            throw new ForbiddenHttpException("You're not the owner");
        }
        $provider = new ActiveDataProvider([
            'query' => Booking::find()->where(['item.owner_id' => \Yii::$app->user->id, 'item_id' => $id])
                ->andWhere('status != :s', [':s' => Booking::AWAITING_PAYMENT])
                ->innerJoinWith('item'),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('by_item', [
            'provider' => $provider,
            'item' => $item,
        ]);
    }
}
