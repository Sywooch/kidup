<?php
namespace mail\mails;

use mail\models\Mailer;
use Yii;
use yii\helpers\Url;

class Item extends Mailer
{
    /**
     * Reminder for unfinished item
     * @param \item\models\Item $item
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
                'item' => Url::to('@web/item/create/edit-basics?id=' . $item->id, true),
            ]
        ]);
    }

}