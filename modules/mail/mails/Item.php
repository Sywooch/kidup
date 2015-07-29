<?php
namespace app\modules\mail\mails;

use app\modules\mail\models\Mailer;
use Carbon\Carbon;
use Yii;
use yii\helpers\Url;

class Item extends Mailer
{
    /**
     * Reminder for unfinished item
     * @param \app\modules\item\models\Item $item
     * @return bool
     */
    public function unfinishedReminder($item)
    {
        return $this->sendMessage([
            'email' => $item->owner->email,
            'subject' => "Byd forældre velkommen, og tjen ekstra penge",
            'type' => self::ITEM_UNPUBLISHED_REMINDER,
            'params' => [
                'itemName' => $item->name,
                'profileName' => $item->owner->profile->first_name,
            ],
            'urls' => [
                'item' => Url::to('@web/item/' . $item->id . '/edit', true),
            ]
        ]);
    }

}