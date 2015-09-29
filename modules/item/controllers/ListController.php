<?php

namespace app\modules\item\controllers;

use app\extended\web\Controller;
use app\modules\booking\models\Booking;
use app\modules\item\models\Item;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class ListController extends Controller
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

    public function actionIndex()
    {
        $unpublishedProvider = new ActiveDataProvider([
            'query' => Item::find()->where(['owner_id' => Yii::$app->user->id, 'is_available' => 0]),
            'pagination' => ['pageSize' => 10,],
        ]);

        $publishedProvider = new ActiveDataProvider([
            'query' => Item::find()->where(['owner_id' => Yii::$app->user->id, 'is_available' => 1]),
            'pagination' => ['pageSize' => 10,],
        ]);

        $requestProvider = new ActiveDataProvider([
            'query' => Booking::find()->where(['item.owner_id' => Yii::$app->user->id])
                ->innerJoinWith('item')
                ->andWhere(['booking.status' => Booking::PENDING]),
            'pagination' => ['pageSize' => 10,],
        ]);

        return $this->render('index', [
            'unpublishedProvider' => $unpublishedProvider,
            'publishedProvider' => $publishedProvider,
            'requestProvider' => $requestProvider,
        ]);
    }
}

