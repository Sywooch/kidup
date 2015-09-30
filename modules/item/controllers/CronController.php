<?php

namespace item\controllers;

use app\helpers\Event;
use \item\models\Item;
use Yii;
use yii\base\Model;

class CronController extends Model
{

    public function hour()
    {
    }

    public function day()
    {
        // reminders unfinished items

        // 2 days
        $items2 = Item::find()->where(['is_available' => 0])->andWhere('created_at < :time && created_at > :time1', [
            ':time' => time() - 2 * 24 * 60 * 60,
            ':time1' => time() - 3 * 24 * 60 * 60
        ])->all();
        foreach ($items2 as $item) {
            Event::trigger($item, Item::EVENT_UNFINISHED_REMINDER);
        }
        $items7 = Item::find()->where(['is_available' => 0])->andWhere('created_at < :time && created_at > :time1', [
            ':time' => time() - 7 * 24 * 60 * 60,
            ':time1' => time() - 8 * 24 * 60 * 60
        ])->all();
        foreach ($items7 as $item) {
            Event::trigger($item, Item::EVENT_UNFINISHED_REMINDER);
        }
    }
}
