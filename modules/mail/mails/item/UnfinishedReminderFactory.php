<?php
namespace mail\mails\item;

use item\models\Item;
use mail\models\UrlFactory;

/**
 * Base class for the item emails
 */
class UnfinishedReminderFactory
{
    public function create(Item $item)
    {
        $mail = new UnfinishedReminder();
        $mail->setSubject('Unfinished item');
        $receiver = (new \mail\components\MailUserFactory())->create($item->owner->profile->getFullName(),
            $item->owner->email);
        $mail->setReceiver($receiver);
        $mail->itemName = $item->name;
        $mail->emailAddress = $item->owner->email;
        $mail->itemUrl = UrlFactory::itemCompletion($item);
        $mail->subject = "Byd forældre velkommen, og tjen ekstra penge";
        return $mail;
    }
}