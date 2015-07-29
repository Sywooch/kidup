<?php

namespace app\modules\item\controllers;

use app\components\WidgetRequest;
use app\controllers\Controller;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\components\Error;
use app\modules\item\models\Item;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;

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

    public function actionIndex($id)
    {
//        $this->transparentNav = true;
        $this->noContainer = true;
        Url::remember();

        /**
         * @var $item \app\modules\item\models\Item
         */
        $item = Item::find()->where(['id' => $id])->one();
        if ($item == null) {
            \Yii::$app->error->notFound('Item not found');
        }
        if($item->is_available == 0 && \Yii::$app->user->id !== $item->owner_id){
            throw new ForbiddenHttpException("This item is not yet available");
        }
        $location = $item->location;

        $itemImages = $item->getImageUrls();

        // prepare for carousel
        $images = [];
        foreach ($itemImages as $img) {
            $images[] = [
                'src' => $img['medium'],
                'url' => $img['original'],
            ];
        }

        $price = $item->getAdPrice();

        if($item->is_available == 0){
            $bookingForm = \Yii::t('item', 'This is a preview. Go here {0} to publish this item.', [
                Html::a(\Yii::t('item', 'link'), '@web/item/'.$item->id.'/edit')
            ]);
        }elseif($item->owner_id == \Yii::$app->user->id){
            $bookingForm = \Yii::t('app', 'This item belongs to you.');
        }else{
            $bookingForm = WidgetRequest::request(WidgetRequest::BOOKING_CREATE_FORM, [
                'item_id' => $item->id,
                'currency_id' => $item->currency_id,
                'prices' => [
                    'daily' => $item->price_day,
                    'weekly' => $item->price_week,
                    'monthly' => $item->price_month,
                ]
            ]);
        }
        return $this->render('view', [
            'model' => $item,
            'location' => $location,
            'images' => $images,
            'price' => [
                'period' => \Yii::t('app', 'per {period}', ['period' => $price['period']]),
                'price' => $price['price'],
                'currency' => $item->currency->forex_name
            ],
            'bookingForm' => $bookingForm,
        ]);
    }
}
