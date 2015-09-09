<?php

namespace app\modules\item\controllers;

use app\components\Cache;
use app\components\WidgetRequest;
use app\controllers\Controller;
use app\modules\images\components\ImageHelper;
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
            'cache' => [
                'class' => 'yii\filters\HttpCache',
                'only' => ['index'],
                'cacheControlHeader' => 'public, max-age=60',
                'enabled' => YII_CACHE,
                'etagSeed' => function ($action, $params) {
                    return Json::encode([
                        Yii::$app->language,
                        \Yii::$app->user->id,
                        \Yii::$app->session->getAllFlashes()
                    ]);
                },
            ],
        ];
    }

    public function actionIndex($id, $new_publish = false)
    {
        Url::remember();
        $this->noContainer = true;
        if ($new_publish !== false) {
            $new_publish = true;
        }

        /**
         * @var $item \app\modules\item\models\Item
         */
        $item = Item::find()->where(['id' => $id])->with('location')->one();

        // prepare for carousel
        $images = Cache::data('item_view-images-carousel' . $id, function () use ($item) {
            $itemImages = $item->getImageNames();
            $images = [];
            foreach ($itemImages as $img) {
                $images[] = [
                    'src' => ImageHelper::url($img, ['q' => 90, 'w' => 400]),
                    'url' => ImageHelper::url($img, ['q' => 90, 'w' => 1600]),
                ];
            }
            return $images;
        }, 10 * 60);

        $price = $item->getAdPrice();

        // find which items are related
        $related_items = $item->getRecommendedItems($item, 3);
        $res = [
            'model' => $item,
            'location' => $item->location,
            'images' => $images,
            'price' => [
                'period' => \Yii::t('app', 'per {period}', ['period' => $price['period']]),
                'price' => $price['price'],
                'currency' => $item->currency->forex_name
            ],
            'show_modal' => $new_publish,
            'bookingForm' => new CreateBooking(),
            'related_items' => $related_items
        ];

        return $this->render('view', $res);
    }
}
