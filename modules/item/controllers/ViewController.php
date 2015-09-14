<?php

namespace app\modules\item\controllers;

use app\components\Cache;
use app\components\WidgetRequest;
use app\controllers\Controller;
use app\models\base\Currency;
use app\modules\images\components\ImageHelper;
use app\modules\item\forms\Create;
use app\modules\item\forms\CreateBooking;
use app\modules\item\models\Item;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;

class ViewController extends Controller
{
    private $item;

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
                'etagSeed' => function ($action, $params) {
                    return Json::encode([
                        Yii::$app->language,
                        \Yii::$app->session->getAllFlashes(),
                        \Yii::$app->user->isGuest,
                        \Yii::$app->request->get(),
                        $this->item->updated_at,
                        $this->item->bookingsCount()
                    ]);
                },
            ],
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index'],
                'duration' => 60 * 20,
                'enabled' => YII_CACHE,
                'variations' => [
                    \Yii::$app->language,
                    \Yii::$app->session->getAllFlashes(),
                    \Yii::$app->request->get(),
                    $this->item->updated_at,
                    $this->item->bookingsCount()
                ],
            ],
        ];
    }


    public function actionIndex($id, $new_publish = false)
    {
        /**
         * @var $item \app\modules\item\models\Item
         */
        $item = Item::find()->where(['id' => $id])->with('location')->one();
        $this->item = $item;

        Url::remember('', 'after_login_url');
        $this->noContainer = true;

        $currency = \Yii::$app->user->isGuest ? Currency::find()->one() : \Yii::$app->user->identity->profile->currency;
        // post for testing and non supporting pjax

        $model = new CreateBooking($item, $currency);

        if ($model->load(\Yii::$app->request->get())) {
            $attempt = $model->attemptBooking();
            if (Yii::$app->request->isPjax || Yii::$app->request->isPost) {
                if ($attempt !== false) {
                    return $attempt;
                }

                return $this->renderAjax('booking_widget', [
                    'model' => $model,
                    'item' => $item,
                    'periods' => []
                ]);
            }else{
                if($attempt !== false){
                    return $this->redirect('@web/booking/'.$model->booking->id.'/confirm');
                }
            }
        }else{
            \Yii::$app->session->remove('ready_to_book');
        }

        // prepare for carousel
        $images = $item->getCarouselImages();

        // find which items are related
        $related_items = $item->getRecommendedItems($item, 3);
        $res = [
            'model' => $item,
            'location' => $item->location,
            'images' => $images,
            'show_modal' => $new_publish !== false, // show modal if new publish
            'bookingForm' => $model,
            'related_items' => $related_items
        ];

        return $this->render('view', $res);
    }
}
