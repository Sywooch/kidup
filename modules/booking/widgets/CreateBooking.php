<?php

namespace app\modules\booking\widgets;

use app\components\Error;
use app\interfaces\RequestableWidgetInterface;
use app\modules\booking\forms\Create;
use app\modules\booking\models\Booking;
use app\modules\item\models\Item;
use yii\base\Widget;

class CreateBooking extends Widget
{
    public $dateFrom;
    public $dateTo;
    public $item_id;
    public $currency_id;
    public $prices;
    public $data;

    public function init()
    {

    }

    public function rules()
    {
        return [
            [['dateFrom', 'dateTo', 'currency_id', 'item_id'], 'required']
        ];
    }

    public function run()
    {
        $item = Item::findOne($this->item_id);
        $model = new Create();
        $model->setAttributes([
            'itemId' => $this->item_id,
            'currencyId' => $this->currency_id,
            'prices' => [
                'week' => $item->price_week,
                'day' => $item->price_day,
                'month' => $item->price_month,
            ]
        ]);
        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            if ($model->save()) {
                //return \Yii::$app->controller->redirect('@web/booking/' . $model->bookingId . "/confirm");
                \Yii::$app->getResponse()->redirect('@web/booking/' . $model->bookingId . "/confirm")->send();
                return false;
            }
        }

        // find the periods
        $periods = [];
        $bookings = Booking::find()->where(['item_id' => $this->item_id])->all();
        foreach ($bookings as $booking) {
            if ($booking->status !== Booking::ACCEPTED) {
                $periods[] = [$booking->time_from, $booking->time_to];
            }
        }

        return $this->render('create_booking', [
            'model' => $model,
            'periods' => $periods
        ]);
    }

}