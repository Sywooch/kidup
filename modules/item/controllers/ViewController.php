<?php

namespace app\modules\item\controllers;

use app\components\Cache;
use app\components\Error;
use app\components\WidgetRequest;
use app\controllers\Controller;
use app\modules\images\components\ImageHelper;
use app\modules\item\models\Item;
use app\modules\item\models\ItemRecommender;
use yii\bootstrap\Html;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
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
        ];
    }

    public function actionIndex($id, $new_publish = false)
    {
        $function = function () use ($id, $new_publish) {
            if ($new_publish !== false) {
                $new_publish = true;
            }
            $this->noContainer = true;
            Url::remember();

            /**
             * @var $item \app\modules\item\models\Item
             */
            $item = Item::getDb()->cache(function () use ($id) {
                return Item::find()->where(['id' => $id])->with('location')->one();
            });
            if ($item == null) {
                throw new NotFoundHttpException("THis item does not exist");
            }
            if ($item->is_available == 0 && \Yii::$app->user->id !== $item->owner_id) {
                throw new ForbiddenHttpException("This item is not yet available");
            }
            $location = $item->location;

            $itemImages = $item->getImageNames();
            // prepare for carousel
            $images = [];
            foreach ($itemImages as $img) {
                $images[] = [
                    'src' => ImageHelper::url($img, ['q' => 90, 'w' => 400]),
                    'url' => ImageHelper::url($img, ['q' => 90, 'w' => 1600]),
                ];
            }

            $price = $item->getAdPrice();

            if ($item->is_available == 0) {
                $bookingForm = \Yii::t('item', 'This is a preview. Go here {0} to publish this item.', [
                    Html::a(\Yii::t('item', 'link'), '@web/item/' . $item->id . '/edit')
                ]);
            } elseif ($item->owner_id == \Yii::$app->user->id) {
                $bookingForm = \Yii::t('app', 'This item belongs to you.');
            } else {
                $bookingForm = Cache::html('booking_create_form', function () use ($item) {
                    WidgetRequest::request(WidgetRequest::BOOKING_CREATE_FORM, [
                        'item_id' => $item->id,
                        'currency_id' => $item->currency_id,
                        'prices' => [
                            'daily' => $item->price_day,
                            'weekly' => $item->price_week,
                            'monthly' => $item->price_month,
                        ]
                    ]);
                }, [], false);
            }

            // find which items are related
        $related_items = $item->getRecommendedItems($item->id, 3);
            $related_items = [];
            $res = [
                'model' => $item,
                'location' => $location,
                'images' => $images,
                'price' => [
                    'period' => \Yii::t('app', 'per {period}', ['period' => $price['period']]),
                    'price' => $price['price'],
                    'currency' => $item->currency->forex_name
                ],
                'bookingForm' => $bookingForm,
                'show_modal' => $new_publish,
                'related_items' => $related_items
            ];

            return $this->render('view', $res);
        };
        return Cache::html('view_item', $function, ['variations' => [$id, $new_publish]]);
    }
}
