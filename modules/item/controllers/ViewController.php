<?php

namespace item\controllers;

use app\extended\web\Controller;
use item\forms\CreateBooking;
use item\models\Item;
use review\models\base\Review;
use user\models\base\Currency;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

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
            [
                'class' => 'yii\filters\HttpCache',
                'only' => ['index'],
                'cacheControlHeader' => 'public, max-age=300',
                'enabled' => YII_CACHE,
            ],
//            [
//                'class' => 'yii\filters\PageCache',
//                'only' => ['index'],
//                'duration' => 60 * 30,
//                'enabled' => YII_CACHE,
//                'variations' => [
//                ],
//            ],
        ];
    }


    public function actionIndex($id, $new_publish = false)
    {
        /**
         * @var $item \item\models\Item
         */
        $item = Item::find()->where(['id' => $id])->with('location')->one();

        if ($item === null) {
            throw new NotFoundHttpException("Item not found");
        }

        Url::remember('', 'after_login_url');
        Url::remember();
        $this->noContainer = true;

        $currency = \Yii::$app->user->isGuest ? Currency::find()->one() : \Yii::$app->user->identity->profile->currency;

        \Yii::$app->session->set('currentBooking', null);
        $model = new CreateBooking($item, $currency);
        if ($model->load(\Yii::$app->request->get())) {
            $attempt = $model->attemptBooking(true);
            if ($attempt !== false) {
                \Yii::$app->session->set('currentBooking', [
                    'itemID' => $item->id,
                    'currencyID' => $currency->id,
                    'dateFrom' => $model->dateFrom,
                    'dateTo' => $model->dateTo
                ]);
            }

            // post for testing and non supporting pjax
            if (Yii::$app->request->isPjax || (YII_ENV == 'test' && Yii::$app->request->isPost)) {
                if ($attempt !== false) {
                    return $attempt;
                }

                return $this->renderAjax('booking_widget', [
                    'model' => $model,
                    'item' => $item,
                    'periods' => [],
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
                ->andWhere(['type' => \review\models\Review::TYPE_USER_PUBLIC]),
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
