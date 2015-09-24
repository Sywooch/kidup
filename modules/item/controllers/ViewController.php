<?php

namespace app\modules\item\controllers;

use app\components\Cache;
use app\components\WidgetRequest;
use app\controllers\Controller;
use app\models\base\Currency;
use app\models\base\Review;
use app\modules\images\components\ImageHelper;
use app\modules\item\forms\Create;
use app\modules\item\forms\CreateBooking;
use app\modules\item\models\Item;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class ViewController extends Controller
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
                        'roles' => ['@', '?'],
                    ],
                ],
            ],
//            [
//                'class' => 'yii\filters\HttpCache',
//                'only' => ['index'],
//                'cacheControlHeader' => 'public, max-age=300',
//                'enabled' => true,
//                'etagSeed' => function ($action, $params) {
//                    $bCount = false;
//                    if (@\Yii::$app->request->get()['id']) {
//                        $q = Item::find()->where(['id' => \Yii::$app->request->get()['id']])->one();
//                        if ($q !== null) {
//                            $bCount = Json::encode([$q->getBookingsCount(), $q->updated_at]);
//                        }
//                    }
//                    return md5(Json::encode([
//                        Yii::$app->language,
//                        \Yii::$app->session->getAllFlashes(),
//                        \Yii::$app->user->isGuest,
//                        \Yii::$app->request->getUrl(),
//                        $params,
//                        $bCount
//                    ]));
//                },
//            ],
        ];
    }


    public function actionIndex($id, $new_publish = false)
    {
        /**
         * @var $item \app\modules\item\models\Item
         */
        $item = Item::find()->where(['id' => $id])->with('location')->one();

        if ($item === null) {
            throw new NotFoundHttpException("Item not found");
        }

        Url::remember('', 'after_login_url');
        $this->noContainer = true;

        $currency = \Yii::$app->user->isGuest ? Currency::find()->one() : \Yii::$app->user->identity->profile->currency;

        $model = new CreateBooking($item, $currency);
        if ($model->load(\Yii::$app->request->get())) {
            $attempt = $model->attemptBooking();

            // post for testing and non supporting pjax
            if (Yii::$app->request->isPjax || (YII_ENV == 'test' && Yii::$app->request->isPost)) {
                if ($attempt !== false) {
                    return $attempt;
                }

                return $this->renderAjax('booking_widget', [
                    'model' => $model,
                    'item' => $item,
                    'periods' => []
                ]);
            } else {
                if ($attempt !== false){
                    return $attempt;
                }
            }
        } else {
            \Yii::$app->session->remove('ready_to_book');
        }

        // prepare for carousel
        $images = $item->getCarouselImages();

        // find which items are related
        $related_items = $item->getRecommendedItems($item, 2);

        $reviewDataProvider = new \yii\data\ActiveDataProvider([
            'query' => Review::find()->where(['item_id' => $item->id])
                ->andWhere(['type' => \app\modules\review\models\Review::TYPE_USER_PUBLIC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $res = [
            'model' => $item,
            'location' => $item->location,
            'images' => $images,
            'show_modal' => $new_publish !== false, // show modal if new publish
            'bookingForm' => $model,
            'reviewDataProvider' => $reviewDataProvider,
            'related_items' => $related_items
        ];

        return $this->render('view', $res);

    }
}
